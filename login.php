<?php
// file: login.php

session_start();

if (!empty($_SESSION['user_loggedIn'])) {
    header("Location: https://web.ics.purdue.edu/~kilroyc/?");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username_input = trim($_POST["my_username"] ?? "");
    $password_input = trim($_POST["my_password"] ?? "");

    $conn = new mysqli("mydb.itap.purdue.edu", "g1154094", "group11", "g1154094");

    if ($conn->connect_error) {
        die("Connection failed");
    }

    $stmt = $conn->prepare("
        SELECT EmployeeID, Username, PasswordHash, Role, EmploymentStatus, FirstName, LastName
        FROM Employee WHERE Username = ?
    ");

    $stmt->bind_param("s", $username_input);
    $stmt->execute();

    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    // plain string comparison (as required)
    if ($employee && $password_input === $employee['PasswordHash']) {

        if ($employee['EmploymentStatus'] !== 'active') {
            header("Location: login.html?error=1");
            exit();
        }

        session_regenerate_id(true);

        $_SESSION['user_loggedIn'] = true;
        $_SESSION['EmployeeID'] = $employee['EmployeeID'];
        $_SESSION['role'] = $employee['Role'];
        $_SESSION['name'] = $employee['FirstName'] . ' ' . $employee['LastName'];
        $_SESSION['Username'] = $employee['Username'];

        switch ($employee['Role']) {
            case 'driver':
                header("Location: driver_dashboard.php");
                break;
            case 'warehouse staff':
                header("Location: warehouse_dashboard.php");
                break;
            default:
                header("Location: https://web.ics.purdue.edu/~kilroyc/?");
        }
        exit();

    } else {
        header("Location: login.html?error=1");
        exit();
    }
}
?>
