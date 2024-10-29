<?php
session_start();

// Ensure only Admins can approve bills
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    echo "Access Denied!";
    exit;
}

// Check for the required bill ID parameter
if (isset($_GET['bill_id'])) {
    $billId = (int)$_GET['bill_id'];

    // Load existing bills
    $billsFilePath = '../data/bills.json';
    $bills = json_decode(file_get_contents($billsFilePath), true) ?: [];

    // Find and update the bill status
    foreach ($bills as &$bill) {
        if ($bill['id'] === $billId) {
            $bill['status'] = 'Approved for Voting'; // Change the status
            break;
        }
    }

    // Save the updated bills array back to the JSON file
    file_put_contents($billsFilePath, json_encode($bills, JSON_PRETTY_PRINT));

    // Redirect back to the dashboard
    header('Location: ../dashboard.php');
    exit;
} else {
    echo "Invalid request.";
}
?>
