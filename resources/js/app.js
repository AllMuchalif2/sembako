import "./bootstrap";
import "../css/app.css";
import Alpine from "alpinejs";
import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    // data table admin
    const categoriesTable = document.querySelector("#categoriesTb");
    if (categoriesTable) {
        new DataTable(categoriesTable);
    }

    const productsTable = document.querySelector("#productsTb");
    if (productsTable) {
        new DataTable(productsTable);
    }

    // Logika untuk konfirmasi hapus
    document.body.addEventListener("submit", function (event) {
        if (event.target && event.target.matches("form.delete-form")) {
            event.preventDefault();
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                event.target.submit();
            }
        }
    });

    // --- CART LOGIC ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (csrfToken) {
        // --- Helper Functions ---
        /**
         * Menampilkan notifikasi global yang meniru komponen Blade.
         * @param {string} message Pesan yang akan ditampilkan.
         * @param {string} type Tipe notifikasi: 'success', 'warning', atau 'error'.
         */
        function showGlobalNotification(message, type = 'success') {
            const container = document.body;
            const notification = document.createElement('div');

            let iconClass, iconColor;
            if (type === 'success') {
                iconClass = 'fa-solid fa-circle-check';
                iconColor = 'text-green-500';
            } else if (type === 'warning') {
                iconClass = 'fa-solid fa-triangle-exclamation';
                iconColor = 'text-yellow-500';
            } else { // error
                iconClass = 'fa-solid fa-circle-xmark';
                iconColor = 'text-red-500';
            }

            notification.className = 'fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden';
            notification.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="${iconClass} ${iconColor} text-xl"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(notification);

            setTimeout(() => notification.remove(), 5000);
        }

        async function updateCartSummary() {
            try {
                const response = await fetch('/cart/summary');
                const data = await response.json();
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.textContent = data.count;
                    el.classList.toggle('hidden', data.count === 0);
                });
            } catch (error) {
                console.error('Error updating cart summary:', error);
            }
        }

        // --- Event Handlers ---
        async function handleAddToCart(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            const formData = new FormData(form);
            const button = form.querySelector('button[type="submit"]');

            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sm"></i>'; // Loading spinner

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) throw new Error('Network response was not ok.');

                await response.json();
                await updateCartSummary();
                showGlobalNotification('Produk ditambahkan ke keranjang!', 'success');

            } catch (error) {
                console.error('Error adding to cart:', error);
                showGlobalNotification('Gagal menambahkan produk.', 'error');
            } finally {
                button.disabled = false;
                // Restore icon based on where it is
                if (form.closest('.group')) { // Product card
                    button.innerHTML = '<i class="fa-solid fa-cart-plus text-sm"></i>';
                } else { // Modal
                    button.innerHTML = '<span>Tambah ke Keranjang</span>';
                }
            }
        }

        // Set untuk melacak produk yang sedang dalam proses update
        const updatingProducts = new Set();

        async function handleUpdateCart(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            const productRow = form.closest('[data-product-id]');
            if (!productRow) return;

            // Simpan tombol yang diklik dan konten aslinya
            const clickedButton = event.submitter;
            let originalButtonContent = '';

            if (clickedButton) {
                originalButtonContent = clickedButton.innerHTML;
                clickedButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';
            }

            const formData = new FormData(form);
            const productId = productRow.dataset.productId;

            // Disable both buttons immediately to prevent rapid clicks
            const minusButton = productRow.querySelector('.btn-minus');
            const plusButton = productRow.querySelector('.btn-plus');

            if (minusButton) minusButton.disabled = true;
            if (plusButton) plusButton.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST', // HTML forms don't support PATCH, so we use POST with _method

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) throw new Error('Network response was not ok.');

                const data = await response.json();
                updateCartSummary();

                // Update UI on cart page
                const allProductRows = document.querySelectorAll(`[data-product-id="${productId}"]`);
                if (allProductRows.length > 0) {
                    allProductRows.forEach(row => {
                        row.querySelector('.item-quantity').textContent = data.item_quantity;
                        row.querySelector('.item-subtotal').textContent = data.item_subtotal_formatted;

                        // Re-evaluate button disabled states
                        // Pastikan quantity dari server adalah angka untuk kalkulasi berikutnya
                        const currentQuantity = parseInt(data.item_quantity, 10);
                        const stockText = row.querySelector('.item-stock')?.textContent || '';
                        const stock = parseInt(stockText.replace(/\D/g, ''), 10) || 0;

                        row.querySelector('.btn-minus').disabled = currentQuantity <= 1;
                        row.querySelector('.btn-plus').disabled = currentQuantity >= stock;

                        // Update the hidden input values in the forms for the next click
                        const minusForm = row.querySelector('.btn-minus').closest('form');
                        const plusForm = row.querySelector('.btn-plus').closest('form');
                        if (minusForm) minusForm.querySelector('input[name="quantity"]').value = currentQuantity - 1;
                        if (plusForm) plusForm.querySelector('input[name="quantity"]').value = currentQuantity + 1;
                    });
                    document.querySelector('.cart-total').textContent = data.cart.total_formatted;
                }

                if (data.warning) {
                    showGlobalNotification(data.warning, 'warning');
                } else {
                    showGlobalNotification('Kuantitas diperbarui.', 'success');
                }

            } catch (error) {
                console.error('Error updating cart:', error);
                showGlobalNotification('Gagal memperbarui kuantitas.', 'error');
            } finally {
                // Kembalikan konten tombol asli setelah selesai
                if (clickedButton && originalButtonContent) {
                    clickedButton.innerHTML = originalButtonContent;
                }

                // Re-enable buttons if they exist, respecting the final state
                const finalQuantity = parseInt(productRow.querySelector('.item-quantity').textContent, 10);
                const stockText = productRow.querySelector('.item-stock')?.textContent || '';
                const stock = parseInt(stockText.replace(/\D/g, ''), 10);

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
            if (!confirm('Anda yakin ingin menghapus produk ini dari keranjang?')) return;

            const form = event.target.closest('form');
            const productId = form.closest('[data-product-id]').dataset.productId;

            try {
                const response = await fetch(form.action, {
                    method: 'POST', // Use POST with _method
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });

                if (!response.ok) throw new Error('Network response was not ok.');

                const data = await response.json();
                await updateCartSummary();

                // Remove item from DOM
                const productRows = document.querySelectorAll(`[data-product-id="${productId}"]`);
                if (productRows.length > 0) {
                    productRows.forEach(productRow => {
                        productRow.style.transition = 'opacity 0.3s ease';
                        productRow.style.opacity = '0';
                        setTimeout(() => {
                            productRow.remove();
                            // Check if cart is now empty
                            if (document.querySelectorAll('[data-product-id]').length === 0) {
                                location.reload(); // Easiest way to show the "empty cart" view
                            } else {
                                document.querySelector('.cart-total').textContent = data.total_formatted;
                            }
                        }, 300);
                    });
                }

                showGlobalNotification('Produk dihapus dari keranjang.', 'success');

            } catch (error) {
                console.error('Error removing from cart:', error);
                showGlobalNotification('Gagal menghapus produk.', 'error');
            }
        }

        // --- Initial Setup & Event Listeners ---

        // Update cart count on page load
        updateCartSummary();

        // Delegate events for dynamically added content
        document.body.addEventListener("submit", function (event) {
            const form = event.target;

            if (form.matches(".add-to-cart-form")) {
                // Untuk add-to-cart, race condition kurang berisiko, bisa langsung panggil
                handleAddToCart(event);
            }
            if (form.matches(".update-cart-form")) {
                const productId = form.closest('[data-product-id]')?.dataset.productId;
                if (productId && !updatingProducts.has(productId)) {
                    updatingProducts.add(productId); // Kunci produk SEBELUM memanggil async function
                    handleUpdateCart(event).finally(() => {
                        updatingProducts.delete(productId); // Buka kunci setelah selesai
                    });
                }
            }
            if (form.matches(".remove-from-cart-form")) {
                handleRemoveFromCart(event);
            }
        });
    }
});
