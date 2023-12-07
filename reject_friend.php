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

    // Update friend request status to 'rejected' for the specific user
    $queryReject = "UPDATE friend_requests SET status = 'rejected' WHERE id = $request_id AND receiver_id = $user_id";
    $conn->query($queryReject);

    // Redirect back to the friends page
    header("Location: friends.php");
    exit();
} else {
    // Handle invalid request
    header("Location: friends.php");
    exit();
}
?>
