<?php 
include 'includes/header.php'; 

$message = "";
if (isset($_POST['submit'])) {
    // Generate ID: PENG-YYYYMMDD-XXXX
    $datePart = date('Ymd');
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM tb_pengaduan WHERE id_pengaduan LIKE ?");
    $stmtCount->execute(["PENG-$datePart-%"]);
    $count = $stmtCount->fetchColumn() + 1;
    $id_pengaduan = "PENG-" . $datePart . "-" . str_pad($count, 3, "0", STR_PAD_LEFT);

    // Get default status (Menunggu)
    $stmtStatus = $pdo->query("SELECT id_status FROM tb_status WHERE nama_status = 'Menunggu' LIMIT 1");
    $id_status = $stmtStatus->fetchColumn();

    // Fallback if 'Menunggu' is not found
    if (!$id_status) {
        $stmtStatus = $pdo->query("SELECT id_status FROM tb_status LIMIT 1");
        $id_status = $stmtStatus->fetchColumn();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO tb_pengaduan (
            id_pengaduan, nama_masyarakat, nik_pelapor, nomor_hp, alamat_lengkap, 
            kecamatan, kelurahan, id_jenis, id_sumber, id_bidang, 
            tanggal_jam_pengaduan, uraian_pengaduan, id_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $id_pengaduan, $_POST['nama'], $_POST['nik'], $_POST['hp'], $_POST['alamat'],
            $_POST['kecamatan'], $_POST['kelurahan'], $_POST['id_jenis'], $_POST['id_sumber'], $_POST['id_bidang'],
            $_POST['tanggal'], $_POST['uraian'], $id_status
        ]);
        $message = "Pengaduan berhasil dicatat! ID: <strong>$id_pengaduan</strong>";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Pencatatan Pengaduan</h2>
        <p class="text-muted">Input data pengaduan masyarakat yang diterima.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success shadow-sm" role="alert">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="card p-4 shadow-sm border-0">
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nama Masyarakat</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">NIK Pelapor <small class="text-muted">(Opsional)</small></label>
                <input type="text" name="nik" class="form-control" placeholder="Nomor Induk Kependudukan">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nomor HP / WhatsApp</label>
                <input type="text" name="hp" class="form-control" placeholder="08xxxxxxxx" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tanggal & Jam Pengaduan</label>
                <input type="datetime-local" name="tanggal" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" required></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Kecamatan</label>
                <input type="text" name="kecamatan" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Kelurahan</label>
                <input type="text" name="kelurahan" class="form-control" required>
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Jenis Pengaduan</label>
                <select name="id_jenis" class="form-select" required>
                    <?php
                    $j_stmt = $pdo->query("SELECT * FROM tb_jenis");
                    while ($j = $j_stmt->fetch()) echo "<option value='{$j['id_jenis']}'>{$j['nama_jenis']}</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Sumber Pengaduan</label>
                <select name="id_sumber" class="form-select" required>
                    <?php
                    $s_stmt = $pdo->query("SELECT * FROM tb_sumber");
                    while ($s = $s_stmt->fetch()) echo "<option value='{$s['id_sumber']}'>{$s['nama_sumber']}</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Diteruskan Ke Bidang</label>
                <select name="id_bidang" class="form-select" required>
                    <?php
                    $b_stmt = $pdo->query("SELECT * FROM tb_bidang");
                    while ($b = $b_stmt->fetch()) echo "<option value='{$b['id_bidang']}'>{$b['nama_bidang']}</option>";
                    ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Uraian Singkat Pengaduan</label>
            <textarea name="uraian" class="form-control" rows="4" required></textarea>
        </div>

        <div class="text-end">
            <button type="reset" class="btn btn-light px-4">Reset</button>
            <button type="submit" name="submit" class="btn btn-primary px-5">Simpan Pengaduan</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
