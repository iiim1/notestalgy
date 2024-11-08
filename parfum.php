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
                <h1>Merk</h1>
                <form class="search-container table-search">
                    <input id="search" placeholder="Cari parfum..." class="search-input tabelSearch-input">
                </form>
                <a href="tambahParfum.php" class="tambah-button">
                    <img src="assets/add.png" alt="Tambah" id="tambah">
                    Tambah
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Merk</th>
                        <th>Nama Parfum</th>
                        <th>Kategori Parfum</th>
                        <th>Deskripsi</th>
                        <th>Notes</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="parfum-data">
                    <?php
                    $sql = "SELECT 
                                p.id_parfum as id_parfum,
                                m.nama as namaMerk, 
                                p.nama as namaParfum, 
                                k.nama as kategori, 
                                p.deskripsi as deskripsi, 
                                p.harga as harga, 
                                p.gambar as gambar   
                                FROM parfum p
                                join merk m on p.id_merk = m.id_merk
                                join kategori k on p.id_kategori = k.id_kategori
                                ";
                    $result = mysqli_query($conn, $sql);
                    $no = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $no++;
                            ?>
                            <tr>
                                <td><?=$no?></td>
                                <td><?=htmlspecialchars($row['namaMerk'])?></td>
                                <td><?=htmlspecialchars($row['namaParfum'])?></td>
                                <td><?=htmlspecialchars($row['kategori'])?></td>
                                <td><?=htmlspecialchars($row['deskripsi'])?></td>
                                <td><a href="detailNotes.php?id=<?= $row['id_parfum']; ?>">Detail Notes</a></td>
                                <td><?=number_format($row['harga'], 0, '.', ',')?></td>
                                <td><?php if($row['gambar']){ ?>
                                    <img src="assets/<?php echo $row['gambar']; ?>" alt="gambar" style="max-width: 100px;">
                                    <?php } ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editParfum.php?id=<?= $row['id_parfum']; ?>" class="action-button">
                                            <img src="assets/edit.png" alt="edit">
                                        </a>
                                        <a href="deleteHandler.php?type=parfum&id=<?= $row['id_parfum']; ?>" class="action-button hapus">
                                            <img src="assets/delete.png" alt="hapus">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="empty-state">Tidak ada data</td>
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
                        document.getElementById("parfum-data").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=parfum", true);
                xhr.send();
            });
        }
    </script>
</body>
</html>