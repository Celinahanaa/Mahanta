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

$sql = "
SELECT 
    g.id, g.gambar, g.author, g.judul, g.deskripsi, k.nama_kategori, 
    COUNT(DISTINCT l.id) AS likes_count
FROM gambar g
LEFT JOIN kategori k ON g.kategori = k.id
LEFT JOIN likes l ON g.id = l.gambar_id
GROUP BY g.id
ORDER BY likes_count DESC
LIMIT 3
";


// Menjalankan query gambar dan kategori
$result = $koneksi->query($sql);

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
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="dashboard.css">
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
            padding: 20px 50px 20px 50px;
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
        #logout-popup .cclose-btnn {
            position: absolute;
            top: 20px;
            right: 30px;
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
        }
        #logout-popup .popup-icon {
            width: 120px; 
            margin-bottom: 1px; 
        }
        #logout-popup h3 {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 10px;
        }
        #logout-popup p {
            font-size: 1.2rem;
            font-weight: 300;
            color: #555;
            margin-bottom: 20px;
        }
        #logout-popup .logout-popup button {
            margin: 5px; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        #logout-popup .popup-btn {
            padding: 10px;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            margin-top: 10px;
            width: 80%; 
            text-align: center; 
            display: inline-block; 
        }
        #logout-popup .primary-btn {
            background: linear-gradient(45deg, #313684, #F584B2);
            border: none;
            color: white;
            font-weight: 500;
            text-decoration: none; 
        }
        #logout-popup .secondary-btn {
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

    <!-- 3d image start -->
    <div class="banner">
        <img class="background1" src="asset/background.png" alt="">
        <div class="slider" style="--quantity: 10">
            <div class="item" style="--position: 1"><img src="img/mozaik1.jpg" alt=""></div>
            <div class="item" style="--position: 2"><img src="img/mozaik2.jpg" alt=""></div>
            <div class="item" style="--position: 3"><img src="img/nature1.jpeg" alt=""></div>
            <div class="item" style="--position: 4"><img src="img/nature2.jpg" alt=""></div>
            <div class="item" style="--position: 5"><img src="img/retro1.avif" alt=""></div>
            <div class="item" style="--position: 6"><img src="img/retro2.avif" alt=""></div>
            <div class="item" style="--position: 7"><img src="img/retro3.jpeg" alt=""></div>
            <div class="item" style="--position: 8"><img src="img/retro4.jpg" alt=""></div>
            <div class="item" style="--position: 9"><img src="img/abstrak1.jpeg" alt=""></div>
            <div class="item" style="--position: 10"><img src="img/abstrak2.jpeg" alt=""></div>
        </div>
        <div class="content">
            <h1>
                WELCOME
            </h1>
        </div>
    </div>
    <!-- 3d image end -->

    <!-- quotes start -->
    <section id="about">
      <div class="container">
          <div class="textBx">
              <h2 class="title">Oscar Wilde</h2>
              <br>
              <p>
                “If a work of art is rich, vital, and complete, those with an artistic instinct will see its beauty, and those interested more in ethics than in aesthetics, will see its moral lesson.” 
              </p>
          </div>
      </div>
    </section>
    <!-- quotes end -->

    <!-- slider img start -->
    <section id="tranding">
      <div class="container">
        <h3 class="text-center section-subheading">- Favorite -</h3>
        <h1 class="text-center section-heading">Art</h1>
      </div>
      <div class="container">
        <div class="swiper tranding-slider">
        <div class="swiper-wrapper">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="swiper-slide tranding-slide">
            <div class="tranding-slide-img">
                <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
            </div>
            <div class="tranding-slide-content">
                <div class="tranding-slide-content-bottom">
                    <h2 class="paint-name">
                        <?= htmlspecialchars($row['judul']); ?> <br>
                    </h2>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<div class="tranding-slider-control">
            <div class="swiper-button-prev slider-arrow">
              <ion-icon name="arrow-back-outline"></ion-icon>
            </div>
            <div class="swiper-button-next slider-arrow">
              <ion-icon name="arrow-forward-outline"></ion-icon>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </section>
    <!-- slider img end -->

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
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
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

// slider js start
var TrandingSlider = new Swiper('.tranding-slider', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    slidesPerView: 'auto',
    coverflowEffect: {
      rotate: 0,
      stretch: 0,
      depth: 100,
      modifier: 2.5,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    }
  });
// slider js end

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

// Mengatur event listener saat halaman dimuat
window.addEventListener('load', function() {
    logoutPopup.style.display = 'none'; // Pastikan pop-up tidak terlihat saat halaman dimuat
});
//logout popup end
</script>
<!-- script -->
</body>
</html>