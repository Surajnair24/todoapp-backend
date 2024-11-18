<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers:*");

// Get the raw POST data
$request = file_get_contents("php://input");
$data = json_decode($request);

// Check if required data is provided
if (isset($data->username) && isset($data->project_name) && isset($data->oldDescription) && isset($data->newDescription)) {
    $username = $data->username;
    $project_name = $data->project_name;
    $oldDescription = $data->oldDescription;
    $newDescription = $data->newDescription;
    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp");

    // Check database connection
    if ($con->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed: " . $con->connect_error]));
    }

    // Sanitize inputs to prevent SQL injection
    $username = $con->real_escape_string($username);
    $project_name = $con->real_escape_string($project_name);
    $oldDescription = $con->real_escape_string($oldDescription);
    $newDescription = $con->real_escape_string($newDescription);

    // Query to update the description and completed status of the todo
    $query = "UPDATE todos SET description = ? WHERE username = ? AND project_name = ? AND description = ?";

    if ($stmt = $con->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("siss", $newDescription, $username, $project_name, $oldDescription);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Todo updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update todo"]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the query"]);
    }

    // Close the database connection
    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
