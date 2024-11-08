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

        .notes-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 30px;
        }

        .notes-category-card {
            padding: 24px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .notes-category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px -1px rgba(0, 0, 0, 0.15);
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--color-text-primary);
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .notes-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .empty-state-icon {
            font-size: 48px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .empty-state-text {
            color: #64748b;
            font-size: 1.1rem;
        }
    </style>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="container">
        <?php
        $id_parfum = mysqli_real_escape_string($conn, $_GET['id']);
        $sqlNotes = "SELECT p.id_parfum, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'top' THEN n.nama END) AS top_notes, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'middle' THEN n.nama END) AS middle_notes, 
                        GROUP_CONCAT(CASE WHEN n.kategori_notes = 'base' THEN n.nama END) AS base_notes 
                    FROM parfum p 
                    LEFT JOIN parfum_notes pn ON p.id_parfum = pn.id_parfum 
                    LEFT JOIN notes n ON pn.id_notes = n.id_notes 
                    WHERE p.id_parfum = '$id_parfum' 
                    GROUP BY p.id_parfum;";

        $resultNotes = mysqli_query($conn, $sqlNotes);

        if ($resultNotes && $resultNotes->num_rows > 0) {
            $row = $resultNotes->fetch_assoc();
        ?>
        <div class="notes-container">
            <div class="notes-category-card top-notes">
                <div class="category-header">
                    <h2 class="category-title">Top Notes</h2>
                </div>
                <div class="notes-list">
                    <?php
                    $top_notes = explode(',', $row['top_notes']);
                    foreach ($top_notes as $note) {
                        if ($note) {
                            echo "<span class='filter-btn'>" . htmlspecialchars(trim($note)) . "</span>";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="notes-category-card middle-notes">
                <div class="category-header">
                    <h2 class="category-title">Middle Notes</h2>
                </div>
                <div class="notes-list">
                    <?php
                    $middle_notes = explode(',', $row['middle_notes']);
                    foreach ($middle_notes as $note) {
                        if ($note) {
                            echo "<span class='filter-btn'>" . htmlspecialchars(trim($note)) . "</span>";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="notes-category-card base-notes">
                <div class="category-header">
                    <h2 class="category-title">Base Notes</h2>
                </div>
                <div class="notes-list">
                    <?php
                    $base_notes = explode(',', $row['base_notes']);
                    foreach ($base_notes as $note) {
                        if ($note) {
                            echo "<span class='filter-btn'>" . htmlspecialchars(trim($note)) . "</span>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <p class="empty-state-text">Tidak ada data.</p>
            </div>
        <?php } ?>          
    </div>
    <?php include('partials/footer.php') ?>
</body>
</html>