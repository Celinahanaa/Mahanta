<?php
require 'koneksi.php';
$register_message = "";
$email = "";
$username = "";
$password = "";
$confirm = "";
$show_popup = false;

if (isset($_POST["submit"])) {
    $email = mysqli_real_escape_string($koneksi, $_POST["email"]);
    $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $password = mysqli_real_escape_string($koneksi, $_POST["password"]);
    $confirm = mysqli_real_escape_string($koneksi, $_POST["confirm"]);

    $duplicate = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        $register_message = "Username or email already exists";
        $email = "";
        $username = "";
        $password = "";
        $confirm = "";
    } else {
        if ($password == $confirm) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO user (email, username, password) VALUES ('$email', '$username', '$hashed_password')";
            if (mysqli_query($koneksi, $query)) {
                $register_message = "Registration successful! <br> Click OK to continue to the login page.";
                $show_popup = true;                
                $email = "";
                $username = "";
                $password = "";
                $confirm = "";
            } else {
                $register_message = "Registration failed. Please try again";
            }
        } else {
            $register_message = "Passwords do not match";
            $password = "";
            $confirm = "";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="logreg.css">
    <style>
        /* css alert */
        .register-message {
            display: flex; 
            justify-content: center; 
            align-items: center;
            margin: 20px auto;
            width: 300px;
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
            z-index: 9999;
            animation: fadeInOut 8s forwards; /* Animation */
            z-index: 9999;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Popup styling */
        .popup-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .popup {
            position: relative;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            background: none;
            border: none;
            font-size: 1.23rem;
            cursor: pointer;
        }
        .popup-icon {
            width: 119px; 
            margin-bottom: 15px; 
        }
        h3 {
            font-size: 0.92rem;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .pp {
            font-size: 0.75rem;
            font-weight: 300;
            color: #555;
            margin-bottom: 10px;
        }
        .popup-btn {
            padding: 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            cursor: pointer;
            margin-top: 10px;
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
        .popup-container.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <section>
        <!-- Background Start -->
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
        <!-- Background End -->

        <!-- Register Start -->
        <div class="register">
            <div class="form-box register">
                <h2>Register</h2>
                <form action="register.php" method="post" autocomplete="off">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                        <input name="email" id="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
                        <input name="username" id="username" type="text" value="<?php echo htmlspecialchars($username); ?>" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                        <input name="password" id="password" type="password" required>
                        <label>Password</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                        <input name="confirm" id="confirm" type="password" required>
                        <label>Confirm Password</label>
                    </div>
                    <button type="submit" name="submit" class="btn">Register</button>
                    <div class="login-register">
                        <p>Have an account? <a href="index.php" class="register-link">Login</a></p>
                    </div>
                </form>
            </div>
            <?php if (!$show_popup && $register_message): ?>
                <p class="register-message" id="registermessage"><?= $register_message ?></p>
            <?php endif; ?>
        </div>
        <!-- Register End -->

        <!-- Popup for registration message -->
        <?php if ($show_popup): ?>
            <div class="popup-container show" id="popupContainer">
                <div class="popup">
                    <button class="close-btn" id="closeBtn">&times;</button>
                    <img src="asset/mona.png" alt="Icon" class="popup-icon">
                    <h3>Registration Complete! 🎉</h3>
                    <p class="pp">Click OK to proceed to the login page and <br> let's start exploring!</p>
                    <button class="popup-btn primary-btn" id="logoutBtn">OK</button>
                    <button class="popup-btn secondary-btn" id="notNowBtn">Not now</button>
                </div>
            </div>
            <div class="overlay" style="display: block;"></div>
        <?php endif; ?>
    </section>

    <!-- Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js" integrity="sha512-/6TZODGjYL7M8qb7P6SflJB/nTGE79ed1RfJk3dfm/Ib6JwCT4+tOfrrseEHhxkIhwG8jCl+io6eaiWLS/UX1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let scene = document.getElementById('scene');
        let parallax = new Parallax(scene);
    </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.esm.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popupContainer = document.getElementById('popupContainer');
            const closeBtn = document.getElementById('closeBtn');
            const logoutBtn = document.getElementById('logoutBtn');
            const notNowBtn = document.getElementById('notNowBtn');

            // Close popup
            closeBtn.addEventListener('click', function() {
                popupContainer.classList.remove('show');
            });

            // Logout button action
            logoutBtn.addEventListener('click', function() {
                window.location.href = 'index.php'; // Adjust to your login URL
            });

            // Not Now button action
            notNowBtn.addEventListener('click', function() {
                popupContainer.classList.remove('show');
            });

            // Close popup when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === popupContainer) {
                    popupContainer.classList.remove('show');
                }
            });
        });

        setTimeout(() => {
            const registermessage = document.getElementById('registermessage');
            if (registermessage) {
                registermessage.classList.add('fade-out');
            }
        }, 800);
        
    </script>
</body>
</html>
