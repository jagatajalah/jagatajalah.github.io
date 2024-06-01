<?php
$servername = "localhost";
$username = "id22255115_kelompok1";  // Ganti dengan username database Anda
$password = "#Dbkelompok1";      // Ganti dengan password database Anda
$database = "id22255115_db_historia";  // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
