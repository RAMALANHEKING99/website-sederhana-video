<?php

session_start();
require 'config.php'; 

$error = false; 


if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username dan password
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error_message'] = true; // Set error message di sesi
        header("Location: login.php"); // Redirect untuk menghindari popup saat refresh
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Video Streaming</title>
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

        .login-container {
            background-color: rgba(255, 255, 255, 0.5); /* Setengah transparan */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        /* Popup styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            text-align: center;
        }

        .popup .popup-message {
            color: red;
            margin-bottom: 15px;
        }

        .popup .close-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup .close-btn:hover {
            background-color: #45a049;
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
    <script>
        function showPopup(message) {
            const popup = document.getElementById('popup');
            const overlay = document.getElementById('overlay');
            const popupMessage = document.getElementById('popup-message');

            popupMessage.textContent = message;
            popup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function closePopup() {
            const popup = document.getElementById('popup');
            const overlay = document.getElementById('overlay');

            popup.style.display = 'none';
            overlay.style.display = 'none';
        }
    </script>
</head>
<body>
<div class="login-container">
    <h2>Login ke Akun Anda</h2>
    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="login-link">
        <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
    </div>
</div>

<!-- Popup -->
<div id="overlay" class="overlay"></div>
<div id="popup" class="popup">
    <p id="popup-message" class="popup-message"></p>
    <button class="close-btn" onclick="closePopup()">Tutup</button>
</div>

<?php if ($error): ?>
    <script>
        window.onload = function() {
            showPopup("Username atau password salah!");
        };
    </script>
<?php endif; ?>

</body>
</html>
