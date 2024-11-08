<!DOCTYPE html>
<html lang="en">
<?php include('partials/head.php') ?>
<style>
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
        position: relative;
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

    @media (min-height: 600px) {
        .tooltip-text {
            bottom: calc(100% + 10px);
        }
        
        .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: rgba(51, 51, 51, 0.95) transparent transparent transparent;
        }
    }

    @media (max-height: 599px) {
        .tooltip-text {
            top: calc(100% + 10px); 
        }
        
        .tooltip-text::after {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent rgba(51, 51, 51, 0.95) transparent;
        }
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

    @media (max-width: 768px) {
        .notes-pyramid {
            padding: 1rem 0.5rem;
            gap: 1.5rem;
        }

        .note-tag {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .tooltip-text {
            font-size: 0.8rem;
            padding: 6px 10px;
        }
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
<body>
    <?php include('partials/navbar.php') ?>
    <main>
        <section class="hero-section animate-on-scroll">
            <h2 class="section-title">Notes</h2>
            <div class="search-container user-search">
                <input id="search" type="search" placeholder="Cari Berdasarkan Notes, atau deskripsi..." class="search-input">
            </div>
            <div class="notes-pyramid" id="notes">
                <?php
                $categories = ['top' => 'Top Notes', 'middle' => 'Middle Notes', 'base' => 'Base Notes'];
                foreach ($categories as $category => $title) {
                    $sql = "SELECT nama, deskripsi FROM notes WHERE kategori_notes ='$category'";
                    $hasil = mysqli_query($conn, $sql);
                    if ($hasil->num_rows > 0): ?>
                        <div class="note-level <?= $category ?>-notes">
                            <h3 class="note-title"><?= $title ?></h3>
                            <div class="note-examples">
                                <?php while ($data = $hasil->fetch_assoc()): ?>
                                    <span class="note-tag tooltip">
                                        <?= htmlspecialchars($data['nama']) ?>
                                        <span class="tooltip-text"><?= htmlspecialchars($data['deskripsi']) ?></span>
                                    </span>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; 
                } ?>
            </div>
        </section>
    </main>
    <?php include('partials/footer.php') ?>
    <script>
        const search = document.getElementById('search');
        if (search) {
            search.addEventListener('keyup', function () {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && this.status == 200) {
                        document.getElementById("notes").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=notesUser", true);
                xhr.send();
            });
        }
    </script>
</body>
</html>