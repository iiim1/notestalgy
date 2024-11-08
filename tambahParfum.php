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
<body>
    <?php include('partials/navbar.php') ?>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="form">
            <h2 class="form-title">Tambah Parfum</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Parfum</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama parfum" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" class="form-control" placeholder="Masukkan harga" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="merk">Pilih Merk</label>
                    <select name="merk" id="merk" class="form-control" required>
                        <option value="">Pilih Merk</option>
                        <?php
                        $sqlMerk = "SELECT id_merk, nama FROM merk";
                        $resultMerk = mysqli_query($conn, $sqlMerk);
                        if ($resultMerk->num_rows > 0) {
                            while ($row = $resultMerk->fetch_assoc()) {?>
                                <option value="<?=$row['id_merk']?>"> <?=htmlspecialchars($row['nama'])?> </option>
                            <?php }
                        } else { ?>
                            <option>Tidak ada data</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kategori">Pilih Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php
                        $sqlKtg = "SELECT id_kategori, nama FROM kategori";
                        $resultKtg = mysqli_query($conn, $sqlKtg);
                        if ($resultKtg->num_rows > 0) {
                            while ($row = $resultKtg->fetch_assoc()) {?>
                                <option value="<?=$row['id_kategori']?>"> <?=htmlspecialchars($row['nama'])?> </option>
                            <?php }
                        } else { ?>
                            <option>Tidak ada data</option>
                        <?php } ?>
                    </select>
                </div>
                <?php 
                $categories = ['top', 'middle', 'base'];
                foreach($categories as $category) {
                    $sqlNotes = "SELECT * FROM notes WHERE kategori_notes = '$category'";
                    $resultNotes = mysqli_query($conn, $sqlNotes);
                    $notesArray = array();
                    while($notes = mysqli_fetch_assoc($resultNotes)) {
                        $notesArray[] = array(
                            'id' => $notes['id_notes'],
                            'nama' => $notes['nama']
                        );
                    }
                ?>
                <div class="notes-section">
                    <h4><?= ucfirst($category) ?> Notes</h4>
                    <div class="dropdown">
                        <input type="text" 
                            placeholder="Pilih <?= ucfirst($category) ?> Notes..." 
                            id="dropdownInput<?= ucfirst($category) ?>" 
                            onfocus="toggleDropdown('<?= $category ?>')" 
                            onkeyup="filterFunction('<?= $category ?>')">
                        <div id="dropdownList<?= ucfirst($category) ?>" class="dropdown-content">
                            <?php foreach($notesArray as $note): ?>
                                <div class="dropdown-item" 
                                    onclick="selectNote(this)" 
                                    data-value="<?= $note['id'] ?>" 
                                    data-category="<?= $category ?>"
                                    data-name="<?= htmlspecialchars($note['nama']) ?>">
                                    <?= htmlspecialchars($note['nama']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div id="selectedNotes<?= ucfirst($category) ?>" class="selected-notes"></div>
                    <input type="hidden" name="selected_notes_<?= $category ?>" id="selectedNotesInput<?= ucfirst($category) ?>">
                </div>
                <?php } ?>
                <div class="form-group full-width">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukkan deskripsi parfum" required></textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="image">Foto Produk</label>
                    <div class="file-input-wrapper">
                        <label for="image" class="file-input-trigger">
                            <span>Pilih Foto</span>
                        </label>
                        <input type="file" id="image" name="gambar" accept=".png,.jpg,.jpeg,.webp" required>
                    </div>
                    <img id="imagePreview" class="preview-image">
                </div>
            </div>
            <button type="submit" id="saveButton" name="submit" class="submit-btn" style="width:100" >Tambah</button>
        </form>
    </div>
    <?php include('partials/footer.php') ?>
    <script>
        function toggleDropdown(category) {
            var dropdownList = document.getElementById("dropdownList" + category.charAt(0).toUpperCase() + category.slice(1));
            dropdownList.style.display = "block";
        }

        function filterFunction(category) {
            var input = document.getElementById("dropdownInput" + category.charAt(0).toUpperCase() + category.slice(1));
            var filter = input.value.toUpperCase();
            var dropdown = document.getElementById("dropdownList" + category.charAt(0).toUpperCase() + category.slice(1));
            var items = dropdown.getElementsByClassName("dropdown-item");
            
            for (var i = 0; i < items.length; i++) {
                var txtValue = items[i].textContent || items[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    items[i].style.display = "";
                } else {
                    items[i].style.display = "none";
                }
            }
        }

        function selectNote(element) {
            var category = element.dataset.category;
            var noteId = element.dataset.value;
            var noteName = element.dataset.name;
            var selectedNotesDiv = document.getElementById("selectedNotes" + category.charAt(0).toUpperCase() + category.slice(1));
            var selectedNotesInput = document.getElementById("selectedNotesInput" + category.charAt(0).toUpperCase() + category.slice(1));
            
            var currentValues = selectedNotesInput.value ? selectedNotesInput.value.split(",") : [];
            if (!currentValues.includes(noteId)) {
                var noteElement = document.createElement("div");
                noteElement.className = "selected-note";
                noteElement.innerHTML = noteName + 
                    '<span onclick="removeNote(this, \'' + noteId + '\', \'' + category + '\')" class="remove-note">&times;</span>';
                selectedNotesDiv.appendChild(noteElement);
                
                currentValues.push(noteId);
                selectedNotesInput.value = currentValues.join(",");
            }
            
            document.getElementById("dropdownList" + category.charAt(0).toUpperCase() + category.slice(1)).style.display = "none";
            document.getElementById("dropdownInput" + category.charAt(0).toUpperCase() + category.slice(1)).value = "";
        }

        function removeNote(element, noteId, category) {
            var selectedNotesInput = document.getElementById("selectedNotesInput" + category.charAt(0).toUpperCase() + category.slice(1));
            
            var currentValues = selectedNotesInput.value.split(",");
            var newValues = currentValues.filter(function(id) {
                return id !== noteId;
            });
            selectedNotesInput.value = newValues.join(",");
            
            element.parentElement.remove();
        }

        document.addEventListener("click", function(event) {
            if (!event.target.matches(".dropdown input")) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        });
    </script>
    <?php
    if (isset($_POST['submit'])) {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
        $image = $_FILES['gambar']['name'];
        $x = explode('.', $image);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];

        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $merk = $_POST['merk'];
        $kategori = $_POST['kategori'];
        $deskripsi = $_POST['deskripsi'];
        
        $check_sql = "SELECT * FROM parfum WHERE nama = '$nama'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            echo '<script>
            showAlert("danger", "Gagal", "Parfum sudah ada.");
            setTimeout(function() {
                window.location.href = "tambahParfum.php";
                }, 1500);
                </script>';
        } else {
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                if ($ukuran < 1048600) {
                    $new_filename = "parfum" . date("Y-m-d_H-i-s") . "." . $ekstensi;
                    $upload_path = 'assets/' . $new_filename;
                    if(move_uploaded_file($file_tmp, $upload_path)){
                        $image = $new_filename;
                    }
                    $sql = "INSERT INTO parfum (id_merk, nama, id_kategori, deskripsi, harga, gambar) VALUES ('$merk', '$nama', '$kategori', '$deskripsi', '$harga', '$image')";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $id_parfum = $conn->insert_id;
                        $topNotes = !empty($_POST['selected_notes_top']) ? explode(',', $_POST['selected_notes_top']) : [];
                        $middleNotes = !empty($_POST['selected_notes_middle']) ? explode(',', $_POST['selected_notes_middle']) : [];
                        $baseNotes = !empty($_POST['selected_notes_base']) ? explode(',', $_POST['selected_notes_base']) : [];
                        
                        $allNotes = array_merge($topNotes, $middleNotes, $baseNotes);
                        $sqlDelete = "DELETE FROM parfum_notes WHERE id_parfum = $id_parfum";
                        mysqli_query($conn, $sqlDelete);
                        
                        foreach ($allNotes as $note_id) {
                            if (!empty($note_id)) {
                                $sqlInsert = "INSERT INTO parfum_notes (id_parfum, id_notes) VALUES ($id_parfum, $note_id)";
                                mysqli_query($conn, $sqlInsert);
                            }
                        }
                        echo '<script>
                            showAlert("sukses", "Berhasil", "Berhasil menambah parfum.");
                            setTimeout(function() {
                                window.location.href = "parfum.php";
                            }, 1500);
                        </script>';
                    } else {
                        echo '<script>
                            showAlert("danger", "Gagal", "Gagal Menambah Parfum. Coba kembali.");
                            setTimeout(function() {
                                window.location.href = "tambahParfum.php";
                            }, 1500);
                        </script>';
                    }
                } else {
                    echo '<script>
                        showAlert("danger", "Gagal", "Ukuran file terlalu besar. Ukuran file maksimal adalah 1MB.");
                        setTimeout(function() {
                            window.location.href = "tambahParfum.php";
                        }, 1500);
                    </script>';
                }
            } else {
                echo '<script>
                    showAlert("danger", "Gagal", "Ekstensi file tidak diperbolehkan. Hanya format PNG, JPG, JPEG, dan WEBP yang diizinkan.");
                    setTimeout(function() {
                        window.location.href = "tambahParfum.php";
                    }, 1500);
                </script>';
            }
        }
    }   
    ?>
</body>
</html>