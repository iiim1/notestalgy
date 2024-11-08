<?php
    include "koneksi.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $userRole = $_SESSION['role'] ?? 'Tidak login'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $halamanSekarang = basename($_SERVER['SCRIPT_NAME']);

    $judul = [
        'index.php' => 'Notestalgy | Temukan Aroma Khas yang Anda Cintai.',
        'tampilNotes.php' => 'Notes | Notestalgy',
        'tampilnotes.php' => 'Notes | Notestalgy',  // Lowercase version
        'tampilMerk.php' => 'Merk | Notestalgy',
        'tampilmerk.php' => 'Merk | Notestalgy',  // Lowercase version
        'tampilParfum.php' => 'Parfum | Notestalgy',
        'tampilparfum.php' => 'Parfum | Notestalgy',  // Lowercase version
        'registrasi.php' => 'Daftarkan Akun Anda | Notestalgy',
        'login.php' => 'Masuk ke Akun Anda | Notestalgy',
        'dashboard.php' => 'Dashboard Admin | Notestalgy',
        'notes.php' => 'Notes | Notestalgy',
        'merk.php' => 'Merk | Notestalgy',
        'parfum.php' => 'Parfum | Notestalgy',
        'ulasan.php' => 'Ulasan | Notestalgy',
        'pengguna.php' => 'Pengguna | Notestalgy',
        'tambahNotes.php' => 'Tambah Notes | Notestalgy',
        'tambahnotes.php' => 'Tambah Notes | Notestalgy',  // Lowercase version
        'tambahMerk.php' => 'Tambah Merk | Notestalgy',
        'tambahmerk.php' => 'Tambah Merk | Notestalgy',  // Lowercase version
        'tambahParfum.php' => 'Tambah Parfum | Notestalgy',
        'tambahparfum.php' => 'Tambah Parfum | Notestalgy',  // Lowercase version
        'tambahUlasan.php' => 'Tambah Ulasan | Notestalgy',
        'tambahulasan.php' => 'Tambah Ulasan | Notestalgy',  // Lowercase version
        'tambahUser.php' => 'Tambah Pengguna | Notestalgy',
        'tambahuser.php' => 'Tambah Pengguna | Notestalgy',  // Lowercase version
        'editNotes.php' => 'Perbarui Notes | Notestalgy',
        'editnotes.php' => 'Perbarui Notes | Notestalgy',  // Lowercase version
        'editMerk.php' => 'Perbarui Merk | Notestalgy',
        'editmerk.php' => 'Perbarui Merk | Notestalgy',  // Lowercase version
        'editParfum.php' => 'Perbarui Parfum | Notestalgy',
        'editparfum.php' => 'Perbarui Parfum | Notestalgy',  // Lowercase version
        'editUlasan.php' => 'Perbarui Ulasan | Notestalgy',
        'editulasan.php' => 'Perbarui Ulasan | Notestalgy',  // Lowercase version
        'editUser.php' => 'Perbarui Pengguna | Notestalgy',
        'edituser.php' => 'Perbarui Pengguna | Notestalgy',  // Lowercase version
        'detailParfum.php' => 'Detail Parfum | Notestalgy',
        'detailparfum.php' => 'Detail Parfum | Notestalgy',  // Lowercase version
        'tambahUlasan.php' => 'Tambah Ulasan | Notestalgy',
        'tambahulasan.php' => 'Tambah Ulasan | Notestalgy',  // Lowercase version
    ];

    if (array_key_exists($halamanSekarang, $judul)) {
        echo '<title>' . $judul[$halamanSekarang] . '</title>';
    }
    ?>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="assets/icon.png">
</head>