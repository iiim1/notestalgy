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
    <?php include('partials/navbar.php');
    $query = mysqli_query($conn, "SELECT* FROM merk WHERE id_merk='$_GET[id]'");
    $data = mysqli_fetch_assoc($query);
    ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form" enctype="multipart/form-data">
            <h2 class="form-title">Perbarui Merk</h2>
            <div class="form-grid">
                <input type="text" hidden name="id" value="<?= $data['id_merk']?>">
                <div class="form-group full-width">
                    <label class="form-label" for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama'])?>" placeholder="Masukkan nama notes" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="image">Logo</label>
                    <div class="file-input-wrapper">
                        <label for="image" class="file-input-trigger">
                            <span><?= $data['logo']?></span>
                        </label>
                        <input type="file" id="image" name="logo" accept=".png,.jpg,.jpeg,.webp">
                    </div>
                    <img id="imagePreview" class="preview-image" src="assets/<?= $data['logo'] ?>">
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Simpan Perubahan</button>
        </form>
    </div>
    <?php 
    include('partials/footer.php');
    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $result = mysqli_query($conn, "SELECT logo FROM merk WHERE id_merk = $id");
        $row = mysqli_fetch_assoc($result);
        $current_image = $row['logo'];

        if($_FILES['logo']['error'] === 4) { 
            $image = $current_image; 
        } else {
            $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
            $image = $_FILES['logo']['name'];
            $x = explode('.', $image);
            $ekstensi = strtolower(end($x));
            $ukuran = $_FILES['logo']['size'];
            $file_tmp = $_FILES['logo']['tmp_name'];
            
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                if($ukuran < 1048600){
                    $new_filename = date("Y-m-d_H-i-s") . "." . $ekstensi;
                    $upload_path = 'assets/' . $new_filename;
                    
                    if(move_uploaded_file($file_tmp, $upload_path)){
                        $image = $new_filename;
                        if($current_image && file_exists('assets/'.$current_image)) {
                            unlink('assets/'.$current_image);
                        }
                    }
                } else {
                    echo '<script>
                            showAlert("danger", "Gagal", "Ukuran file terlalu besar. Ukuran file maksimal adalah 1MB.");
                        </script>';
                    exit;
                }
            } else {
                echo '<script>
                    showAlert("danger", "Gagal", "Ekstensi file tidak diperbolehkan. Hanya format PNG, JPG, JPEG, dan WEBP yang diizinkan.");
                </script>';
                exit;
            }
        }
        $sql = "UPDATE merk SET logo='$image', nama='$nama' WHERE id_merk='$id'";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo '<script>
                showAlert("sukses", "Berhasil", "Berhasil memperbarui merk.");
                setTimeout(function() {
                    window.location.href = "merk.php";
                }, 1500);
            </script>';
        } else {
            echo '<script>
                showAlert("danger", "Gagal", "Gagal memperbarui Merk. Coba kembali.");
                setTimeout(function() {
                    window.location.href = "merk.php";
                }, 1500);
            </script>';
        }
    }
    ?>
</body>
</html>
