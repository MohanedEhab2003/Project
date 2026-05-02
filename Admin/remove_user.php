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
    $username = $_POST['username'];
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        if ($user_type === 'customer') {
            // First, get the user from users table to delete the association
            $query = "SELECT id FROM users WHERE customer_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Delete from users table
                $query = "DELETE FROM users WHERE id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();
            }
            
            // Delete from Customers table (this will cascade if set up)
            $query = "DELETE FROM customers WHERE customers_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            $_SESSION['admin_success'] = "Customer '$username' has been removed successfully.";
            
        } elseif ($user_type === 'caregiver') {
            // First, get the user from users table to delete the association
            $query = "SELECT id FROM users WHERE caregiver_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Delete from users table
                $query = "DELETE FROM users WHERE id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();
            }
            
            // Delete from Caregivers table
            $query = "DELETE FROM caregivers WHERE caregivers_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            $_SESSION['admin_success'] = "Caregiver '$username' has been removed successfully.";
        }
        
        // Commit transaction
        $pdo->commit();
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['admin_error'] = "Failed to remove user: " . $e->getMessage();
    }
    
    // Redirect back to the page admin came from
    $referer = $_SERVER['HTTP_REFERER'] ?? 'admin_dashboard.php';
    header("Location: $referer");
    die();
    
} else {
    header("Location: admin_dashboard.php");
    die();
}
?>