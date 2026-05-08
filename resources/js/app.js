import './bootstrap';

const sidebar = document.querySelector('#sidebar');
const sidebarToggle = document.querySelector('#sidebar-toggle');
const sidebarOverlay = document.querySelector('#sidebar-overlay');

const setSidebarOpen = (isOpen) => {
    if (!sidebar || !sidebarOverlay) {
        return;
    }

    sidebar.classList.toggle('-translate-x-full', !isOpen);
    sidebarOverlay.classList.toggle('hidden', !isOpen);
};

sidebarToggle?.addEventListener('click', () => setSidebarOpen(true));
sidebarOverlay?.addEventListener('click', () => setSidebarOpen(false));

document.querySelectorAll('[data-alert]').forEach((alert) => {
    setTimeout(() => {
        alert.classList.add('opacity-0');
    }, 4000);
});
