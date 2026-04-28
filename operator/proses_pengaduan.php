<?php 
include 'includes/header.php'; 

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, s.nama_status FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE p.id_pengaduan = ? AND p.id_bidang = ?");
$stmt->execute([$id, $_SESSION['id_bidang']]);
$data = $stmt->fetch();

if (!$data) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan atau Anda tidak memiliki akses ke pengaduan ini.</div>";
    include 'includes/footer.php';
    exit;
}

// Update status to 'Diproses' if currently 'Menunggu'
if ($data['nama_status'] == 'Menunggu') {
    $stmtProc = $pdo->prepare("UPDATE tb_pengaduan SET id_status = (SELECT id_status FROM tb_status WHERE nama_status = 'Diproses') WHERE id_pengaduan = ?");
    $stmtProc->execute([$id]);
}

$message = "";
if (isset($_POST['submit'])) {
    $penyelesaian = $_POST['penyelesaian'];
    $tgl_selesai = $_POST['tgl_selesai'];

    $stmtStatus = $pdo->query("SELECT id_status FROM tb_status WHERE nama_status = 'Selesai' LIMIT 1");
    $id_status_selesai = $stmtStatus->fetchColumn();

    try {
        $stmtUpdate = $pdo->prepare("UPDATE tb_pengaduan SET 
            uraian_penyelesaian = ?, 
            tanggal_jam_selesai = ?, 
            id_status = ? 
            WHERE id_pengaduan = ?");
        $stmtUpdate->execute([$penyelesaian, $tgl_selesai, $id_status_selesai, $id]);
        
        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Tangani Pengaduan</h2>
        <p class="text-muted">ID: <strong><?php echo $id; ?></strong></p>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card p-4 shadow-sm border-0 mb-4">
            <h5 class="fw-bold mb-3">Informasi Pengaduan</h5>
            <table class="table table-sm table-borderless">
                <tr><td width="120" class="text-muted">Nama</td><td>: <?php echo $data['nama_masyarakat']; ?></td></tr>
                <tr><td class="text-muted">Nomor HP</td><td>: <?php echo $data['nomor_hp']; ?></td></tr>
                <tr><td class="text-muted">Tanggal Masuk</td><td>: <?php echo date('d/m/Y H:i', strtotime($data['tanggal_jam_pengaduan'])); ?></td></tr>
                <tr><td class="text-muted">Alamat</td><td>: <?php echo $data['alamat_lengkap']; ?></td></tr>
            </table>
            <hr>
            <h6 class="fw-bold">Uraian:</h6>
            <p class="small bg-light p-3 rounded"><?php echo nl2br($data['uraian_pengaduan']); ?></p>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-4 shadow-sm border-0">
            <h5 class="fw-bold mb-3">Input Penyelesaian</h5>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Uraian Penyelesaian</label>
                    <textarea name="penyelesaian" class="form-control" rows="6" placeholder="Jelaskan langkah penyelesaian yang dilakukan..." required></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Tanggal & Jam Selesai</label>
                    <input type="datetime-local" name="tgl_selesai" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                </div>
                <div class="text-end">
                    <a href="index.php" class="btn btn-light px-4">Batal</a>
                    <button type="submit" name="submit" class="btn btn-success px-5">Simpan & Selesai</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
