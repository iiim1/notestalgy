<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('partials/head.php') ?>
    
    <style>
        body {
            background: var(--color-bg-admin);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .stats-card {
            padding: 24px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px -1px rgba(0, 0, 0, 0.15);
        }

        .stats-card h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stats-card p {
            font-size: 1.25rem;
            font-weight: bold;
        }


        .recent-activity {
            margin-top: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-details {
            flex: 1;
        }

        .activity-user {
            font-weight: bold;
            color: #4CAF50;
        }

        .activity-time {
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.7);
        }

        .rating {
            display: inline-block;
            padding: 4px 8px;
            background: rgba(76, 175, 80, 0.2);
            border-radius: 4px;
            color: #4CAF50;
        }

        .stats-detail {
            margin-top: 10px;
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.7);
        }

        .trend-up {
            color: #4CAF50;
        }

        .trend-down {
            color: #f44336;
        }

        .trend-arrow {
            display: inline-block;
            width: 0;
            height: 0;
            margin-right: 5px;
        }

        .trend-up .trend-arrow {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 8px solid #4CAF50;
        }

        .trend-down .trend-arrow {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 8px solid #f44336;
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    include('partials/navbar.php');

    $qPengguna = $conn->query("SELECT COUNT(*) AS total FROM pengguna");
    $rowPengguna = $qPengguna->fetch_assoc();
    $totalPengguna = $rowPengguna['total'];

    $qParfum = $conn->query("SELECT COUNT(*) AS total FROM Parfum");
    $rowParfum = $qParfum->fetch_assoc();
    $totalParfum = $rowParfum['total']; 

    $qUlasan = $conn->query("SELECT COUNT(*) AS total FROM Ulasan");
    $rowUlasan = $qUlasan->fetch_assoc();
    $totalUlasan = $rowUlasan['total']; 

    $qNotes = $conn->query("SELECT COUNT(*) AS total FROM Notes");
    $rowNotes = $qNotes->fetch_assoc();
    $totalNotes = $rowNotes['total'];

    $qNewUsers = $conn->query("SELECT COUNT(*) AS total FROM pengguna WHERE DATE(tanggal_buat) = CURDATE()");
    $rowNewUsers = $qNewUsers->fetch_assoc();
    $newUsers = $rowNewUsers['total'];
    
    $qPersentaseBaru = $conn->query("SELECT 
                                        ROUND(
                                            (COUNT(*) * 100.0) / (SELECT COUNT(*) 
                                                                    FROM pengguna 
                                                                    WHERE DATE(tanggal_buat) < CURDATE()), 2) 
                                                                    AS persen_peningkatan
                                    FROM pengguna
                                    WHERE DATE(tanggal_buat) = CURDATE()");
    $rowPersentaseBaru = $qPersentaseBaru->fetch_assoc();
    $PersentaseBaru = $rowPersentaseBaru['persen_peningkatan'] ?? '0';

    $qTerpopuler = $conn->query("SELECT p.nama AS parfum_terpopuler
                                FROM parfum p
                                JOIN ulasan u ON p.id_parfum = u.id_parfum
                                GROUP BY p.id_parfum
                                ORDER BY COUNT(u.id_ulasan) DESC
                                LIMIT 1");
    $rowTerpopuler = $qTerpopuler->fetch_assoc();
    $Terpopuler = $rowTerpopuler['parfum_terpopuler'] ?? 'Belum Ada Parfum Terpopuler';

    $qratarata = $conn->query("SELECT AVG(u.rating) AS rating_rata_rata
                                FROM ulasan u
                                JOIN parfum p ON u.id_parfum = p.id_parfum");
    $rowratarata = $qratarata->fetch_assoc();
    $ratarata =  number_format(($rowratarata['rating_rata_rata']), 0, '.', ',') ?? 'Belum Ada Rating';

    $qNotesPop = $conn->query("SELECT n.nama AS notes_terpopuler
                                FROM notes n
                                JOIN parfum_notes pn ON n.id_notes = pn.id_notes
                                JOIN ulasan u ON pn.id_parfum = u.id_parfum
                                GROUP BY n.id_notes
                                ORDER BY COUNT(u.id_ulasan) DESC
                                LIMIT 1");
    $rowNotesPop = $qNotesPop->fetch_assoc();
    $NotesPop = $rowNotesPop['notes_terpopuler'] ?? 'Belum Ada Notes Terpopuler';

    $qRecentReviews = $conn->query("
        SELECT 
            u.username,
            p.nama AS nama_parfum,
            ul.rating,
            ul.tanggal_buat,
            ul.komentar
        FROM Ulasan ul
        JOIN pengguna u ON ul.id_pengguna = u.id_pengguna
        JOIN Parfum p ON ul.id_parfum = p.id_parfum
        ORDER BY ul.tanggal_buat DESC
        LIMIT 5
    ");
    ?>

    <div class="container">
        <div class="stats-container">
            <div class="stats-card">
                <h2>Total Pengguna</h2>
                <p><?php echo htmlspecialchars($totalPengguna); ?></p>
                <div class="stats-detail">
                    <div>Baru hari ini: <?php echo $newUsers; ?></div>
                    <div class="trend-up">
                        <span class="trend-arrow"></span><?php echo htmlspecialchars($PersentaseBaru);?>
                    </div>
                </div>
            </div>
            <div class="stats-card">
                <h2>Total Parfum</h2>
                <p><?php echo htmlspecialchars($totalParfum); ?></p>
                <div class="stats-detail">Terpopuler: <?php echo htmlspecialchars($Terpopuler); ?></div>
            </div>
            <div class="stats-card">
                <h2>Total Ulasan</h2>
                <p><?php echo htmlspecialchars($totalUlasan); ?></p>
                <div class="stats-detail">Rating rata-rata: <?php echo $ratarata; ?></div>
            </div>
            <div class="stats-card">
                <h2>Total Notes</h2>
                <p><?php echo htmlspecialchars($totalNotes); ?></p>
                <div class="stats-detail">Terpopuler: <?php echo htmlspecialchars($NotesPop); ?></div>
            </div>
        </div>
        <div class="recent-activity">
            <h2>Aktivitas Terbaru</h2>
            <ul class="activity-list">
                <?php while($review = $qRecentReviews->fetch_assoc()): ?>
                <li class="activity-item">
                    <div class="activity-details">
                        <span class="activity-user"><?php echo htmlspecialchars($review['username']); ?></span>
                        memberikan ulasan untuk 
                        <strong><?php echo htmlspecialchars($review['nama_parfum']); ?></strong>
                        <div class="activity-time">
                            <?php echo date('d M Y H:i', strtotime($review['tanggal_buat'])); ?>
                        </div>
                    </div>
                    <div class="rating">
                        <?php echo htmlspecialchars($review['rating']); ?>/5
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
    <?php include('partials/footer.php') ?>
</body>
</html>