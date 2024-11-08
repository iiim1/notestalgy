<!DOCTYPE html>
<html lang="en">
<?php include('partials/head.php') ?>
<style>
    .section {
        padding: 2rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .merk {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        padding: 2rem 0;
        justify-items: center;
        align-items: center;
    }

    /* Brand Container */
    .tooltip {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 200px;
        padding: 1rem;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .tooltip:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .tooltip img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .tooltip-text {
        visibility: hidden;
        width: max-content;
        max-width: 200px;
        background-color: rgba(51, 51, 51, 0.95);
        color: #fff;
        text-align: center;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.9rem;
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
        max-width: min(200px, 90vw);
    }

    @media (max-width: 1200px) {
        .merk {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        .tooltip {
            height: 180px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 1.5rem 0.8rem;
        }

        .merk {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .tooltip {
            height: 150px;
            padding: 0.8rem;
        }

        .tooltip-text {
            font-size: 0.8rem;
            padding: 6px 10px;
        }
    }

    @media (max-width: 480px) {
        .merk {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.8rem;
        }

        .tooltip {
            height: 120px;
            padding: 0.6rem;
        }
    }

    .tooltip img {
        opacity: 0;
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
<body>
    <?php include('partials/navbar.php') ?>
    <main>
        <section class="section animate-on-scroll">
            <h2 class="section-title">Merk</h2>
            <div class="search-container user-search">
                <input id="search" placeholder="Cari Merk..." class="search-input">
            </div>
            <div class="merk" id="merk">
                <?php
                    $sqlMerk = "SELECT * FROM merk";
                    $data = mysqli_query($conn, $sqlMerk);
                    while($d = mysqli_fetch_assoc($data)){ ?>
                        <div class="tooltip">
                            <img 
                                src="assets/<?= $d['logo']; ?>" 
                                alt="<?= htmlspecialchars($d['nama']); ?>"
                            >
                            <span class="tooltip-text"><?= htmlspecialchars($d['nama']); ?></span>
                        </div>
                    <?php } ?>
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
                        document.getElementById("merk").innerHTML = xhr.responseText;
                    }
                };
                xhr.open("GET", "searchHandler.php?search=" + search.value + "&type=merkUser", true);
                xhr.send();
            });
        }
    </script>
</body>
</html>