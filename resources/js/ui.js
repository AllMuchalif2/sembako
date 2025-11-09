/**
 * Menampilkan notifikasi global.
 * @param {string} message Pesan yang akan ditampilkan.
 * @param {string} type Tipe notifikasi: 'success', 'warning', atau 'error'.
 */
export function showGlobalNotification(message, type = 'success') {
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

export function initConfirmDelete() {
    document.body.addEventListener("submit", function (event) {
        if (event.target && event.target.matches("form.delete-form")) {
            event.preventDefault();
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                event.target.submit();
            }
        }
    });
}
