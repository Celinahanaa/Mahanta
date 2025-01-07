<?php
session_start(); 
require 'koneksi.php'; // Pastikan file koneksi benar

if (!empty($_SESSION["id"]) && isset($_SESSION["role"]) && $_SESSION["role"] == 1) {
  $id = $_SESSION["id"];
  $result = mysqli_query($koneksi, "SELECT * FROM user WHERE id = '$id' AND role = 1");
  $row = mysqli_fetch_assoc($result);
  if (!$row) {
    echo "User with admin role not found"; // Debugging
    header("Location: index.php");
    exit();
  }
} else {
  echo "Session invalid or not admin"; // Debugging
  header("Location: index.php");
  exit();
}

// Query untuk menghitung total user
$sql = "SELECT COUNT(*) AS total_user FROM user"; // Ganti 'users' dengan nama tabel Anda
$result = $koneksi->query($sql);

// Periksa hasil query
if ($result) {
    $row = $result->fetch_assoc();
    $total_user = $row['total_user']; // Ambil nilai total_user
} else {
    die("Query gagal: " . $koneksi->error);
}

// Query untuk mendapatkan total user minggu lalu
$sql_last_week = "SELECT COUNT(*) AS total_user_last_week FROM user WHERE created_at >= NOW() - INTERVAL 1 WEEK";
$result_last_week = $koneksi->query($sql_last_week);

if ($result_last_week) {
    $row_last_week = $result_last_week->fetch_assoc();
    $total_user_last_week = $row_last_week['total_user_last_week'];
} else {
    die("Query gagal: " . $koneksi->error);
}

// Menghitung persentase perubahan
$percentage_change = 0;
if ($total_user_last_week > 0) {
    $percentage_change = (($total_user - $total_user_last_week) / $total_user_last_week) * 100;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #f8f9fa;
            padding: 1rem;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #495057;
            display: block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
        }
        .sidebar ul li a:hover {
            background-color: #e9ecef;
        }
        header {
            margin-left: 250px;
            padding: 1rem;
            background-color: #fff;
        }
        .main {
            margin-left: 250px;
            padding: 1rem;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
  <nav>
    <a href="" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <span class="fs-4 ps-3">Mahanta</span>
    </a>
    <hr class="hr">
    <ul class="nav nav-pills flex-column mb-auto">
      <!-- Home Section -->
      <li class="nav-item">
        <a href="admin.php" class="nav-link d-flex align-items-center" aria-expanded="false" aria-controls="home-collapse">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house me-3" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/></svg>
        Home
        </a>
      </li>

      <li class="nav-item">
        <a href="adminabout.php" class="nav-link d-flex align-items-center" aria-expanded="false" aria-controls="home-collapse">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle me-3" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/></svg>        About
        </a>
      </li>

      <!-- Category Section -->
      <li class="nav-item">
        <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#category-collapse" aria-expanded="false" aria-controls="category-collapse">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid me-3" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/></svg>        Category
        </a>
        <div class="collapse" id="category-collapse">
          <ul class="list-unstyled ps-5">
            <li><a href="upcategory.php" class="link-body-emphasis text-decoration-none rounded">Updates</a></li>
            <li><a href="repcategory.php" class="link-body-emphasis text-decoration-none rounded">Reports</a></li>
          </ul>
        </div>
      </li>

      <!-- Art Section -->
      <li class="nav-item">
        <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#art-collapse" aria-expanded="false" aria-controls="art-collapse">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-brush me-3" viewBox="0 0 16 16"><path d="M15.825.12a.5.5 0 0 1 .132.584c-1.53 3.43-4.743 8.17-7.095 10.64a6.1 6.1 0 0 1-2.373 1.534c-.018.227-.06.538-.16.868-.201.659-.667 1.479-1.708 1.74a8.1 8.1 0 0 1-3.078.132 4 4 0 0 1-.562-.135 1.4 1.4 0 0 1-.466-.247.7.7 0 0 1-.204-.288.62.62 0 0 1 .004-.443c.095-.245.316-.38.461-.452.394-.197.625-.453.867-.826.095-.144.184-.297.287-.472l.117-.198c.151-.255.326-.54.546-.848.528-.739 1.201-.925 1.746-.896q.19.012.348.048c.062-.172.142-.38.238-.608.261-.619.658-1.419 1.187-2.069 2.176-2.67 6.18-6.206 9.117-8.104a.5.5 0 0 1 .596.04M4.705 11.912a1.2 1.2 0 0 0-.419-.1c-.246-.013-.573.05-.879.479-.197.275-.355.532-.5.777l-.105.177c-.106.181-.213.362-.32.528a3.4 3.4 0 0 1-.76.861c.69.112 1.736.111 2.657-.12.559-.139.843-.569.993-1.06a3 3 0 0 0 .126-.75zm1.44.026c.12-.04.277-.1.458-.183a5.1 5.1 0 0 0 1.535-1.1c1.9-1.996 4.412-5.57 6.052-8.631-2.59 1.927-5.566 4.66-7.302 6.792-.442.543-.795 1.243-1.042 1.826-.121.288-.214.54-.275.72v.001l.575.575zm-4.973 3.04.007-.005zm3.582-3.043.002.001h-.002z"/></svg>        Art
        </a>
        <div class="collapse" id="art-collapse">
          <ul class="list-unstyled ps-5">
            <li><a href="upart.php" class="link-body-emphasis text-decoration-none rounded">Updates</a></li>
            <li><a href="repart.php" class="link-body-emphasis text-decoration-none rounded">Reports</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </nav>
</aside>





<!-- Header -->
<header class="d-flex justify-content-end align-items-center">
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="img2/cel.jpg" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>Celina Hana</strong>
      </a>
      <ul class="dropdown-menu text-small shadow">
        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
      </ul>
    </div>
</header>

<!-- Main Content -->
<div class="main">
<div class="row g-4">
    <!-- Welcome Back Card -->
    <div class="col-12 col-sm-6 col-xxl-3 d-flex">
        <div class="card border-0 shadow-sm flex-fill bg-gradient-primary text-white">
            <div class="card-body p-4 d-flex align-items-center">
                <div>
                    <h5 class="fw-bold text-muted mb-2">Welcome Back, Celina!</h5>
                    <p class="text-muted mb-0">Mahanta Dashboard</p>
                </div>
                <div class="ms-auto">
                    <img src="img2/cel.jpg" alt="Customer Support" class="img-fluid" style="max-width: 80px;">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Earnings Card -->
    <div class="col-12 col-sm-6 col-xxl-3 d-flex">
        <div class="card border-0 shadow-sm flex-fill">
            <div class="card-body py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="fw-bold text-success mb-2"><?= htmlspecialchars($total_user) ?></h3>
                        <p class="text-muted mb-2">Total User</p>
                        <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2"><?= number_format($percentage_change, 1) ?>%</span>
                        <small class="text-muted">Since last week</small>
                        </div>
                    </div>
                    <div class="ms-5">
                      <div class="icon-container bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                      <i class="bi bi-people fs-5 text-success"></i>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
