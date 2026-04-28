<?php include 'includes/header.php'; 

// Fetch Avg Resolution Time
$avgStmt = $pdo->query("SELECT AVG(TIMESTAMPDIFF(HOUR, tanggal_jam_pengaduan, tanggal_jam_selesai)) as avg_hours FROM tb_pengaduan WHERE id_status = (SELECT id_status FROM tb_status WHERE nama_status='Selesai')");
$avgHours = $avgStmt->fetchColumn();
$avgText = "-";
if ($avgHours) {
    $days = floor($avgHours / 24);
    $hours = floor($avgHours % 24);
    $avgText = ($days > 0 ? $days . " Hari " : "") . $hours . " Jam";
}

// Data for Trend Chart
$trendStmt = $pdo->query("SELECT DATE_FORMAT(tanggal_jam_pengaduan, '%M %Y') as bulan, COUNT(*) as total FROM tb_pengaduan GROUP BY DATE_FORMAT(tanggal_jam_pengaduan, '%M %Y'), DATE_FORMAT(tanggal_jam_pengaduan, '%Y-%m') ORDER BY DATE_FORMAT(tanggal_jam_pengaduan, '%Y-%m') ASC LIMIT 6");
$trendLabels = []; $trendData = [];
while ($row = $trendStmt->fetch()) {
    $trendLabels[] = $row['bulan'];
    $trendData[] = $row['total'];
}

// Data for Bidang Chart
$bidangStmt = $pdo->query("SELECT b.nama_bidang, COUNT(p.id_pengaduan) as total FROM tb_bidang b LEFT JOIN tb_pengaduan p ON b.id_bidang = p.id_bidang GROUP BY b.id_bidang, b.nama_bidang");
$bidangLabels = []; $bidangData = [];
while ($row = $bidangStmt->fetch()) {
    $bidangLabels[] = $row['nama_bidang'];
    $bidangData[] = $row['total'];
}

// Data for Sumber Chart
$sumberStmt = $pdo->query("SELECT s.nama_sumber, COUNT(p.id_pengaduan) as total FROM tb_sumber s LEFT JOIN tb_pengaduan p ON s.id_sumber = p.id_sumber GROUP BY s.id_sumber, s.nama_sumber");
$sumberLabels = []; $sumberData = [];
while ($row = $sumberStmt->fetch()) {
    $sumberLabels[] = $row['nama_sumber'];
    $sumberData[] = $row['total'];
}

?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1 brand-font">Dashboard & Analytics</h2>
        <p class="text-muted mb-0">Pemantauan kinerja dan statistik pengaduan masyarakat.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="export_pengaduan.php" class="btn btn-sm btn-outline-success fw-600 px-3 rounded-pill me-2"><i class="fas fa-file-excel me-1"></i> Export Data</a>
        <span class="text-muted small fw-600 bg-white px-3 py-2 rounded-pill shadow-sm border border-light-border"><i class="fas fa-calendar-alt me-2 text-primary"></i> <?php echo date('d M Y'); ?></span>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <?php
    $counts = [
        'Total Pengaduan' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan")->fetchColumn(), 'icon' => 'fa-inbox', 'color' => 'primary', 'bg' => 'bg-primary'],
        'Menunggu' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Menunggu'")->fetchColumn(), 'icon' => 'fa-hourglass-half', 'color' => 'warning', 'bg' => 'bg-warning'],
        'Selesai' => ['count' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Selesai'")->fetchColumn(), 'icon' => 'fa-check-circle', 'color' => 'success', 'bg' => 'bg-success'],
        'Avg. Penyelesaian' => ['count' => $avgText, 'icon' => 'fa-tachometer-alt', 'color' => 'info', 'bg' => 'bg-info'],
    ];

    foreach ($counts as $label => $data) :
    ?>
    <div class="col-md-6 col-xl-3">
        <div class="card p-4 h-100 card-hover-lift border-0">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-bold text-uppercase ls-1 mb-2"><?php echo $label; ?></p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: <?php echo $label == 'Avg. Penyelesaian' ? '1.2rem' : '1.8rem'; ?>;"><?php echo $data['count']; ?></h3>
                </div>
                <div class="<?php echo $data['bg']; ?> bg-opacity-10 p-3 rounded-circle text-<?php echo $data['color']; ?>">
                    <i class="fas <?php echo $data['icon']; ?> fa-lg"></i>
                </div>
            </div>
            <?php if($label == 'Avg. Penyelesaian'): ?>
                <div class="mt-3 text-muted small fw-500"><i class="fas fa-chart-line text-info me-1"></i> Rata-rata dari tiket selesai</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card p-4 border-0 h-100">
            <h6 class="fw-bold brand-font mb-4">Tren Pengaduan (6 Bulan Terakhir)</h6>
            <canvas id="trendChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-4 border-0 h-100">
            <h6 class="fw-bold brand-font mb-4">Distribusi per Bidang</h6>
            <canvas id="bidangChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="card p-4 border-0">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark brand-font"><i class="fas fa-bolt text-warning me-2"></i> Pengaduan Terbaru & Urgent</h5>
                <a href="daftar_pengaduan.php" class="btn btn-sm btn-light fw-600 text-primary px-3 rounded-pill">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3 text-muted fw-bold">ID TIKET</th>
                            <th class="text-muted fw-bold">PELAPOR</th>
                            <th class="text-muted fw-bold">BIDANG</th>
                            <th class="text-muted fw-bold">STATUS</th>
                            <th class="text-muted fw-bold">UMUR LAPORAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT p.*, b.nama_bidang, s.nama_status, 
                                            TIMESTAMPDIFF(DAY, p.tanggal_jam_pengaduan, NOW()) as days_old 
                                            FROM tb_pengaduan p 
                                            JOIN tb_bidang b ON p.id_bidang = b.id_bidang 
                                            JOIN tb_status s ON p.id_status = s.id_status 
                                            ORDER BY p.created_at DESC LIMIT 5");
                        while ($row = $stmt->fetch()) :
                            $isOverdue = ($row['nama_status'] != 'Selesai' && $row['days_old'] >= 3);
                            $badgeClass = 'badge-soft-warning';
                            if($row['nama_status'] == 'Selesai') $badgeClass = 'badge-soft-success';
                            elseif($row['nama_status'] == 'Diproses') $badgeClass = 'badge-soft-info';
                            
                            $rowClass = $isOverdue ? 'bg-danger-soft border-urgent' : '';
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td class="ps-3">
                                <span class="fw-bold text-dark font-monospace"><?php echo $row['id_pengaduan']; ?></span>
                                <?php if($isOverdue): ?><span class="badge bg-danger ms-2" style="font-size: 0.6rem;">SLA OVERDUE</span><?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center text-muted" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user fa-sm"></i>
                                    </div>
                                    <span class="fw-600 text-dark"><?php echo $row['nama_masyarakat']; ?></span>
                                </div>
                            </td>
                            <td><span class="text-muted fw-500"><i class="fas fa-building fa-sm text-secondary me-1"></i> <?php echo $row['nama_bidang']; ?></span></td>
                            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo strtoupper($row['nama_status']); ?></span></td>
                            <td>
                                <?php if($row['nama_status'] == 'Selesai'): ?>
                                    <span class="text-success small fw-bold"><i class="fas fa-check me-1"></i> Selesai</span>
                                <?php else: ?>
                                    <span class="<?php echo $isOverdue ? 'text-danger fw-bold' : 'text-muted'; ?> small">
                                        <?php echo $row['days_old']; ?> Hari Berlalu
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Shared Colors
    const primaryColor = 'rgba(79, 70, 229, 0.8)';
    const infoColor = 'rgba(14, 165, 233, 0.8)';
    const warningColor = 'rgba(245, 158, 11, 0.8)';
    
    // Trend Chart (Line)
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($trendLabels); ?>,
            datasets: [{
                label: 'Jumlah Pengaduan Masuk',
                data: <?php echo json_encode($trendData); ?>,
                borderColor: primaryColor,
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });

    // Bidang Chart (Doughnut)
    const ctxBidang = document.getElementById('bidangChart').getContext('2d');
    new Chart(ctxBidang, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($bidangLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($bidangData); ?>,
                backgroundColor: [primaryColor, infoColor, warningColor, '#10b981', '#f43f5e'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
