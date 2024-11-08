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
                <h1>Notes</h1>
                <form class="search-container table-search">
                    <input id="search" placeholder="Cari notes..." class="search-input tabelSearch-input">
                </form>
                <a href="tambahNotes.php" class="tambah-button">
                    <img src="assets/add.png" alt="Tambah" id="tambah">
                    Tambah
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Notes</th>
                        <th>Kategori Notes</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="notes-data">
                    <?php
                    $sqlNotes = "SELECT * FROM notes";
                    $resultNotes = mysqli_query($conn, $sqlNotes);
                    $no = 0;
                    if ($resultNotes->num_rows > 0) {
                        while ($row = $resultNotes->fetch_assoc()) {
                            $no++;
                            ?>
                        
                            <tr>
                                <td><?=$no?></td>
                                <td><?=htmlspecialchars($row['nama'])?></td>
                                <td><?=htmlspecialchars($row['kategori_notes'])?></td>
                                <td><?=htmlspecialchars($row['deskripsi'])?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editNotes.php?id=<?=$row['id_notes'];?>" class="action-button">
                                            <img src="assets/edit.png" alt="edit">
                                        </a>
                                        <a href="deleteHandler.php?type=notes&id=<?= $row['id_notes']; ?>" class="action-button hapus"> 
                                            <img src="assets/delete.png" alt="hapus">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="6" class="empty-state">Tidak ada data</td>
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
                        document.getElementById("notes-data").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=notes", true);
                xhr.send();
            });
        }
    </script>
</body>
</html>