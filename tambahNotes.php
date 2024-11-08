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
        };
    }
?>
<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form">
            <h2 class="form-title">Tambah Notes</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama notes" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukkan deskripsi notes" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="kategori_notes">Pilih Kategori</label>
                    <select name="kategori_notes" id="kategori_notes" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php
                        $query = "SHOW COLUMNS FROM `notes` LIKE 'kategori_notes'";
                        $result = mysqli_query($conn, $query);
                        if ($row = $result->fetch_assoc()) {
                            preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
                            foreach (explode("','", $matches[1]) as $kategori_notes) { ?>
                                <option value="<?=$kategori_notes?>"> <?=ucfirst($kategori_notes)?></option>;
                            <?php }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Tambah</button>
        </form>
    </div>
    <?php include('partials/footer.php') ?>
    <?php
        if(isset($_POST['submit'])){
            $nama = mysqli_real_escape_string($conn, $_POST['nama']);
            $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
            $kategori = mysqli_real_escape_string($conn, $_POST['kategori_notes']);
            
            $checkQuery = "SELECT * FROM notes WHERE nama = '$nama' AND kategori_notes = '$kategori'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                echo '<script>
                    showAlert("danger", "Gagal", "Notes sudah ada.");
                </script>';
            } else {
                $sql = "INSERT INTO notes (nama, deskripsi, kategori_notes) VALUES ('$nama', '$deskripsi', '$kategori')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo '<script>
                        showAlert("sukses", "Berhasil", "Berhasil Menambah notes");
                        setTimeout(function() {
                            window.location.href = "notes.php";
                        }, 1500);
                    </script>';
                } else {
                    echo '<script>
                        showAlert("danger", "Gagal", "Terjadi kesalahan saat menambah notes.");
                    </script>';
                }
            }
        };
        ?>
</body>
</html>
