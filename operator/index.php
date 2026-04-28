<?php include 'includes/header.php'; ?>

<div class="row mb-5 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1 brand-font">Daftar Pengaduan Masuk</h2>
        <p class="text-muted mb-0">Menampilkan laporan masyarakat yang diteruskan ke bidang <strong><?php echo $bidang_name; ?></strong>.</p>
    </div>
    <div class="col-md-4 text-end">
        <span class="text-muted small fw-600 bg-white px-3 py-2 rounded-pill shadow-sm border border-light-border"><i class="fas fa-calendar-alt me-2 text-info"></i> <?php echo date('d F Y'); ?></span>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm">
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
                    <th class="text-muted fw-bold">TANGGAL MASUK</th>
                    <th class="text-center text-muted fw-bold pe-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT p.*, s.nama_status 
                                      FROM tb_pengaduan p 
                                      JOIN tb_status s ON p.id_status = s.id_status 
                                      WHERE p.id_bidang = ? 
                                      ORDER BY (p.id_status = (SELECT id_status FROM tb_status WHERE nama_status='Menunggu')) DESC, p.created_at DESC");
                $stmt->execute([$_SESSION['id_bidang']]);
                
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
                    <td>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo strtoupper($row['nama_status']); ?>
                        </span>
                    </td>
                    <td><span class="text-muted small fw-600"><?php echo date('d M Y, H:i', strtotime($row['tanggal_jam_pengaduan'])); ?></span></td>
                    <td class="text-center pe-3">
                        <?php if ($row['nama_status'] != 'Selesai') : ?>
                            <a href="proses_pengaduan.php?id=<?php echo $row['id_pengaduan']; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" style="font-weight: 600;">
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
