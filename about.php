<?php 
session_start(); 
require 'koneksi.php';

if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: index.php");
    exit();
}

$sqlAbout = "SELECT * FROM about";
$resultAbout = $koneksi->query($sqlAbout);

// Query untuk mengambil data kategori
$sqlKategori = "SELECT * FROM kategori";
$resultKategori = $koneksi->query($sqlKategori);

if (!$resultKategori) {
    die("Query gagal: " . $koneksi->error);
} elseif ($resultKategori->num_rows === 0) {
    echo "Tidak ada kategori yang ditemukan.";
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="about.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
          .h3, .p, .logout-popup {
            font-family: 'Poppins', sans-serif;
        }

        .logout-popup {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logout-popup-content {
            position: relative;
            background: #fff;
            border-radius: 10px;
            padding: 18px 50px 18px 50px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease;
            z-index: 1000;
        }
        @keyframes fadeIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .cclose-btnn {
            position: absolute;
            top: 7px;
            right: 7px;
            background: none;
            border: none;
            font-size: 1.23rem;
            cursor: pointer;
        }

        .popup-icon {
            width: 119px; 
            margin-bottom: 1px; 
        }
        h3 {
            font-size: 0.92rem;
            font-weight: 500;
            margin-bottom: 10px;
        }
        p {
            font-size: 0.75rem;
            font-weight: 300;
            color: #555;
            margin-bottom: 25px;
        }

        .logout-popup button {
            margin: 5px; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }

        .popup-btn {
            padding: 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            cursor: pointer;
            margin-top: 28px;
            width: 79%; 
            text-align: center; 
            display: inline-block; 
        }

        .primary-btn {
            background: linear-gradient(45deg, #313684, #F584B2);
            border: none;
            color: white;
            font-weight: 500;
            text-decoration: none; 
        }

        .secondary-btn {
            background: none;
            border: none;
            color: #888;
            text-decoration: underline;
            font-weight: 400;
        }
    </style>
</head>
<body>
    <!-- cursor start -->
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <!-- cursor end -->

    <!-- navbar start -->
    <header>
        <h2 class="logo">MAHANTA</h2>
        <nav class="navigation">
            <a href="dashboard.php">Home</a>
            <a href="about.php">About</a>
            <a href="art.php">Art</a>
            <button class="btnlogout-popup" id="logout-button">Logout</button>
        </nav>
    </header>
    <!-- navbar end -->
    
    <!-- parallax start -->
    <section class="banner">
      <img src="asset/background.png" id="bg" alt="">
      <img src="asset/love.png" id="love" alt="">
      <img src="asset/mahanta.png" id="text" alt="">
      <img src="asset/ombak1.png" id="ombak" alt="">
      <img src="asset/ombak2.png" id="ombik" alt="">
    </section>
    <!-- parallax end -->

    <!-- about start -->
    <section id="about">
    <div class="container">
        <div class="textBx">
            <?php 
            if ($resultAbout->num_rows > 0) {
                while ($rowAbout = $resultAbout->fetch_assoc()) {
                    echo "<h2 class='title'>" . htmlspecialchars($rowAbout['nama']) . "</h2>";
                    echo "<br>";
                    echo "<p>" . nl2br(htmlspecialchars($rowAbout['paragraf'])) . "</p>";
                }
            } else {
                echo "<p>No information available.</p>";
            }
            ?>
        </div>
        <div class="imgBx">
    <?php 
    if ($resultAbout->num_rows > 0) {
        // Menampilkan gambar untuk setiap baris data
        $resultAbout->data_seek(0); // Reset pointer hasil query ke awal
        while ($rowAbout = $resultAbout->fetch_assoc()) {
            if (!empty($rowAbout['foto'])) {
                echo "<img src='" . htmlspecialchars($rowAbout['foto']) . "' alt='Foto' '>";
            } else {
                echo "<p>No image available.</p>";
            }
        }
    } else {
        echo "<p>No image available.</p>";
    }
    ?>
    </div>
    </div>
    </section>
    <!-- about end -->
    
    <!-- chat start -->
    <a href="https://wa.me/62895371405852" target="_blank">
      <button class="btn-floating-whatsapp">
          <span class="iconwa"><ion-icon name="chatbubbles-outline"></ion-icon></span>
      </button>
    </a>
    <!-- chat end -->
    
    <!-- footer start -->
    <footer class="footer">
      <div class="containers">
        <div class="rows">
          <div class="footer-logo">
            <h4>MAHANTA</h4>
          </div>
          <div class="footer-col">
    <h4>Category</h4>
    <ul>
        <?php
        $sqlKategori = "SELECT * FROM kategori";
        $resultKategori = $koneksi->query($sqlKategori);

        if ($resultKategori && $resultKategori->num_rows > 0) {
            while ($rowKategori = $resultKategori->fetch_assoc()) {
                echo '<li><a href="art.php?category=' . urlencode($rowKategori['nama_kategori']) . '" target="_blank">'
                     . htmlspecialchars($rowKategori['nama_kategori']) . '</a></li>';
            }
        } else {
            echo '<li>No categories available</li>';
        }
        ?>
    </ul>
</div>
          <div class="footer-col">
            <h4>Follow</h4>
            <div class="social-links">
              <a href="https://www.youtube.com" target="_blank"><ion-icon name="logo-youtube"></ion-icon></a>
              <a href="https://www.instagram.com" target="_blank"><ion-icon name="logo-instagram"></ion-icon></a>
              <a href="https://x.com" target="_blank"><ion-icon name="logo-twitter"></ion-icon></a>
            </div>
          </div>
        </div>
      </div>
      <div class="bottom-bar">
        <p>&copy; 2024 MAHANTA. All rights reserved</p>
      </div>
   </footer>
   <!-- footer end -->

    <!-- Popup Container -->
    <div class="logout-popup" id="logout-popup">
        <div class="logout-popup-content">
            <button class="cclose-btnn" id="close-popup" aria-label="Close popup">&times;</button>
            <img src="asset/iso.png" alt="icon" class="popup-icon">
            <h3>Are you sure you want to leave <br> Mahanta?</h3>
            <p class="p">Don’t miss out on the latest art updates, <br> inspiration, and creative moments! <br> Stay connected and inspired!</p>
            <button class="popup-btn primary-btn" id="confirm-logout">Logout</button>
            <button class="popup-btn secondary-btn" id="cancel-logout">Not Now</button>
        </div>
    </div>
    <!-- pop up end -->

<!-- script -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script>
// parallax start
let pyramid = document.getElementById('love');
let text = document.getElementById('text');
let stone = document.getElementById('ombak');
let man = document.getElementById('ombik');
window.addEventListener('scroll',function(){
  let value = window.scrollY; 
  love.style.right = value * 0.25 +'px';
  text.style.left = value * 2 +'px';
  ombak.style.right = value * 1 +'px';
  ombik.style.left = value * 0.5  +'px';
});
// parallax end

//cursors js start
const coords = { x: 0, y: 0 };
const circles = document.querySelectorAll(".circle");
const colors = [
  "#ef2d7c",
  "#e30d9b",
  "#d300af",
  "#be00ba",
  "#a41dbd",
  "#8630b9",
  "#643cae",
  "#3e439d",
];
circles.forEach(function (circle, index) {
  circle.x = 0;
  circle.y = 0;
  circle.style.backgroundColor = colors[index % colors.length];
});
window.addEventListener("mousemove", function(e){
  coords.x = e.clientX;
  coords.y = e.clientY;
});
function animateCircles() { 
  let x = coords.x;
  let y = coords.y; 
  circles.forEach(function (circle, index) {
    circle.style.left = x - 12 + "px";
    circle.style.top = y - 12 + "px";
    circle.style.scale = (circles.length - index) / circles.length; 
    circle.x = x;
    circle.y = y;
    const nextCircle = circles[index + 1] || circles[0];
    x += (nextCircle.x - x) * 0.3;
    y += (nextCircle.y - y) * 0.3;
  });
  requestAnimationFrame(animateCircles);
}
animateCircles();
// cursors js end

//navbar start
let header = document.querySelector("header");
  let aboutSection = document.querySelector("#about");

  window.addEventListener("scroll", function() {
      let aboutSectionTop = aboutSection.offsetTop;

      if (window.scrollY >= aboutSectionTop - 50) { 
          header.classList.add("scrolled");
      } else {
          header.classList.remove("scrolled"); 
      }
  });
//navbar end

//logout popup start
const logoutButton = document.getElementById('logout-button'); // pastikan ID ini sesuai dengan HTML
const logoutPopup = document.getElementById('logout-popup');
const confirmLogout = document.getElementById('confirm-logout');
const cancelLogout = document.getElementById('cancel-logout');
const closePopup = document.getElementById('close-popup');
const showLogoutPopup = (event) => {
    event.preventDefault(); 
    logoutPopup.style.display = 'flex'; 
};
logoutButton.addEventListener('click', showLogoutPopup);
closePopup.addEventListener('click', function() {
    logoutPopup.style.display = 'none'; 
});
confirmLogout.addEventListener('click', function() {
    window.location.href = 'logout.php'; 
});
cancelLogout.addEventListener('click', function() {
    logoutPopup.style.display = 'none'; 
});
logoutPopup.addEventListener('click', function(event) {
    if (event.target === logoutPopup) {
        logoutPopup.style.display = 'none'; 
    }
});
window.addEventListener('load', function() {
    logoutPopup.style.display = 'none';
});
//logout popup end
</script>
<!-- script -->
</body>
</html>