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
    <?php 
    include('partials/head.php');
    if (!isset($_GET['id'])) {
        header('Location: parfum.php');
        exit();
    }
    $id_parfum = $_GET['id'];

    $sql = "SELECT * FROM parfum WHERE id_parfum = $id_parfum";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows === 0) {
        header('Location: parfum.php');
        exit();
    }
    $parfum = mysqli_fetch_assoc($result);

    $existing_notes = array(
        'top' => array(),
        'middle' => array(),
        'base' => array()
    );

    $sql_notes = "SELECT n.id_notes, n.nama, n.kategori_notes 
                  FROM notes n 
                  JOIN parfum_notes pn ON n.id_notes = pn.id_notes 
                  WHERE pn.id_parfum = $id_parfum";
    $result_notes = mysqli_query($conn, $sql_notes);
    while ($note = mysqli_fetch_assoc($result_notes)) {
        $existing_notes[$note['kategori_notes']][] = array(
            'id' => $note['id_notes'],
            'nama' => $note['nama']
        );
    }
    ?>
<body>
    <?php include('partials/navbar.php') ?>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="form">
            <h2 class="form-title">Edit Parfum</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Parfum</label>
                    <input type="text" id="nama" name="nama" class="form-control" 
                           value="<?= htmlspecialchars($parfum['nama']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" class="form-control" 
                           value="<?= $parfum['harga'] ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="merk">Pilih Merk</label>
                    <select name="merk" id="merk" class="form-control" required>
                        <?php
                        $sqlMerk = "SELECT id_merk, nama FROM merk";
                        $resultMerk = mysqli_query($conn, $sqlMerk);
                        while ($row = $resultMerk->fetch_assoc()) {
                            $selected = ($row['id_merk'] == $parfum['id_merk']) ? 'selected' : '';
                            echo "<option value='{$row['id_merk']}' {$selected}>{$row['nama']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="kategori">Pilih Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <?php
                        $sqlKtg = "SELECT id_kategori, nama FROM kategori";
                        $resultKtg = mysqli_query($conn, $sqlKtg);
                        while ($row = $resultKtg->fetch_assoc()) {
                            $selected = ($row['id_kategori'] == $parfum['id_kategori']) ? 'selected' : '';
                            echo "<option value='{$row['id_kategori']}' {$selected}>{$row['nama']}</option>";
                        }
                        ?>
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
                    <div id="selectedNotes<?= ucfirst($category) ?>" class="selected-notes">
                        <?php foreach($existing_notes[$category] as $note): ?>
                            <div class="selected-note">
                                <?= htmlspecialchars($note['nama']) ?>
                                <span onclick="removeNote(this, '<?= $note['id'] ?>', '<?= $category ?>')" class="remove-note">&times;</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="selected_notes_<?= $category ?>" 
                           id="selectedNotesInput<?= ucfirst($category) ?>" 
                           value="<?= implode(',', array_column($existing_notes[$category], 'id')) ?>">
                </div>
                <?php } ?>
                <div class="form-group full-width">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" required><?= htmlspecialchars($parfum['deskripsi']) ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label" for="image">Foto Produk</label>
                    <div class="file-input-wrapper">
                        <label for="image" class="file-input-trigger">
                            <span>Pilih Foto</span>
                        </label>
                        <input type="file" id="image" name="gambar" accept=".png,.jpg,.jpeg,.webp">
                        <input type="hidden" name="gambar_lama" value="<?= $parfum['gambar'] ?>">
                    </div>
                    <img id="imagePreview" class="preview-image" src="assets/<?= $parfum['gambar'] ?>">
                </div>
            </div>
            <button type="submit" name="submit" class="submit-btn" style="width:100">Simpan Perubahan</button>
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

        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    <?php
    if (isset($_POST['submit'])) {
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $merk = $_POST['merk'];
        $kategori = $_POST['kategori'];
        $deskripsi = $_POST['deskripsi'];
        $gambar_lama = $_POST['gambar_lama'];
        
        // Check if name exists but exclude current parfum
        $check_sql = "SELECT * FROM parfum WHERE nama = '$nama' AND id_parfum != $id_parfum";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            echo '<script>
                showAlert("danger", "Gagal", "Nama parfum sudah ada.");
                setTimeout(function() {
                    window.location.href = "editParfum.php?id=' . $id_parfum . '";
                }, 1500);
            </script>';
            exit();
        }

        if ($_FILES['gambar']['size'] > 0) {
            $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
            $image = $_FILES['gambar']['name'];
            $x = explode('.', $image);
            $ekstensi = strtolower(end($x));
            $ukuran = $_FILES['gambar']['size'];
            $file_tmp = $_FILES['gambar']['tmp_name'];

            if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
                echo '<script>
                    showAlert("danger", "Gagal", "Ekstensi file tidak diperbolehkan. Hanya format PNG, JPG, JPEG, dan WEBP yang diizinkan.");
                    setTimeout(function() {
                        window.location.href = "editParfum.php?id=' . $id_parfum . '";
                    }, 1500);
                </script>';
                exit();
            }

            if ($ukuran > 1048600) {
                echo '<script>
                    showAlert("danger", "Gagal", "Ukuran file terlalu besar. Ukuran file maksimal adalah 1MB.");
                    setTimeout(function() {
                        window.location.href = "editParfum.php?id=' . $id_parfum . '";
                    }, 1500);
                </script>';
                exit();
            }

            $new_filename = "parfum" . date("Y-m-d_H-i-s") . "." . $ekstensi;
            $upload_path = 'assets/' . $new_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                if (file_exists('assets/' . $gambar_lama)) {
                    unlink('assets/' . $gambar_lama);
                }
                $gambar_lama = $new_filename;
            }
        }

        $sql = "UPDATE parfum SET 
                id_merk = '$merk',
                nama = '$nama',
                id_kategori = '$kategori',
                deskripsi = '$deskripsi',
                harga = '$harga',
                gambar = '$gambar_lama'
                WHERE id_parfum = $id_parfum";
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
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
                showAlert("sukses", "Berhasil", "Berhasil mengupdate parfum.");
                setTimeout(function() {
                    window.location.href = "parfum.php";
                }, 1500);
            </script>';
        } else {
            echo '<script>
                showAlert("danger", "Gagal", "Gagal mengupdate parfum. Coba kembali.");
                setTimeout(function() {
                    window.location.href = "editParfum.php?id=' . $id_parfum . '";
                }, 1500);
            </script>';
        }
    }
    ?>
</body>
</html>