<?php
// Page to list EOIs for a specific job reference

// Include header and management navigation partials
include 'header.inc';
include 'manage-nav.inc';

// Include DB settings; this should initialize $conn (mysqli connection)
require_once 'manage_settings.php';

// Start main wrapper for consistent page layout
echo "<div class='page-wrapper'>";

// Start content area (site-content and container usually used for styling)
echo "<div class='site-content'><div class='container'>";

// Read jobRef from GET parameters and trim whitespace to avoid accidental mismatch
$jobRef = trim($_GET['jobRef'] ?? '');

// Prepare a parameterised SELECT to safely query EOIs matching the provided jobRef
$sql = "SELECT * FROM eoi WHERE jobRef = ?";
$stmt = mysqli_prepare($conn, $sql);

// If statement preparation failed (returns false), display error message
if ($stmt === false) {
    echo "<div class='no-results-box'><p>Database error (prepare failed).</p></div>";
} else {
    // Bind jobRef as string ("s") and execute the prepared statement
    mysqli_stmt_bind_param($stmt, "s", $jobRef);
    mysqli_stmt_execute($stmt);

    // Retrieve the result set produced by the executed prepared statement
    $result = mysqli_stmt_get_result($stmt);

    // Escape jobRef for safe insertion into HTML (prevents XSS)
    $safeJobRef = htmlspecialchars($jobRef, ENT_QUOTES, 'UTF-8');

    // Output heading showing which job reference is being listed
    echo "<h2 class='formname' >EOIs for Job Reference: $safeJobRef</h2>";

    // If the result set has rows, render a table of EOIs
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table class='data-table'>
                <tr>
                    <th>EOI Number</th><th>Job Ref</th><th>First Name</th><th>Last Name</th>
                    <th>DOB</th><th>Gender</th><th>Street Address</th><th>Suburb</th>
                    <th>State</th><th>Postcode</th><th>Email</th><th>Phone</th>
                    <th>Skill 1</th><th>Skill 2</th><th>Skill 3</th><th>Skill 4</th>
                    <th>Other Skills</th><th>Status</th>
                </tr>";
        // Reused pattern for HTML table cells: echo "<td>" . htmlspecialchars($row['COLUMN'], ENT_QUOTES, 'UTF-8') . "</td>";
        // htmlspecialchars ensures any data from the DB that contains special characters does not break HTML or enable XSS.
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
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
            echo "</tr>";
        }
        echo "</table>";
    } else {
        // No matching EOIs found for the provided jobRef
        echo "<div class='no-results-box'>No EOIs found.</div>";
    }

    // Close prepared statement resource
    mysqli_stmt_close($stmt);
}

// Return link and close content containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB connection and main wrapper
mysqli_close($conn);
echo "</div>"; // close wrapper

?>
