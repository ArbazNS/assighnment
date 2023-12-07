<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Update friend request status to 'accepted' for the specific user
    $queryAccept = "UPDATE friend_requests SET status = 'accepted' WHERE id = $request_id AND receiver_id = $user_id";
    $conn->query($queryAccept);

    // Add the friends to the friends table
    $queryAddFriend = "INSERT INTO friends (user_id, friend_id) SELECT receiver_id, sender_id FROM friend_requests WHERE id = $request_id";
    $conn->query($queryAddFriend);

    // Redirect back to the friends page
    header("Location: friends.php");
    exit();
} else {
    // Handle invalid request
    header("Location: friends.php");
    exit();
}
?>
