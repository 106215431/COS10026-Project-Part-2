<?php
// Page to list EOIs for a specific job reference
// Include header markup (common page head, CSS links, etc.)
include 'header.inc';
// Include navigation specific to management pages
include 'manage-nav.inc';
// Include DB connection/settings; sets $conn
require_once 'manage_settings.php';

// Open main wrapper div for page layout
echo "<div class='page-wrapper'>";

// Open main content container (site-content and container are CSS classes)
echo "<div class='site-content'><div class='container'>";

// Read and trim jobRef from GET parameters; trim removes whitespace
$jobRef = trim($_GET['jobRef'] ?? '');

// Prepare SQL with placeholder to avoid SQL injection
$sql = "SELECT * FROM eoi WHERE jobRef = ?";
$stmt = mysqli_prepare($conn, $sql);

// If prepare fails, inform user (mysqli_prepare returns false on error)
if ($stmt === false) {
    // Show DB prepare error (keeps logic simple; in production log error)
    echo "<div class='no-results-box'><p>Database error (prepare failed).</p></div>";
} else {
    // Bind the jobRef parameter as string ("s") to the prepared statement
    mysqli_stmt_bind_param($stmt, "s", $jobRef);
    // Execute prepared statement
    mysqli_stmt_execute($stmt);
    // Fetch the resultset produced by the executed prepared statement
    $result = mysqli_stmt_get_result($stmt);

    // Escape jobRef for safe HTML output (prevents XSS)
    $safeJobRef = htmlspecialchars($jobRef, ENT_QUOTES, 'UTF-8');
    // Output heading showing which job reference the EOIs belong to
    echo "<h2 class='formname' >EOIs for Job Reference: $safeJobRef</h2>";

    // If result exists and has at least one row, render a table
    if ($result && mysqli_num_rows($result) > 0) {
        // Table header row describing columns
        echo "<table class='data-table'>
                <tr>
                    <th>EOI Number</th><th>Job Ref</th><th>First Name</th><th>Last Name</th>
                    <th>DOB</th><th>Gender</th><th>Street Address</th><th>Suburb</th>
                    <th>State</th><th>Postcode</th><th>Email</th><th>Phone</th>
                    <th>Skill 1</th><th>Skill 2</th><th>Skill 3</th><th>Skill 4</th>
                    <th>Other Skills</th><th>Status</th>
                </tr>";
        // Loop over each row in the resultset
        while ($row = mysqli_fetch_assoc($result)) {
            // Start a new table row for this record
            echo "<tr>";
            // For each field, escape output with htmlspecialchars to prevent XSS.
            // Pattern reused: echo "<td>" . htmlspecialchars($row['COLUMN'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['EOInumber'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['jobRef'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['firstName'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['lastName'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['dob'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['streetAddress'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['suburb'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['state'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['postcode'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['skill1'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['skill2'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['skill3'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['skill4'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['otherSkills'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
            // Close table row
            echo "</tr>";
        }
        // Close the table after rows processed
        echo "</table>";
    } else {
        // No matching EOIs found — inform the user
        echo "<div class='no-results-box'>No EOIs found.</div>";
    }
    // Close prepared statement to free resources
    mysqli_stmt_close($stmt);
}

// Link back to manage home and close containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB connection (good practice) and close wrapper div
mysqli_close($conn);
echo "</div>"; // close wrapper


?>
