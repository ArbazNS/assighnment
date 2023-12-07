<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_GET['user_id'];

    // Check if the users are not already friends
    $checkFriendshipQuery = "SELECT * FROM friends WHERE (user_id = $user_id AND friend_id = $friend_id) OR (user_id = $friend_id AND friend_id = $user_id)";
    $checkFriendshipResult = $conn->query($checkFriendshipQuery);

    // Check if a friend request already exists
    $checkRequestQuery = "SELECT * FROM friend_requests WHERE sender_id = $user_id AND receiver_id = $friend_id AND status = 'pending'";
    $checkRequestResult = $conn->query($checkRequestQuery);

    if ($checkFriendshipResult->num_rows == 0 && $checkRequestResult->num_rows == 0) {
        // Send friend request
        $sendRequestQuery = "INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES ($user_id, $friend_id, 'pending')";
        $conn->query($sendRequestQuery);

        // Redirect back to the friends page
        header("Location: friends.php");
        exit();
    } else {
        // Users are already friends or a request already exists
        echo "Friend request already sent or already friends.";
    }
} else {
    // Invalid request
    echo "Invalid request";
}
?>
