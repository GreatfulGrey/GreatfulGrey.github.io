<?php
session_start();

$error = "";

if ($_SESSION['user_loggedIn']) {
    header("location: dashboard.php");
    exit();
}

$users = [
    "driver1"    => ["password" => "",  "role" => "",    "name" => ""],
    "driver2"    => ["password" => "",  "role" => "",    "name" => ""],
    "warehouse1" => ["password" => "",  "role" => "", "name" => ""],
    "warehouse2" => ["password" => "",  "role" => "", "name" => ""],
    "manager1"   => ["password" => "",  "role" => "",   "name" => ""],
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

?>
