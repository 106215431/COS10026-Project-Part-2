<?php
include 'header.inc';
include 'manage-nav.inc';
require_once("manage_settings.php");

// Read the EOI number and new status from POST form
$eoi = trim($_POST['eoinumber'] ?? '');
$status = trim($_POST['status'] ?? '');

// If either is empty, stop execution
if ($eoi === '' || $status === '') {
    die("EOI Number and Status are required.");
}

// SQL to update status using prepared statement
$sql = "UPDATE eoi SET status = ? WHERE EOInumber = ?";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters: status (string), EOInumber (integer)
mysqli_stmt_bind_param($stmt, "si", $status, $eoi);

// Execute the update
mysqli_stmt_execute($stmt);

// Check how many rows were updated
$rows = mysqli_stmt_affected_rows($stmt);

// Page heading
echo "<h2>Update EOI Status</h2>";

// If update succeeded
if ($rows > 0) {
    echo "<p>Status of EOI <strong>$eoi</strong> updated to <strong>$status</strong>.</p>";
} else {
    echo "<p>No EOI found with number <strong>$eoi</strong> — nothing updated.</p>";
}

// Navigation link
echo "<br><br><a href='manage.php' class='return-link'>Return to Home</a>";

// Close DB
mysqli_close($conn);
?>
