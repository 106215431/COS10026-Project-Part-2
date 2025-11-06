<?php
include 'header.inc';
include 'nav.inc';
require_once("manage_settings.php");

// Read jobRef from GET parameter
$jobRef = trim($_GET['jobRef'] ?? '');

// If empty, stop program
if ($jobRef === '') {
    die("Please enter a job reference.");
}

// Prepared SQL to avoid SQL injection
$sql = "SELECT * FROM eoi WHERE jobRef = ?";

// Prepare statement
$stmt = mysqli_prepare($conn, $sql);

// Bind the jobRef as string
mysqli_stmt_bind_param($stmt, "s", $jobRef);

// Execute the query
mysqli_stmt_execute($stmt);

// Get result set from executed statement
$result = mysqli_stmt_get_result($stmt);

// Show heading
echo "<h2>EOIs for Job Reference: $jobRef</h2>";

// If there are results, display them
if (mysqli_num_rows($result) > 0) {

    // Start table
    echo "<table border='1' cellpadding='5'>
            <tr>
                <th>EOI Number</th>
                <th>Job Ref</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Street Address</th>
                <th>Suburb</th>
                <th>State</th>
                <th>Postcode</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Skill 1</th>
                <th>Skill 2</th>
                <th>Skill 3</th>
                <th>Skill 4</th>
                <th>Other Skills</th>
                <th>Status</th>
            </tr>";

    // Print each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['EOInumber']}</td>
                <td>{$row['jobRef']}</td>
                <td>{$row['firstName']}</td>
                <td>{$row['lastName']}</td>
                <td>{$row['dob']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['streetAddress']}</td>
                <td>{$row['suburb']}</td>
                <td>{$row['state']}</td>
                <td>{$row['postcode']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['skill1']}</td>
                <td>{$row['skill2']}</td>
                <td>{$row['skill3']}</td>
                <td>{$row['skill4']}</td>
                <td>{$row['otherSkills']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    // No matching EOIs
    echo "<p>No EOIs found for job reference <strong>$jobRef</strong>.</p>";
}

// Link back
echo "<br><br><a href='manage.php' class='return-link'>Return to Home</a>";
// Close DB connection
mysqli_close($conn);
?>
