<?php


// Get user by email from users table (unified authentication)
function get_user_by_email($pdo, $email) {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Get customer details by customer_id
function get_customer_by_id($pdo, $customer_id) {
    $query = "SELECT customers_id, username, email, phone, profile_picture, is_verified, bio 
              FROM Customers WHERE customers_id = :customer_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Get caregiver details by caregiver_id
function get_caregiver_by_id($pdo, $caregiver_id) {
    $query = "SELECT caregivers_id, username, email, phone, profile_picture, is_verified, bio, experience_years, price_per_hour, availability_status 
              FROM Caregivers WHERE caregivers_id = :caregiver_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':caregiver_id', $caregiver_id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
?>