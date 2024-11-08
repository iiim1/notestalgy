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

    $sqlParfum = "SELECT 
                    p.nama AS nama, 
                    m.nama AS merk, 
                    k.nama AS kategori, 
                    p.deskripsi, 
                    p.harga, 
                    p.gambar 
                FROM 
                    parfum p
                INNER JOIN 
                    merk m ON p.id_merk = m.id_merk
                INNER JOIN 
                    kategori k ON p.id_kategori = k.id_kategori
                WHERE 
                    p.id_parfum = $idParfum";
    $parfumData = mysqli_query($conn, $sqlParfum);
    $parfum = mysqli_fetch_assoc($parfumData);

    $sqlNotes = "SELECT p.id_parfum, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'top' THEN n.nama END SEPARATOR '|') AS top_notes, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'middle' THEN n.nama END SEPARATOR '|') AS middle_notes, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'base' THEN n.nama END SEPARATOR '|') AS base_notes,
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'top' THEN n.deskripsi END SEPARATOR '|') AS deskripsi_top, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'middle' THEN n.deskripsi END SEPARATOR '|') AS deskripsi_middle, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'base' THEN n.deskripsi END SEPARATOR '|') AS deskripsi_base 
                    FROM parfum p 
                    LEFT JOIN parfum_notes pn ON p.id_parfum = pn.id_parfum 
                    LEFT JOIN notes n ON pn.id_notes = n.id_notes 
                    WHERE p.id_parfum = '$idParfum' 
                    GROUP BY p.id_parfum";
    $resultNotes = mysqli_query($conn, $sqlNotes);
    $row = $resultNotes ? $resultNotes->fetch_assoc() : null;

    $sqlUlasan = "SELECT 
                    u.rating, 
                    u.komentar, 
                    u.tanggal_buat, 
                    us.id_pengguna, 
                    us.name AS nama_pengguna
                FROM 
                    ulasan u
                INNER JOIN 
                    pengguna us ON u.id_pengguna = us.id_pengguna
                WHERE 
                    u.id_parfum = $idParfum";
                    
    $ulasanData = mysqli_query($conn, $sqlUlasan);
    ?>
<head>
    <style>
        .notes-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 30px;
        }

        .notes-pyramid {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
            gap: 2rem; 
        }

        .note-level {
            width: 100%;
            max-width: 1200px;
            text-align: center;
        }

        .note-examples {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
        }

        .note-tag {
            background-color: #f0f0f0;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .tooltip {
            position: block;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip-text {
            visibility: hidden;
            width: max-content;
            max-width: 300px;
            background-color: rgba(51, 51, 51, 0.95);
            color: #fff;
            text-align: center;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            line-height: 1.4;        
            position: absolute;
            z-index: 1000;
            left: 50%;
            transform: translateX(-50%);        
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;        
            word-wrap: break-word;
            white-space: normal;
        }
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .tooltip:hover .tooltip-text {
            max-width: min(300px, 90vw);
        }

        .note-tag:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .top-notes .note-tag {
            background-color: #985353;
        }

        .middle-notes .note-tag {
            background-color: #2f126b;
        }

        .base-notes .note-tag {
            background-color: #c14c94;
        }
    </style>
</head>
<body>
    <?php include('partials/navbar.php'); ?>
    <div class="container">
        <section class="parfum-detail">
            <div class="parfum-image">
                <img src="assets/<?= $parfum['gambar']; ?>" alt="Gambar Parfum">
            </div>
            <div class="parfum-info">
                <div class="title-with-actions">
                    <h2><?= htmlspecialchars($parfum['nama']); ?></h2>
                    <section class="CUD-ulasan">
                        <a href="tambahUlasan.php?id_parfum=<?= $idParfum ?>" class="tambah-button">
                            Tambah Ulasan
                            <img src="assets/add.png" alt="Tambah" id="tambah">
                        </a>
                    </section>
                </div>
                <h2 style="color:var(--color-primary);">Rp. <?= number_format($parfum['harga']); ?></h2>
                <p><?= htmlspecialchars($parfum['merk']); ?></p>
                <p><?= htmlspecialchars($parfum['kategori']); ?></p>
                <p><?= htmlspecialchars($parfum['deskripsi']); ?></p>
                <section class="notes-detail">
                    <?php if ($row) { ?>
                        <div class="notes-container">
                            <?php 
                            $note_categories = [
                                'Top Notes' => ['notes' => 'top_notes', 'desc' => 'deskripsi_top', 'class' => 'top-notes'],
                                'Middle Notes' => ['notes' => 'middle_notes', 'desc' => 'deskripsi_middle', 'class' => 'middle-notes'],
                                'Base Notes' => ['notes' => 'base_notes', 'desc' => 'deskripsi_base', 'class' => 'base-notes']
                            ];
                            foreach ($note_categories as $title => $data) {
                                $notes = explode('|', $row[$data['notes']]);
                                $descriptions = explode('|', $row[$data['desc']]);
                                ?>
                                <div class="note-level <?= $data['class']; ?>">
                                    <h4 class="category-title"><?= $title; ?></h4>
                                    <div class="notes-list">
                                        <?php foreach ($notes as $index => $note) {
                                            if ($note) { ?>
                                                <span class="note-tag tooltip">
                                                    <?= htmlspecialchars(trim($note)); ?>
                                                    <span class="tooltip-text"><?= htmlspecialchars(trim($descriptions[$index] ?? '')); ?></span>
                                                </span>
                                        <?php } } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="empty-state">
                            <p class="empty-state-text">Tidak ada data.</p>
                        </div>
                    <?php } ?>
                </section>
            </div>
        </section>
        <section class="pengguna-review">
            <h3>Ulasan
                <span class="toggle-button" style="cursor:pointer">+</span>
            </h3>
            <div class="review-content" style="display:none;">
                <?php while($ulasan = mysqli_fetch_array($ulasanData)) { ?>
                    <div class="list-review">
                        <div class="review-item">
                            <h4><?= htmlspecialchars($ulasan['nama_pengguna']); ?></h4>
                            <p><strong>Rating:</strong> 
                            <span class="rating">
                                <?php 
                                    echo str_repeat('★', $ulasan['rating']) . str_repeat('☆', 5 - $ulasan['rating']);
                                    ?>
                                </span>
                            </p>
                            <p><small><?= htmlspecialchars($ulasan['tanggal_buat']); ?></small></p>
                            <p><?= htmlspecialchars($ulasan['komentar']); ?></p>
                            <?php if($ulasan['id_pengguna'] == $idPengguna) { ?>
                                <div class="CUD-ulasan">
                                    <a href="editUlasan.php?id_parfum=<?= $idParfum?>" class="action-button">
                                        <img src="assets/edit.png" alt="Edit Ulasan">
                                    </a> 
                                    <a href="hapusUlasan.php?id_parfum=<?= $idParfum; ?>" class="action-button">
                                        <img src="assets/delete.png" alt="Hapus Ulasan">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
    <?php include('partials/footer.php'); ?>
</body>
</html>
