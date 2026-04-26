<?php


// Start session and include required files
require_once 'config_session.inc.php';
require_once 'db.inc.php';
require_once 'login_model.inc.php';
require_once 'login_controller.inc.php';

// Check if form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]) ? true : false;
    
    // Initialize errors array
    $errors = [];
    
    // ========== VALIDATION SECTION ==========
    
    // 1. Check if email is empty
    if (is_email_empty($email)) {
        $errors["empty_email"] = "Please enter your email address.";
    }
    
    // 2. Check if password is empty
    if (is_password_empty($password)) {
        $errors["empty_password"] = "Please enter your password.";
    }
    
    // 3. Validate email format (if not empty)
    if (!is_email_empty($email) && is_email_invalid_login($email)) {
        $errors["invalid_email"] = "Please enter a valid email address.";
    }
    
    // ========== DATABASE CHECK ==========
    
    // Only check database if no validation errors so far
    if (empty($errors)) {
        
        // Get user from database by email
        $user = get_user_by_email($pdo, $email);
        
        // Check if user exists
        if (is_user_not_found($user)) {
            $errors["user_not_found"] = "No account found with this email address. Please sign up first.";
        } else {
            // User exists, verify password
            if (is_password_wrong($password, $user['password_hash'])) {
                $errors["wrong_password"] = "Incorrect password. Please try again.";
            }
        }
    }
    
    // ========== IF ERRORS EXIST, STORE IN SESSION AND REDIRECT ==========
    if (!empty($errors)) {
        $_SESSION["errors_login"] = $errors;
        $_SESSION["login_email"] = $email; // Store email to repopulate form
        header("Location: ../login.php");
        die();
    }
    
    // ========== LOGIN SUCCESSFUL - SET SESSION VARIABLES ==========
    
    // Get full user details based on user type
    if ($user['user_type'] === 'customer') {
        $customer = get_customer_by_id($pdo, $user['customer_id']);
        
        // Set session variables for customer
        $_SESSION['user_id'] = $customer['customers_id'];
        $_SESSION['username'] = $customer['username'];
        $_SESSION['user_email'] = $customer['email'];
        $_SESSION['user_type'] = 'customer';
        $_SESSION['user_phone'] = $customer['phone'];
        $_SESSION['user_bio'] = $customer['bio'];
        $_SESSION['is_verified'] = $customer['is_verified'];
        $_SESSION['profile_picture'] = $customer['profile_picture'];
        
        // Handle "Remember Me" functionality
        if ($remember_me) {
            // Set cookie to remember email for 30 days
            setcookie("remember_email", $email, time() + (86400 * 30), "/");
        } else {
            // Clear remember me cookie if exists
            if (isset($_COOKIE['remember_email'])) {
                setcookie("remember_email", "", time() - 3600, "/");
            }
        }
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        // Redirect to customer dashboard (go back to SignUp folder)
        header("Location: ../../SignUp/customer_dashboard.php");
        die();
        
    } elseif ($user['user_type'] === 'caregiver') {
        $caregiver = get_caregiver_by_id($pdo, $user['caregiver_id']);
        
        // Set session variables for caregiver
        $_SESSION['user_id'] = $caregiver['caregivers_id'];
        $_SESSION['username'] = $caregiver['username'];
        $_SESSION['user_email'] = $caregiver['email'];
        $_SESSION['user_type'] = 'caregiver';
        $_SESSION['user_phone'] = $caregiver['phone'];
        $_SESSION['user_bio'] = $caregiver['bio'];
        $_SESSION['is_verified'] = $caregiver['is_verified'];
        $_SESSION['profile_picture'] = $caregiver['profile_picture'];
        $_SESSION['experience_years'] = $caregiver['experience_years'];
        $_SESSION['price_per_hour'] = $caregiver['price_per_hour'];
        $_SESSION['availability_status'] = $caregiver['availability_status'];
        
        // Handle "Remember Me" functionality
        if ($remember_me) {
            // Set cookie to remember email for 30 days
            setcookie("remember_email", $email, time() + (86400 * 30), "/");
        } else {
            // Clear remember me cookie if exists
            if (isset($_COOKIE['remember_email'])) {
                setcookie("remember_email", "", time() - 3600, "/");
            }
        }
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        // Redirect to caregiver dashboard (go back to SignUp folder)
        header("Location: ../../SignUp/caregiver_dashboard.php");
        die();
    }
    
} else {
    // If not POST request, redirect to login page
    header("Location: ../login.php");
    die();
}
?>