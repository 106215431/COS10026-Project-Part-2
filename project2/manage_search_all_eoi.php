<?php
// Page to display all EOIs with optional sorting
include 'header.inc';
include 'manage-nav.inc';
require_once("manage_settings.php");

// Small CSS to push footer to bottom and style empty box / table
echo "<div class='page-wrapper'>";

// Allowed sort fields to prevent SQL injection via column names.
// Whitelisting column names ensures safe concatenation into ORDER BY.
$allowedSortFields = [
    'EOInumber', 'jobRef', 'firstName', 'lastName',
    'dob', 'gender', 'streetAddress', 'suburb',
    'state', 'postcode', 'email', 'phone', 'status'
];

// Read sort parameters from GET with defaults
$sortby = $_GET['sortby'] ?? 'EOInumber';
$order  = $_GET['order'] ?? 'ASC';

// Validate sort column against whitelist and default if invalid
if (!in_array($sortby, $allowedSortFields)) {
    $sortby = 'EOInumber';
}
// Only allow ASC or DESC (default to ASC)
$order = ($order === 'DESC') ? 'DESC' : 'ASC';

// Build and execute query using validated column and order
// Note: column and order are validated above, so concatenation here is acceptable
$sql = "SELECT * FROM eoi ORDER BY $sortby $order";
$result = mysqli_query($conn, $sql);

?>
<!-- Form to allow users to change sorting -->
<form action="manage_search_all_eoi.php" method="get">
    <label><strong>Sort by:</strong></label><br>
    <select name="sortby">
        <option value="EOInumber">EOI Number</option>
        <option value="jobRef">Job Reference</option>
        <option value="firstName">First Name</option>
        <option value="lastName">Last Name</option>
        <option value="dob">Date of Birth</option>
        <option value="gender">Gender</option>
        <option value="streetAddress">Street Address</option>
        <option value="suburb">Suburb</option>
        <option value="state">State</option>
        <option value="postcode">Postcode</option>
        <option value="email">Email</option>
        <option value="phone">Phone</option>
        <option value="status">Status</option>
    </select>

    <br><br>

    <label><strong>Order:</strong></label><br>
    <select name="order">
        <option value="ASC">Ascending</option>
        <option value="DESC">Descending</option>
    </select>

    <br><br>

    <button type="submit">Apply Sorting</button>
</form>

<br><br>

<?php
// If result set contains rows, render an HTML table
if ($result && mysqli_num_rows($result) > 0) {
    // Header row
    echo "<table class='data-table'>
            <tr>
                <th>EOI Number</th><th>Job Ref</th><th>First Name</th><th>Last Name</th>
                <th>DOB</th><th>Gender</th><th>Street Address</th><th>Suburb</th>
                <th>State</th><th>Postcode</th><th>Email</th><th>Phone</th>
                <th>Skill 1</th><th>Skill 2</th><th>Skill 3</th><th>Skill 4</th>
                <th>Other Skills</th><th>Status</th>
            </tr>";
    // Loop through rows and escape output to prevent XSS
    // Reused pattern: htmlspecialchars($row['col'], ENT_QUOTES, 'UTF-8') for each cell
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['EOInumber'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['jobRef'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['firstName'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['lastName'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['dob'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['streetAddress'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['suburb'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['state'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['postcode'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['skill1'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['skill2'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['skill3'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['skill4'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['otherSkills'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
   // No rows found
   echo "<div class='no-results-box'>No EOIs found.</div>" ;
}

// Return link and close containers
echo "<br><a href='manage.php' class='return-link'>Return to Home</a>";
echo "</div></div>";

// Close DB and wrapper
mysqli_close($conn);
echo "</div>"; // close wrapper


?>
