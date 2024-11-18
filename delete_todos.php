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

    $username = $con->real_escape_string($username);
    $project_name = $con->real_escape_string($project_name);
    $description = $con->real_escape_string($description);

    $query = "DELETE FROM todos WHERE username = '$username' AND project_name = '$project_name' AND description = '$description'";
    if ($con->query($query) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Todo deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete todo"]);
    }

    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
