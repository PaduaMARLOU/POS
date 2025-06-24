document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons(); // Initial icon rendering

    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isPasswordHidden = passwordInput.type === 'password';
            passwordInput.type = isPasswordHidden ? 'text' : 'password';

            // Set icon based on new state
            toggleBtn.innerHTML = `<i data-lucide="${isPasswordHidden ? 'eye-off' : 'eye'}"></i>`;
            lucide.createIcons();
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
