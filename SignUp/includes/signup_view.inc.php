<?php

function check_signup_errors()
{
    if (isset($_SESSION['errors_Signup'])) {
        $errors = $_SESSION['errors_Signup'];
        
        echo "<br>";
        echo '<div style="background-color: #ffe6e6; color: #d63031; padding: 15px; border-radius: 10px; margin: 20px 0;">';
        
        foreach ($errors as $error) {
            // Special styling for ID photo error
            if (strpos($error, 'National ID photo') !== false || strpos($error, 'upload a clear photo') !== false) {
                echo "<p style='color: #d63031; margin: 5px 0;'>📸 ❌ $error</p>";
            } else {
                echo "<p style='color: #d63031; margin: 5px 0;'>❌ $error</p>";
            }
        }
        
        echo '</div>';
        unset($_SESSION['errors_Signup']);
    }
    
    // Display success message if exists
    if (isset($_SESSION['signup_success'])) {
        echo '<div style="background-color: #e6ffe6; color: #00b894; padding: 15px; border-radius: 10px; margin: 20px 0;">';
        echo "<p>✅ " . $_SESSION['signup_success'] . "</p>";
        echo '</div>';
        unset($_SESSION['signup_success']);
    }
}
?>