<!DOCTYPE html>
<html lang="en">
    <?php include('partials/head.php') ?>
    <style>
        .star {
            color: #ccc; 
            font-size: 20px;
        }
        .filled-star {
            color: #FFD700; 
        }
    </style>
    <body>
    <?php include('partials/navbar.php') ?>
    <section class="parfum-collection" id="parfum">
        <h2>Koleksi Parfum</h2>
        <div class="search-container user-search">
            <input id="search" placeholder="Cari Berdasarkan Nama Parfum, Merek, atau Aroma..." class="search-input">
        </div>
        <div class="product-grid" id="product-grid">
            <?php
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
                        GROUP BY 
                            p.id_parfum, m.nama
                        ORDER BY p.id_parfum DESC";
            $dataParfum = mysqli_query($conn, $sqlParfum);
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
            <?php } ?>
        </div>
    </section>
    <?php include('partials/footer.php') ?>
        <script>
        const search = document.getElementById('search');
        if (search) {
            search.addEventListener('keyup', function () {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && this.status == 200) {
                        document.getElementById("product-grid").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=parfumUser", true);
                xhr.send();
            });
        }

        let stars = document.getElementsByClassName("star");
        let output = document.getElementById("output");

        function gfg(n) {
            remove();
            let cls = "";
            for (let i = 0; i < n; i++) {
                if (n == 1) cls = "one";
                else if (n == 2) cls = "two";
                else if (n == 3) cls = "three";
                else if (n == 4) cls = "four";
                else if (n == 5) cls = "five";
                stars[i].className = "star " + cls;
            }
            output.innerText = "Rating: " + n + "/5";
            document.getElementById("rating").value = n;
        }

        function remove() {
            for (let i = 0; i < 5; i++) {
                stars[i].className = "star";
            }
        }
    </script>
</body>
</html>