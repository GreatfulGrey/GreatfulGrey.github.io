<?php
$servername = "mydb.itap.purdue.edu";
$username = "g1154094";   // your CAREER/group username
$password = "group11";   // your group password
$database = $username;    // ITaP set up database name = your career login

// Create connection (ONLY NEEDED ONCE per PHP page!)
$conn = new mysqli($servername, $username, $password, $database);

// Check connection was successful, otherwise immediately exit the script
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// create a string for our sql query
$sql = "SELECT track_name, time FROM track WHERE time < (SELECT AVG(time) FROM track)";

// submit the string to SQL through the connection indicated in $conn
$result = mysqli_query($conn, $sql);

// loop through results
foreach ($result as $row) {
    echo $row['track_name'] ;
    echo $row['time'] ;
    echo "<br>";
    
    
}

// Close the connection (REMEMBER TO DO THIS!)
$conn->close();
?>
