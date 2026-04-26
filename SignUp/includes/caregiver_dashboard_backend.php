<?php
// Start session and check authentication
require_once 'config_session.inc.php';
require_once 'db.inc.php';

// Check if user is logged in and is a caregiver
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'caregiver') {
    header("Location: ../index.php");
    die();
}

// Get user's data from database
$user_id = $_SESSION['user_id'];
$query = "SELECT cg.caregivers_id, cg.username, cg.email, cg.bio, cg.profile_picture, 
                 cg.is_verified, cg.experience_years, cg.price_per_hour, cg.availability_status,
                 l.city, l.area 
          FROM Caregivers cg 
          LEFT JOIN Location l ON cg.location_id = l.location_id 
          WHERE cg.caregivers_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// If user not found in database, logout
if (!$user_data) {
    session_destroy();
    header("Location: ../index.php");
    die();
}

// Assign variables for frontend use
$is_verified = (bool)($user_data['is_verified'] ?? false);
$username = $user_data['username'] ?? $_SESSION['username'];
$email = $user_data['email'] ?? $_SESSION['user_email'];
$bio = $user_data['bio'] ?? '';
$profile_picture = $user_data['profile_picture'] ?? null;
$experience_years = $user_data['experience_years'] ?? 'Not specified';
$price_per_hour = $user_data['price_per_hour'] ?? 'Not specified';
$availability_status = $user_data['availability_status'] ?? 'available';

// Format location
$location = '';
if (!empty($user_data['city']) || !empty($user_data['area'])) {
    $location = trim(($user_data['city'] ?? '') . ', ' . ($user_data['area'] ?? ''));
    $location = rtrim($location, ', ');
} else {
    $location = 'Not specified';
}

// Store bio in session for display
$_SESSION['user_bio'] = $bio;
?>