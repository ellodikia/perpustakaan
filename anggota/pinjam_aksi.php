<?php
session_start();
include '../assets/koneksi.php'; 

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id_buku'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id_buku']);
    $id_member = $_SESSION['id_anggota'];
    $tgl_pinjam = date('Y-m-d');
    $jatuh_tempo = date('Y-m-d', strtotime('+7 days'));

    $cek_double = mysqli_query($koneksi, "SELECT * FROM transaksi 
                                          WHERE id_anggota = '$id_member' 
                                          AND id_buku = '$id_buku' 
                                          AND status = 'dipinjam'");
    
    if (mysqli_num_rows($cek_double) > 0) {
        echo "<script>alert('Gagal! Anda sedang meminjam buku ini.'); window.location='pinjam_buku.php';</script>";
        exit();
    }

    $cek_stok = mysqli_query($koneksi, "SELECT stok_tersedia, judul_buku FROM buku WHERE id_buku = '$id_buku'");
    $b = mysqli_fetch_assoc($cek_stok);

    if ($b && $b['stok_tersedia'] > 0) {
        $query_pinjam = "INSERT INTO transaksi (id_anggota, id_buku, tanggal_pinjam, jatuh_tempo, status) 
                         VALUES ('$id_member', '$id_buku', '$tgl_pinjam', '$jatuh_tempo', 'dipinjam')";
        
        if (mysqli_query($koneksi, $query_pinjam)) {
            mysqli_query($koneksi, "UPDATE buku SET stok_tersedia = stok_tersedia - 1 WHERE id_buku = '$id_buku'");
            
            echo "<script>alert('Buku berhasil dipinjam! Silakan ambil di perpustakaan.'); window.location='riwayat_peminjaman.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan sistem saat memproses.'); window.location='pinjam_buku.php';</script>";
        }
    } else {
        echo "<script>alert('Maaf, stok buku ini sudah habis!'); window.location='pinjam_buku.php';</script>";
    }
} else {
    header("Location: pinjam_buku.php");
    exit();
}
?>