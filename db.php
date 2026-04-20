<?php
// db.php — single source of truth for the database connection
// Include this at the top of every PHP file that needs data

$host = "localhost";
$user = "root";
$pass = "";               // blank by default in XAMPP
$db   = "pharmacool_erp"; // must match the name you created in phpMyAdmin

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // In api/ files this returns JSON so JS can read the error
    http_response_code(500);
    die(json_encode([
        "error" => "Database connection failed: " . $conn->connect_error
    ]));
}

// Tell MySQL to use UTF-8 so special characters display correctly
$conn->set_charset("utf8mb4");
?>