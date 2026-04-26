<?php

// Check if email is empty
function is_email_empty($email) {
    if (empty($email)) {
        return true;
    }
    return false;
}

// Check if password is empty
function is_password_empty($password) {
    if (empty($password)) {
        return true;
    }
    return false;
}

// Validate email format
function is_email_invalid_login($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

// Check if user exists in database (either customer or caregiver)
function is_user_not_found($user) {
    if (!$user) {
        return true;
    }
    return false;
}

// Verify password
function is_password_wrong($password, $hashed_password) {
    if (!password_verify($password, $hashed_password)) {
        return true;
    }
    return false;
}
?>