<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = $_POST['username'];
    $email = $_POST['email'];
    $kata_sandi = $_POST['password']; // Kata sandi dalam format teks biasa

    // Tidak melakukan hashing pada kata sandi

    $sql = "INSERT INTO pengguna (nama_pengguna, email, kata_sandi) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if prepare was successful
    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }

    $stmt->bind_param("sss", $nama_pengguna, $email, $kata_sandi);

    if ($stmt->execute()) {
        echo "Pendaftaran berhasil! Silakan <a href='masuk.php'>masuk</a>.";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/stylebuatakun.css">
</head>
<body>
    <div class="headline">
        <header>HISTORIA</header> <!-- Menambahkan kata "HISTORIA" di atas kotak form -->
    </div>
    <div class="subheadline">
        <p>Selamat datang di HISTORIA, Temukan keajaiban sejarah yang terlupakan!</p> <!-- Kata-kata yang mengajak pembaca agar lebih tertarik -->
    </div>
    <div class="login-box">
        <form method="post" action="buatakun.php">
            <div class="input-box">
                <input type="text" name="username" class="input-field" placeholder="Nama Pengguna" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" class="input-field" placeholder="Alamat Email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Kata Sandi" autocomplete="off" required>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn" id="submit">Daftar</button>
            </div>
        </form>
        <div class="sign-up-link">
            <p>Sudah punya akun? <a href="masuk.php">Masuk</a></p>
        </div>
    </div>
</body>
</html>
