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
                <h1>Pengguna</h1>
                <form class="search-container table-search">
                    <input id="search" placeholder="Cari Pengguna..." class="search-input tabelSearch-input">
                </form>
                <a href="tambahUser.php" class="tambah-button">
                    <img src="assets/add.png" alt="Tambah" id="tambah">
                    Tambah
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Buat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="users-data"
                    <?php
                    $sqlUsers = "SELECT * FROM pengguna";
                    $resultUsers = mysqli_query($conn, $sqlUsers);
                    $no = 0;
                    if ($resultUsers->num_rows > 0) {
                        while ($row = $resultUsers->fetch_assoc()) {
                            $no++;
                            ?>
                            <tr>
                                <td><?=$no?></td>
                                <td><?=htmlspecialchars($row['username'])?></td>
                                <td><?=htmlspecialchars($row['name'])?></td>
                                <td><?=htmlspecialchars($row['email'])?></td>
                                <td><?=$row['role']?></td>
                                <td><?=$row['tanggal_buat']?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="deleteHandler.php?type=pengguna&id=<?= $row['id_pengguna']; ?>" class="action-button hapus">
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
                        document.getElementById("users-data").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=pengguna", true);
                xhr.send();
            });
        }
    </script>
</body>
</html>