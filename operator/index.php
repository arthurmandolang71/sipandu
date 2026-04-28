<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Daftar Pengaduan Bidang</h2>
        <p class="text-muted">Menampilkan laporan yang perlu ditindaklanjuti oleh <strong><?php echo $bidang_name; ?></strong>.</p>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Masyarakat</th>
                    <th>Status</th>
                    <th>Tanggal Masuk</th>
                    <th class="text-center">Aksi</th>
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
                ?>
                <tr>
                    <td><code><?php echo $row['id_pengaduan']; ?></code></td>
                    <td><?php echo $row['nama_masyarakat']; ?></td>
                    <td>
                        <span class="badge bg-<?php 
                            echo ($row['nama_status'] == 'Selesai' ? 'success' : ($row['nama_status'] == 'Diproses' ? 'info' : 'warning')); 
                        ?>">
                            <?php echo $row['nama_status']; ?>
                        </span>
                    </td>
                    <td><small><?php echo date('d/m/Y H:i', strtotime($row['tanggal_jam_pengaduan'])); ?></small></td>
                    <td class="text-center">
                        <?php if ($row['nama_status'] != 'Selesai') : ?>
                            <a href="proses_pengaduan.php?id=<?php echo $row['id_pengaduan']; ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-edit"></i> Tangani
                            </a>
                        <?php else : ?>
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="fas fa-check-double"></i> Selesai
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmt->rowCount() == 0) echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Tidak ada data pengaduan untuk bidang ini.</td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
