<?php
header("Access-Control-Allow-Origin:*"); 
header("Access-Control-Allow-Method:GET,POST");
header("Access-Control-Allow-Headers:*");

$request = file_get_contents("php://input");
$data = json_decode($request);

if (isset($data->email, $data->password)) {
    $email = $data->email;
    $password = $data->password;

    // Database connection
    $con = new mysqli("localhost", "root", "", "todoapp") or die("Error connecting to database");

    // Check if email exists
    $checkQuery = "SELECT * FROM sign_up_details WHERE username = '$email'";
    $result = $con->query($checkQuery);

    if ($result->num_rows > 0) {
        // Fetch the stored password hash
        $user = $result->fetch_assoc();
        $storedPasswordHash = $user['password'];

        // Verify the entered password against the stored hash
        if (password_verify($password, $storedPasswordHash)) {
            // Password matches
            echo json_encode(["status" => "success", "message" => "Login successful"]);
        } else {
            // Incorrect password
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        // Email does not exist
        echo json_encode(["status" => "error", "message" => "Email does not exist"]);
    }
} else {
    // Missing data
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}
?>
