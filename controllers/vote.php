<?php
session_start();

// Ensure only MPs can vote
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'MP') {
    echo "Access Denied!";
    exit;
}

// Check for the required query parameters
if (isset($_GET['bill_id']) && isset($_GET['vote'])) {
    $billId = (int)$_GET['bill_id'];
    $vote = $_GET['vote'] === 'For' ? 'For' : 'Against'; // Ensure the vote is either "For" or "Against"
    $username = $_SESSION['user']['username'];

    // Path to the votes file
    $votesFilePath = '../data/votes.json';
    $votes = json_decode(file_get_contents($votesFilePath), true) ?: [];

    // Add the vote
    $votes[] = [
        'bill_id' => $billId,
        'user' => $username,
        'vote' => $vote
    ];

    // Save the vote to the file
    file_put_contents($votesFilePath, json_encode($votes, JSON_PRETTY_PRINT));

    // Redirect back to the dashboard
    header('Location: ../dashboard.php');
    exit;
} else {
    echo "Invalid request.";
}
?>
