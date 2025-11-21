<?php
// Include header (page head, styles) and management navigation UI
include 'header.inc';
include 'manage-nav.inc';

// Include DB connection/config which provides $conn (mysqli)
require_once 'manage_settings.php';

// Start page wrapper for consistent layout
echo "<div class='page-wrapper'>";

echo "<div class='site-content'><div class='container'>";
// Page title
echo "<h2 class = 'formname'>Delete EOIs</h2>";

// Enforce POST method so deletes cannot be triggered by visiting a URL (GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Notify the user and stop further execution
    echo "<div class='no-results-box'><p>Please submit the form to delete EOIs.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Retrieve and trim the job reference from POST; default to empty string if not provided
$jobRef = trim($_POST['jobRef'] ?? '');

// Validate input presence
if ($jobRef === '') {
    // Prompt for jobRef and exit early
    echo "<div class='no-results-box'><p>Please enter a job reference.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Prepare a parameterised DELETE query to avoid SQL injection
$sql = "DELETE FROM eoi WHERE jobRef = ?";
$stmt = mysqli_prepare($conn, $sql);

// Check prepare success
if ($stmt === false) {
    // Basic error output; for production consider logging mysqli_error($conn) instead
    echo "<div class='no-results-box'><p class='error'>Database error (prepare failed).</p></div>";
} else {
    // Bind the jobRef parameter as a string ("s")
    mysqli_stmt_bind_param($stmt, "s", $jobRef);

    // Execute the prepared DELETE statement
    mysqli_stmt_execute($stmt);

    // Get the count of deleted rows
    $rows = mysqli_stmt_affected_rows($stmt);

    // Close statement to free resources
    mysqli_stmt_close($stmt);

    // Escape jobRef for safe HTML output (prevent XSS)
    $safeJobRef = htmlspecialchars($jobRef, ENT_QUOTES, 'UTF-8');

    if ($rows > 0) {
        // Success message: cast rows to int to avoid any formatting/injection issues
        echo "<div class='no-results-box'><p>Successfully deleted <strong>" . (int)$rows . "</strong> EOIs for job reference <strong>$safeJobRef</strong>.</p></div>";
    } else {
         // No rows matched the provided jobRef
         echo "<div class='no-results-box'>No EOIs have that job references number.</div>";
    }
}

// Return link and close content containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB connection and wrapper div
mysqli_close($conn);
echo "</div>"; // close wrapper

?>
