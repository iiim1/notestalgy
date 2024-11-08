<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['is_login'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="form-container kecil">
        <form action="" method="post" class="form">
            <h2 class="form-title">Tambah Ulasan</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <div class="star-card">
                        <span onclick="gfg(1)" class="star">★</span>
                        <span onclick="gfg(2)" class="star">★</span>
                        <span onclick="gfg(3)" class="star">★</span>
                        <span onclick="gfg(4)" class="star">★</span>
                        <span onclick="gfg(5)" class="star">★</span>
                        <h3 id="output">Rating: 0/5</h3>
                    </div>
                </div>
                <input type="hidden" name="rating" id="rating" value="0">
                <div class="form-group full-width">
                    <label class="form-label" for="komentar">Komentar Anda:</label>
                    <input type="text" id="komentar" name="komentar" class="form-control" placeholder="Masukkan Komentar anda tentang parfum ini" required>
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Tambah</button>
        </form>
    </div>

    <?php include('partials/footer.php') ?>
    <script>
        let stars = document.getElementsByClassName("star");
        let output = document.getElementById("output");

        function gfg(n) {
            remove();
            let cls = "";
            for (let i = 0; i < n; i++) {
                if (n == 1) cls = "one";
                else if (n == 2) cls = "two";
                else if (n == 3) cls = "three";
                else if (n == 4) cls = "four";
                else if (n == 5) cls = "five";
                stars[i].className = "star " + cls;
            }
            output.innerText = "Rating: " + n + "/5";
            document.getElementById("rating").value = n;
        }

        function remove() {
            for (let i = 0; i < 5; i++) {
                stars[i].className = "star";
            }
        }
    </script>
    <?php
    if (isset($_POST['submit'])) {
        $idParfum = $_GET['id_parfum'];
        $idPengguna = $_SESSION['id_pengguna'];
        $rating = $_POST['rating'];
        $komentar = $_POST['komentar'];

        $cek = "SELECT * FROM ulasan WHERE id_pengguna = '$idPengguna' AND id_parfum = '$idParfum'";
        $result = mysqli_query($conn, $cek);

        if (mysqli_num_rows($result) > 0) {
            
            echo '<script>
                showAlert("danger", "Gagal", "Anda sudah memberikan ulasan untuk parfum ini");
                setTimeout(function() {
                    window.location.href = "parfum.php";
                }, 1500);
            </script>';
            exit();
        } else {
            $query = "INSERT INTO ulasan (id_pengguna, id_parfum, rating, komentar) VALUES ('$idPengguna', '$idParfum', '$rating', '$komentar')";
            if (mysqli_query($conn, $query)) {
                echo '<script>
                    showAlert("sukses", "Berhasil", "Ulasan berhasil ditambahkan");
                    setTimeout(function() {
                        window.location.href = "detailParfum.php?id_parfum='. $idParfum .'";
                    }, 1500);
                </script>';
                exit();
            } else {
                echo '<script>
                    showAlert("danger", "Gagal", "Gagal menambahkan ulasan: ' . mysqli_error($conn) . '");
                    setTimeout(function() {
                        window.location.href = "tambahUlasan.php";
                    }, 1500);
                </script>';
                exit();
            }
        }
    }
    ?>
</body>
</html>

