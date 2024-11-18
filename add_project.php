<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: *");

// Retrieve and decode the incoming JSON request
$request = file_get_contents("php://input");
$data = json_decode($request);

// Validate required fields
if (isset($data->username, $data->project_title)) {
    $username = $data->username;
    $project_title = $data->project_title;

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp");

    // Check for connection error
    if ($con->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed"]));
    }

    // Sanitize inputs
    $username = $con->real_escape_string($username);
    $project_title = $con->real_escape_string($project_title);

    // Insert project into the database
    $insertQuery = "INSERT INTO projects (username, project_title) VALUES ('$username', '$project_title')";

    if ($con->query($insertQuery) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Project added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding project: " . $con->error]);
    }

    // Close the database connection
    $con->close();
} else {
    // Missing required data
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
