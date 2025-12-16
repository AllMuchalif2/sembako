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
                row.querySelector(".item-quantity").textContent =
                    data.item_quantity;
                row.querySelector(".item-subtotal").textContent =
                    data.item_subtotal_formatted;

                const currentQuantity = parseInt(data.item_quantity, 10);
                const stockText =
                    row.querySelector(".item-stock")?.textContent || "";
                const stock = parseInt(stockText.replace(/\D/g, ""), 10) || 0;

                row.querySelector(".btn-minus").disabled = currentQuantity <= 1;
                row.querySelector(".btn-plus").disabled =
                    currentQuantity >= stock;

                const minusForm = row
                    .querySelector(".btn-minus")
                    .closest("form");
                const plusForm = row.querySelector(".btn-plus").closest("form");
                if (minusForm)
                    minusForm.querySelector('input[name="quantity"]').value =
                        currentQuantity - 1;
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

        const finalQuantity = parseInt(
            productRow.querySelector(".item-quantity").textContent,
            10
        );
        const stockText =
            productRow.querySelector(".item-stock")?.textContent || "";
        const stock = parseInt(stockText.replace(/\D/g, ""), 10);

        if (minusButton) {
            minusButton.disabled = finalQuantity <= 1;
        }
        if (plusButton) {
            plusButton.disabled = finalQuantity >= stock;
        }
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
