    <?php
    session_start();
    include('db.php');

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch user details
    $user_id = $_SESSION['user_id'];
    $queryUser = "SELECT * FROM users WHERE id = $user_id";
    $userResult = $conn->query($queryUser);
    $user = $userResult->fetch_assoc();

    // Fetch friends updates from accepted friends
    $queryUpdates = "SELECT u.*, username FROM updates u
                    JOIN users ON u.user_id = users.id
                    ORDER BY u.created_at DESC
                    LIMIT 20";
    $updatesResult = $conn->query($queryUpdates);

    // Handle status update form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $update_text = $_POST['update_text'];
        $query = "INSERT INTO updates (user_id, update_text) VALUES ($user_id, '$update_text')";
        $conn->query($query);

        // Redirect to refresh the page
        header("Location: page.php?user_id=$user_id");
        exit();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">

    <div class="container mt-5">
        <div class="jumbotron">
            <h1>Profile</h1><hr>
            <h1 class="display-4">Welcome, <?php echo $user['username']; ?>!</h1>

            <!-- Status update form -->
            <form method="post" action="" class="mt-4">
                <div class="mb-3">
                    <label for="update_text" class="form-label">Post a new update:</label>
                    <textarea class="form-control" name="update_text" rows="4" maxlength="200" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Update</button>
            </form>

            <!-- Friends updates -->
            <div class="mt-5">
                <h2>Friends Updates</h2>
                <?php while ($update = $updatesResult->fetch_assoc()) : ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo $update['username'] . ': ' . $update['update_text'] . ' (' . $update['created_at'] . ')'; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Links to Friends Page and Logout -->
            <div class="mt-5">
                <a href="friends.php?user_id=<?php echo $user_id; ?>" class="btn btn-secondary">friends Page</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, but needed for certain Bootstrap features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
