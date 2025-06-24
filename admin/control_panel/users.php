<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT id, username, account_type, email, phone, created_at, is_active, is_locked FROM users");
$users = $stmt->fetchAll();
?>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Account Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Locked</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['account_type']) ?></td>
                    <td><?= $user['email'] ? htmlspecialchars($user['email']) : '-' ?></td>
                    <td><?= $user['phone'] ? htmlspecialchars($user['phone']) : '-' ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <span class="<?= $user['is_active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td><?= $user['is_locked'] ? 'Yes' : 'No' ?></td>
                    <td class="actions">
                        <i class="fa fa-pen-to-square" title="Edit"
                           onclick='openEditModal(<?= json_encode($user) ?>)'></i>
                        <i class="fa fa-trash" title="Delete"
                           onclick="openDeleteModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')"></i>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <div class="modal-header">Edit User</div>
        <form id="editForm" method="POST" action="control_panel/users/update_user.php">
            <input type="hidden" name="id" id="editUserId">
            
            <label>Username:</label>
            <input type="text" name="username" id="editUsername" required><br><br>

            <label>Password:</label>
            <input type="password" name="password" id="editPassword" placeholder="Leave blank to keep current"><br><br>

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
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editPassword').value = ''; // password not prefilled
    document.getElementById('editEmail').value = user.email || '';
    document.getElementById('editPhone').value = user.phone || '';
    document.getElementById('editAccountType').value = user.account_type;
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
</script>
