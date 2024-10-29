<?php
session_start();
if ($_SESSION['user']['role'] !== 'Admin') {
    echo "Access Denied!";
    exit;
}

require '../classes/BillRepository.php';
$repo = new BillRepository();

if (isset($_GET['id'])) {
    $bills = $repo->getAllBills();
    foreach ($bills as $key => $bill) {
        if ($bill['id'] == $_GET['id']) {
            unset($bills[$key]); // Remove the bill
            $repo->saveBills(array_values($bills)); // Re-index and save
            header('Location: ../dashboard.php');
            exit;
        }
    }
}
