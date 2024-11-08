function showAlert(type, headText, bodyText) {
    const toastWrapper = document.querySelector(".toast-wrapper");
    const toast = toastWrapper.querySelector(".toast");
    const header = toast.querySelector(".text-1");
    const message = toast.querySelector(".text-2");
    const progress = toast.querySelector(".progress");

    // Tampilkan toast-wrapper
    toastWrapper.style.display = "block";

    if (type === "sukses") {
        toast.classList.add("active");
        header.innerText = headText;
        message.innerText = bodyText;
        progress.classList.add("active");
        toast.style.backgroundColor = "#d4edda";
        header.style.color = "#155724";
    } else if (type === "danger") {
        toast.classList.add("active");
        header.innerText = headText;
        message.innerText = bodyText;
        progress.classList.add("active");
        toast.style.backgroundColor = "#f8d7da";
        header.style.color = "#721c24";
    }

    setTimeout(() => {
        toast.classList.remove("active");
        progress.classList.remove("active");
        toastWrapper.style.display = "none";
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
    const darkmode = document.getElementById("dark");
    const profile = document.getElementById("profile");
    const logout = document.getElementById("logout");
    const tambah = document.getElementById("tambah");
    const typo = document.getElementById("typo");

    if (localStorage.getItem("dark-theme") === "enabled") {
        document.body.classList.add("dark-theme");
        if (darkmode) darkmode.src = "assets/sun.png";
        if (profile) profile.src = "assets/profile-dark.png";
        if (logout) logout.src = "assets/logout-dark.png";
        if (tambah) tambah.src = "assets/add-dark.png";
        if (typo) typo.src = "assets/typo-dark.png";
    }

    if (darkmode) {
        darkmode.onclick = function () {
            document.body.classList.toggle("dark-theme");
            if (document.body.classList.contains("dark-theme")) {
                if (darkmode) darkmode.src = "assets/sun.png";
                if (profile) profile.src = "assets/profile-dark.png";
                if (logout) logout.src = "assets/logout-dark.png";
                if (tambah) tambah.src = "assets/add-dark.png";
                if (typo) typo.src = "assets/typo-dark.png";
                localStorage.setItem("dark-theme", "enabled"); 
            } else {
                if (darkmode) darkmode.src = "assets/moon.png";
                if (profile) profile.src = "assets/profile.png";
                if (logout) logout.src = "assets/logout.png";
                if (tambah) tambah.src = "assets/add.png";
                if (typo) typo.src = "assets/typo.png";
                localStorage.setItem("dark-theme", "disabled"); 
            }
        };
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');
    const navRight = document.querySelector('.login-register');

    if (burger && navLinks && navRight) {
        burger.addEventListener('click', function () {
            this.classList.toggle('change');
            navLinks.classList.toggle('active');
            navRight.classList.toggle('active');
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                burger.classList.remove('change');
                navLinks.classList.toggle('active');
                navRight.classList.toggle('active');
            });
        });
    }


    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            } else {
                entry.target.classList.remove('is-visible');
            }
        });
    }, {
        threshold: 0.1
    });

    animatedElements.forEach(element => {
        observer.observe(element);
    });

    const image = document.getElementById('image');
    if (image) image.addEventListener('change', function (e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Update file input trigger text
    if (image) image.addEventListener('change', function (e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            this.previousElementSibling.querySelector('span').textContent = fileName;
        }
    });

    const toggleButton = document.querySelector('.toggle-button');
    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            const reviewContent = document.querySelector('.review-content');

            if (reviewContent.style.display === 'none' || reviewContent.style.display === '') {
                reviewContent.style.display = 'block';
                toggleButton.textContent = '-';
            } else {
                let konfirmasi = confirm("Sembunyikan ulasan?");
                if (konfirmasi) {
                    reviewContent.style.display = 'none';
                    toggleButton.textContent = '+';
                }
            }
        });
    }

    const modal = document.getElementById("confirmDeleteModal");
    let deleteLink;

    const deleteButtons = document.querySelectorAll(".hapus");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            deleteLink = this.href;
            modal.style.display = "block"; 
        });
    });

    document.getElementById("confirmDelete").onclick = function () {
        window.location.href = deleteLink; 
    }

    document.getElementById("cancelDelete").onclick = function () {
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none"; 
        }
    }

    document.querySelector(".close").onclick = function () {
        modal.style.display = "none"; 
    }

});
