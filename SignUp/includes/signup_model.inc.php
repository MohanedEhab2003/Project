<?php



function get_username_Caregiver(object $pdo, string $username)
{
    $query = "SELECT username FROM Caregivers WHERE username = :username;";
    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_username_Customer(object $pdo, string $username)
{
    $query = "SELECT username FROM Customers WHERE username = :username;";
    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_email_Caregiver(object $pdo, string $email)
{
    $query = "SELECT email FROM Caregivers WHERE email = :email;";
    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_email_Customer(object $pdo, string $email)
{
    $query = "SELECT email FROM Customers WHERE email = :email;";
    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}



function get_national_id_Caregiver(object $pdo, string $nationalID) {
    $query = "SELECT national_id FROM Caregivers WHERE national_id = :national_id;";
    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':national_id', $nationalID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_national_id_Customer(object $pdo, string $nationalID) {
    $query = "SELECT national_id FROM Customers WHERE national_id = :national_id;";
    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':national_id', $nationalID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function handle_location(object $pdo, string $location_string) {
    $parts = explode(',', $location_string);
    $city = trim($parts[0]);
    $area = isset($parts[1]) ? trim($parts[1]) : '';
    
    $query = "SELECT location_id FROM Location WHERE city = :city AND area = :area;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':area', $area);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return $result['location_id'];
    }
    
    $query = "INSERT INTO Location (city, area) VALUES (:city, :area);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':area', $area);
    $stmt->execute();
    
    return $pdo->lastInsertId();
}

// Modified: Added bio parameter
function create_caregiver($pdo, $username, $email, $hashed_password, $nationalID, $phone, $experience, $price, $bio, $profile_pic_path, $id_photo_path, $location_id) {
    $query = "INSERT INTO Caregivers (username, email, password_hash, phone, national_id, national_id_image, profile_picture, experience_years, price_per_hour, bio, location_id, is_verified) 
              VALUES (:username, :email, :password, :phone, :national_id, :id_photo, :profile_pic, :experience, :price, :bio, :location_id, 0);";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':national_id', $nationalID);
    $stmt->bindParam(':id_photo', $id_photo_path);
    $stmt->bindParam(':profile_pic', $profile_pic_path);
    $stmt->bindParam(':experience', $experience);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':location_id', $location_id);
    $stmt->execute();
    
    return $pdo->lastInsertId();
}

// Modified: Added bio parameter
function create_customer($pdo, $username, $email, $hashed_password, $nationalID, $phone, $bio, $profile_pic_path, $id_photo_path, $location_id) {
    $query = "INSERT INTO Customers (username, email, password_hash, phone, national_id, national_id_image, profile_picture, bio, location_id, is_verified) 
              VALUES (:username, :email, :password, :phone, :national_id, :id_photo, :profile_pic, :bio, :location_id, 0);";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':national_id', $nationalID);
    $stmt->bindParam(':id_photo', $id_photo_path);
    $stmt->bindParam(':profile_pic', $profile_pic_path);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':location_id', $location_id);
    $stmt->execute();
    
    return $pdo->lastInsertId();
}

function create_user_entry($pdo, $email, $hashed_password, $user_type, $customer_id, $caregiver_id) {
    $query = "INSERT INTO users (email, password_hash, user_type, customer_id, caregiver_id) 
              VALUES (:email, :password, :user_type, :customer_id, :caregiver_id);";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':caregiver_id', $caregiver_id);
    $stmt->execute();
    
    return $pdo->lastInsertId();
}
?>