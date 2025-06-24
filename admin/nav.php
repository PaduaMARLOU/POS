<!-- admin/nav.php -->
<link rel="stylesheet" href="css/nav.css">
<script src="https://unpkg.com/lucide@latest"></script>

<nav class="navbar">
    <div class="nav-links">
        <a href="dashboard.php"><i data-lucide="layout-dashboard"></i> Dashboard</a>
        <a href="cashier.php"><i data-lucide="credit-card"></i> Cashier</a>
        <a href="debt.php"><i data-lucide="file-minus"></i> Debt</a>
        <a href="products.php"><i data-lucide="package"></i> Products</a>
        <a href="control_panel.php"><i data-lucide="settings"></i> Control Panel</a>
    </div>
    <button class="logout-link" id="logoutBtn"><i data-lucide="log-out"></i> Logout</button>
</nav>

<!-- Logout Modal -->
<div id="logoutModal" class="modal-overlay hidden">
    <div class="modal-box">
        <h3>Confirm Logout</h3>
        <p>Are you sure you want to logout?</p>
        <div class="modal-actions">
            <button id="cancelLogout" class="cancel-btn">Cancel</button>
            <a href="../logout.php" class="confirm-btn">Yes, Logout</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const logoutBtn = document.getElementById('logoutBtn');
    const modal = document.getElementById('logoutModal');
    const cancel = document.getElementById('cancelLogout');

    logoutBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    cancel.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
});
</script>
