<?php
header("Access-Control-Allow-Origin:*"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers:*");

// Check if username is passed as a query parameter
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp");

    if ($con->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed"]));
    }

    $username = $con->real_escape_string($username);

    // Fetch projects for the user
    $query = "SELECT project_title FROM projects WHERE username = '$username'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = ["id" => uniqid(), "title" => $row['project_title']];
        }
        echo json_encode(["status" => "success", "projects" => $projects]);
    } else {
        echo json_encode(["status" => "success", "projects" => []]);
    }

    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
