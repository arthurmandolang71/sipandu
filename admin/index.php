<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Dashboard Admin</h2>
        <p class="text-muted">Selamat datang di Sistem Pengaduan Terpadu.</p>
    </div>
</div>

<div class="row">
    <?php
    // Get counts
    $counts = [
        'Total Pengaduan' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan")->fetchColumn(),
        'Menunggu' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Menunggu'")->fetchColumn(),
        'Diproses' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Diproses'")->fetchColumn(),
        'Selesai' => $pdo->query("SELECT COUNT(*) FROM tb_pengaduan p JOIN tb_status s ON p.id_status = s.id_status WHERE s.nama_status = 'Selesai'")->fetchColumn(),
    ];

    $colors = ['primary', 'warning', 'info', 'success'];
    $i = 0;
    foreach ($counts as $label => $val) :
    ?>
    <div class="col-md-3 mb-4">
        <div class="card p-3 border-start border-4 border-<?php echo $colors[$i++]; ?>">
            <div class="card-body p-0">
                <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo $label; ?></p>
                <h3 class="fw-bold mb-0"><?php echo $val; ?></h3>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Pengaduan Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Masyarakat</th>
                            <th>Bidang</th>
                            <th>Status</th>
                            <th>Tanggal</th>
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
                        ?>
                        <tr>
                            <td><code><?php echo $row['id_pengaduan']; ?></code></td>
                            <td><?php echo $row['nama_masyarakat']; ?></td>
                            <td><?php echo $row['nama_bidang']; ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo ($row['nama_status'] == 'Selesai' ? 'success' : ($row['nama_status'] == 'Diproses' ? 'info' : 'warning')); 
                                ?>">
                                    <?php echo $row['nama_status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_jam_pengaduan'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($stmt->rowCount() == 0) echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada data pengaduan.</td></tr>"; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="daftar_pengaduan.php" class="btn btn-sm btn-outline-primary">Lihat Semua →</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
