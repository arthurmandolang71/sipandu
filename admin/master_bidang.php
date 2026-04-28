<?php 
include 'includes/header.php'; 

// Handle CRUD Logic
$message = "";
if (isset($_POST['add'])) {
    $nama = $_POST['nama_bidang'];
    $stmt = $pdo->prepare("INSERT INTO tb_bidang (nama_bidang) VALUES (?)");
    $stmt->execute([$nama]);
    $message = "Bidang berhasil ditambahkan!";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tb_bidang WHERE id_bidang = ?");
    $stmt->execute([$id]);
    $message = "Bidang berhasil dihapus!";
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen Bidang</h2>
        <p class="text-muted">Kelola data bidang pengaduan di Disdukcapil.</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Bidang
        </button>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bidang</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM tb_bidang ORDER BY id_bidang ASC");
                $no = 1;
                while ($row = $stmt->fetch()) :
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_bidang']; ?></td>
                    <td class="text-center">
                        <a href="?delete=<?php echo $row['id_bidang']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i>
                        </a>
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
                <h5 class="modal-title">Tambah Bidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Bidang</label>
                    <input type="text" name="nama_bidang" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="add" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
