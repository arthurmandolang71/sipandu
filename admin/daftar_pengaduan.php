<?php 
include 'includes/header.php'; 

// Filter logic
$where = "";
if (isset($_GET['status']) && $_GET['status'] != '') {
    $where = " WHERE p.id_status = " . intval($_GET['status']);
}

$sql = "SELECT p.*, b.nama_bidang, s.nama_status 
        FROM tb_pengaduan p 
        JOIN tb_bidang b ON p.id_bidang = b.id_bidang 
        JOIN tb_status s ON p.id_status = s.id_status 
        $where
        ORDER BY p.created_at DESC";
$stmt = $pdo->query($sql);
$data = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Semua Pengaduan</h2>
        <p class="text-muted">Daftar seluruh laporan masyarakat yang masuk.</p>
    </div>
    <div class="col-md-6 text-end">
        <form class="d-inline-block" method="GET">
            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <?php
                $s_stmt = $pdo->query("SELECT * FROM tb_status");
                while ($s = $s_stmt->fetch()) {
                    $sel = (isset($_GET['status']) && $_GET['status'] == $s['id_status']) ? 'selected' : '';
                    echo "<option value='{$s['id_status']}' $sel>{$s['nama_status']}</option>";
                }
                ?>
            </select>
        </form>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama & HP</th>
                    <th>Bidang</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row) : ?>
                <tr>
                    <td><code><?php echo $row['id_pengaduan']; ?></code></td>
                    <td>
                        <strong><?php echo $row['nama_masyarakat']; ?></strong><br>
                        <small class="text-muted"><?php echo $row['nomor_hp']; ?></small>
                    </td>
                    <td><?php echo $row['nama_bidang']; ?></td>
                    <td>
                        <span class="badge bg-<?php 
                            echo ($row['nama_status'] == 'Selesai' ? 'success' : ($row['nama_status'] == 'Diproses' ? 'info' : 'warning')); 
                        ?>">
                            <?php echo $row['nama_status']; ?>
                        </span>
                    </td>
                    <td><small><?php echo date('d/m/Y H:i', strtotime($row['tanggal_jam_pengaduan'])); ?></small></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo str_replace('-','',$row['id_pengaduan']); ?>">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($data) == 0) echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Tidak ada data ditemukan.</td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Render Modals Outside Table -->
<?php foreach ($data as $row) : ?>
<div class="modal fade" id="detailModal<?php echo str_replace('-','',$row['id_pengaduan']); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Pengaduan <?php echo $row['id_pengaduan']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Data Pelapor</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="120" class="text-muted">Nama</td><td>: <?php echo $row['nama_masyarakat']; ?></td></tr>
                            <tr><td class="text-muted">NIK</td><td>: <?php echo $row['nik_pelapor'] ?: '-'; ?></td></tr>
                            <tr><td class="text-muted">HP</td><td>: <?php echo $row['nomor_hp']; ?></td></tr>
                            <tr><td class="text-muted">Alamat</td><td>: <?php echo $row['alamat_lengkap']; ?></td></tr>
                            <tr><td class="text-muted">Lokasi</td><td>: <?php echo $row['kelurahan']; ?>, <?php echo $row['kecamatan']; ?></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Pengaduan</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="120" class="text-muted">Bidang</td><td>: <?php echo $row['nama_bidang']; ?></td></tr>
                            <tr><td class="text-muted">Status</td><td>: <span class="badge bg-<?php echo ($row['nama_status'] == 'Selesai' ? 'success' : ($row['nama_status'] == 'Diproses' ? 'info' : 'warning')); ?>"><?php echo $row['nama_status']; ?></span></td></tr>
                            <tr><td class="text-muted">Tanggal</td><td>: <?php echo date('d M Y H:i', strtotime($row['tanggal_jam_pengaduan'])); ?></td></tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="fw-bold">Uraian Pengaduan:</h6>
                <p class="bg-light p-3 rounded small"><?php echo nl2br($row['uraian_pengaduan']); ?></p>
                
                <?php if ($row['nama_status'] == 'Selesai') : ?>
                    <hr>
                    <h6 class="text-success fw-bold">Penyelesaian:</h6>
                    <p class="border border-success border-opacity-25 p-3 rounded small bg-success bg-opacity-10"><?php echo nl2br($row['uraian_penyelesaian']); ?></p>
                    <small class="text-muted">Diselesaikan pada: <?php echo date('d M Y H:i', strtotime($row['tanggal_jam_selesai'])); ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
