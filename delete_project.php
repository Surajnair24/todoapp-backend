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

    // Delete the project from the database
    $deleteQuery = "DELETE FROM projects WHERE username = '$username' AND project_title = '$project_title'";

    if ($con->query($deleteQuery) === TRUE) {
        if ($con->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Project deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Project not found"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting project: " . $con->error]);
    }

    // Close the database connection
    $con->close();
} else {
    // Missing required data
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
