<?php
require_once '../db.php';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_config'])) {
    $stmt = $pdo->prepare("UPDATE configs SET type = ?, content = ? WHERE id = ?");
    $stmt->execute([$_POST['type'], $_POST['content'], $_POST['id']]);
    header("Location: configs.php");
    exit;
}

// Fetch all configs
$stmt = $pdo->query("SELECT * FROM configs ORDER BY id DESC");
$configs = $stmt->fetchAll();
?>

<!-- Configs Dropdown -->
<div class="user-section">
    <button class="dropdown-btn" onclick="toggleSection('configsTable')">▶ Configs</button>
    <div id="configsTable" class="table-wrapper table-content" style="display: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Content</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($configs as $cfg): ?>
                    <tr>
                        <td><?= htmlspecialchars($cfg['id']) ?></td>
                        <td><?= htmlspecialchars($cfg['type']) ?></td>
                        <td><?= nl2br(htmlspecialchars($cfg['content'])) ?></td>
                        <td><?= htmlspecialchars($cfg['created_at']) ?></td>
                        <td><?= htmlspecialchars($cfg['updated_at']) ?></td>
                        <td class="actions">
                            <i class="fa fa-pen-to-square" title="Edit" onclick='openEditModal(<?= json_encode($cfg) ?>)'></i>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <div class="modal-header">Edit Config</div>
        <form method="POST">
            <input type="hidden" name="id" id="editId">

            <label>Type:</label>
            <input type="text" name="type" id="editType" required><br><br>

            <label>Content:</label>
            <textarea name="content" id="editContent" rows="5" required style="width:100%; resize: vertical;"></textarea><br><br>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" name="update_config" class="btn-save">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(config) {
    document.getElementById('editId').value = config.id;
    document.getElementById('editType').value = config.type;
    document.getElementById('editContent').value = config.content;
    document.getElementById('editModal').style.display = 'block';
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
</script>
