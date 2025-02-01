<?php
// Konfigurasi database MySQL
$servername = "localhost";  // Nama host database
$username = "root";         // Nama pengguna database
$password = "";             // Password pengguna database
$dbname = "video"; // Nama database


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
