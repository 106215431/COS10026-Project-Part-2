<?php
// Include header.inc: typically outputs <head>, navigation CSS links, opening <body>, etc.
include 'header.inc';

// Include manage-nav.inc: management navigation bar (links for manage pages)
include 'manage-nav.inc';

// Require DB settings and connection: this file should create $conn (mysqli connection)
require_once 'manage_settings.php';

// Output wrapper start: page layout container (CSS class controls spacing)
echo "<div class='page-wrapper'>";

echo "<div class='site-content'><div class='container'>";

// Page title
echo "<h2 class = 'formname'>Update EOI Status</h2>";

// Ensure the form was submitted via POST to avoid accidental updates from GET URLs.
// $_SERVER['REQUEST_METHOD'] contains the HTTP method used for the request.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Inform the user and stop further processing
    echo "<div class='no-results-box'><p>Please submit the status update form.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    // Close the opened content containers to keep HTML valid
    echo "</div></div>";
    // Include footer (if present) and exit to avoid executing the rest
    include 'footer.inc';
    exit;
}

// Retrieve posted values safely; null coalescing provides default empty string.
// trim removes leading/trailing whitespace which can cause validation surprises.
$eoi = trim($_POST['eoinumber'] ?? '');
$status = trim($_POST['status'] ?? '');

// Validate that both EOI number and status are provided
if ($eoi === '' || $status === '') {
    // Show an error-like box and exit early
    echo "<div class='no-results-box'><p>EOI Number and Status are required.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Prepare an UPDATE statement with parameter placeholders to prevent SQL injection.
// The SQL sets status for the record matching EOInumber.
$sql = "UPDATE eoi SET status = ? WHERE EOInumber = ?";
$stmt = mysqli_prepare($conn, $sql);

// If preparing the statement failed, mysqli_prepare returns false.
if ($stmt === false) {
    // Show a user-friendly error (in production, log the real mysqli_error)
    echo "<div class='no-results-box'><p class='error'>Database error (prepare failed).</p></div>";
} else {
    // Cast the submitted EOI number to integer to ensure the correct type is bound.
    // (int) converts non-numeric strings to 0; you may want stronger validation.
    $eoiInt = (int)$eoi;

    // Bind parameters to the prepared statement:
    // "si" means first parameter is string (s) for status, second is integer (i) for EOInumber.
    // Binding protects from SQL injection and ensures correct types are sent to the DB.
    mysqli_stmt_bind_param($stmt, "si", $status, $eoiInt);

    // Execute the prepared statement with the bound parameters.
    mysqli_stmt_execute($stmt);

    // Get the number of affected rows; useful to tell if update occurred.
    // If no rows matched or the status is unchanged, affected rows may be 0.
    $rows = mysqli_stmt_affected_rows($stmt);

    // Free statement resources.
    mysqli_stmt_close($stmt);

    if ($rows > 0) {
        // On success, show an escaped message to avoid XSS.
        // htmlspecialchars converts special HTML characters to safe entities.
        echo "<div class='no-results-box'><p>Status of EOI <strong>" . htmlspecialchars($eoiInt, ENT_QUOTES, 'UTF-8') . "</strong> updated to <strong>" . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . "</strong>.</p></div>";
    } else {
        // No matching EOI or no change to the status field.
        echo "<div class='no-results-box'>No EOIs found.</div>";
    }
}

// Link back to manage home and close content containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB connection to release resources; echo closing wrapper div.
mysqli_close($conn);
echo "</div>"; // close wrapper

?>
