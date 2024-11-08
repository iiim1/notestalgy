<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
    <style>
        .search-results-dropdown {
            position: absolute;
            width: 535px;
            background: var(--color-white);
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
        }

        .search-result-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: var(--color-text-secondary);
        }

        .search-result-item .merk-name {
            color: var(--color-text-primary);
            font-size: 0.9em;
        }

        .no-results {
            padding: 15px;
            text-align: center;
            color: var(--color-text-primary);
            font-style: italic;
        }
    </style>
    <body>
    <?php include('partials/navbar.php') ?>
    <main>
        <section class="hero animate-on-scroll" id="beranda">
            <div class="hero-content">
                <img src="assets/typo.png" class="note-logo" alt="Notestalgy Logo" id="typo">
                <h1>Temukan Aroma Khas yang Anda Cintai</h1>
                <p>Temukan Wewangian Sempurna Anda Melalui Ulasan Otentik dan Rekomendasi Terbaik</p>
                <div class="search-container">
                    <input type="text" id="searchInput" name="search" class="search-input"
                    placeholder="Cari berdasarkan nama parfum atau merk..." 
                    autocomplete="off">
                    <div id="searchResults" class="search-results-dropdown"></div>
            </div>
        </section>
        <section class="hero-section animate-on-scroll" id="parfum">
            <h2 class="section-title">Parfum dengan Rating Teratas</h2>
            <div class="category-grid">
                <?php
                $sql = "SELECT 
                        p.*,
                        m.nama AS merk,
                        ROUND(AVG(u.rating), 1) as avg_rating,
                        COUNT(u.id_ulasan) as review_count
                    FROM parfum p
                    JOIN merk m on p.id_merk = m.id_merk
                    LEFT JOIN ulasan u ON p.id_parfum = u.id_parfum
                    GROUP BY p.id_parfum, p.id_merk, p.nama, p.id_kategori, p.deskripsi, p.harga, p.gambar, p.tanggal_buat, p.tanggal_ubah
                    HAVING review_count > 0
                    ORDER BY avg_rating DESC, review_count DESC
                    LIMIT 3";
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    echo "Error: " . mysqli_error($conn);
                } else {  ?>                     
                <div class="card-container">
                    <?php while($row = mysqli_fetch_assoc($result)) { 
                        $rating = $row['avg_rating'];
                        $fullStars = floor($rating);                        
                        $stars = str_repeat('★', $fullStars)
                        ?>
                        <div class="card">
                            <div class="imgBx">
                                <img src="assets/<?php echo htmlspecialchars($row['gambar']); ?>" 
                                    alt="<?php echo htmlspecialchars($row['nama']); ?>">
                            </div>
                            <div class="contentBx" style="color:var(--color-primary);" >
                                <h2 style="color:var(--color-primary);"><?php echo htmlspecialchars($row['nama']); ?></h2>
                                <p style="color:var(--color-primary);"><?php echo htmlspecialchars($row['merk']); ?></p>
                                <div class="rating-container">
                                    <span class="rating"><?php echo $stars; ?></span>
                                    <span class="reviews">
                                        <?php echo number_format($rating, 1); ?> 
                                        (<?php echo number_format($row['review_count']); ?> reviews)
                                    </span>
                                </div>
                                <a href="detailParfum.php?id_parfum=<?php echo $row['id_parfum']; ?>">Lihat Detail</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                    <?php 
                    }
                ?>
            </div>
        </section>

        <section class="hero-section animate-on-scroll" id="kategori">
            <h2 class="section-title">Kategori</h2>
            <div class="category-grid">
                <?php
                $sqlKategori = "SELECT * FROM kategori";
                $hasilKategori = mysqli_query($conn, $sqlKategori);
                if ($hasilKategori->num_rows > 0) {
                    while ($data = $hasilKategori->fetch_assoc()) {
                        ?>
                        <div class="category-card">
                            <h3><?= htmlspecialchars($data['nama']) ?></h3>
                            <p><?= htmlspecialchars($data['deskripsi']) ?></p>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </section>
        <section class="hero-section animate-on-scroll" id="notes">
            <h2 class="section-title">Memahami Notes di Setiap Wewangian</h2>
            <div class="notes-pyramid">
                <?php
                $categories = ['top' => 'Top Notes', 'middle' => 'Middle Notes', 'base' => 'Base Notes'];
                foreach ($categories as $category => $title) {
                    $sql = "SELECT nama FROM notes WHERE kategori_notes ='$category' LIMIT 3";
                    $hasil = mysqli_query($conn, $sql);
                    if ($hasil->num_rows > 0): ?>
                        <div class="note-level <?= $category ?>-notes">
                            <h3 class="note-title"><?= $title ?></h3>
                            <p class="note-description"><?= ($category == 'top') ? 'Kesan Pertama - Bertahan 15-30 Menit' : (($category == 'middle') ? 'Karakter Utama - Bertahan 2-4 Jam' : 'Dasar Wewangian - Bertahan Lebih dari 4 Jam') ?></p>
                            <div class="note-examples">
                                <?php while ($data = $hasil->fetch_assoc()): ?>
                                    <span class="note-tag"><?= htmlspecialchars($data['nama']) ?></span>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; 
                } ?>
            </div>
            <a href="tampilNotes.php" class="explore">Lihat Selengkapnya</a>
        </section>
        <section class="hero-section animate-on-scroll" id="merk" >
            <h2 class="section-title">Merk</h2>
            <div class="slider">
                <div class="slide-track">
                    <?php
                    $sqlMerk = "SELECT * FROM merk";
                    $data = mysqli_query($conn, $sqlMerk);
                    while($d = mysqli_fetch_array($data)){?>
                        <div class="slide">
                            <img src="assets/<?= $d['logo']; ?>" alt="<?= htmlspecialchars($d['nama']); ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <a href="tampilMerk.php" class="explore">Lihat Selengkapnya</a>
        </section>
    </main>
    <?php include('partials/footer.php') ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let debounceTimer;

        function performSearch(query) {
            if (query.length === 0) {
                searchResults.style.display = 'none';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        searchResults.style.display = 'block';
                        searchResults.innerHTML = xhr.responseText;
                        
                        const productCards = searchResults.querySelectorAll('.product-card');
                        productCards.forEach(card => {
                            const button = card.querySelector('.submit-btn');
                            const href = button?.getAttribute('onclick')?.match(/location\.href='([^']+)'/)?.[1];
                            if (href) {
                                card.style.cursor = 'pointer';
                                card.addEventListener('click', () => window.location.href = href);
                            }
                        });
                    } else {
                        searchResults.innerHTML = '<div class="no-results">Error loading results</div>';
                    }
                }
            };

            xhr.open('GET', `searchHandler.php?type=index&search=${encodeURIComponent(query)}`, true);
            xhr.send();
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(this.value);
            }, 200);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.length > 0) {
                performSearch(this.value);
            }
        });
    });
</script>
</body>
</html>
