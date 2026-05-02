<?php
session_start();
require_once 'includes/db.inc.php';

// Check if admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $user_id = $_POST['user_id'];
    
    if ($user_type === 'customer') {
        $query = "UPDATE Customers SET is_verified = 1 WHERE customers_id = :user_id";
    } else {
        $query = "UPDATE Caregivers SET is_verified = 1 WHERE caregivers_id = :user_id";
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['admin_success'] = "User verified successfully!";
    } else {
        $_SESSION['admin_error'] = "Failed to verify user.";
    }
    
    header("Location: admin_dashboard.php");
    die();
} else {
    header("Location: admin_dashboard.php");
    die();
}
?>