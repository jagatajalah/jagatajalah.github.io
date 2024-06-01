<?php
session_start();
include 'koneksi.php';

// Periksa koneksi database
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$id_penulis = $_SESSION['id_penulis'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Hapus artikel
        $id_artikel = $_POST['id_artikel'];
        $sql = "DELETE FROM artikel_sejarah WHERE id_artikel=? AND id_penulis=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("ii", $id_artikel, $id_penulis);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Artikel berhasil dihapus.";
        } else {
            $message = "Gagal menghapus artikel.";
        }
        $stmt->close();
    } elseif (isset($_POST['judul']) && isset($_POST['isi']) && isset($_POST['kategori'])) {
        $judul = $_POST['judul'];
        $isi = $_POST['isi'];
        $kategori = $_POST['kategori'];
        $tanggal_publikasi = date('Y-m-d'); // Set tanggal publikasi ke tanggal hari ini
        
        if (isset($_POST['update'])) {
            // Update artikel
            $id_artikel = $_POST['id_artikel'];
            $sql = "UPDATE artikel_sejarah SET judul=?, isi=?, kategori=?, tanggal_publikasi=? WHERE id_artikel=? AND id_penulis=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing query: " . $conn->error);
            }
            $stmt->bind_param("ssssii", $judul, $isi, $kategori, $tanggal_publikasi, $id_artikel, $id_penulis);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "Artikel berhasil diperbarui.";
            } else {
                $message = "Gagal memperbarui artikel.";
            }
            $stmt->close();
        } else {
            // Tambah artikel
            $sql = "INSERT INTO artikel_sejarah (id_penulis, judul, isi, kategori, tanggal_publikasi) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing query: " . $conn->error);
            }
            $stmt->bind_param("issss", $id_penulis, $judul, $isi, $kategori, $tanggal_publikasi);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "Artikel berhasil ditambahkan.";
            } else {
                $message = "Gagal menambahkan artikel.";
            }
            $stmt->close();
        }
    } else {
        $message = "Mohon lengkapi semua kolom.";
    }
}

$edit_article = null;
if (isset($_GET['edit'])) {
    $id_artikel = $_GET['edit'];
    $sql = "SELECT * FROM artikel_sejarah WHERE id_artikel=? AND id_penulis=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_artikel, $id_penulis);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $edit_article = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Artikel</title>
</head>
<body>
    <h2><?php echo isset($edit_article) ? 'Edit' : 'Tambah'; ?> Artikel</h2>
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="post" action="#">
        <?php if (isset($edit_article)): ?>
            <input type="hidden" name="id_artikel" value="<?php echo $edit_article['id_artikel']; ?>">
        <?php endif; ?>
        <label for="judul">Judul:</label><br>
        <input type="text" id="judul" name="judul" value="<?php echo isset($edit_article) ? $edit_article['judul'] : ''; ?>" required><br><br>
        <label for="isi">Isi Artikel:</label><br>
        <textarea id="isi" name="isi" rows="4" cols="50" required><?php echo isset($edit_article) ? $edit_article['isi'] : ''; ?></textarea><br><br>
        <label for="kategori">Kategori:</label><br>
        <select id="kategori" name="kategori" required>
            <option value="Sejarah Dunia" <?php echo (isset($edit_article) && $edit_article['kategori'] == 'Sejarah Dunia') ? 'selected' : ''; ?>>Sejarah Dunia</option>
            <option value="Sejarah Kultur" <?php echo (isset($edit_article) && $edit_article['kategori'] == 'Sejarah Kultur') ? 'selected' : ''; ?>>Sejarah Kultur</option>
            <option value="Biografi Tokoh" <?php echo (isset($edit_article) && $edit_article['kategori'] == 'Biografi Tokoh') ? 'selected' : ''; ?>>Biografi Tokoh</option>
        </select><br><br>
        <input type="submit" name="<?php echo isset($edit_article) ? 'update' : 'submit'; ?>" value="<?php echo isset($edit_article) ? 'Update Artikel' : 'Tambah Artikel'; ?>">
    </form>

    <h2>Daftar Artikel</h2>
    <table border="1">
        <tr>
            <th>Judul</th>
            <th>Isi</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM artikel_sejarah WHERE id_penulis=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_penulis);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
            echo "<td>" . htmlspecialchars($row['isi']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
            echo "<td>";
            echo "<a href='?edit=" . $row['id_artikel'] . "'>Edit</a> ";
            echo "<form method='post' action='#' style='display:inline-block;'>";
            echo "<input type='hidden' name='id_artikel' value='" . $row['id_artikel'] . "'>";
            echo "<input type='hidden' name='delete'>"; // Tambahkan input tersembunyi untuk aksi hapus
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        $stmt->close();
        ?>
    </table>
</body>
</html>


