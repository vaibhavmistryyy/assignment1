<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'MP') {
    echo "Access Denied!";
    exit; // Only MPs can create new bills
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $billsFilePath = '../data/bills.json'; // Path to the bills JSON file
    $bills = json_decode(file_get_contents($billsFilePath), true) ?: []; // Load existing bills or initialize an empty array

    // Create a new bill with the provided data
    $newBill = [
        'id' => count($bills) + 1, // Set a new ID based on the current number of bills
        'title' => trim($_POST['title']),
        'description' => trim($_POST['description']),
        'author' => $_SESSION['user']['username'],
        'status' => 'Draft', // Default status for new bills
        'amendments' => [] // Initialize an empty amendments array
    ];

    $bills[] = $newBill; // Append the new bill to the existing bills array
    if (file_put_contents($billsFilePath, json_encode($bills, JSON_PRETTY_PRINT)) === false) {
        echo "Failed to save bill data. Check file permissions or path.";
        exit;
    }
     // Save all bills back to the JSON file
    header('Location: ../dashboard.php'); // Redirect to the dashboard

    // Send an email notification to the admin
    $to = 'admin@example.com';  // Admin's email
    $subject = "New Bill Created: {$newBill['title']}";
    $message = "A new bill has been proposed by {$_SESSION['user']['username']}.";
    $headers = "From: no-reply@legislationsystem.com";

    mail($to, $subject, $message, $headers); // Send the email
    exit;
}
?>

<!-- HTML form for creating a new bill -->
<form method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required><br>
    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea><br>
    <input type="submit" value="Create Bill">
</form>
