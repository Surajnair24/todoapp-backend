<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers:*");

if (isset($_GET['username']) && isset($_GET['project_name'])) {
    $username = $_GET['username'];
    $project_name = $_GET['project_name'];

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp");

    if ($con->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed"]));
    }

    $username = $con->real_escape_string($username);
    $project_name = $con->real_escape_string($project_name);

    $query = "SELECT id, description, completed, date_created FROM todos WHERE username = '$username' AND project_name = '$project_name'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $todos = [];
        while ($row = $result->fetch_assoc()) {
            $todos[] = $row;
        }
        echo json_encode(["status" => "success", "todos" => $todos]);
    } else {
        echo json_encode(["status" => "success", "todos" => []]);
    }

    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
