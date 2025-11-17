<?php
// Include shared header and navigation
include 'header.inc';
include 'manage-nav.inc';
// Include DB connection/settings; defines $conn
require_once 'manage_settings.php';

// Open page wrapper (layout)
echo "<div class='page-wrapper'>";

echo "<div class='site-content'><div class='container'>";

// Read search category and search value from GET parameters, provide defaults
$category = $_GET['category'] ?? '';
// Trim whitespace for the value
$value = trim($_GET['value'] ?? '');
// If value empty, show message and exit gracefully
if ($value === '') {
    echo "<div class='empty-box'><p>Please enter a name to search.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Initialize statement variable
$stmt = null;

// Build prepared statements depending on the selected category.
// For LIKE searches we use placeholders and bind parameters to avoid injection.
if ($category === 'firstname') {
    // Search by first name containing the provided value
    $sql = "SELECT * FROM eoi WHERE firstName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    // Surround value with % for substring match
    $val = "%" . $value . "%";
    mysqli_stmt_bind_param($stmt, "s", $val);
    // Heading for results — escape user-provided value for HTML
    $heading = "First name contains: " . htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

} elseif ($category === 'lastname') {
    // Search by last name containing the provided value
    $sql = "SELECT * FROM eoi WHERE lastName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $val = "%" . $value . "%";
    mysqli_stmt_bind_param($stmt, "s", $val);
    $heading = "Last name contains: " . htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

} elseif ($category === 'fullname') {
    // For fullname, split input on whitespace to get at least first and last name
    $parts = preg_split('/\s+/', $value);
    if (count($parts) < 2) {
        // If user didn't enter two parts, ask for full name
        echo "<div class='no-results-box'><p>Please enter FULL NAME (first & last).</p></div>";
        echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
        echo "</div></div>";
        exit;
    }
    // Use first token as first name and last token as last name to allow middle names
    $firstname = $parts[0];
    $lastname = $parts[count($parts)-1];
    // Prepared query matches first and last name separately using LIKE
    $sql = "SELECT * FROM eoi WHERE firstName LIKE ? AND lastName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $fn = "%" . $firstname . "%";
    $ln = "%" . $lastname . "%";
    mysqli_stmt_bind_param($stmt, "ss", $fn, $ln);
    // Heading shows the parts used (escaped)
    $heading = "Full name matches: " . htmlspecialchars($firstname . ' ' . $lastname, ENT_QUOTES, 'UTF-8');

} else {
    // Invalid category provided — show error and exit
    echo "<div class='empty-box'><p class='error'>Invalid category.</p></div>";
    echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
    echo "</div></div>";
    include 'footer.inc';
    exit;
}

// Execute the prepared statement and get resultset
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Output heading for results page (static label)
echo "<h2 class='formname'>Search Results</h2>";

// If result contains rows, render a table
if ($result && mysqli_num_rows($result) > 0) {
    // Table header (column names)
    echo "<table class='data-table'>
            <tr>
                <th>EOI Number</th><th>Job Ref</th><th>First Name</th><th>Last Name</th>
                <th>DOB</th><th>Gender</th><th>Street Address</th><th>Suburb</th>
                <th>State</th><th>Postcode</th><th>Email</th><th>Phone</th>
                <th>Skill 1</th><th>Skill 2</th><th>Skill 3</th><th>Skill 4</th>
                <th>Other Skills</th><th>Status</th>
            </tr>";
    // Pattern reused inside loop: each cell uses htmlspecialchars($row['col'], ENT_QUOTES, 'UTF-8')
    // This prevents XSS by escaping HTML special characters; comment shown once here and applied to each cell below.
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
    // No results found message
    echo "<div class='no-results-box'>No EOIs found.</div>";
}

// Close statement and connection, output return link and wrapper close
mysqli_stmt_close($stmt);

echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

mysqli_close($conn);
echo "</div>"; // close wrapper

?>
