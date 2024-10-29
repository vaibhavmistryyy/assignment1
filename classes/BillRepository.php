<?php

class BillRepository {
    private $filePath;

    public function __construct() {
        $this->filePath = __DIR__ . '/../data/bills.json'; // Adjusted path to bills.json file
    }

    // Get all bills
    public function getAllBills() {
        if (!file_exists($this->filePath)) {
            return []; // Return an empty array if the file doesn't exist
        }

        $bills = json_decode(file_get_contents($this->filePath), true);
        return $bills ?: []; // Return an empty array if decoding fails
    }

    // Add a new bill
    public function addBill($bill) {
        $bills = $this->getAllBills(); // Get existing bills
        $bills[] = $bill; // Add the new bill
        file_put_contents($this->filePath, json_encode($bills, JSON_PRETTY_PRINT)); // Save to file
    }
}

?>
