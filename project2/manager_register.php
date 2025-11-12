<?php
// -------------------------
// Prevent direct URL access (only via form)
// -------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manager_register_form.php");
    exit();
}

// -------------------------
// Database connection
// -------------------------
require_once("settings.php"); 
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure.</p>");
}

// -------------------------
// Helper function to sanitise inputs
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
$username = sanitise_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// -------------------------
// Server-side validation
// -------------------------
$errors = [];

// Username validation
if (empty($username)) {
    $errors[] = "Username is required.";
} elseif (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
    $errors[] = "Username must be 3–20 characters long and contain only letters, numbers, or underscores.";
}

// Password validation: at least 8 chars, 1 upper, 1 lower, 1 number
if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
    $errors[] = "Password must be at least 8 characters, include one uppercase letter, one lowercase letter, and one number.";
}

// -------------------------
// Stop if validation fails
// -------------------------
if (!empty($errors)) {
    echo "<!DOCTYPE html><html lang='en'><head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='http://localhost/COS10026-Project-Part-2/styles/styles.css'>
    <title>Registration Failed</title>
    </head><body>
    <div class='response-container'>
        <h2>Registration Failed</h2>
        <div class='error-message'>
            <p>We found the following issues:</p>
            <ul>";
    foreach ($errors as $err) echo "<li>$err</li>";
    echo "  </ul>
        </div>
        <a href='manager_register_form.html' class='return-link'>Go Back</a>
    </div>
    </body></html>";
    exit();
}

// -------------------------
// Create managers table if not exists
// -------------------------
$create_table_sql = "
CREATE TABLE IF NOT EXISTS managers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
";
mysqli_query($conn, $create_table_sql);

// -------------------------
// Check for existing username
// -------------------------
$stmt = mysqli_prepare($conn, "SELECT username FROM managers WHERE username = ?");
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "<!DOCTYPE html><html lang='en'><head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='http://localhost/COS10026-Project-Part-2/styles/styles.css'>
    <title>Registration Failed</title>
    </head><body>
    <div class='response-container'>
        <h2>Registration Failed</h2>
        <div class='error-message'>
            <p>The username <strong>$username</strong> is already taken. Please choose another.</p>
        </div>
        <a href='manager_register_form.html' class='return-link'>Go Back</a>
    </div>
    </body></html>";
    exit();
}

// -------------------------
// Insert new manager
// -------------------------
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$insert_sql = "INSERT INTO managers (username, password) VALUES (?, ?)";
$stmt_insert = mysqli_prepare($conn, $insert_sql);
mysqli_stmt_bind_param($stmt_insert, 'ss', $username, $hashedPassword);

if (mysqli_stmt_execute($stmt_insert)) {
    echo "<!DOCTYPE html><html lang='en'><head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='http://localhost/COS10026-Project-Part-2/styles/styles.css'>
    <title>Registration Successful</title>
    </head><body>
    <div class='response-container'>
        <h2>Registration Successful!</h2>
        <div class='success-message'>
            <p>Manager account <strong>$username</strong> has been successfully created.</p>
            <p>You can now <a href='manager_login.php'>log in</a> to manage.php.</p>
        </div>
        <a href='index.php' class='return-link'>Return to Home</a>
    </div>
    </body></html>";
} else {
    echo "<p>Something went wrong while saving your account. Please try again.</p>";
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt_insert);
mysqli_close($conn);
?>
