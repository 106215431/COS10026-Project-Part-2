<?php
// Include header layout
include 'header.inc';
include 'nav.inc';

// Include database settings (connection)
require_once("manage_settings.php");

// Prepare SQL to fetch ALL EOIs
$sql = "SELECT * FROM eoi";

// Execute the SQL query
$result = mysqli_query($conn, $sql);

// Title for the page
echo "<h2>All EOIs</h2>";

// Check if the table has any rows
if (mysqli_num_rows($result) > 0) {

    // Start table and print header row
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

    // Loop through each row and print it
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
    // No data found
    echo "<p>No EOIs found.</p>";
}

// Navigation link
echo "<br><br><a href='manage.php' class='return-link'>Return to Home</a>";

// Close DB connection
mysqli_close($conn);
?>
<?php include 'footer.inc'; ?>