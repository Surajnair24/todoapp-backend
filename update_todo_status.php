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

    // Get the current status of the todo
    $query = "SELECT completed FROM todos WHERE username = ? AND project_name = ? AND description = ?";
    if ($stmt = $con->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("sss", $username, $project_name, $description);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the current status of the todo
            $todo = $result->fetch_assoc();
            $currentStatus = $todo['completed'];

            // Toggle the completed status
            $newStatus = ($currentStatus == 1) ? 0 : 1;

            // Update the todo status
            $updateQuery = "UPDATE todos SET completed = ? WHERE username = ? AND project_name = ? AND description = ?";
            if ($updateStmt = $con->prepare($updateQuery)) {
                $updateStmt->bind_param("isss", $newStatus, $username, $project_name, $description);
                if ($updateStmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Todo status updated successfully"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update todo status"]);
                }
                $updateStmt->close();
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to prepare the update query"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Todo not found"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the select query"]);
    }

    // Close the database connection
    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
