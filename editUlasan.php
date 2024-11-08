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
<?php 
    include('partials/head.php'); 
    $idParfum = $_GET['id_parfum'];
    $idPengguna = $_SESSION['id_pengguna'];
    $query = mysqli_query($conn, "SELECT * FROM ulasan WHERE id_pengguna='$idPengguna' AND id_parfum='$idParfum'");
    $data = mysqli_fetch_assoc($query);
    $rating = number_format($data['rating'], 0, '.', ',');
?>
    <style>
        .star {
            color: #ccc; 
            font-size: 20px;
            cursor: pointer;
        }
        .filled-star {
            color: #FFD700; 
        }
    </style>
<body>
    <?php include('partials/navbar.php'); ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form">
            <h2 class="form-title">Edit Ulasan</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <div class="star-card">
                        <span onclick="gfg(1)" class="star <?= $rating >= 1 ? 'filled-star' : '' ?>">★</span>
                        <span onclick="gfg(2)" class="star <?= $rating >= 2 ? 'filled-star' : '' ?>">★</span>
                        <span onclick="gfg(3)" class="star <?= $rating >= 3 ? 'filled-star' : '' ?>">★</span>
                        <span onclick="gfg(4)" class="star <?= $rating >= 4 ? 'filled-star' : '' ?>">★</span>
                        <span onclick="gfg(5)" class="star <?= $rating >= 5 ? 'filled-star' : '' ?>">★</span>
                        
                        <h3 id="output">Rating: <?= $rating ?>/5</h3>
                    </div>
                </div>
                <input type="hidden" name="rating" id="rating" value="<?= $rating ?>">
                <div class="form-group full-width">
                    <label class="form-label" for="komentar">Komentar Anda:</label>
                    <input type="text" id="komentar" name="komentar" class="form-control" value="<?= htmlspecialchars($data['komentar']) ?>" placeholder="Masukkan Komentar anda tentang parfum ini" required>
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100%">Edit</button>
        </form>
    </div>

    <?php include('partials/footer.php'); ?>
    <script>
        let stars = document.getElementsByClassName("star");
        let output = document.getElementById("output");
    
        function gfg(n) {
            remove();
            for (let i = 0; i < n; i++) {
                stars[i].classList.add("filled-star");
            }
            output.innerText = "Rating: " + n + "/5";
            document.getElementById("rating").value = n;
        }
    
        function remove() {
            for (let i = 0; i < 5; i++) {
                stars[i].classList.remove("filled-star");
            }
        }
    </script>
    <?php
    if (isset($_POST['submit'])) {
        $rating = $_POST['rating'];
        $komentar = $_POST['komentar'];

        $sql = "UPDATE ulasan SET rating='$rating', komentar='$komentar' WHERE id_pengguna='$idPengguna' AND id_parfum='$idParfum'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo '<script>
                showAlert("sukses", "Berhasil", "Ulasan berhasil diperbarui");
                setTimeout(function() {
                    window.location.href = "tampilUlasan.php";
                }, 1500);
            </script>';
            exit();
        } else {
            echo '<script>
                showAlert("danger", "Gagal", "Gagal memperbarui ulasan!");
                setTimeout(function() {
                    window.location.href = "tampilUlasan.php";
                }, 1500);
            </script>';
            exit();
        }
    } ?>
</body>
</html>
