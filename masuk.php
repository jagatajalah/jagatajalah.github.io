<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pengguna = $_POST['nama']; // Perbaiki untuk menggunakan 'nama' sebagai name attribute di form
    $kata_sandi = $_POST['password'];

    $sql = "SELECT * FROM pengguna WHERE nama_pengguna=? AND kata_sandi=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nama_pengguna, $kata_sandi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['nama_pengguna'] = $nama_pengguna;
        header("Location: beranda.php");
        exit;
    } else {
        $error_message = "Nama pengguna atau kata sandi salah.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    <link rel="stylesheet" href="css/stylemasuk.css">
</head>
<body>
    <div class="container">
        <h2>Masuk</h2>
        <?php
        // Tampilkan pesan kesalahan jika ada
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>
        <form method="post" action="#">
            <label for="nama">Nama Pengguna</label>
            <input type="text" id="nama" name="nama" required>
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Masuk">
        </form>
        <p>Belum punya akun? <a href="buatakun.php">Buat disini</a></p>
    </div>
</body>
</html>
