<?php


function check_login_errors() {
    if (isset($_SESSION['errors_login'])) {
        $errors = $_SESSION['errors_login'];
        
        echo '<div style="background-color: #ffe6e6; color: #d63031; padding: 15px; border-radius: 10px; margin-top: 20px;">';
        
        foreach ($errors as $error) {
            echo "<p style='color: #d63031; margin: 5px 0; font-size: 13px;'>❌ " . htmlspecialchars($error) . "</p>";
        }
        
        echo '</div>';
        
        unset($_SESSION['errors_login']);
    }
}

// Function to display success message
function display_success_message() {
    if (isset($_SESSION['login_success'])) {
        echo '<div style="background-color: #e6ffe6; color: #00b894; padding: 15px; border-radius: 10px; margin-top: 20px;">';
        echo "<p>✅ " . htmlspecialchars($_SESSION['login_success']) . "</p>";
        echo '</div>';
        unset($_SESSION['login_success']);
    }
}

// Function to get remembered email
function get_remembered_email() {
    if (isset($_COOKIE['remember_email'])) {
        return htmlspecialchars($_COOKIE['remember_email']);
    }
    return '';
}
?>