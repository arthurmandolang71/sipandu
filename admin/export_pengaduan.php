<?php
// admin/export_pengaduan.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require_once '../config/database.php';

// Set headers to trigger a file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Data_Pengaduan_SIPANDU_' . date('Y-m-d') . '.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['ID Pengaduan', 'Nama Masyarakat', 'NIK', 'Nomor HP', 'Alamat', 'Kecamatan', 'Kelurahan', 'Bidang', 'Jenis', 'Sumber', 'Status', 'Tanggal Masuk', 'Tanggal Selesai', 'Uraian', 'Penyelesaian']);

// Fetch the data
$sql = "SELECT p.id_pengaduan, p.nama_masyarakat, p.nik_pelapor, p.nomor_hp, p.alamat_lengkap, p.kecamatan, p.kelurahan, 
               b.nama_bidang, j.nama_jenis, sum.nama_sumber, s.nama_status, 
               p.tanggal_jam_pengaduan, p.tanggal_jam_selesai, p.uraian_pengaduan, p.uraian_penyelesaian
        FROM tb_pengaduan p
        LEFT JOIN tb_bidang b ON p.id_bidang = b.id_bidang
        LEFT JOIN tb_jenis j ON p.id_jenis = j.id_jenis
        LEFT JOIN tb_sumber sum ON p.id_sumber = sum.id_sumber
        LEFT JOIN tb_status s ON p.id_status = s.id_status
        ORDER BY p.created_at DESC";

$stmt = $pdo->query($sql);

// Loop through the rows and output them to the CSV
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id_pengaduan'],
        $row['nama_masyarakat'],
        $row['nik_pelapor'],
        $row['nomor_hp'],
        $row['alamat_lengkap'],
        $row['kecamatan'],
        $row['kelurahan'],
        $row['nama_bidang'],
        $row['nama_jenis'],
        $row['nama_sumber'],
        $row['nama_status'],
        $row['tanggal_jam_pengaduan'],
        $row['tanggal_jam_selesai'],
        $row['uraian_pengaduan'],
        $row['uraian_penyelesaian']
    ]);
}

fclose($output);
exit;
