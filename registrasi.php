<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form"">
            <h2 class="form-title">Daftar Akun</h2>
            <?php if(!empty($error)): ?>
                <p class="error" style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <p class="success" style="color: green;"><?php echo $success; ?></p>
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="text" id="email" name="email" placeholder="Masukkan email" class="form-control" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan Nama Lengkap" required class="form-control">
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="username">Nama Pengguna</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan Nama Pengguna" required class="form-control">
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Kata Sandi" required class="form-control">
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Daftar</button>
        </form>
    </div>
    <?php 
    include('partials/footer.php');
    
    if (isset($_POST['submit'])) {
        $username = $_POST["username"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        if (strlen($username) < 3) {
            echo '<script>
                showAlert("danger", "Isi Form dengan benar", "Username harus lebih dari 3 karakter.");
            </script>';
        } elseif (strlen($password) < 5) {
            echo '<script>
                showAlert("danger", "Isi Form dengan benar", "Password minimal 5 karakter.");
            </script>';
        } else {
            $stmt = $conn->prepare("SELECT username FROM pengguna WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                echo '<script>
                    showAlert("danger", "Isi Form dengan benar", "Username sudah digunakan.");
                </script>';
            } else {
                $stmt = $conn->prepare("SELECT email FROM pengguna WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo '<script>
                        showAlert("danger", "Isi Form dengan benar", "Email sudah digunakan.");
                    </script>';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $conn->prepare("INSERT INTO pengguna (username, name, email, password) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $name, $email, $hashed_password);
                    
                    if ($stmt->execute()) {
                        echo '<script>
                            showAlert("sukses", "Pendaftaran Berhasil", "Silahkan login dengan akun yang didaftarkan");
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 1500);
                        </script>';
                        exit;
                    } else {
                        echo '<script>
                            showAlert("danger", "Gagal mendaftar", "Gagal mendaftar");
                            setTimeout(function() {
                                window.location.href = "registrasi.php";
                            }, 1500);
                        </script>';
                    }
                }
            }
        }
    }
    ?>
</body>
</html>
