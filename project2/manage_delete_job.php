<?php
include 'header.inc';
require_once("manage_settings.php");

// Read jobRef from POST parameter
$jobRef = trim($_POST['jobRef'] ?? '');

// If nothing entered, stop the script
if ($jobRef === '') {
    die("Please enter a job reference.");
}

// Prepared delete query
$sql = "DELETE FROM eoi WHERE jobRef = ?";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

// Bind the jobRef value
mysqli_stmt_bind_param($stmt, "s", $jobRef);

// Execute deletion
mysqli_stmt_execute($stmt);

// Count how many rows were removed
$rows = mysqli_stmt_affected_rows($stmt);

// Display results
echo "<h2>Delete EOIs</h2>";

// If at least 1 row deleted
if ($rows > 0) {
    echo "<p>Successfully deleted <strong>$rows</strong> EOIs for job reference <strong>$jobRef</strong>.</p>";
} else {
    echo "<p>No EOIs found for job reference <strong>$jobRef</strong>. Nothing deleted.</p>";
}

// Back link
echo "<br><br><a href='manage.php' class='return-link'>Return to Home</a>";

// Close connection
mysqli_close($conn);
?>
