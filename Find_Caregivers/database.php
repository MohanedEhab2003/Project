<?php
$host = "localhost";
$dbname = "all_age_care";
$user = "root";
$password = "1234"; 

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    echo "<div style='text-align: center; margin-top: 20px; color: red; font-family: Arial;'>";
    echo "Connection failed, please try again later";
    echo "</div>";
    exit;
}


?>
