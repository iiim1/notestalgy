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
    <style>
        body {
            background: var(--color-bg-admin);
        }
    </style>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-header">
                <h1>Ulasan</h1>
                <form class="search-container table-search">
                    <input id="search" placeholder="Cari Ulasan..." class="search-input tabelSearch-input">
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama User</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal Buat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="ulasan-data"
                    <?php
                    $sqlUsers = "SELECT 
                                    u.id_ulasan AS id_ulasan,
                                    u.rating, 
                                    u.komentar, 
                                    u.tanggal_buat, 
                                    us.username,
                                    us.name AS nama_user
                                FROM 
                                    ulasan u
                                INNER JOIN 
                                    pengguna us ON u.id_pengguna = us.id_pengguna";
                    $resultUsers = mysqli_query($conn, $sqlUsers);
                    $no = 0;
                    if ($resultUsers->num_rows > 0) {
                        while ($row = $resultUsers->fetch_assoc()) {
                            $no++;
                            ?>
                        
                            <tr>
                                <td><?=$no?></td>
                                <td><?=htmlspecialchars($row['username']);?></td>
                                <td><?=htmlspecialchars($row['nama_user'])?></td>
                                <td><?=$row['rating']?></td>
                                <td><?=htmlspecialchars($row['komentar'])?></td>
                                <td><?=$row['tanggal_buat']?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="deleteHandler.php?type=ulasan&id=<?= $row['id_ulasan']; ?>" class="action-button hapus">
                                            <img src="assets/delete.png" alt="hapus">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7" class="empty-state">Tidak ada data</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('partials/footer.php') ?>
    <script>
        const search = document.getElementById('search');
        if (search) {
            search.addEventListener('keyup', function () {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && this.status == 200) {
                        document.getElementById("ulasan-data").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=ulasan", true);
                xhr.send();
            });
        }
    </script>
</body>
</html>