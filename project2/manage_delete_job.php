<?php
// Include shared header and management navigation
include 'header.inc';
include 'manage-nav.inc';
// DB connection/settings; defines $conn
require_once 'manage_settings.php';

// Wrapper for page content
echo "<div class='page-wrapper'>";

echo "<div class='site-content'><div class='container'>";
echo "<h2 class = 'formname'>Delete EOIs</h2>";

// Ensure request method is POST to prevent accidental GET deletions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Inform user to submit the form correctly and stop further processing
    echo "<div class='no-results-box'><p>Please submit the form to delete EOIs.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Retrieve and trim the job reference from POST data
$jobRef = trim($_POST['jobRef'] ?? '');
if ($jobRef === '') {
    // If empty, prompt user and exit
    echo "<div class='no-results-box'><p>Please enter a job reference.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Use prepared statement to avoid SQL injection when deleting rows
$sql = "DELETE FROM eoi WHERE jobRef = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    // Handle database prepare error
    echo "<div class='no-results-box'><p class='error'>Database error (prepare failed).</p></div>";
} else {
    // Bind parameter as string and execute; "s" indicates string type
    mysqli_stmt_bind_param($stmt, "s", $jobRef);
    mysqli_stmt_execute($stmt);

    // Get number of affected rows to report back to user
    $rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    // Escape jobRef for safe HTML output (prevents XSS)
    $safeJobRef = htmlspecialchars($jobRef, ENT_QUOTES, 'UTF-8');
    if ($rows > 0) {
        // Success message with integer-casted row count (to avoid injection in output)
        echo "<div class='no-results-box'><p>Successfully deleted <strong>" . (int)$rows . "</strong> EOIs for job reference <strong>$safeJobRef</strong>.</p></div>";
    } else {
         // No matching rows found
         echo "<div class='no-results-box'>No EOIs have that job references number.</div>";
    }
}

// Return link and close containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB connection and page wrapper
mysqli_close($conn);
echo "</div>"; // close wrapper

?>
