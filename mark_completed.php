<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers:*");

// Get the raw POST data
$request = file_get_contents("php://input");
$data = json_decode($request);

// Check if required data is provided
if (isset($data->username) && isset($data->project_name) && isset($data->description)) {
    $username = $data->username;
    $project_name = $data->project_name;
    $description = $data->description;

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp");

    // Check database connection
    if ($con->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed: " . $con->connect_error]));
    }

    // Sanitize inputs to prevent SQL injection
    $username = $con->real_escape_string($username);
    $project_name = $con->real_escape_string($project_name);
    $description = $con->real_escape_string($description);

    // Query to update the todo status to completed (1)
    $query = "UPDATE todos SET completed = 1 WHERE username = ? AND project_name = ? AND description = ?";

    if ($stmt = $con->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("sss", $username, $project_name, $description);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Todo marked as completed"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to mark todo as completed"]);
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
