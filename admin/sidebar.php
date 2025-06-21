<!-- Load Lucide Icons + Sidebar CSS -->
<link rel="stylesheet" href="css/sidebar.css">
<script src="https://unpkg.com/lucide@latest"></script>

<div class="sidebar" id="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i data-lucide="chevron-left" id="toggle-icon"></i>
    </button>

    <h2>Menu</h2>
    <ul>
        <li>
            <a href="dashboard.php">
                <i data-lucide="home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="products.php">
                <i data-lucide="box"></i> <span>Products</span>
            </a>
        </li>
        <li>
            <a href="credit.php">
                <i data-lucide="credit-card"></i> <span>Credit</span>
            </a>
        </li>
        <li>
            <a href="control_panel.php">
                <i data-lucide="settings"></i> <span>Control Panel</span>
            </a>
        </li>
    </ul>
</div>

<script src="js/sidebar.js"></script>
<script>lucide.createIcons();</script>
