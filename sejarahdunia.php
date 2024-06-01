<?php
session_start();
include 'koneksi.php';

// Mengambil data dari tabel artikel_sejarah dengan nama penulis khusus untuk kategori Sejarah Dunia
$sql = "SELECT a.*, p.nama_penulis FROM artikel_sejarah a JOIN penulis p ON a.id_penulis = p.id_penulis WHERE a.kategori='Sejarah Dunia'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HISTORIA - Sejarah Dunia</title>
    <link rel="stylesheet" href="css/stylesejarahdunia.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>HISTORIA</h1>
            <p>Platform Membaca Sejarah Online</p>
            <nav>
                <a href="beranda.php">Beranda</a>
                <a href="sejarahdunia.php">Sejarah Dunia</a>
                <a href="sejarahkultur.php">Sejarah Kultur</a>
                <a href="biografitokoh.php">Biografi Tokoh</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                    <a href="tambah_artikelsejarah.php">Tambah Artikel</a>
                <?php } ?>
            </nav>
        </div>
    </header>

    <section id="sejarah-dunia" class="articles">
        <div class="container">
            <h2>Kumpulan Artikel Sejarah Dunia</h2>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='article'>";
                    echo "<h3>" . $row['judul'] . "</h3>";
                    echo "<p>Penulis: " . $row['nama_penulis'] . "</p>"; // Tampilkan nama penulis
                    echo "<p>" . $row['isi'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Tidak ada artikel sejarah dunia ditemukan.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>
</body>
</html>
