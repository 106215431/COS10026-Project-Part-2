<?php
// ---------------------------------------------------------------------
// login_manager.php
// Validates and authenticates manager login credentials
// ---------------------------------------------------------------------

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure.</p>");
}

// Start session for login persistence
session_start();

// ---------------------------------------------------------------------
// Prevent direct URL access (only via POST form submission)
// ---------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: manager_login_form.php");
    exit();
}

// ---------------------------------------------------------------------
// Helper: sanitise input
// ---------------------------------------------------------------------
function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ---------------------------------------------------------------------
// Collect and sanitise data
// ---------------------------------------------------------------------
$username = sanitise_input($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$errors = [];




// ---------------------------------------------------------------------
// Verify username and password
// ---------------------------------------------------------------------
$stmt = mysqli_prepare($conn, "SELECT password FROM managers WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $hashedPassword);
    mysqli_stmt_fetch($stmt);

    if (password_verify($password, $hashedPassword)) {
        // Correct password — set session and show success
        $_SESSION["username"] = $username;

        echo "<!DOCTYPE html><html lang='en'><head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../styles/styles.css'>
        <title>Login Successful</title>
        </head><body>
        <div class='response-container'>
            <h2>Login Successful</h2>
            <div class='success-message'>
                <p>Welcome back, <strong>$username</strong>!</p>
                <p>You can now access the <a href='manage.php'>Manager Dashboard</a>.</p>
            </div>
        </div>
        </body></html>";
    } else {
        // Invalid password
        echo "<!DOCTYPE html><html lang='en'><head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../styles/styles.css'>
        <title>Login Failed</title>
        </head><body>
        <div class='response-container'>
            <h2>Login Failed</h2>
            <div class='error-message'>
                <p>Incorrect password. Please try again.</p>
            </div>
            <a href='manager_login_form.php' class='return-link'>Go Back</a>
        </div>
        </body></html>";
    }
} else {
    // Username not found
    echo "<!DOCTYPE html><html lang='en'><head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='../styles/styles.css'>
    <title>Login Failed</title>
    </head><body>
    <div class='response-container'>
        <h2>Login Failed</h2>
        <div class='error-message'>
            <p>The username <strong>$username</strong> does not exist.</p>
        </div>
        <a href='manager_login_form.php' class='return-link'>Go Back</a>
    </div>
    </body></html>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
