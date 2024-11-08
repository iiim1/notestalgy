<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header('location:login.php');
    exit();
}

$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="en">
    <?php 
    include('partials/head.php');
    $stmt = mysqli_prepare($conn, "SELECT * FROM pengguna WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $penggunaData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    ?>
<body>
    <?php include('partials/navbar.php'); ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form">
            <h2 class="form-title">Perbaharui Profil</h2>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="username">Nama Pengguna</label>
                    <input type="text" name="username" class="form-control" id="username" value="<?php echo htmlspecialchars($penggunaData['username']); ?>" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($penggunaData['name']); ?>" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($penggunaData['email']); ?>" required>
                </div>
            </div>
            <button type="submit" name="update_profile" class="submit-btn" style="width:100%">Perbaharui</button>
        </form>
    </div>

    <?php 
    include('partials/footer.php'); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $new_username = trim($_POST['username'] ?? '');

        if (!$name || !$email || !$new_username) {
            echo '<script>
                showAlert("danger", "Coba Lagi", "Nama, email, dan username harus diisi!");
            </script>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>
                showAlert("danger", "Coba Lagi", "Format email tidak valid!");
            </script>';
        } else {
            if ($new_username !== $username) {
                $sql = "SELECT * FROM pengguna WHERE username='$new_username'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo '<script>
                        showAlert("danger", "Coba Lagi", "Username sudah digunakan.");
                    </script>';
                } else {
                    $sqlUpdate = "UPDATE pengguna SET name='$name', email='$email', username='$new_username' WHERE username='$username'";
                    if (mysqli_query($conn, $sqlUpdate)) {
                        echo '<script>
                            showAlert("sukses", "Berhasil", "Profil berhasil diperbarui!");
                        </script>';
                        $_SESSION['username'] = $new_username;
                        $username = $new_username;
                        $penggunaData = ['name' => $name, 'email' => $email, 'username' => $new_username];
                    } else {
                        echo '<script>
                            showAlert("danger", "Coba Lagi", "Gagal memperbarui profil!");
                        </script>';
                    }
                }
            } else {
                $sqlUpdate = "UPDATE pengguna SET name='$name', email='$email' WHERE username='$username'";
                if (mysqli_query($conn, $sqlUpdate)) {
                    echo '<script>
                        showAlert("sukses", "Berhasil", "Profil berhasil diperbarui!");
                    </script>';
                    $penggunaData['name'] = $name;
                    $penggunaData['email'] = $email;
                } else {
                    echo '<script>
                        showAlert("danger", "Coba Lagi", "Gagal memperbarui profil!");
                    </script>';
                }
            }
        }
    }
    ?>
</body>
</html>