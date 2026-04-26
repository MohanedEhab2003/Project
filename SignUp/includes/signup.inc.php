<?php
// Start session and include required files
require_once 'config_session.inc.php';
require_once 'db.inc.php';
require_once 'signup_model.inc.php';
require_once 'signup_controller.inc.php';

/**
 * Helper function to upload files securely
 */
function upload_file($file, $subdirectory) {
    $target_dir = "../uploads/" . $subdirectory . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return null;
    }
    
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "uploads/" . $subdirectory . "/" . $new_filename;
    }
    
    return null;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ========== ULTIMATE FIX: Get role from user_role_final first ==========
    // This is set by JavaScript right before form submission
    $role = isset($_POST["user_role_final"]) ? $_POST["user_role_final"] : null;
    
    // Fallback to regular user_role if final is not set
    if ($role === null || $role === '') {
        $role = isset($_POST["user_role"]) ? $_POST["user_role"] : 'caregiver';
    }
    
    // Debug log (remove after testing)
    error_log("=== ROLE DETECTION ===");
    error_log("user_role_final: " . ($_POST["user_role_final"] ?? "NOT SET"));
    error_log("user_role: " . ($_POST["user_role"] ?? "NOT SET"));
    error_log("Final role used: " . $role);
    
    // ========== SAFETY CHECK: Forcefully remove experience/price for customers ==========
    if ($role === 'customer') {
        // Customers should NEVER have experience or price - completely ignore them
        $experience = null;
        $price = null;
        // Remove from $_POST so validation doesn't see them
        unset($_POST['experience']);
        unset($_POST['price']);
    } else {
        // Only get experience and price for caregivers
        $experience = isset($_POST["experience"]) ? trim($_POST["experience"]) : null;
        $price = isset($_POST["price"]) ? trim($_POST["price"]) : null;
    }
    
    // Get all other form data (common for both roles)
    $username = trim($_POST["Username"]);
    $nationalID = trim($_POST["nationalID"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $confirmPass = $_POST["confirmPass"];
    $password = $_POST["password"];
    $location = trim($_POST["location"]);
    $bio = isset($_POST["bio"]) ? trim($_POST["bio"]) : '';
    
    // Handle file uploads
    $profile_pic_path = null;
    $id_photo_path = null;
    
    // Upload profile picture if provided (optional)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_pic_path = upload_file($_FILES['profile_picture'], 'profile_pics');
    }
    
    // Upload national ID photo (REQUIRED for both roles)
    $id_photo_uploaded = false;
    if (isset($_FILES['id_photo']) && $_FILES['id_photo']['error'] == 0) {
        if(is_valid_id_photo($_FILES['id_photo'])){
            $id_photo_path = upload_file($_FILES['id_photo'], 'id_photos');
            if($id_photo_path){
                $id_photo_uploaded = true;
            }
        }
    }
    
    try {
        // ERROR HANDLING
        $errors = [];

        // Check for empty inputs (common fields)
        if(is_input_empty_common($username, $nationalID, $phone, $email, $confirmPass, $password, $location)){
            $errors["empty_input"] = "Please fill in all fields.";
        }

        // Validate email
        if(is_email_invalid($email)){
            $errors["invalid_email"] = "Please enter a valid email address.";
        }
        
        // Validate national ID format (exactly 14 digits)
        if(!preg_match('/^\d{14}$/', $nationalID)){
            $errors["invalid_national_id"] = "National ID must be exactly 14 digits.";
        }
        
        // Check if passwords match
        if($password !== $confirmPass){
            $errors["password_mismatch"] = "Passwords do not match.";
        }
        
        // Check if National ID photo was uploaded (REQUIRED for both roles)
        if(!$id_photo_uploaded || $id_photo_path === null){
            $errors["id_photo_required"] = "Please upload a clear photo of your National ID. This is required for verification purposes.";
        }

        // Role-based validation
        if($role === 'caregiver') {
            // Check Caregiver table
            if(is_username_taken_CG($pdo, $username)){
                $errors["username_taken"] = "Username is already taken by another caregiver.";
            }
            if(is_email_registered_CG($pdo, $email)){
                $errors["email_registered"] = "Email is already registered as a caregiver.";
            }
            if(is_national_id_taken_CG($pdo, $nationalID)){
                $errors["national_id_taken"] = "This National ID is already registered as a caregiver.";
            }
            if(is_username_taken_Customer($pdo, $username)){
                $errors["username_taken_customer"] = "Username is already taken by a care seeker.";
            }
            if(is_email_registered_Customer($pdo, $email)){
                $errors["email_registered_customer"] = "Email is already registered as a care seeker.";
            }
            if(is_national_id_taken_Customer($pdo, $nationalID)){
                $errors["national_id_taken_customer"] = "This National ID is already registered as a care seeker.";
            }
            
            // ONLY validate provider fields for caregivers
            if($experience === null || $price === null) {
                $errors["provider_fields_missing"] = "Experience and price per hour are required for care providers.";
            } else {
                if(empty($experience) || empty($price)){
                    $errors["provider_fields_empty"] = "Please fill in experience and price per hour.";
                }
                if(!empty($experience) && (!is_numeric($experience) || $experience < 0)){
                    $errors["invalid_experience"] = "Please enter a valid number for experience (0 or more).";
                }
                if(!empty($price) && (!is_numeric($price) || $price < 0)){
                    $errors["invalid_price"] = "Please enter a valid price per hour.";
                }
            }
        } 
        elseif($role === 'customer') {
            // Check Customer table - NO experience/price validation at all
            if(is_username_taken_Customer($pdo, $username)){
                $errors["username_taken"] = "Username is already taken by another care seeker.";
            }
            if(is_email_registered_Customer($pdo, $email)){
                $errors["email_registered"] = "Email is already registered as a care seeker.";
            }
            if(is_national_id_taken_Customer($pdo, $nationalID)){
                $errors["national_id_taken"] = "This National ID is already registered as a care seeker.";
            }
            if(is_username_taken_CG($pdo, $username)){
                $errors["username_taken_caregiver"] = "Username is already taken by a care provider.";
            }
            if(is_email_registered_CG($pdo, $email)){
                $errors["email_registered_caregiver"] = "Email is already registered as a care provider.";
            }
            if(is_national_id_taken_CG($pdo, $nationalID)){
                $errors["national_id_taken_caregiver"] = "This National ID is already registered as a care provider.";
            }
        }

        // If errors exist, store in session and redirect
        if($errors){
            $_SESSION["errors_Signup"] = $errors;
            header("Location: ../index.php");
            die();
        }
        
        // Handle location
        $location_id = handle_location($pdo, $location);
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user based on role
        if($role === 'caregiver') {
            $caregiver_id = create_caregiver(
                $pdo, $username, $email, $hashed_password, $nationalID, 
                $phone, $experience, $price, $bio, $profile_pic_path, $id_photo_path, $location_id
            );
            
            create_user_entry($pdo, $email, $hashed_password, 'caregiver', null, $caregiver_id);
            
            $_SESSION['user_id'] = $caregiver_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_type'] = 'caregiver';
            $_SESSION['username'] = $username;
            $_SESSION['user_bio'] = $bio;
            
            header("Location: ../caregiver_dashboard.php");
            die();
        } 
        elseif($role === 'customer') {
            $customer_id = create_customer(
                $pdo, $username, $email, $hashed_password, $nationalID, 
                $phone, $bio, $profile_pic_path, $id_photo_path, $location_id
            );
            
            create_user_entry($pdo, $email, $hashed_password, 'customer', $customer_id, null);
            
            $_SESSION['user_id'] = $customer_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_type'] = 'customer';
            $_SESSION['username'] = $username;
            $_SESSION['user_bio'] = $bio;
            
            header("Location: ../customer_dashboard.php");
            die();
        }
        
    }
    catch(PDOException $e){
        $_SESSION["errors_Signup"] = ["database_error" => "Signup failed: " . $e->getMessage()];
        header("Location: ../index.php");
        die();
    }
}
else{
    header("Location: ../index.php");
    die();
}
?>