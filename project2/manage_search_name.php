<?php include 'header.inc'; ?>
<?php
require_once("manage_settings.php");   // Include database connection settings

// Read the selected category from the dropdown (firstname, lastname, fullname)
$category = $_GET['category'] ?? '';

// Read the text the user entered and trim spaces
$value = trim($_GET['value'] ?? '');

// If input is empty, stop the script
if ($value === '') {
    die("Please enter a name to search.");
}

/*
----------------------------------------------------
 Search by FIRST NAME
----------------------------------------------------
*/
if ($category === "firstname") {

    // SQL query using a placeholder ?
    $sql = "SELECT * FROM eoi WHERE firstName LIKE ?";
    
    // Prepare the SQL query
    $stmt = mysqli_prepare($conn, $sql);

    // Add % for partial matching
    $val = "%$value%";

    // Bind the value to the placeholder (s = string)
    mysqli_stmt_bind_param($stmt, "s", $val);
}

/*
----------------------------------------------------
 Search by LAST NAME
----------------------------------------------------
*/
elseif ($category === "lastname") {

    $sql = "SELECT * FROM eoi WHERE lastName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);

    $val = "%$value%";
    mysqli_stmt_bind_param($stmt, "s", $val);
}

/*
----------------------------------------------------
 Search by FULL NAME (first + last)
----------------------------------------------------
*/
elseif ($category === "fullname") {

    // Split the full name into separate words
    $parts = preg_split('/\s+/', $value);

    // If there is not at least 2 words, stop
    if (count($parts) < 2) {
        die("Please enter FULL NAME (first & last).");
    }

    // First word = first name, last word = last name
    $firstname = $parts[0];
    $lastname  = $parts[count($parts)-1];

    // SQL with two placeholders
    $sql = "SELECT * FROM eoi WHERE firstName LIKE ? AND lastName LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Add % for LIKE matching
    $fn = "%$firstname%";
    $ln = "%$lastname%";

    // Bind both values (ss = two strings)
    mysqli_stmt_bind_param($stmt, "ss", $fn, $ln);
}

/*
----------------------------------------------------
 Invalid category
----------------------------------------------------
*/
else {
    die("Invalid category.");
}

/*
----------------------------------------------------
 Execute prepared query and get results
----------------------------------------------------
*/
mysqli_stmt_execute($stmt);               // Run the SQL query
$result = mysqli_stmt_get_result($stmt);  // Fetch the result set

/*
----------------------------------------------------
 Display results in a table
----------------------------------------------------
*/
if (mysqli_num_rows($result) > 0) {

echo "<table border='1' cellpadding='5'>";
echo "<tr>
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

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['EOInumber']}</td>";
    echo "<td>{$row['jobRef']}</td>";
    echo "<td>{$row['firstName']}</td>";
    echo "<td>{$row['lastName']}</td>";
    echo "<td>{$row['dob']}</td>";
    echo "<td>{$row['gender']}</td>";
    echo "<td>{$row['streetAddress']}</td>";
    echo "<td>{$row['suburb']}</td>";
    echo "<td>{$row['state']}</td>";
    echo "<td>{$row['postcode']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['phone']}</td>";
    echo "<td>{$row['skill1']}</td>";
    echo "<td>{$row['skill2']}</td>";
    echo "<td>{$row['skill3']}</td>";
    echo "<td>{$row['skill4']}</td>";
    echo "<td>{$row['otherSkills']}</td>";
    echo "<td>{$row['status']}</td>";
    echo "</tr>";
}
echo "</table>";

} 
else {
    echo "No EOIs found matching your search.";
}

/*
----------------------------------------------------
 ADD RETURN BUTTON
----------------------------------------------------
*/
echo "<br><br><a href='manage.php' class='return-link'>Return to Home</a>";

mysqli_close($conn);   // Close the database connection
?>
