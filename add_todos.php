<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers:*");

$request = file_get_contents("php://input");
$data = json_decode($request);

if (isset($data->username) && isset($data->project_name) && isset($data->description)) {
    $username = $data->username;
    $project_name = $data->project_name;
    $description = $data->description;

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp");

    if ($con->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed"]));
    }

    // Escape input to prevent SQL injection
    $username = $con->real_escape_string($username);
    $project_name = $con->real_escape_string($project_name);
    $description = $con->real_escape_string($description);

    // Check if a todo with the same description already exists for the same username and project_name
    $query = "SELECT * FROM todos WHERE username = '$username' AND project_name = '$project_name' AND description = '$description'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        // If the todo already exists, return an error message
        echo json_encode(["status" => "error", "message" => "Todo with this description already exists for this project"]);
    } else {
        // Insert new todo if it does not exist
        $query = "INSERT INTO todos (username, project_name, description) VALUES ('$username', '$project_name', '$description')";
        if ($con->query($query) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Todo added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error inserting todo: " . $con->error]);
        }
    }

    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
