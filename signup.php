<?php
include("db.php");

// Function to hash the password using bcrypt

// Handle the signup form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username already exists
    $checkUserQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUserQuery);

    if($result->num_rows > 0) {
        // User already exists, show alert or take appropriate action
        echo '<script>alert("Username already exists. Please choose a different username.");</script>';
    } else {

        // Insert user data into the database
        $insertUserQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if($conn->query($insertUserQuery) === TRUE) {
            echo '<script>alert("Signup successful!")</script>';
        } else {
            echo "Error: ".$insertUserQuery."<br>".$conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">



    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 300px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="Button"] {
            background-color: #008CBA;
            color: #fff;
            cursor: pointer;
        }

        p {
            color: red;
        }
    </style>

</head>

<body>



    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Signup Form</h2>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Signup</button>
                    <a href="index.html" class="btn btn-secondary">To Login</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>