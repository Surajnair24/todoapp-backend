<?php
header("Access-Control-Allow-Origin:*"); 
header("Access-Control-Allow-Method:GET,POST");
header("Access-Control-Allow-Headers:*");

$request = file_get_contents("php://input");
$data = json_decode($request);

if (isset($data->email, $data->password)) {
    $email = $data->email;

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp") or die("Error connecting to database");

    // Check if email already exists
    $checkQuery = "SELECT * FROM sign_up_details WHERE username = '$email'";
    $result = $con->query($checkQuery);

    if ($result->num_rows > 0) {
        // Email exists, send error response
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
    } else {
        // Hash the password using bcrypt
        $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);

        // Proceed with inserting the new user
        $query = "INSERT INTO sign_up_details (username, password) VALUES ('$email', '$hashedPassword')";
        $res = $con->query($query);

        if ($res) {
            echo json_encode(["status" => "success", "message" => "User successfully registered"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
