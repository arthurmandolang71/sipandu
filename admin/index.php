<?php include 'includes/header.php'; ?>

<div class="row mb-5 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1 brand-font">Dashboard Overview</h2>
        <p class="text-muted mb-0">Pantau dan kelola laporan masyarakat secara real-time.</p>
    </div>
    <div class="col-md-4 text-end">
        <span class="text-muted small fw-600 bg-white px-3 py-2 rounded-pill shadow-sm border border-light-border"><i class="fas fa-calendar-alt me-2 text-primary"></i> <?php echo date('d F Y'); ?></span>
    </div>
</div>

<div class="row g-4 mb-5">
    <?php
    // Get counts
    $counts = [
        'Total Pengaduan' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan")->fetchColumn(), 'icon' => 'fa-inbox', 'color' => 'primary', 'bg' => 'bg-primary'],
        'Menunggu' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Menunggu'")->fetchColumn(), 'icon' => 'fa-hourglass-half', 'color' => 'warning', 'bg' => 'bg-warning'],
        'Diproses' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Diproses'")->fetchColumn(), 'icon' => 'fa-cogs', 'color' => 'info', 'bg' => 'bg-info'],
        'Selesai' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Selesai'")->fetchColumn(), 'icon' => 'fa-check-circle', 'color' => 'success', 'bg' => 'bg-success'],
    ];

    foreach ($counts as $label => $data) :
    ?>
    <div class="col-md-6 col-xl-3">
        <div class="card p-4 h-100 card-hover-lift border-0">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-bold text-uppercase ls-1 mb-2"><?php echo $label; ?></p>
                    <h2 class="fw-bold mb-0 text-dark"><?php echo $data['count']; ?></h2>
                </div>
                <div class="<?php echo $data['bg']; ?> bg-opacity-10 p-3 rounded-circle text-<?php echo $data['color']; ?>">
                    <i class="fas <?php echo $data['icon']; ?> fa-lg"></i>
                </div>
            </div>
            <?php if($label == 'Total Pengaduan'): ?>
                <div class="mt-3 text-muted small fw-500"><i class="fas fa-arrow-up text-success me-1"></i> Semua laporan masuk</div>
            <?php elseif($label == 'Menunggu'): ?>
                <div class="mt-3 text-muted small fw-500"><i class="fas fa-exclamation-circle text-warning me-1"></i> Perlu verifikasi</div>
            <?php elseif($label == 'Diproses'): ?>
                <div class="mt-3 text-muted small fw-500"><i class="fas fa-spinner fa-spin text-info me-1"></i> Sedang ditangani</div>
            <?php elseif($label == 'Selesai'): ?>
                <div class="mt-3 text-muted small fw-500"><i class="fas fa-check text-success me-1"></i> Laporan terselesaikan</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card p-4 border-0">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark brand-font"><i class="fas fa-bolt text-warning me-2"></i> Pengaduan Terbaru</h5>
                <a href="daftar_pengaduan.php" class="btn btn-sm btn-light fw-600 text-primary px-3 rounded-pill">Lihat Semua Laporan <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3 text-muted fw-bold">ID TIKET</th>
                            <th class="text-muted fw-bold">PELAPOR</th>
                            <th class="text-muted fw-bold">BIDANG PENANGANAN</th>
                            <th class="text-muted fw-bold">STATUS</th>
                            <th class="text-muted fw-bold">TANGGAL MASUK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT p.*, b.nama_bidang, s.nama_status 
                                            FROM tb_pengaduan p 
                                            JOIN tb_bidang b ON p.id_bidang = b.id_bidang 
                                            JOIN tb_status s ON p.id_status = s.id_status 
                                            ORDER BY p.created_at DESC LIMIT 5");
                        while ($row = $stmt->fetch()) :
                            
                            $badgeClass = 'badge-soft-warning';
                            if($row['nama_status'] == 'Selesai') $badgeClass = 'badge-soft-success';
                            if($row['nama_status'] == 'Diproses') $badgeClass = 'badge-soft-info';
                        ?>
                        <tr>
                            <td class="ps-3"><span class="fw-bold text-dark font-monospace"><?php echo $row['id_pengaduan']; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center text-muted" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user fa-sm"></i>
                                    </div>
                                    <span class="fw-600 text-dark"><?php echo $row['nama_masyarakat']; ?></span>
                                </div>
                            </td>
                            <td><span class="text-muted fw-500"><i class="fas fa-building fa-sm text-secondary me-1"></i> <?php echo $row['nama_bidang']; ?></span></td>
                            <td>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo strtoupper($row['nama_status']); ?>
                                </span>
                            </td>
                            <td><span class="text-muted small fw-600"><?php echo date('d M Y, H:i', strtotime($row['tanggal_jam_pengaduan'])); ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($stmt->rowCount() == 0) echo "<tr><td colspan='5' class='text-center py-5 text-muted'><div class='empty-state'><i class='fas fa-inbox fa-3x mb-3 text-light-border'></i><br>Belum ada data pengaduan masuk.</div></td></tr>"; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
