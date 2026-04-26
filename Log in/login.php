<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/login_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Age Care - Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <!-- starting of header -->
        <header class="header">
            <div class="logo">💜 All Age Care</div>
            <div class="nav-wrapper">
                <nav>
                    <a href="../Home/index.html">Home</a>
                    <a href="../About Us/index.html">About us</a>
                    <a href="../Contact Us/contactus.html">Contact Us</a>
                    <a href="../SignUp/index.php">Sign Up</a>
                </nav>
            </div>
            <div class="header-spacer"></div>
        </header>
        <!-- the end of header -->
        <hr>
        <div class="second-container">
            <!-- LEFT LOGIN -->
            <div class="login-card">
                <div class="logo">All Age Care</div>

                <h2>Welcome back</h2>
                <p>Sign in to manage bookings and care services</p>
                
                <!-- Login Form -->
                <form id="loginForm" action="includes/login.inc.php" method="POST">
                    <!-- email input -->
                    <label>Email</label>
                    <input type="email" name="email" id="email" placeholder="james.carter@example.com" value="<?php echo get_remembered_email(); ?>">
                    <div class="error" id="emailError">Please enter a valid email address</div>
                    
                    <!-- password input -->
                    <label>Password</label>
                    <div class="password-box" id="passwordd">
                        <input type="password" name="password" id="password" placeholder="••••••••">
                        <span class="show" id="showPassword">Show</span>
                    </div>
                    <div class="error" id="passwordError">Password must be at least 8 characters.</div>

                    <div class="checkbox">
                        <p>Remember me</p>
                        <input type="checkbox" name="remember_me" id="remember" class="special">
                    </div>

                    <button type="submit" id="btn">Log In</button>
                </form>

                <div class="signup">
                    Need an account? <a href="../SignUp/index.php">Sign Up</a>
                </div>
                
                <!-- Display login errors here -->
                <?php check_login_errors(); ?>
            </div>

            <!-- RIGHT INFO -->
            <div class="info-card">
                <h3>Why sign in?</h3>

                <div class="info-item">✔ Manage bookings</div>
                <div class="info-item">✔ Message Caregivers</div>
                <div class="info-item">✔ View care history</div>

                <h3>Trusted & secure</h3>
                <div class="pictures">
                    <img class="badge" src="images/first.jpg" alt="badge">
                    <img class="badge" src="images/second.jpg" alt="badge">
                    <img class="badge" src="images/third.jpg" alt="badge">
                    <img class="badge" src="images/forth.jpg" alt="badge">
                </div>
            </div>
        </div>
    </div>
    <script src="login.js"></script>
</body>

</html>