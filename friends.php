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

// Fetch friends
$queryFriends = "SELECT u.id AS friend_id, u.username
                FROM friends f
                JOIN users u ON f.friend_id = u.id
                WHERE f.user_id = $user_id";
$friendsResult = $conn->query($queryFriends);

// Fetch all users who aren't friends
$queryAllUsers = "SELECT id AS user_id, username
                 FROM users
                 WHERE id NOT IN (
                     SELECT friend_id FROM friends WHERE user_id = $user_id
                 ) AND id <> $user_id";
$allUsersResult = $conn->query($queryAllUsers);

// Fetch incoming friend requests
$queryIncomingRequests = "SELECT r.id AS request_id, u.id AS sender_id, u.username AS sender_username
                         FROM friend_requests r
                         JOIN users u ON r.sender_id = u.id
                         WHERE r.receiver_id = $user_id AND r.status = 'pending'";
$incomingRequestsResult = $conn->query($queryIncomingRequests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style></style>
<body class="bg-light">

<div class="container mt-5">
    <div class="jumbotron">
        <h1 class="display-4">Friends List</h1><hr>

        <!-- Incoming Friend Requests Section -->
        <div class="mt-5 ">
            <h2>Incoming Friend Requests</h2>
            <?php while ($incomingRequest = $incomingRequestsResult->fetch_assoc()) : ?>
                <div class="card border-infomb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $incomingRequest['sender_username']; ?> wants to be your friend!</h5>
                        <a href="accept_friend.php?request_id=<?php echo $incomingRequest['request_id']; ?>" class="btn btn-success">Accept</a>
                        <a href="reject_friend.php?request_id=<?php echo $incomingRequest['request_id']; ?>" class="btn btn-danger">Reject</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Existing Friends Section -->
        <div class="mt-5">
            <h2>Your Friends</h2>
            <?php while ($friend = $friendsResult->fetch_assoc()) : ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $friend['username']; ?></h5>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- All Users Section -->
        <div class="mt-5">
            <h2>All Users</h2>
            <?php while ($user = $allUsersResult->fetch_assoc()) : ?>
                <div class="card border-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $user['username']; ?></h5>
                        <?php
                        // Check if a friend request has been sent
                        $queryCheckRequest = "SELECT * FROM friend_requests WHERE sender_id = $user_id AND receiver_id = {$user['user_id']} AND status = 'pending'";
                        $requestSentResult = $conn->query($queryCheckRequest);

                        if ($requestSentResult->num_rows > 0) {
                            // Friend request sent
                            echo '<p>Request Sent</p>';
                        } else {
                            // Display "Add Friend" button
                            echo '<a href="send_friend_request.php?user_id=' . $user['user_id'] . '" class="btn btn-success">Add Friend</a>';
                        }
                        ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Link to Profile and Logout -->
        <div class="mt-5">
            <a href="page.php?user_id=<?php echo $user_id; ?>" class="btn btn-secondary">Back to Profile</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional, but needed for certain Bootstrap features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
