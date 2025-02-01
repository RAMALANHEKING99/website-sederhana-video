<?php
// register.php

// Include the database configuration file
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Koneksi ke database sudah dilakukan di config.php
    // $conn = new mysqli("localhost", "root", "", "video"); // This line is removed

    // Cek apakah username sudah terdaftar
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='error-message'>Username sudah terdaftar!</div>";
    } else {
        // Insert data pengguna baru
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $hashed_password);
        $stmt->execute();

        echo "<div class='success-message'>Pendaftaran berhasil! Silakan <a href='login.php'>login</a></div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Video Streaming</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            background-image: url('uploads/subtitles/oplos.png'); /* Ganti dengan path gambar latar belakang */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.5); /* Setengah transparan */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .register-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .register-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .register-container button:hover {
            background-color: #45a049;
        }

        .error-message, .success-message {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .register-container {
                padding: 20px;
                max-width: 100%;
                margin: 0 20px;
            }

            .register-container h2 {
                font-size: 20px;
            }

            .register-container input, .register-container button {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Daftar Akun Baru</h2>
    <form method="POST" action="register.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>

    <div class="register-link">
        <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>
</div>

</body>
</html>
