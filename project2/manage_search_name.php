<?php
// Include shared header and navigation visuals
include 'header.inc';
include 'manage-nav.inc';
// Include DB settings/connection; $conn is expected to be available afterwards
require_once 'manage_settings.php';

// Start page wrapper for consistent layout
echo "<div class='page-wrapper'>";

echo "<div class='site-content'><div class='container'>";

// Read search category (firstname/lastname/fullname) and raw search value from GET
$category = $_GET['category'] ?? '';
// Trim to remove accidental leading/trailing whitespace
$value = trim($_GET['value'] ?? '');

// If the user did not supply a search value, show message and stop
if ($value === '') {
    echo "<div class='empty-box'><p>Please enter a name to search.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Initialize statement variable; will be assigned a prepared statement below
$stmt = null;

// Depending on the category, prepare an appropriate SQL with placeholders.
// Use LIKE for substring matching; user-supplied values are bound as parameters.
if ($category === 'firstname') {
    // Query to match firstName column using LIKE
    $sql = "SELECT * FROM eoi WHERE firstName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    // Add wildcard characters for substring matching
    $val = "%" . $value . "%";
    mysqli_stmt_bind_param($stmt, "s", $val);
    // Safe heading for display (escape the raw input)
    $heading = "First name contains: " . htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

} elseif ($category === 'lastname') {
    // Query to match lastName column using LIKE
    $sql = "SELECT * FROM eoi WHERE lastName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $val = "%" . $value . "%";
    mysqli_stmt_bind_param($stmt, "s", $val);
    $heading = "Last name contains: " . htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

} elseif ($category === 'fullname') {
    // For fullname search, require at least two tokens (first and last)
    $parts = preg_split('/\s+/', $value);
    if (count($parts) < 2) {
        // If not enough parts, instruct the user and exit
        echo "<div class='no-results-box'><p>Please enter FULL NAME (first & last).</p></div>";
        echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
        echo "</div></div>";
        exit;
    }
    // Use first token as first name and last token as last name (ignores middle names)
    $firstname = $parts[0];
    $lastname = $parts[count($parts)-1];
    // Query matches both firstName and lastName with LIKE
    $sql = "SELECT * FROM eoi WHERE firstName LIKE ? AND lastName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $fn = "%" . $firstname . "%";
    $ln = "%" . $lastname . "%";
    mysqli_stmt_bind_param($stmt, "ss", $fn, $ln);
    // Safe heading using escaped concatenation
    $heading = "Full name matches: " . htmlspecialchars($firstname . ' ' . $lastname, ENT_QUOTES, 'UTF-8');

} else {
    // Invalid category: show error and exit
    echo "<div class='no-results-box'><p class='error'>Invalid category.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Execute the prepared statement and fetch the result set
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Output a static heading for results (could include $heading if desired)
echo "<h2 class='formname'>Search Results</h2>";

// If there are results, render them as a table
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table class='data-table'>
            <tr>
                <th>EOI Number</th><th>Job Ref</th><th>First Name</th><th>Last Name</th>
                <th>DOB</th><th>Gender</th><th>Street Address</th><th>Suburb</th>
                <th>State</th><th>Postcode</th><th>Email</th><th>Phone</th>
                <th>Skill 1</th><th>Skill 2</th><th>Skill 3</th><th>Skill 4</th>
                <th>Other Skills</th><th>Status</th>
            </tr>";
    // Reused escaping pattern: for each database field displayed, we call
    // htmlspecialchars($row['COLUMN'], ENT_QUOTES, 'UTF-8') to prevent XSS.
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
    // Inform the user if no EOIs matched the criteria
    echo "<div class='no-results-box'>No EOIs found.</div>";
}

// Close the prepared statement and database connection, output return link
mysqli_stmt_close($stmt);

echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

mysqli_close($conn);
echo "</div>"; // close wrapper

?>
