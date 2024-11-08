<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php include('partials/head.php') ?>
    <style>
        body {
            background: linear-gradient(100deg, #fdeded 0%, #c9a6a6 100%);
        }
    </style>
<body>
    <?php include('partials/navbar.php') ?>
    <?php include('partials/footer.php') ?>

    <?php
    $idPengguna = $_SESSION['id_pengguna'];
    $idParfum = $_GET['id_parfum']; 
    $sql = "DELETE FROM ulasan WHERE id_pengguna = '$idPengguna' AND id_parfum = '$idParfum'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>
            showAlert("sukses", "Berhasil", "Data berhasil dihapus.");
            setTimeout(function() {
                window.location.href = "tampilUlasan.php";
            }, 1000);
        </script>';
    } else {
        echo '<script>
            showAlert("danger", "Gagal", "Gagal menghapus data.");
            setTimeout(function() {
                window.location.href = "tampilUlasan.php";
            }, 1000);
        </script>';
    } ?>
</body>
</html>