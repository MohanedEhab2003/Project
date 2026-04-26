<?php



function is_input_empty_common($username, $nationalID, $phone, $email, $confirmPass, $password, $location){
    if(empty($username) || empty($nationalID) || empty($phone) || empty($email) || empty($confirmPass) || empty($password) || empty($location)){
        return true;
    }
    else{
        return false;
    }
}

function is_email_invalid($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    else{
        return false;
    }
}

function is_username_taken_CG(object $pdo, string $username){
    if(get_username_Caregiver($pdo, $username)){
        return true;
    }
    else{
        return false;
    }
}

function is_username_taken_Customer(object $pdo, string $username){
    if(get_username_Customer($pdo, $username)){
        return true;
    }
    else{
        return false;
    }
}

function is_email_registered_CG(object $pdo, string $email){
    if(get_email_Caregiver($pdo, $email)){
        return true;
    }
    else{
        return false;
    }
}

function is_email_registered_Customer(object $pdo, string $email){
    if(get_email_Customer($pdo, $email)){
        return true;
    }
    else{
        return false;
    }
}



// Check if national ID is taken in Caregiver table
function is_national_id_taken_CG(object $pdo, string $nationalID){
    if(get_national_id_Caregiver($pdo, $nationalID)){
        return true;
    }
    return false;
}

// Check if national ID is taken in Customers table
function is_national_id_taken_Customer(object $pdo, string $nationalID){
    if(get_national_id_Customer($pdo, $nationalID)){
        return true;
    }
    return false;
}

// Check if National ID photo was uploaded
function is_national_id_photo_uploaded($id_photo_path){
    if(empty($id_photo_path) || $id_photo_path === null){
        return false;
    }
    return true;
}

// Validate ID photo file type and size
function is_valid_id_photo($file){
    // Check if file was uploaded without errors
    if(!isset($file) || $file['error'] !== 0){
        return false;
    }
    
    // Check file size (max 5MB = 5,000,000 bytes)
    if($file['size'] > 5000000){
        return false;
    }
    
    // Check allowed file types
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if(!in_array($file_type, $allowed_types)){
        return false;
    }
    
    return true;
}

// Ensure both roles upload ID photo
function is_id_photo_required_for_role($role, $has_id_photo){
    // Both caregiver and customer MUST upload ID photo for verification
    if($has_id_photo === false){
        return false;
    }
    return true;
}
?>