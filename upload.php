<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

        // Menghandle upload video, judul, dan dekripsi
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Menghandle video
            if (isset($_FILES['video'])) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["video"]["name"]);
                $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                // Cek apakah file video adalah mp4
                if ($videoFileType != "mp4") {
                    echo "Hanya file video MP4 yang diperbolehkan.";
                } else {
                    // Pindahkan video dari folder sementara ke folder video
                    if (move_uploaded_file($_FILES["video"]["tmp_name"], "videos/" . basename($_FILES["video"]["name"]))) {
                        echo "Video berhasil diupload.";
                    } else {
                        echo "Terjadi kesalahan saat mengupload video.";
                    }
                }
            }

            // Menangani dekripsi dan judul video
            if (isset($_POST['dekripsi']) && isset($_POST['judul_video'])) {
                $dekripsi = $_POST['dekripsi']; // Menyimpan dekripsi yang dikirim
                $judul_video = $_POST['judul_video']; // Menyimpan judul video yang dimasukkan pengguna

                // Menyiapkan data yang akan disimpan
                $videoData = [
                    'judul' => $judul_video,
                    'dekripsi' => $dekripsi,
                    'video_path' => "videos/" . basename($_FILES["video"]["name"])
                ];

                // Menyimpan data ke file JSON
                $jsonFile = 'video_data.json';

                // Memeriksa apakah file JSON sudah ada
                if (file_exists($jsonFile)) {
                    // Membaca data yang ada di file JSON
                    $currentData = json_decode(file_get_contents($jsonFile), true);
                } else {
                    // Jika file belum ada, buat array kosong
                    $currentData = [];
                }

                // Menambahkan data baru ke dalam array
                $currentData[] = $videoData;

                // Menyimpan data yang telah diperbarui ke dalam file JSON
                if (file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT))) {
                    echo "Data video berhasil disimpan dalam file JSON.";
                } else {
                    echo "Terjadi kesalahan saat menyimpan data ke file JSON.";
                }
            }
        }
        ?>
        <link rel="stylesheet" href="style.css">

        <h1>Upload Video dengan Judul dan Deskripsi</h1>

        <!-- Form untuk upload video, judul video, dan dekripsi -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Judul Video:
            <input type="text" name="judul_video" id="judul_video" placeholder="Masukkan judul video" required>
            
            <br><br>
            Pilih video untuk diupload:
            <input type="file" name="video" id="video" accept="video/mp4" onchange="previewVideo()" required>
            
            <br><br>
            Dekripsi:
            <textarea name="dekripsi" id="dekripsi" rows="4" cols="50" placeholder="Masukkan deskripsi video"></textarea>

            <br><br>
            <input type="submit" value="Upload Video" name="submit">
        </form>

        <a href="index.php">Kembali ke Beranda</a>

        <!-- Menampilkan pratinjau video -->
        <h3>Pratinjau Video:</h3>
        <video id="videoPreview" width="300" controls style="display:none;">
            <source id="videoSource" src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div id="videoTitleContainer" style="text-align: center; margin-top: 10px; font-size: 20px; font-weight: bold;"></div>

        <div id="dekripsiContainer" style="text-align: center; margin-top: 10px;">
            <p id="dekripsiText"></p>
        </div>

        <script>
            function previewVideo() {
                const file = document.getElementById('video').files[0];
                const preview = document.getElementById('videoPreview');
                const videoSource = document.getElementById('videoSource');
                const videoTitleContainer = document.getElementById('videoTitleContainer');
                const dekripsiText = document.getElementById('dekripsiText');
                const dekripsi = document.getElementById('dekripsi').value;
                const judul_video = document.getElementById('judul_video').value;

                // Pastikan file yang dipilih adalah video
                if (file && file.type.match('video.*')) {
                    const fileURL = URL.createObjectURL(file); // Membuat URL objek untuk file yang dipilih
                    videoSource.src = fileURL; // Menetapkan URL file ke elemen source
                    preview.style.display = 'block'; // Menampilkan pratinjau video
                    preview.load(); // Memuat video agar bisa diputar

                    // Menampilkan judul video berdasarkan input pengguna
                    videoTitleContainer.innerText = "Judul Video: " + judul_video;

                    // Menampilkan dekripsi
                    if (dekripsi) {
                        dekripsiText.innerText = dekripsi; // Menampilkan teks dekripsi
                    }

                    preview.play(); // Memulai pemutaran video setelah dimuat
                } else {
                    preview.style.display = 'none'; // Menyembunyikan pratinjau jika tidak ada file video yang valid
                }
            }

            const videoElement = document.getElementById('videoPreview');
            videoElement.ontimeupdate = function() {
                const currentTime = videoElement.currentTime;

                if (currentTime >= 5 && currentTime <= 10) {
                    document.getElementById('dekripsiContainer').style.display = 'block';
                } else {
                    document.getElementById('dekripsiContainer').style.display = 'none';
                }
            };
        </script>
 
