<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form">
            <h2 class="form-title">Login</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan Username" autocomplete="username" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" autocomplete="current-password" required>
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Login</button>
        </form>
    </div>
    <?php include('partials/footer.php') ?>
    <?php

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM pengguna WHERE username='$username'";
        $result = mysqli_query($conn, $sql);

        if($result){
            $data = mysqli_fetch_assoc($result);
            $_SESSION['role'] = $data['role'];
            if($data && password_verify($password, $data['password'])){
                $_SESSION['username'] = $data['username'];
                $_SESSION['id_pengguna'] = $data['id_pengguna'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['is_login'] = true;

            if($data['role'] == 'admin'){
                echo '<script>
                    showAlert("sukses", "Login Berhasil", "Anda masuk sebagai admin!");
                    setTimeout(function() {
                        window.location.href = "dashboard.php";
                    }, 1500);
                </script>';
                exit();
            } else {
                echo '<script>
                    showAlert("sukses", "Login Berhasil", "Selamat datang, ' . $data['username'] . '!");
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 1500);
                </script>';
                exit();
            }
            } else {
                echo '<script>
                        showAlert("danger", "Login Gagal", "Username atau password salah.");
                        setTimeout(function() {
                            window.location.href = "login.php";
                        }, 1500);
                    </script>';
            }
        } else {
            die("Query error: " . mysqli_error($conn));
        }
    }
?>
</body>
</html>