<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("db.php");

    $username = $_POST["username"];
    $password = $_POST["password"];
    

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION["user_id"] = $user["id"]; // Store user ID in session
            $response = array("success" => true, "redirect" => "subdirectory/page.php");
            echo json_encode($response);
            
        } else {
            $response = array("success" => false, "message" => "Invalid username or password");
            echo json_encode($response);
        }
    } else {
        $response = array("success" => false, "message" => "Error executing the query: " . mysqli_error($conn));
        echo json_encode($response);
    }

    mysqli_close($conn);
}
?>
