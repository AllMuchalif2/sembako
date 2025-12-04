/**
 * Menampilkan notifikasi global.
 * @param {string} message Pesan yang akan ditampilkan.
 * @param {string} type Tipe notifikasi: 'success', 'warning', atau 'error'.
 */
export function showGlobalNotification(message, type = 'success') {
    // Remove any existing notifications first
    const existingNotifs = document.querySelectorAll('.global-notification');
    existingNotifs.forEach(notif => notif.remove());

    const container = document.body;
    const notification = document.createElement('div');

    let iconClass, progressGradient;
    if (type === 'success') {
        iconClass = 'fa-solid fa-circle-check text-green-500';
        progressGradient = 'from-green-400 to-green-600';
    } else if (type === 'warning') {
        iconClass = 'fa-solid fa-triangle-exclamation text-yellow-500';
        progressGradient = 'from-yellow-400 to-yellow-600';
    } else { // error
        iconClass = 'fa-solid fa-circle-xmark text-red-500';
        progressGradient = 'from-red-400 to-red-600';
    }

    notification.className = 'global-notification fixed bottom-4 left-4 right-4 sm:left-auto sm:right-5 sm:bottom-5 z-50 sm:max-w-sm w-auto sm:w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2 transition-all duration-300 ease-out';
    
    notification.innerHTML = `
        <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r ${progressGradient} transition-all duration-50 ease-linear notification-progress" style="width: 100%"></div>
        <div class="p-3 sm:p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="${iconClass} text-lg sm:text-xl"></i>
                </div>
                <div class="ml-2 sm:ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-xs sm:text-sm font-medium text-gray-900">${message}</p>
                </div>
                <div class="ml-2 flex-shrink-0 flex">
                    <button class="notification-close inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-md p-1">
                        <span class="sr-only">Close</span>
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;

    container.appendChild(notification);

    // Trigger animation
    requestAnimationFrame(() => {
        notification.classList.remove('opacity-0', 'translate-y-2', 'sm:translate-x-2');
        notification.classList.add('opacity-100', 'translate-y-0', 'sm:translate-x-0');
    });

    // Progress bar animation
    const progressBar = notification.querySelector('.notification-progress');
    const duration = 5000;
    const startTime = Date.now();
    
    const animateProgress = () => {
        const elapsed = Date.now() - startTime;
        const remaining = Math.max(0, 100 - (elapsed / duration * 100));
        progressBar.style.width = `${remaining}%`;
        
        if (remaining > 0) {
            requestAnimationFrame(animateProgress);
        } else {
            closeNotification(notification);
        }
    };
    
    requestAnimationFrame(animateProgress);

    // Close button handler
    const closeButton = notification.querySelector('.notification-close');
    closeButton.addEventListener('click', () => closeNotification(notification));
}

function closeNotification(notification) {
    notification.classList.add('opacity-0', 'translate-y-2', 'sm:translate-x-2');
    notification.classList.remove('opacity-100', 'translate-y-0', 'sm:translate-x-0');
    setTimeout(() => notification.remove(), 300);
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
