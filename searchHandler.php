<?php
include "koneksi.php";

$search = $_GET["search"] ?? '';
$type = $_GET["type"] ?? '';

switch($type) {
    case 'notes':
        searchNotes($conn, $search);
        break;
    case 'notesUser':
        searchNotesUser($conn, $search);
        break;
    case 'pengguna':
        searchPengguna($conn, $search);
        break;
    case 'merk':
        searchMerk($conn, $search);
        break;
    case 'merkUser':
        searchMerkUser($conn, $search);
        break;
    case 'parfum':
        searchParfum($conn, $search);
        break;
    case 'parfumUser':
        searchParfumUser($conn, $search);
        break;
    case 'ulasan':
        searchUlasan($conn, $search);
        break;
    case 'index':
        searchParfumIndex($conn, $search);
        break;
    default:
        echo "Tipe pencarian tidak valid";
}

function searchNotes($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $data = mysqli_query($conn, "SELECT * FROM notes WHERE nama LIKE '%$search%' OR deskripsi LIKE '%$search%'");
    $no = 0;
    if ($data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) {
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
    <?php } 
}

function searchMerk($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $data = mysqli_query($conn, "SELECT * FROM merk WHERE nama LIKE '%$search%'");
    $no = 0;
    if ($data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) {
            $no++;
            ?>
            <tr>
                <td><?=$no?></td>
                <td><?=htmlspecialchars($row['nama'])?></td>
                <td><?php if($row['logo']): ?>
                    <img src="assets/<?php echo $row['logo']; ?>" alt="Logo" style="max-width: 100px;">
                    <?php else: ?>
                        No Logo
                    <?php endif; 
                    ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="editMerk.php?id=<?=$row['id_merk'];?>" class="action-button">
                            <img src="assets/edit.png" alt="edit">
                        </a>
                        <a href="deleteHandler.php?type=merk&id=<?= $row['id_merk']; ?>" class="action-button hapus">
                            <img src="assets/delete.png" alt="hapus">
                        </a>
                    </div>
                </td>
            </tr>
        <?php }
    } else { ?>
        <tr>
            <td colspan="4" class="empty-state">Tidak ada data</td>
        </tr>
    <?php }
}

function searchMerkUser($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $data = mysqli_query($conn, "SELECT * FROM merk WHERE nama LIKE '%$search%'");
    if ($data->num_rows > 0) {
        while ($d = $data->fetch_assoc()) {?>
            <div class="tooltip">
                <img src="assets/<?= $d['logo']; ?>" alt="<?= htmlspecialchars($d['nama']); ?>">
                <span class="tooltip-text"><?= htmlspecialchars($d['nama']); ?></span>
            </div>
        <?php }
    } else { ?>
        <div class="tooltip">
            <span>Belum ada Merk tersebut</span>
        </div>
    <?php }
}

function searchPengguna($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $data = mysqli_query($conn, "SELECT * FROM pengguna WHERE name LIKE '%$search%' OR email LIKE '%$search%'");
    $no = 0;
    if ($data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) {
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
    <?php } 
}

function searchParfum($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $data = mysqli_query($conn, "SELECT 
                                p.id_parfum,
                                m.nama AS namaMerk, 
                                p.nama AS namaParfum, 
                                k.nama AS kategori, 
                                p.deskripsi, 
                                p.harga, 
                                p.gambar   
                                FROM parfum p
                                JOIN merk m ON p.id_merk = m.id_merk
                                JOIN kategori k ON p.id_kategori = k.id_kategori
                                WHERE m.nama LIKE '%$search%' 
                                OR p.nama LIKE '%$search%' 
                                OR k.nama LIKE '%$search%'
                                OR p.deskripsi LIKE '%$search%'
                                OR p.harga LIKE '%$search%'");
    $no = 0;
    if ($data && $data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) {
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
                <td><?php if($row['gambar']) { ?>
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
    <?php }
}

function searchParfumUser($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $sqlParfum = "SELECT 
                    p.id_parfum,
                    p.nama AS nama_parfum,
                    p.harga,
                    p.gambar,
                    m.nama AS merk_nama,
                    k.nama As nama_kategori,
                    AVG(u.rating) AS rating,
                    COUNT(u.id_ulasan) AS jumlah_ulasan
                FROM 
                    parfum p
                JOIN 
                    merk m ON p.id_merk = m.id_merk
                JOIN 
                    kategori k ON p.id_kategori = k.id_kategori
                LEFT JOIN 
                    ulasan u ON p.id_parfum = u.id_parfum
                WHERE m.nama LIKE '%$search%' 
                OR p.nama LIKE '%$search%' 
                GROUP BY 
                    p.id_parfum, m.nama
                ORDER BY p.id_parfum DESC";
    $dataParfum = mysqli_query($conn, $sqlParfum);
    if ($dataParfum && $dataParfum->num_rows > 0) {
        while ($d = $dataParfum->fetch_assoc()) { 
            $averageRating = round($d['rating']);?>
            <div class="product-card" id="card-<?= htmlspecialchars($d['id_parfum']); ?>">
                <img src="assets/<?= htmlspecialchars($d['gambar']); ?>" alt="<?= htmlspecialchars($d['nama_parfum']); ?>" class="product-image">
                <h3><?= htmlspecialchars($d["nama_parfum"]); ?></h3>
                <h5><?= htmlspecialchars($d["merk_nama"]); ?></h5>
                <h6><?= htmlspecialchars($d["nama_kategori"]); ?></h5>
                <div class="detail">
                    <div class="star-card" style="width:100%;margin:0;background:none;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?= $i <= $averageRating ? 'filled-star' : ''; ?>">★</span>
                        <?php endfor; ?>
                        <h3 id="output">Rating: <?= number_format($d['rating'], 0, '.', ',') ?>/5</h3>
                    </div>
                    <span class="reviews"><?= number_format($d['rating'], 0, '.', ',') ?> (<?= $d['jumlah_ulasan'] ?> reviews)</span>
                    <p>Rp <?= number_format($d["harga"]); ?></p>
                    <button style="margin:0;" class="submit-btn" onclick="location.href='detailParfum.php?id_parfum=<?= htmlspecialchars($d['id_parfum']); ?>'">Lihat Detail</button>
                </div>
            </div>
        <?php }
    } else { ?>
        <div class="product-card">
            <h3>Belum ada.</h3>
            <h5>Parfum tidak ditemukan</h5>
        </div>
    <?php }
}

function searchNotesUser($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $categories = ['top' => 'Top Notes', 'middle' => 'Middle Notes', 'base' => 'Base Notes'];
    
    foreach ($categories as $category => $title) {
        $sqlCategory = "SELECT nama, deskripsi FROM notes WHERE kategori_notes ='$category' AND (nama LIKE '%$search%' OR deskripsi LIKE '%$search%')";
        $hasil = mysqli_query($conn, $sqlCategory);
        
        if ($hasil->num_rows > 0) { ?>
            <div class='note-level <?=$category?>-notes'>
                <h3 class='note-title'><?=$title?></h3>
                <div class='note-examples'>
                <?php while ($data = $hasil->fetch_assoc()) { ?>
                    <span class='note-tag tooltip'>
                        <?= htmlspecialchars($data['nama']); ?>
                        <span class='tooltip-text'><?= htmlspecialchars($data['deskripsi']); ?></span>
                    </span>
                <?php } ?> <!-- Add missing space here -->
                </div>
            </div>
        <?php
        } else { ?>
            <div class='note-level <?=$category?>-notes'>
                <h3 class='note-title'><?=$title?></h3>
                <p>Tidak ada notes tersebut ditemukan pada level ini.</p>
            </div>
        <?php }
    }
}



function searchUlasan($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $data = mysqli_query($conn, "SELECT 
                                    u.id_ulasan AS id_ulasan,
                                    u.rating, 
                                    u.komentar, 
                                    u.tanggal_buat, 
                                    us.username,
                                    us.name AS nama_user
                                FROM 
                                    ulasan u
                                INNER JOIN 
                                    pengguna us ON u.id_pengguna = us.id_pengguna
                                WHERE u.rating LIKE '%$search%' 
                                OR u.komentar LIKE '%$search%' 
                                OR u.tanggal_buat LIKE '%$search%'
                                OR us.username LIKE '%$search%'
                                OR us.name LIKE '%$search%'");
    $no = 0;
    if ($data->num_rows > 0) {
        while ($row = $data->fetch_assoc()) {
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
    <?php }
}

function searchParfumIndex($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $sqlParfum = "SELECT p.id_parfum, p.nama, m.nama as merk_nama 
                  FROM parfum p 
                  JOIN merk m ON p.id_merk = m.id_merk 
                  WHERE p.nama LIKE '%$search%' 
                  OR m.nama LIKE '%$search%'
                  ORDER BY p.nama ASC 
                  LIMIT 5";
    
    $dataParfum = mysqli_query($conn, $sqlParfum);
    
    if ($dataParfum->num_rows > 0) {
        while($d = mysqli_fetch_array($dataParfum)) {
            echo "<div class='search-result-item' onclick='window.location.href=\"detailParfum.php?id_parfum=" . 
                 htmlspecialchars($d['id_parfum']) . "\"'>" .
                 "<strong>" . htmlspecialchars($d['nama']) . "</strong>" .
                 "<span class='merk-name'> by " . htmlspecialchars($d['merk_nama']) . "</span>" .
                 "</div>";
        }
    } else {
        echo "<div class='no-results'>Parfum tidak ditemukan</div>";
    }
}