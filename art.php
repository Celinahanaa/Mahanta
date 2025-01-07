<?php 
session_start(); 
require 'koneksi.php';

// Cek apakah user sudah login
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
} else {
    // Redirect jika user belum login
    header("Location: index.php");
    exit();
}

// Query untuk mengambil gambar, kategori, dan menghitung jumlah like
$sql = "
SELECT 
    g.id, g.gambar, g.author, g.judul, g.deskripsi, k.nama_kategori, 
    COUNT(DISTINCT l.id) AS likes_count
FROM gambar g
LEFT JOIN kategori k ON g.kategori = k.id
LEFT JOIN likes l ON g.id = l.gambar_id
GROUP BY g.id

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
    <title>Art</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="art.css">
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
    
<!-- kategori start -->
<div class="button-container">
    <button class="custom-button" data-category="All">All</button>
    <?php
    if ($resultKategori->num_rows > 0) {
        while ($rowKategori = $resultKategori->fetch_assoc()) {
            echo '<button class="custom-button" data-category="' . htmlspecialchars($rowKategori['nama_kategori']) . '">' . htmlspecialchars($rowKategori['nama_kategori']) . '</button>';
        }
    } else {
        echo '<p>No categories available</p>';
    }
    ?>
</div>
<!-- kategori end -->

  
    <!-- img gallery start -->
<div class="image-container" id="artimage">
    <div class="image-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<img src="uploads/' . $row['gambar'] . '" alt="' . htmlspecialchars($row['judul']) . '" 
                    data-category="' . htmlspecialchars($row['nama_kategori']) . '" 
                    data-name="' . htmlspecialchars($row['judul']) . '" 
                    data-author="' . htmlspecialchars($row['author']) . '" 
                    data-story="' . htmlspecialchars($row['deskripsi']) . '" 
                    data-likes="' . $row['likes_count'] . '">';
            }
        } else {
            echo '<p>No images found in the database.</p>';
        }
        ?>
    </div>

    <div class="popup" id="popup">
        <div class="contentBox">
            <div class="close"></div>
            <div class="imgBx">
                <img src="" id="popup-img-element" alt="">
            </div>
            <div class="content">
                <div>
                    <h3 id="image-name"></h3>
                    <h2 id="image-author"></h2>
                    <p id="image-story"></p>
                    <button id="likeButton" class="btn-secondary like-review">
                        <i class="fa fa-heart" aria-hidden="true"></i> Like
                    </button>                    
                </div>
            </div>
        </div>    
    </div>
</div>
<!-- img gallery end -->

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
      // cursors js start
      const coords = { x: 0, y: 0 };
      const circles = document.querySelectorAll(".circle");
      const colors = [
        "#ef2d7c", "#e30d9b", "#d300af",
        "#a41dbd", "#8630b9",
        "#643cae", "#3e439d",
      ];
      circles.forEach(function (circle, index) {
        circle.x = 0;
        circle.y = 0;
        circle.style.backgroundColor = colors[index % colors.length];
      });
      window.addEventListener("mousemove", function(e) {
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
      
// Filter images by category
const buttons = document.querySelectorAll('.custom-button');
const images = document.querySelectorAll('.image-grid img');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        const category = button.getAttribute('data-category');
        images.forEach(image => {
            image.style.display = (category === "All" || image.getAttribute('data-category') === category) ? "block" : "none";
        });
    });
});

// Popup functionality
const popup = document.getElementById('popup');
const popupImg = document.getElementById('popup-img-element');
const closeBtn = document.querySelector('.close');
const likeButton = document.getElementById('likeButton');
let liked = false;

// Function to show the popup when an image is clicked
images.forEach(image => {
    image.addEventListener('click', () => {
        const imgSrc = image.src;
        const imgName = image.getAttribute('data-name');
        const imgAuthor = image.getAttribute('data-author');
        const imgStory = image.getAttribute('data-story');

        // Populate popup content
        popupImg.src = imgSrc;
        document.getElementById('image-name').innerText = imgName || "Unknown Title";
        document.getElementById('image-author').innerText = imgAuthor || "Unknown Author";
        document.getElementById('image-story').innerText = imgStory || "No description available.";

        // Show the popup
        popup.style.display = 'flex';
        checkLikeStatus();
    });
});


// Function to close the popup
closeBtn.addEventListener('click', () => {
    popup.style.display = 'none';
});

// Close popup when clicking outside of it
popup.addEventListener('click', (event) => {
    if (event.target === popup) {
        popup.style.display = 'none';
    }
});


// Fungsi untuk memeriksa status Like dari database setelah login
function checkLikeStatus() {
    const imgSrc = popupImg.src; // Pastikan sumber gambar dari popup
    console.log('Checking like status for:', imgSrc); // Debugging
    
    if (!imgSrc) {
        alert('No image selected to like.');
        return;
    }


    // Kirim permintaan ke server untuk memeriksa status like di database
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'checklike.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');


    xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
        console.log('Raw response:', xhr.responseText); // Debug respons
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    liked = response.liked; // Set status like
                    updateLikeButton();
                } else {
                    alert('Failed to check like status. Please try again.');
                }
            } catch (error) {
                console.error('Error parsing response:', error);
            }
        } else {
            console.error('Request failed with status:', xhr.status);
        }
    }
};


    xhr.send(`imgSrc=${encodeURIComponent(imgSrc)}`);
}


// Fungsi untuk memperbarui status tombol Like berdasarkan status liked
function updateLikeButton() {
    if (liked) {
        likeButton.innerHTML = '<i class="fa fa-heart" aria-hidden="true"></i> You liked this';
        likeButton.querySelector('.fa-heart').classList.add('animate-like');
    } else {
        likeButton.innerHTML = '<i class="fa fa-heart" aria-hidden="true"></i> Like';
        likeButton.querySelector('.fa-heart').classList.remove('animate-like');
    }
}

likeButton.addEventListener('click', () => {
    const imgSrc = popupImg.src; // Pastikan sumber gambar dari popup
    if (!imgSrc) {
        alert('No image selected to like.');
        return;
    }

    // Toggle status like
    liked = !liked;

    // Perbarui tombol like
    updateLikeButton();

    // Kirim permintaan ke server untuk memperbarui status like
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'like.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                // Debugging: cek apakah respons valid JSON
                console.log('Raw response:', xhr.responseText);
                
                // Mengecek apakah respons mengandung JSON
                if (xhr.responseText.startsWith("{") || xhr.responseText.startsWith("[")) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Update like count in the UI
                        document.getElementById('like-count').innerText = response.like_count;
                    } else {
                        alert('Failed to update like. Please try again.');
                    }
                } else {
                    console.error("Unexpected response format: ", xhr.responseText);
                }
            } catch (error) {
                console.error('Error parsing response:', error);
            }
        }
    };

    // Kirim permintaan ke server untuk memperbarui status like
    xhr.send(`imgSrc=${encodeURIComponent(imgSrc)}&liked=${liked}`);
});

      //navbar start
      let header = document.querySelector("header");
      let aboutSection = document.querySelector("#artimage");
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
