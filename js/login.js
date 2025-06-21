document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons(); // Render initial icons

    // Toggle password visibility
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // Change the icon
            toggleBtn.innerHTML = `<i data-lucide="${isPassword ? 'eye' : 'eye-off'}"></i>`;
            lucide.createIcons(); // Re-render icon
        });
    }

    // Prevent multiple form submissions
    const form = document.querySelector('form');
    const loginButton = document.getElementById('loginButton');

    if (form && loginButton) {
        form.addEventListener('submit', () => {
            loginButton.disabled = true;
            loginButton.textContent = 'Logging in...';
        });
    }
});
