<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT id, username, account_type, email, phone, created_at, updated_at, last_login, login_attempts, is_active, is_locked FROM users");
$users = $stmt->fetchAll();

$activeUsers = array_filter($users, fn($u) => $u['is_active']);
$inactiveUsers = array_filter($users, fn($u) => !$u['is_active']);
?>

<!-- Add User Button -->
<div style="margin-bottom: 1rem;">
    <button class="btn-add" onclick="openAddModal()">➕ Add User</button>
</div>

<!-- Active Users Dropdown -->
<div class="user-section">
    <button class="dropdown-btn" onclick="toggleSection('activeTable')">▶ Active Users</button>
    <div id="activeTable" class="table-wrapper table-content" style="display: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Last Login</th>
                    <th>Login Attempts</th>
                    <th>Status</th>
                    <th>Locked</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activeUsers as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['account_type']) ?></td>
                        <td><?= $user['email'] ? htmlspecialchars($user['email']) : '-' ?></td>
                        <td><?= $user['phone'] ? htmlspecialchars($user['phone']) : '-' ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><?= htmlspecialchars($user['updated_at']) ?></td>
                        <td><?= $user['last_login'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($user['login_attempts']) ?></td>
                        <td><span class="status-active">Active</span></td>
                        <td><?= $user['is_locked'] ? 'Yes' : 'No' ?></td>
                        <td class="actions">
                            <i class="fa fa-pen-to-square" title="Edit" onclick='openEditModal(<?= json_encode($user) ?>)'></i>
                            <i class="fa fa-trash" title="Delete" onclick="openDeleteModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')"></i>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Inactive Users Dropdown -->
<div class="user-section">
    <button class="dropdown-btn" onclick="toggleSection('inactiveTable')">▶ Inactive Users</button>
    <div id="inactiveTable" class="table-wrapper table-content" style="display: none;">
        <table class="table inactive-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Last Login</th>
                    <th>Login Attempts</th>
                    <th>Status</th>
                    <th>Locked</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inactiveUsers as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['account_type']) ?></td>
                        <td><?= $user['email'] ? htmlspecialchars($user['email']) : '-' ?></td>
                        <td><?= $user['phone'] ? htmlspecialchars($user['phone']) : '-' ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><?= htmlspecialchars($user['updated_at']) ?></td>
                        <td><?= $user['last_login'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($user['login_attempts']) ?></td>
                        <td><span class="status-inactive">Inactive</span></td>
                        <td><?= $user['is_locked'] ? 'Yes' : 'No' ?></td>
                        <td class="actions">
                            <i class="fa fa-pen-to-square" title="Edit" onclick='openEditModal(<?= json_encode($user) ?>)'></i>
                            <i class="fa fa-trash" title="Delete" onclick="openDeleteModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')"></i>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit/Add Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <div class="modal-header" id="modalTitle">Edit User</div>
        <form id="editForm" method="POST">
            <input type="hidden" name="id" id="editUserId">

            <label>Username:</label>
            <input type="text" name="username" id="editUsername" required><br><br>

            <label>Password:</label>
            <input type="password" name="password" id="editPassword" placeholder="Leave blank to keep current"><br><br>

            <label>Confirm Password:</label>
            <input type="password" id="editConfirmPassword" placeholder="Re-enter new password"><br><br>

            <label>Email:</label>
            <input type="email" name="email" id="editEmail"><br><br>

            <label>Phone:</label>
            <input type="text" name="phone" id="editPhone"><br><br>

            <label>Account Type:</label>
            <select name="account_type" id="editAccountType">
                <option value="admin">admin</option>
                <option value="user">user</option>
                <option value="staff">staff</option>
            </select><br><br>

            <label>Is Active:</label>
            <select name="is_active" id="editIsActive">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select><br><br>

            <label>Is Locked:</label>
            <select name="is_locked" id="editIsLocked">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select><br><br>

            <label>Login Attempts:</label>
            <input type="number" name="login_attempts" id="editLoginAttempts" min="0"><br><br>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('deleteModal')">&times;</span>
        <div class="modal-header">Confirm Delete</div>
        <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
        <form method="POST" action="control_panel/users/delete_user.php">
            <input type="hidden" name="id" id="deleteUserId">
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn-confirm">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(user) {
    document.getElementById('modalTitle').innerText = 'Edit User';
    document.getElementById('editForm').action = 'control_panel/users/update_user.php';

    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editPassword').value = '';
    document.getElementById('editConfirmPassword').value = '';
    document.getElementById('editEmail').value = user.email || '';
    document.getElementById('editPhone').value = user.phone || '';
    document.getElementById('editAccountType').value = user.account_type;
    document.getElementById('editIsActive').value = user.is_active ? '1' : '0';
    document.getElementById('editIsLocked').value = user.is_locked ? '1' : '0';
    document.getElementById('editLoginAttempts').value = user.login_attempts || 0;

    document.getElementById('editModal').style.display = 'block';
}

function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Add User';
    document.getElementById('editForm').action = 'control_panel/users/add_user.php';

    document.getElementById('editUserId').value = '';
    document.getElementById('editUsername').value = '';
    document.getElementById('editPassword').value = '';
    document.getElementById('editConfirmPassword').value = '';
    document.getElementById('editEmail').value = '';
    document.getElementById('editPhone').value = '';
    document.getElementById('editAccountType').value = 'user';
    document.getElementById('editIsActive').value = '1';
    document.getElementById('editIsLocked').value = '0';
    document.getElementById('editLoginAttempts').value = '0';

    document.getElementById('editModal').style.display = 'block';
}

function openDeleteModal(userId, username) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUserName').innerText = username;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function toggleSection(id) {
    const section = document.getElementById(id);
    const btn = section.previousElementSibling;
    const isVisible = section.style.display === 'block';
    section.style.display = isVisible ? 'none' : 'block';
    btn.innerHTML = (isVisible ? '▶' : '▼') + ' ' + btn.innerHTML.slice(2);
}

document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            const password = document.getElementById('editPassword').value;
            const confirm = document.getElementById('editConfirmPassword').value;

            if (password && password !== confirm) {
                e.preventDefault();
                alert("Passwords do not match!");
            }
        });
    }
});
</script>

<!-- Optional: Add User Button Styling -->
<style>
.btn-add {
    background-color: #28a745;
    color: white;
    padding: 8px 16px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.btn-add:hover {
    background-color: #218838;
}
</style>
