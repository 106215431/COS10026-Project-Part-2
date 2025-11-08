<?php
/*******************************************************
 * INCLUDE PAGE SECTIONS AND DATABASE CONNECTION
 *******************************************************/

// Include the common header HTML
include 'header.inc';

// Include navigation bar
include 'nav.inc';

// Include database connection settings (creates $conn)
require_once("manage_settings.php");


/*******************************************************
 * ALLOWED SORTING FIELDS (MATCHES YOUR DATABASE COLUMNS)
 *******************************************************/

// Only these columns are allowed for ORDER BY
// This prevents SQL injection (e.g., someone putting "DROP TABLE")
$allowedSortFields = [
    'EOInumber', 'jobRef', 'firstName', 'lastName',
    'dob', 'gender', 'streetAddress', 'suburb',
    'state', 'postcode', 'email', 'phone', 'status'
];


/*******************************************************
 * READ SORTING CHOICES FROM USER (GET PARAMETERS)
 *******************************************************/

// If the user selected a field to sort by, use it
// Otherwise default to sorting by EOInumber
$sortby = $_GET['sortby'] ?? 'EOInumber';

// If user selected ASC or DESC, use it
// Otherwise default to ASC
$order  = $_GET['order'] ?? 'ASC';


/*******************************************************
 * VALIDATE THE SORTING FIELD
 *******************************************************/

// If user tries to sort by an invalid column (or hack input),
// reset to safe default column EOInumber
if (!in_array($sortby, $allowedSortFields)) {
    $sortby = 'EOInumber';
}


/*******************************************************
 * VALIDATE SORT ORDER (ASC or DESC ONLY)
 *******************************************************/

// This line ensures only "DESC" is allowed for DESC,
// and everything else becomes ASC.
// (Prevents SQL injection and invalid values)
$order = ($order === 'DESC') ? 'DESC' : 'ASC';


/*******************************************************
 * BUILD SQL QUERY WITH SORTING
 *******************************************************/

// Now build the SQL query safely using validated values
// Example output: SELECT * FROM eoi ORDER BY firstName ASC;
$sql = "SELECT * FROM eoi ORDER BY $sortby $order";


/*******************************************************
 * EXECUTE SQL QUERY AND FETCH RESULTS
 *******************************************************/

$result = mysqli_query($conn, $sql);


// Display Title (shows sorting method)
echo "<h2>All EOIs (Sorted by $sortby $order)</h2>";

?>

<!-- =================================================== -->
<!--   SORTING FORM (Displayed above the results table)  -->
<!-- =================================================== -->

<form action="search_all_eoi.php" method="get">

    <!-- Sorting Column Dropdown -->
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

    <!-- Sorting Direction Dropdown -->
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
/*******************************************************
 * DISPLAY RESULTS TABLE
 *******************************************************/

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {

    // Start HTML table and output column headers
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

    // Loop through each returned row and print the data
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
    // No EOIs exist in table
    echo "<p>No EOIs found.</p>";
}


/*******************************************************
 * RETURN BUTTON + FOOTER
 *******************************************************/

echo "<br><br><a href='manage.php' class='return-link'>Return to Home</a><br><br>";

// Close database connection
mysqli_close($conn);

// Include footer HTML
include 'footer.inc';
?>
