<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header('location:login.php');
    exit();
} else {
    $userRole = $_SESSION['role'] ?? 'Tidak login';
    if ($userRole !== 'admin') {
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
    <style>
        body {
            background: var(--color-bg-admin);
        }
    </style>
<body>
    <?php include('partials/navbar.php') ?>
    <?php include('partials/footer.php') ?>
    <?php
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $id = (int)$_GET['id'];

    $gambar = null; 

    if ($type == "merk") {
        $gambar = 'logo'; 
    } else if ($type == "parfum") {
        $gambar = 'gambar'; 
    }

    if ($gambar && ($type == "merk" || $type == "parfum")) {
        $qGambar = mysqli_query($conn, "SELECT $gambar FROM $type WHERE id_{$type} = $id");
        $row = mysqli_fetch_assoc($qGambar);

        if ($row && isset($row[$gambar])) {
            $photoPath = 'assets/' . $row[$gambar]; 
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
    }

    $sql = "DELETE FROM $type WHERE id_{$type} = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>
            showAlert("sukses", "Berhasil", "Data berhasil dihapus.");
            setTimeout(function() {
                window.location.href = "' . $type . '.php";
            }, 1000);
        </script>';
    } else {
        echo '<script>
            showAlert("danger", "Gagal", "Gagal menghapus data.");
            setTimeout(function() {
                window.location.href = "' . $type . '.php";
            }, 1000);
        </script>';
    }
    ?>
</body>
</html>
