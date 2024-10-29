<?php
session_start();
if ($_SESSION['user']['role'] !== 'MP') {
    echo "Access Denied!";
    exit;
}

require '../classes/BillRepository.php';
$repo = new BillRepository();

// Get the bill by ID
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bills = $repo->getAllBills();
    foreach ($bills as &$bill) {
        if ($bill['id'] == $_POST['bill_id']) {
            // Add amendment to the bill
            $amendment = [
                'text' => $_POST['amendment'],
                'proposed_by' => $_SESSION['user']['username'],
                'date' => date('Y-m-d')
            ];
            $bill['amendments'][] = $amendment;
            $repo->saveBills($bills);
            header('Location: ../dashboard.php');
        }
    }
} else {
    // Load the bill details
    $bill = $repo->getBillById($_GET['id']);
    if ($bill && $bill['status'] === 'Draft') {
        echo "<h2>Propose an Amendment to '{$bill['title']}'</h2>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='bill_id' value='{$bill['id']}'>";
        echo "Amendment: <textarea name='amendment' required></textarea><br>";
        echo "<input type='submit' value='Submit Amendment'>";
        echo "</form>";
    } else {
        echo "Bill not found or not eligible for amendment.";
    }
}
?>
