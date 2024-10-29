<?php
session_start();
if ($_SESSION['user']['role'] !== 'Admin') {
    echo "Access Denied!";
    exit;
}

require '../classes/BillRepository.php';
$repo = new BillRepository();

if (isset($_GET['id'])) {
    $bill = $repo->getBillById($_GET['id']);
    if (!$bill) {
        echo "Bill not found.";
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update the bill with the new values
        $bill['title'] = $_POST['title'];
        $bill['description'] = $_POST['description'];
        $repo->saveBills($repo->getAllBills()); // Save updated bill
        header('Location: ../dashboard.php');
    }
}
?>

<form method="POST">
    <h2>Edit Bill</h2>
    Title: <input type="text" name="title" value="<?= $bill['title'] ?>" required><br>
    Description: <textarea name="description" required><?= $bill['description'] ?></textarea><br>
    <input type="submit" value="Update Bill">
</form>
