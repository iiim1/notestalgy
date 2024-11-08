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
    <?php include('partials/navbar.php') ?>
    <div class="form-container kecil">
        <form action="" method="post" enctype="multipart/form-data" class="form">
            <h2 class="form-title">Tambah Merk</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama merk" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="image">Logo</label>
                    <div class="file-input-wrapper">
                        <label for="image" class="file-input-trigger">
                            <span>Pilih Foto</span>
                        </label>
                        <input type="file" id="image" name="logo" accept=".png,.jpg,.jpeg,.webp" required>
                    </div>
                    <img id="imagePreview" class="preview-image">
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Tambah</button>
        </form>
    </div>
    <?php 
    include('partials/footer.php');

    if (isset($_POST['submit'])) {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
        $image = $_FILES['logo']['name'];
        $x = explode('.', $image);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['logo']['size'];
        $file_tmp = $_FILES['logo']['tmp_name'];

        $nama = $_POST['nama'];
        
        $check_sql = "SELECT * FROM merk WHERE nama = '$nama'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo '<script>
                showAlert("danger", "Gagal", "Merk sudah ada. Silakan gunakan nama merk lain.");
                setTimeout(function() {
                    window.location.href = "tambahMerk.php";
                }, 1500);
            </script>';
        } else {
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                if ($ukuran < 1048600) {
                    $new_filename = date("Y-m-d_H-i-s") . "." . $ekstensi;
                    $upload_path = 'assets/' . $new_filename;
                    if(move_uploaded_file($file_tmp, $upload_path)){
                        $image = $new_filename;
                    }
                    $sql = "INSERT INTO merk (nama, logo) VALUES ('$nama', '$image')";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        echo '<script>
                            showAlert("sukses", "Berhasil", "Berhasil menambah merk.");
                            setTimeout(function() {
                                window.location.href = "merk.php";
                            }, 1500);
                        </script>';
                    } else {
                        echo '<script>
                            showAlert("danger", "Gagal", "Gagal Menambah Merk. Coba kembali.");
                            setTimeout(function() {
                                window.location.href = "tambahMerk.php";
                            }, 1500);
                        </script>';
                    }
                } else {
                    echo '<script>
                        showAlert("danger", "Gagal", "Ukuran file terlalu besar. Ukuran file maksimal adalah 1MB.");
                        setTimeout(function() {
                            window.location.href = "tambahMerk.php";
                        }, 1500);
                    </script>';
                }
            } else {
                echo '<script>
                    showAlert("danger", "Gagal", "Ekstensi file tidak diperbolehkan. Hanya format PNG, JPG, JPEG, dan WEBP yang diizinkan.");
                    setTimeout(function() {
                        window.location.href = "tambahMerk.php";
                    }, 1500);
                </script>';
            }
        }
    }   
    ?>
</body>
</html>
