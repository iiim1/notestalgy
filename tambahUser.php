<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
        header('location:login.php');
        exit();
    } else {
        $penggunaRole = $_SESSION['role'] ?? 'Tidak login';
        if ($penggunaRole !== 'admin') {
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
    <div class="form-container">
        <form action="" method="post" class="form">
            <h2 class="form-title">Tambah Pengguna</h2>
            <div class="form-grid">
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
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="text" id="email" name="email" placeholder="Masukkan email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="role">Pilih Role</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Pilih Role</option>
                        <?php
                        $query = "SHOW COLUMNS FROM `pengguna` LIKE 'role'";
                        $result = mysqli_query($conn, $query);
                        if ($row = $result->fetch_assoc()) {
                            preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
                            foreach (explode("','", $matches[1]) as $role) { ?>
                                <option value="<?=$role?>"> <?=ucfirst($role)?></option>;
                            <?php }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100" >Tambah</button>
        </form>
    </div>
    <?php 
    include('partials/footer.php');
    if (isset($_POST['submit'])) {
        $username = $_POST["username"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = $_POST["role"];
        
        if (strlen($username) < 3) {
            echo '<script>
                showAlert("danger", "Isi Form dengan benar", "username harus lebih dari 3 karakter.");
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
                    showAlert("danger", "Isi Form dengan benar", "username sudah digunakan.");
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
                            showAlert("sukses", "Berhasil", "Penambahan pengguna Berhasil");
                            setTimeout(function() {
                                window.location.href = "pengguna.php";
                            }, 1500);
                        </script>';
                    } else {
                        echo '<script>
                            showAlert("danger", "Gagal mendaftar", "Gagal mendaftar");
                            setTimeout(function() {
                                window.location.href = "tambahPengguna.php";
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

