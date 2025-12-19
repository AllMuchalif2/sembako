import { showGlobalNotification } from "./ui.js";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

// Set untuk melacak produk yang sedang dalam proses update
const updatingProducts = new Set();

async function updateCartSummary() {
    try {
        const response = await fetch("/cart/summary");
        const data = await response.json();
        const cartCountElements = document.querySelectorAll(".cart-count");
        cartCountElements.forEach((el) => {
            el.textContent = data.count;
            el.classList.toggle("hidden", data.count === 0);
        });
    } catch (error) {
        console.error("Error updating cart summary:", error);
    }
}

async function handleAddToCart(event) {
    event.preventDefault();
    const form = event.target.closest("form");
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');

    button.disabled = true;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sm"></i>'; // Loading spinner

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: formData,
        });

        if (!response.ok) throw new Error("Network response was not ok.");

        await response.json();
        await updateCartSummary();
        showGlobalNotification("Produk ditambahkan ke keranjang!", "success");
    } catch (error) {
        console.error("Error adding to cart:", error);
        showGlobalNotification("Gagal menambahkan produk.", "error");
    } finally {
        button.disabled = false;
        // Restore icon based on where it is
        if (form.closest(".group")) {
            // Product card
            button.innerHTML = '<i class="fa-solid fa-cart-plus text-sm"></i>';
        } else {
            // Modal
            button.innerHTML = "<span>Tambah ke Keranjang</span>";
        }
    }
}

async function handleUpdateCart(event) {
    event.preventDefault();
    const form = event.target.closest("form");
    const productRow = form.closest("[data-product-id]");
    if (!productRow) return;

    const clickedButton = event.submitter;
    let originalButtonContent = "";

    if (clickedButton) {
        originalButtonContent = clickedButton.innerHTML;
        clickedButton.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';
    }

    const formData = new FormData(form);
    const productId = productRow.dataset.productId;

    const minusButton = productRow.querySelector(".btn-minus");
    const plusButton = productRow.querySelector(".btn-plus");

    if (minusButton) minusButton.disabled = true;
    if (plusButton) plusButton.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: formData,
        });

        if (!response.ok) throw new Error("Network response was not ok.");

        const data = await response.json();
        updateCartSummary();

        const allProductRows = document.querySelectorAll(
            `[data-product-id="${productId}"]`
        );
        if (allProductRows.length > 0) {
            allProductRows.forEach((row) => {
                const quantityEl = row.querySelector(".item-quantity");
                if (quantityEl) {
                    if (quantityEl.tagName === "INPUT") {
                        quantityEl.value = data.item_quantity;
                    } else {
                        quantityEl.textContent = data.item_quantity;
                    }
                }
                row.querySelector(".item-subtotal").textContent =
                    data.item_subtotal_formatted;

                const currentQuantity = parseInt(data.item_quantity, 10);
                const stockText =
                    row.querySelector(".item-stock")?.textContent || "";
                const stock = parseInt(stockText.replace(/\D/g, ""), 10) || 0;

                // Handle Minus Form Swapping Logic
                const minusForm = row
                    .querySelector(".btn-minus")
                    ?.closest("form");
                if (minusForm) {
                    const methodInput = minusForm.querySelector(
                        'input[name="_method"]'
                    );
                    const hiddenQtyInput = minusForm.querySelector(
                        'input[name="quantity"]'
                    );

                    const btn = minusForm.querySelector("button");
                    if (btn) btn.disabled = false; // Re-enable button

                    if (currentQuantity <= 1) {
                        // Switch to Remove Mode for next click
                        minusForm.action = row.dataset.removeUrl;
                        if (methodInput) methodInput.value = "DELETE";
                        // Ensure class matches for event delegation
                        minusForm.classList.remove("update-cart-form");
                        minusForm.classList.add("remove-from-cart-form");

                        if (
                            btn &&
                            btn.classList.contains("hover:bg-gray-300")
                        ) {
                            btn.classList.replace(
                                "hover:bg-gray-300",
                                "hover:bg-red-200"
                            );
                        }
                    } else {
                        // Switch to Update Mode
                        minusForm.action = row.dataset.updateUrl;
                        if (methodInput) methodInput.value = "PATCH";
                        if (hiddenQtyInput)
                            hiddenQtyInput.value = currentQuantity - 1;

                        minusForm.classList.remove("remove-from-cart-form");
                        minusForm.classList.add("update-cart-form");

                        if (btn && btn.classList.contains("hover:bg-red-200")) {
                            btn.classList.replace(
                                "hover:bg-red-200",
                                "hover:bg-gray-300"
                            );
                        }
                    }
                }

                const plusButton = row.querySelector(".btn-plus");
                if (plusButton) plusButton.disabled = currentQuantity >= stock;

                const plusForm = row
                    .querySelector(".btn-plus")
                    ?.closest("form");
                if (plusForm)
                    plusForm.querySelector('input[name="quantity"]').value =
                        currentQuantity + 1;
            });
            document.querySelector(".cart-total").textContent =
                data.cart.total_formatted;
        }

        if (data.warning) {
            showGlobalNotification(data.warning, "warning");
        } else {
            showGlobalNotification("Kuantitas diperbarui.", "success");
        }
    } catch (error) {
        console.error("Error updating cart:", error);
        showGlobalNotification("Gagal memperbarui kuantitas.", "error");
    } finally {
        if (clickedButton && originalButtonContent) {
            clickedButton.innerHTML = originalButtonContent;
        }

        // Remove the finally block stock logic that could override the input value reader
    }
}

async function handleRemoveFromCart(event) {
    event.preventDefault();
    if (!confirm("Anda yakin ingin menghapus produk ini dari keranjang?"))
        return;

    const form = event.target.closest("form");
    const productId = form.closest("[data-product-id]").dataset.productId;

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: new FormData(form),
        });

        if (!response.ok) throw new Error("Network response was not ok.");

        const data = await response.json();
        await updateCartSummary();

        const productRows = document.querySelectorAll(
            `[data-product-id="${productId}"]`
        );
        if (productRows.length > 0) {
            productRows.forEach((productRow) => {
                productRow.style.transition = "opacity 0.3s ease";
                productRow.style.opacity = "0";
                setTimeout(() => {
                    productRow.remove();
                    if (
                        document.querySelectorAll("[data-product-id]")
                            .length === 0
                    ) {
                        location.reload();
                    } else {
                        document.querySelector(".cart-total").textContent =
                            data.total_formatted;
                    }
                }, 300);
            });
        }

        showGlobalNotification("Produk dihapus dari keranjang.", "success");
    } catch (error) {
        console.error("Error removing from cart:", error);
        showGlobalNotification("Gagal menghapus produk.", "error");
    }
}

function initCartEvents() {
    if (!csrfToken) {
        console.warn(
            "CSRF token not found. Cart functionality will be disabled."
        );
        return;
    }

    // Update cart count on page load
    updateCartSummary();

    // Delegate events for dynamically added content
    document.body.addEventListener("submit", function (event) {
        const form = event.target;

        if (form.matches(".add-to-cart-form")) {
            handleAddToCart(event);
        }

        if (form.matches(".update-cart-form")) {
            const productId =
                form.closest("[data-product-id]")?.dataset.productId;
            if (productId && !updatingProducts.has(productId)) {
                updatingProducts.add(productId);
                handleUpdateCart(event).finally(() => {
                    updatingProducts.delete(productId);
                });
            }
        }

        if (form.matches(".remove-from-cart-form")) {
            handleRemoveFromCart(event);
        }
    });
}

export { initCartEvents };
