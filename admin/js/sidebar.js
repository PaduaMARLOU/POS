function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const icon = document.getElementById('toggle-icon');

    const isCollapsed = sidebar.classList.toggle('collapsed');
    icon.setAttribute('data-lucide', isCollapsed ? 'chevron-right' : 'chevron-left');

    localStorage.setItem('sidebarCollapsed', isCollapsed);
    lucide.createIcons();
}

// On page load, restore state
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const icon = document.getElementById('toggle-icon');
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        icon.setAttribute('data-lucide', 'chevron-right');
    } else {
        icon.setAttribute('data-lucide', 'chevron-left');
    }

    lucide.createIcons();
});
