<?php
// Include header and navigation
include 'header.inc';
include 'manage-nav.inc';
// DB connection/settings; defines $conn
require_once 'manage_settings.php';

// Open page wrapper
echo "<div class='page-wrapper'>";

echo "<div class='site-content'><div class='container'>";
echo "<h2 class = 'formname'>Update EOI Status</h2>";

// Require POST to avoid accidental updates via GET (safer)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Inform user to submit the form correctly and exit
    echo "<div class='no-results-box'><p>Please submit the status update form.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Retrieve and trim inputs from POST to remove whitespace
$eoi = trim($_POST['eoinumber'] ?? '');
$status = trim($_POST['status'] ?? '');
// Validate that both fields are present
if ($eoi === '' || $status === '') {
    echo "<div class='no-results-box'><p>EOI Number and Status are required.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Prepare UPDATE statement with placeholders (status string, EOInumber integer)
$sql = "UPDATE eoi SET status = ? WHERE EOInumber = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    // Prepare failed — show basic error; in production, log details instead
    echo "<div class='no-results-box'><p class='error'>Database error (prepare failed).</p></div>";
} else {
    // Cast EOInumber to integer to ensure correct type; (int) will produce 0 if invalid
    $eoiInt = (int)$eoi;
    // Bind parameters: "si" means string then integer (status, EOInumber)
    mysqli_stmt_bind_param($stmt, "si", $status, $eoiInt);
    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Check how many rows were affected by the update
    $rows = mysqli_stmt_affected_rows($stmt);
    // Close the statement resource
    mysqli_stmt_close($stmt);

    if ($rows > 0) {
        // Success message — escape output to avoid XSS
        echo "<div class='no-results-box'><p>Status of EOI <strong>" . htmlspecialchars($eoiInt, ENT_QUOTES, 'UTF-8') . "</strong> updated to <strong>" . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . "</strong>.</p></div>";
    } else {
        // Either no matching EOI or status was unchanged
        echo "<div class='no-results-box'>No EOIs found.</div>";
    }
}

// Return link and closing containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB connection and wrapper
mysqli_close($conn);
echo "</div>"; // close wrapper

?>
