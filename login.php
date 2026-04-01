<?php
session_start();

$error = "";

if ($_SESSION['user_loggedIn']) {
    header("location: dashboard.php");
    exit();
}

$users = [
    "driver1"    => ["password" => "pass1", "role" => "driver",    "name" => "Driver One"],
    "driver2"    => ["password" => "pass2", "role" => "driver",    "name" => "Driver Two"],
    "warehouse1" => ["password" => "pass3", "role" => "warehouse", "name" => "Warehouse One"],
    "warehouse2" => ["password" => "pass4", "role" => "warehouse", "name" => "Warehouse Two"],
    "manager1"   => ["password" => "pass5", "role" => "manager",   "name" => "Manager One"],
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["my_username"] ?? "");
    $password = trim($_POST["my_password"] ?? "");

    if (isset($users[$username]) && $users[$username]["password"] === $password) {
        $_SESSION["user"]  = $username;
        $_SESSION["role"]  = $users[$username]["role"];
        $_SESSION["name"]  = $users[$username]["name"];
        $_SESSION["user_loggedIn"] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password. Please try again.";
    }
}  
?>
