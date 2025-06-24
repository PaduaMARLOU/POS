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
                        <i class="fa fa-pen-to-square" title="Edit"></i>
                        <i class="fa fa-trash" title="Delete"></i>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
