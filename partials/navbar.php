<div class="toast-wrapper">
    <div class="toast">
        <div class="toast-content">
            <i class="fas fa-solid fa-check check"></i>
            <div class="message">
                <span class="text text-1"></span>
                <span class="text text-2"></span>
            </div>
        </div>
        <div class="progress"></div>
    </div>
</div>
<div id="confirmDeleteModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus data ini?</p>
        <button id="confirmDelete" class="btn btn-primary">Ya</button>
        <button id="cancelDelete" class="btn btn-secondary">Tidak</button>
    </div>
</div>


<nav class="navbar">
    <img src="assets/icon.png" alt="Logo" class="logo">
    <div class="burger">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="notes.php">Notes</a>
        <a href="merk.php">Merk</a>
        <a href="parfum.php">Parfum</a>
        <a href="ulasan.php">Ulasan</a>
        <a href="pengguna.php">Pengguna</a>
    </div>
    <?php } else {?>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="tampilNotes.php">Notes</a>
            <a href="tampilMerk.php">Merk</a>
            <a href="tampilParfum.php">Parfum</a>
            <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) { ?>
                <a href="tampilUlasan.php">Ulasan</a>
            <?php } ?>
        </div>
    <?php }?>
    <div class="login-register">
        <?php
            if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) { ?>
                <a href="registrasi.php" class="btn btn-secondary">Daftar</a>
                <a href="login.php" class="btn btn-primary">Masuk</a>
        <?php } else {?>
            <a href="profile.php" class="navbar-icon profile"><img src="assets/profile.png" alt="" id="profile"></a>
            <a href="logout.php" class="navbar-icon logout"><img src="assets/logout.png" alt="" id="logout"></a>
        <?php }?>
        <button id="darktheme">
            <img id="dark" src="assets/moon.png" alt="dark theme toggle" id="theme-icon">
        </button>
    </div>
</nav>