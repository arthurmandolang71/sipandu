<?php include 'includes/header.php'; 

// Fetch Operator Stats
$bidang_id = $_SESSION['id_bidang'];
$pendingCount = $pdo->prepare("SELECT COUNT(*) FROM tb_pengaduan WHERE id_bidang = ? AND id_status != (SELECT id_status FROM tb_status WHERE nama_status='Selesai')");
$pendingCount->execute([$bidang_id]);
$pending = $pendingCount->fetchColumn();

$completedToday = $pdo->prepare("SELECT COUNT(*) FROM tb_pengaduan WHERE id_bidang = ? AND id_status = (SELECT id_status FROM tb_status WHERE nama_status='Selesai') AND DATE(tanggal_jam_selesai) = CURDATE()");
$completedToday->execute([$bidang_id]);
$completed = $completedToday->fetchColumn();

// Fetch Overdue Count (SLA > 3 Days)
$overdueCount = $pdo->prepare("SELECT COUNT(*) FROM tb_pengaduan WHERE id_bidang = ? AND id_status != (SELECT id_status FROM tb_status WHERE nama_status='Selesai') AND TIMESTAMPDIFF(DAY, tanggal_jam_pengaduan, NOW()) >= 3");
$overdueCount->execute([$bidang_id]);
$overdue = $overdueCount->fetchColumn();
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1 brand-font">Daftar Pengaduan Masuk</h2>
        <p class="text-muted mb-0">Menampilkan laporan masyarakat yang diteruskan ke bidang <strong><?php echo $bidang_name; ?></strong>.</p>
    </div>
    <div class="col-md-4 text-end">
        <span class="text-muted small fw-600 bg-white px-3 py-2 rounded-pill shadow-sm border border-light-border"><i class="fas fa-calendar-alt me-2 text-info"></i> <?php echo date('d F Y'); ?></span>
    </div>
</div>

<!-- Daily Reminders & Stats -->
<div class="row mb-5 g-4">
    <div class="col-md-12 col-lg-6">
        <?php if($overdue > 0): ?>
            <div class="alert alert-danger border-0 d-flex align-items-center p-4 rounded-4 h-100 shadow-sm" style="background-color: #fff1f2;">
                <i class="fas fa-exclamation-triangle fa-3x text-danger me-4 opacity-75"></i>
                <div>
                    <h5 class="fw-bold text-danger mb-1 brand-font">Peringatan Keterlambatan (SLA)</h5>
                    <p class="mb-0 text-danger opacity-75">Anda memiliki <strong><?php echo $overdue; ?> pengaduan</strong> yang sudah melewati batas waktu penanganan (Lebih dari 3 hari). Segera prioritaskan!</p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info border-0 d-flex align-items-center p-4 rounded-4 h-100 shadow-sm" style="background-color: #f0f9ff;">
                <i class="fas fa-info-circle fa-3x text-info me-4 opacity-75"></i>
                <div>
                    <h5 class="fw-bold text-info mb-1 brand-font">Ringkasan Harian</h5>
                    <p class="mb-0 text-info opacity-75">Anda memiliki <strong><?php echo $pending; ?> pengaduan</strong> yang menunggu untuk diproses. Tetap semangat!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 p-4 rounded-4 h-100 shadow-sm text-center">
            <h6 class="text-muted small fw-bold text-uppercase ls-1 mb-3">Diselesaikan Hari Ini</h6>
            <h2 class="display-5 fw-bold text-success mb-0 brand-font"><?php echo $completed; ?></h2>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 p-4 rounded-4 h-100 shadow-sm text-center">
            <h6 class="text-muted small fw-bold text-uppercase ls-1 mb-3">Total Antrean</h6>
            <h2 class="display-5 fw-bold text-warning mb-0 brand-font"><?php echo $pending; ?></h2>
        </div>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm rounded-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark brand-font"><i class="fas fa-list-ul text-info me-2"></i> Tugas Pengaduan</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3 text-muted fw-bold">ID TIKET</th>
                    <th class="text-muted fw-bold">PELAPOR</th>
                    <th class="text-muted fw-bold">STATUS</th>
                    <th class="text-muted fw-bold">UMUR LAPORAN</th>
                    <th class="text-center text-muted fw-bold pe-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT p.*, s.nama_status, 
                                      TIMESTAMPDIFF(DAY, p.tanggal_jam_pengaduan, NOW()) as days_old 
                                      FROM tb_pengaduan p 
                                      JOIN tb_status s ON p.id_status = s.id_status 
                                      WHERE p.id_bidang = ? 
                                      ORDER BY (p.id_status = (SELECT id_status FROM tb_status WHERE nama_status='Menunggu')) DESC, p.created_at DESC");
                $stmt->execute([$_SESSION['id_bidang']]);
                
                while ($row = $stmt->fetch()) :
                    $isOverdue = ($row['nama_status'] != 'Selesai' && $row['days_old'] >= 3);
                    $badgeClass = 'badge-soft-warning';
                    if($row['nama_status'] == 'Selesai') $badgeClass = 'badge-soft-success';
                    if($row['nama_status'] == 'Diproses') $badgeClass = 'badge-soft-info';
                    
                    $rowClass = $isOverdue ? 'bg-danger-soft border-urgent' : '';
                ?>
                <tr class="<?php echo $rowClass; ?>">
                    <td class="ps-3">
                        <span class="fw-bold text-dark font-monospace"><?php echo $row['id_pengaduan']; ?></span>
                        <?php if($isOverdue): ?><br><span class="badge bg-danger mt-1" style="font-size: 0.6rem;">URGENT</span><?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center text-muted" style="width: 35px; height: 35px;">
                                <i class="fas fa-user fa-sm"></i>
                            </div>
                            <span class="fw-600 text-dark"><?php echo $row['nama_masyarakat']; ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo strtoupper($row['nama_status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['nama_status'] == 'Selesai'): ?>
                            <span class="text-success small fw-bold"><i class="fas fa-check me-1"></i> Selesai</span>
                        <?php else: ?>
                            <span class="<?php echo $isOverdue ? 'text-danger fw-bold' : 'text-muted'; ?> small">
                                <i class="fas fa-clock me-1"></i> <?php echo $row['days_old']; ?> Hari
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center pe-3">
                        <?php if ($row['nama_status'] != 'Selesai') : ?>
                            <a href="proses_pengaduan.php?id=<?php echo $row['id_pengaduan']; ?>" class="btn btn-sm btn-<?php echo $isOverdue ? 'danger' : 'primary'; ?> rounded-pill px-3 shadow-sm" style="font-weight: 600;">
                                <i class="fas fa-edit me-1"></i> Tangani
                            </a>
                        <?php else : ?>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold" style="border: 1px solid rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-check-double me-1"></i> Selesai
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmt->rowCount() == 0) echo "<tr><td colspan='5' class='text-center py-5 text-muted'><div class='empty-state'><i class='fas fa-check-circle fa-3x mb-3 text-success opacity-50'></i><br>Kerja bagus! Tidak ada pengaduan yang menunggu.</div></td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
