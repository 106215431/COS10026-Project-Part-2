<?php
// -------------------------
// Prevent direct URL access
// -------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: apply.html");
    exit();
}

// -------------------------
// Database connection
// -------------------------
require_once("settings.php"); // contains $host, $user, $pwd, $sql_db

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure.</p>");
}

echo "<!DOCTYPE html><html lang='en'><head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='https://106215431.github.io/COS10026-Project-Part-2/styles/styles.css'>
<title>EOI Submission</title>
</head><body>";

// -------------------------
// Helper function to sanitize inputs
// -------------------------
function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// -------------------------
// Collect and sanitise form data
// -------------------------
$jobRef = sanitise_input($_POST['jobRef'] ?? '');
$firstName = sanitise_input($_POST['firstName'] ?? '');
$lastName = sanitise_input($_POST['lastName'] ?? '');
$dob = sanitise_input($_POST['dob'] ?? '');
$gender = sanitise_input($_POST['gender'] ?? '');
$address = sanitise_input($_POST['address'] ?? '');
$suburb = sanitise_input($_POST['suburb'] ?? '');
$state = sanitise_input($_POST['state'] ?? '');
$postcode = sanitise_input($_POST['postcode'] ?? '');
$email = sanitise_input($_POST['email'] ?? '');
$phone = sanitise_input($_POST['phone'] ?? '');
$skills = $_POST['skills'] ?? [];
$otherSkills = sanitise_input($_POST['otherSkills'] ?? '');

// -------------------------
// Server-side validation
// -------------------------
$errors = [];

// Job Ref
if (empty($jobRef)) $errors[] = "Job reference number is required.";

// Name
if (!preg_match("/^[A-Za-z]{1,20}$/", $firstName)) $errors[] = "First name must contain only letters (max 20).";
if (!preg_match("/^[A-Za-z]{1,20}$/", $lastName)) $errors[] = "Last name must contain only letters (max 20).";

// DOB validation dd/mm/yyyy
if (!preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $dob)) {
    $errors[] = "Date of birth must be in dd/mm/yyyy format.";
}

// Gender
if (!in_array($gender, ['Male', 'Female', 'Other'])) $errors[] = "Invalid gender selected.";

// Address
if (empty($address) || strlen($address) > 40) $errors[] = "Street address must be 1–40 characters.";
if (empty($suburb) || strlen($suburb) > 40) $errors[] = "Suburb/Town must be 1–40 characters.";

// State and postcode
$state_pattern = ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'];

if (empty($state)) {
    $errors[] = "State is required.";
} elseif (!in_array($state, $state_pattern)) {
    $errors[] = "Invalid state selected.";
}

if (empty($postcode)) {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}

// Check postcode–state match only if both are valid so far
if (
    !empty($state) && 
    in_array($state, $state_pattern) && 
    preg_match("/^\d{4}$/", $postcode)
) {
    $state_postcode_ranges = [
        'VIC' => ['3','8'],
        'NSW' => ['1','2'],
        'QLD' => ['4','9'],
        'NT'  => ['0'],
        'WA'  => ['6'],
        'SA'  => ['5'],
        'TAS' => ['7'],
        'ACT' => ['0']
    ];
    $first_digit = substr($postcode, 0, 1);
    if (!in_array($first_digit, $state_postcode_ranges[$state])) {
        $errors[] = "Postcode does not match the selected state.";
    }
}

// State/Postcode matching
$state_postcode_ranges = [
    'VIC' => ['3','8'],
    'NSW' => ['1','2'],
    'QLD' => ['4','9'],
    'NT'  => ['0'],
    'WA'  => ['6'],
    'SA'  => ['5'],
    'TAS' => ['7'],
    'ACT' => ['0']
];


// Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";

// Phone
if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) $errors[] = "Phone must contain 8–12 digits (numbers or spaces).";

// Skills
if (empty($skills)) $errors[] = "At least one technical skill must be selected.";
if (in_array("Other", $skills) && empty($otherSkills)) $errors[] = "Please specify your other skills.";

// -------------------------
// Stop if validation fails
// -------------------------
if (!empty($errors)) {
   echo "<div class='response-container'>
        <h2>Submission Failed</h2>
        <div class='error-message'>
            <p>We found the following issues with your submission:</p>
            <ul>";
foreach ($errors as $err) echo "<li>$err</li>";
echo "  </ul>
        </div>
        <a href='apply.html' class='return-link'>Go back and fix the form</a>
      </div>";

    exit();
}

// -------------------------
// Convert DOB to yyyy-mm-dd
// -------------------------
list($day, $month, $year) = explode("/", $dob);
$dob_mysql = "$year-$month-$day";

// -------------------------
// Create table if not exists
// -------------------------
$create_table_sql = "
CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  jobRef VARCHAR(20) NOT NULL,
  firstName VARCHAR(20) NOT NULL,
  lastName VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  streetAddress VARCHAR(40) NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode CHAR(4) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  skill1 VARCHAR(50) DEFAULT NULL,
  skill2 VARCHAR(50) DEFAULT NULL,
  skill3 VARCHAR(50) DEFAULT NULL,
  skill4 VARCHAR(50) DEFAULT NULL,
  otherSkills VARCHAR(200)
) ENGINE=InnoDB;
";

mysqli_query($conn, $create_table_sql);

// -------------------------
// Prepare skills
// -------------------------
$skill1 = $skills[0] ?? NULL;
$skill2 = $skills[1] ?? NULL;
$skill3 = $skills[2] ?? NULL;
$skill4 = $skills[3] ?? NULL;

// -------------------------
// Insert data into eoi table
// -------------------------
$insert_sql = "INSERT INTO eoi
(jobRef, firstName, lastName, dob, gender, streetAddress, suburb, state, postcode, email, phone, skill1, skill2, skill3, skill4, otherSkills)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $insert_sql);
mysqli_stmt_bind_param($stmt, 'ssssssssssssssss',
    $jobRef, $firstName, $lastName, $dob_mysql, $gender,
    $address, $suburb, $state, $postcode, $email, $phone,
    $skill1, $skill2, $skill3, $skill4, $otherSkills
);

if (mysqli_stmt_execute($stmt)) {
    $eoiNumber = mysqli_insert_id($conn);
    echo "<div class='response-container'>
        <h2>Application Submitted Successfully!</h2>
        <div class='success-message'>
            <p>Your Expression of Interest Number is: <strong>$eoiNumber</strong></p>
            <p>Thank you, <strong>$firstName $lastName</strong>, for applying for job reference <strong>$jobRef</strong>.</p>
        </div>
        <a href='index.php' class='return-link'>Return to Home</a>
      </div>";
} else {
    echo "<p>Something went wrong while submitting your application. Please try again.</p>";
}
echo "</body></html>";
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
