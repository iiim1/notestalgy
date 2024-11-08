<?php 
include('koneksi.php');
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$idPengguna = $_SESSION['id_pengguna'];

$sql = "SELECT 
            u.rating,
            u.komentar,
            u.tanggal_buat,
            u.tanggal_ubah,
            p.id_parfum,
            p.nama AS nama_parfum
        FROM 
            ulasan u
        INNER JOIN
            parfum p ON u.id_parfum = p.id_parfum
        WHERE 
            u.id_pengguna = $idPengguna";


$ulasanPengguna = mysqli_query($conn, $sql);

if (!$ulasanPengguna) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
    <body>
    <?php include('partials/navbar.php') ?>
    <div class="container">
        <div class="list-review">
            <?php if (mysqli_num_rows($ulasanPengguna) > 0) { ?>
                <?php while ($ulasan = mysqli_fetch_assoc($ulasanPengguna)) { ?>
                    <div class="CUD-ulasan">
                    <a href="editUlasan.php?id_parfum=<?= $ulasan['id_parfum']; ?>" class="action-button">
                            <img src="assets/edit.png" alt="edit">
                        </a>
                        <a href="hapusUlasan.php?id_parfum=<?= $ulasan['id_parfum'] ?>" class="action-button">
                            <img src="assets/delete.png" alt="hapus">
                        </a>
                    </div>
                    <div class="review-item">
                        <h4><?= htmlspecialchars($ulasan['nama_parfum']); ?></h4>
                        <p><strong>Rating:</strong> 
                            <span class="rating">
                            <?php 
                                echo str_repeat('★', $ulasan['rating']) . str_repeat('☆', 5 - $ulasan['rating']);
                            ?>
                            </span>
                        </p>
                        <p><small><?= htmlspecialchars($ulasan['tanggal_buat']); ?></small></p>
                        <p><?= htmlspecialchars($ulasan['komentar']); ?></p>
                    </div>
                <?php } ?>
                <?php } else { ?>
                    <div class="review-item">
                        <h5>Anda Belum membuat ulasan apapun</h5>
                    </div>
                <?php } ?>
        </div>
    </div>

    <?php include('partials/footer.php') ?>
    </body>
</html>
