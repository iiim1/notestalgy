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
<body>
    <?php 
    include('partials/navbar.php');
    $query = mysqli_query($conn, "SELECT * FROM notes WHERE id_notes='" . mysqli_real_escape_string($conn, $_GET['id']) . "'");
    $data = mysqli_fetch_assoc($query); 
    ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form">
            <h2 class="form-title">Perbarui Notes</h2>
            <div class="form-grid">
                <input type="hidden" name="id" value="<?= $data['id_notes'] ?>" >
                <div class="form-group full-width">
                    <label class="form-label" for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" placeholder="Masukkan nama notes" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukkan deskripsi notes" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100%">Simpan Perubahan</button>
        </form>
    </div>
    <?php include('partials/footer.php') ?>
    <?php
    if (isset($_POST['submit'])) {
        $id_notes = mysqli_real_escape_string($conn, $_POST['id']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

        $checkQuery = "SELECT * FROM notes WHERE nama = '$nama' AND id_notes != '$id_notes'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo '<script>
                showAlert("danger", "Gagal", "Nama notes sudah ada");
            </script>';
        } else {
            $sql = "UPDATE notes SET nama='$nama', deskripsi='$deskripsi' WHERE id_notes='$id_notes'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo '<script>
                    showAlert("sukses", "Berhasil", "Berhasil memperbarui notes");
                    setTimeout(function() {
                        window.location.href = "notes.php";
                    }, 1500);
                </script>';
                exit();
            } else {
                echo '<script>
                    showAlert("danger", "gagal", "Gagal memperbarui notes");
                    setTimeout(function() {
                        window.location.href = "notes.php";
                    }, 1500);
                </script>';
            }
        }
    }
    ?>
</body>
</html>
