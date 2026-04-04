<?php
session_start();

if (!empty($_SESSION['user_loggedIn'])) {
    header("Location: https://web.ics.purdue.edu/~kilroyc/?");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username_input = trim($_POST["my_username"] ?? "");
    $password_input = trim($_POST["my_password"] ?? "");

    // --- Purdue DB connection ---
    $servername = "mydb.itap.purdue.edu";
    $username = "g1154094";       // your CAREER/group username
    $password = "group11";        // your group password
    $database = $username;        // ITaP sets database name = your career login

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // --- End connection ---

    $sql = "SELECT EmployeeID, Username, PasswordHash, Role, EmploymentStatus, FirstName, LastName
            FROM Employee WHERE Username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if ($employee && $password_input === $employee['PasswordHash']) {
        if ($employee['EmploymentStatus'] !== 'active') {
            $conn->close();
            header("Location: login.html?error=1");
            exit();
        }

        session_regenerate_id(true);
        $_SESSION['user_loggedIn']  = true;
        $_SESSION['EmployeeID']     = $employee['EmployeeID'];
        $_SESSION['role']           = $employee['Role'];
        $_SESSION['name']           = $employee['FirstName'] . ' ' . $employee['LastName'];
        $_SESSION['Username']       = $employee['Username'];

        $conn->close(); // close before redirecting

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
        $conn->close();
        header("Location: login.html?error=1");
        exit();
    }
}
?>
