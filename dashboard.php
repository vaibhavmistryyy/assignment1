<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['MP', 'Admin'])) {
    echo "Access Denied!";
    exit;
}

require 'classes/BillRepository.php';
$repo = new BillRepository();
$bills = $repo->getAllBills(); // Get all bills

echo '<a href="controllers/addBill.php" style="display: inline-block; padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Create New Bill</a><br><br>'; // Option to create new bills

echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: auto;'>"; // Container for better layout

if (!empty($bills)) {
    $votesFilePath = 'data/votes.json';
    $votes = json_decode(file_get_contents($votesFilePath), true) ?: []; // Load all votes

    foreach ($bills as $bill) {
        echo "<div style='border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;'>"; // Card style for each bill
        echo "<h2 style='color: #333; margin-top: 0;'>{$bill['title']} <span style='font-size: 0.8em; color: #777;'>({$bill['status']})</span></h2>";
        echo "<p style='color: #555;'><strong>Description:</strong> {$bill['description']}</p>";
        echo "<p style='color: #555;'><strong>Author:</strong> {$bill['author']}</p>";

        // Count votes for this bill
        $yesVotes = 0;
        $noVotes = 0;
        foreach ($votes as $vote) {
            if ($vote['bill_id'] == $bill['id']) {
                if ($vote['vote'] === 'For') {
                    $yesVotes++;
                } else {
                    $noVotes++;
                }
            }
        }

        // Display voting results
        echo "<p><strong>Votes:</strong> <span style='color: #4CAF50;'>For: {$yesVotes}</span>, <span style='color: #f44336;'>Against: {$noVotes}</span></p>";

        // Show "Approve for Voting" button for Admins if the status is "Draft"
        if ($bill['status'] === 'Draft' && $_SESSION['user']['role'] === 'Admin') {
            echo "<a href='controllers/approveBill.php?bill_id={$bill['id']}' style='color: #007bff; text-decoration: none;'>Approve for Voting</a>";
        }

        // Show voting buttons only to MPs if the bill is approved for voting
        if ($bill['status'] === 'Approved for Voting' && $_SESSION['user']['role'] === 'MP') {
            echo "<a href='controllers/vote.php?bill_id={$bill['id']}&vote=For' style='color: #4CAF50; text-decoration: none; margin-right: 10px;'>Vote For</a>";
            echo "<a href='controllers/vote.php?bill_id={$bill['id']}&vote=Against' style='color: #f44336; text-decoration: none;'>Vote Against</a>";
        }

        // Show "Close Voting" button for Admins if the status is "Approved for Voting"
        if ($bill['status'] === 'Approved for Voting' && $_SESSION['user']['role'] === 'Admin') {
            echo "<a href='controllers/closeVoting.php?bill_id={$bill['id']}' style='color: #f44336; text-decoration: none;'>Close Voting</a>";
        }

        echo "</div>"; // End of bill card
    }
} else {
    echo "<p style='color: #f44336;'>No bills found.</p>";
}

echo "</div>"; // End of container
?>
