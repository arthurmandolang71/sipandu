<?php 
include 'includes/header.php'; 

// Handle CRUD Logic
$message = "";
if (isset($_POST['add'])) {
    $nama = $_POST['nama_sumber'];
    $stmt = $pdo->prepare("INSERT INTO tb_sumber (nama_sumber) VALUES (?)");
    $stmt->execute([$nama]);
    $message = "Sumber berhasil ditambahkan!";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tb_sumber WHERE id_sumber = ?");
    $stmt->execute([$id]);
    $message = "Sumber berhasil dihapus!";
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen Sumber</h2>
        <p class="text-muted">Kelola data bidang pengaduan di Disdukcapil.</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Sumber
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
                    <th>Nama Sumber</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM tb_sumber ORDER BY id_sumber ASC");
                $no = 1;
                while ($row = $stmt->fetch()) :
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_sumber']; ?></td>
                    <td class="text-center">
                        <a href="?delete=<?php echo $row['id_sumber']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
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
                <h5 class="modal-title">Tambah Sumber</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Sumber</label>
                    <input type="text" name="nama_sumber" class="form-control" required>
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
