<?php 
 session_start();
require 'koneksi.php';

$login_message = "";
$username = "";
$password = "";
$show_popup = false;

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $password = mysqli_real_escape_string($koneksi, $_POST["password"]);

    // Query untuk mengambil data user
    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Verifikasi password
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = "true";
            $_SESSION["id"] = $row["id"];
            $_SESSION["role"] = $row["role"]; // Simpan role ke session

            // Cek role dan arahkan ke halaman sesuai
            if ($row["role"] == 1) {
                header("Location: admin.php"); // Admin
                exit();
            } elseif ($row["role"] == 0) {
                header("Location: dashboard.php"); // User
                exit();
            }
        } else {
            $login_message = "Incorrect password";
            $show_popup = true; 
            $password = "";
        }
    } else {
        $login_message = "User not registered";
        $show_popup = true; 
        $username = "";
        $password = "";
    }
}
?>                                                                                                                                                                                                                     


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="logreg.css">
    <style>
        .login-message {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            width: 200px;
            height: 40px;
            background: #F584B2;
            border: 1px solid #F584B2; 
            border-radius: 2px;
            font-size: 0.8em;
            color: #fff;
            font-weight: 300; 
            letter-spacing: 1px;
            line-height: 40px; 
            text-decoration: none; 
            animation: fadeInOut 8s forwards; /* Animation */
            z-index: 9999;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <section>
        <!-- background start -->
        <div class="container">
            <div id="scene">
                <div class="layer" data-depth-x="-0.5"><img src="asset/box1.png"></div>
                <div class="layer" data-depth-x="-0.10" data-depth-y="-0.35"><img src="asset/love.png"></div>
                <div class="layer" data-depth-x="-0.15"><img src="asset/circle.png"></div>
                <div class="layer" data-depth-x="-0.25"><img src="asset/box2.png"></div>
                <div class="layer" data-depth-x="-0.25"><img src="asset/water.png"></div>
                <div class="layer" data-depth-x="-0.25"><img src="asset/holo.png"></div>
            </div>
        </div>
        <!-- background end -->

        <!-- box login start -->
        <div class="login">
            <div class="form-box login">
                <h2>Login</h2>
                <form action="index.php" method="POST" autocomplete="off">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
                        <input name="username" type="text" value="<?php echo htmlspecialchars($username); ?>" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                        <input name="password" type="password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit" class="btn">Login</button>
                    <div class="login-register">
                        <p>Don't have an account? <a href="register.php" class="register-link">Register</a></p>
                    </div>
                </form>
            </div>
            <?php if ($show_popup): ?>
            <div class="login-message" id="loginMessage">
                <p><?= $login_message; ?></p>
            </div>
            <?php endif; ?>
        </div>
        <!-- box login end -->
    </section>

    <!-- script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js" integrity="sha512-/6TZODGjYL7M8qb7P6SflJB/nTGE79ed1RfJk3dfm/Ib6JwCT4+tOfrrseEHhxkIhwG8jCl+io6eaiWLS/UX1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let scene = document.getElementById('scene');
        let parallax = new Parallax(scene);

        // Remove login message after 8 seconds
        setTimeout(() => {
            const loginMessage = document.getElementById('loginMessage');
            if (loginMessage) {
                loginMessage.classList.add('fade-out');
            }
        }, 900);
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
