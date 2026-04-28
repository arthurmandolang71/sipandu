<?php 
include 'includes/header.php'; 

$message = "";
if (isset($_POST['add'])) {
    $nama     = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];
    $bidang   = ($role == 'operator') ? $_POST['id_bidang'] : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO tb_users (nama_user, username, password, role, id_bidang) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $username, $password, $role, $bidang]);
        $message = "User berhasil ditambahkan!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tb_users WHERE id_user = ?");
    $stmt->execute([$id]);
    $message = "User berhasil dihapus!";
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen User</h2>
        <p class="text-muted">Kelola akun admin dan operator bidang.</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah User
        </button>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Bidang</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT u.*, b.nama_bidang FROM tb_users u LEFT JOIN tb_bidang b ON u.id_bidang = b.id_bidang ORDER BY u.role ASC");
                while ($row = $stmt->fetch()) :
                ?>
                <tr>
                    <td><?php echo $row['nama_user']; ?></td>
                    <td><code><?php echo $row['username']; ?></code></td>
                    <td><span class="badge bg-<?php echo ($row['role'] == 'admin' ? 'dark' : 'secondary'); ?>"><?php echo ucfirst($row['role']); ?></span></td>
                    <td><?php echo $row['nama_bidang'] ?? '-'; ?></td>
                    <td class="text-center">
                        <?php if ($row['username'] !== 'admin') : ?>
                        <a href="?delete=<?php echo $row['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_user" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="operator">Operator</option>
                    </select>
                </div>
                <div class="mb-3 d-none" id="bidangGroup">
                    <label class="form-label">Bidang</label>
                    <select name="id_bidang" class="form-select">
                        <?php
                        $b_stmt = $pdo->query("SELECT * FROM tb_bidang");
                        while ($b = $b_stmt->fetch()) echo "<option value='{$b['id_bidang']}'>{$b['nama_bidang']}</option>";
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="add" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const bidangGroup = document.getElementById('bidangGroup');
    if (this.value === 'operator') {
        bidangGroup.classList.remove('d-none');
    } else {
        bidangGroup.classList.add('d-none');
    }
});
</script>

<?php include 'includes/footer.php'; ?>
