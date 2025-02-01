<?php
session_start(); 
require 'config.php'; 


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

echo "<h1>Selamat Datang di website sederhana</h1>";

echo "<div style='text-align: right; margin-top: -40px;'>
        <span>Halo, " . htmlspecialchars($username) . " | <a href='logout.php'>Logout</a></span>
      </div>";

echo "<a href='upload.php'>
        <button style='padding: 10px 20px; font-size: 16px;'>Upload Video</button>
    </a>";


echo "<div class='video-container'>";

$jsonFile = 'video_data.json';
if (file_exists($jsonFile)) {
    $videoDataArray = json_decode(file_get_contents($jsonFile), true);

    foreach ($videoDataArray as $videoData) {
        $video_path = $videoData['video_path']; 
        $video_title = $videoData['judul'];   
        $video_description = $videoData['dekripsi']; 

        echo "<div class='video-item'>
                <video class='video-thumbnail' controls data-video-src='" . $video_path . "'>
                    <source src='" . $video_path . "' type='video/mp4'>
                    Your browser does not support the video tag.
                </video>
                <div class='video-description'>
                    <h3>" . htmlspecialchars($video_title) . "</h3>
                    <p>" . htmlspecialchars($video_description) . "</p>
                </div>
            </div>";
    }
} else {
    echo "<p>Belum ada video yang diupload.</p>";
}

echo "</div>";
?>

<div id="videoModal" class="modal">
    <span class="close-btn">&times;</span>
    <video id="modalVideo" controls></video>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    h1 {
        color: #333;
        margin-top: 20px;
    }

    a {
        text-decoration: none;
    }

    button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #FF0000;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    button:hover {
        background-color: #e60000;
    }

    /* Menggunakan grid untuk menampilkan video */
    .video-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
        padding: 0 10px;
    }

    .video-item {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .video-thumbnail {
        width: 100%;
        height: 200px;
        object-fit: contain;
        cursor: pointer;
        border-radius: 8px;
    }

    .video-description {
        padding: 10px;
        text-align: left;
    }

    .video-description h3 {
        font-size: 18px;
        color: #333;
        margin: 0;
    }

    .video-description p {
        font-size: 14px;
        color: #666;
        margin: 5px 0;
    }

    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
    }

    .modal video {
        width: 80%;
        max-width: 900px;
        height: auto;
        border-radius: 8px;
    }

    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        color: #fff;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    /* Responsif untuk layar kecil (mobile) */
    @media (max-width: 768px) {
        .modal video {
            width: 95%;
        }
    }

    /* Responsif untuk layar besar (desktop/laptop) */
    @media (min-width: 769px) {
        .modal video {
            width: 80%;
        }
    }
</style>

<script>
    
    const videoThumbnails = document.querySelectorAll('.video-thumbnail');
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    const closeBtn = document.querySelector('.close-btn');

    
    videoThumbnails.forEach((video) => {
        video.addEventListener('click', () => {
            const videoSrc = video.getAttribute('data-video-src');
            modal.style.display = 'flex';
            modalVideo.src = videoSrc;
        });
    });

    // Menutup modal saat tombol close diklik
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        modalVideo.src = '';  // Hentikan video saat modal ditutup
    });

    // Menutup modal saat area di luar video diklik
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
            modalVideo.src = '';  
        }
    });
</script>
