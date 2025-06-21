document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons(); // Render initial icons

    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';

        // Change the icon
        toggleBtn.innerHTML = `<i data-lucide="${isPassword ? 'eye' : 'eye-off'}"></i>`;
        lucide.createIcons(); // Re-render icon
    });
});
