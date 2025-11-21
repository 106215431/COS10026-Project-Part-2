<?php
// ---------------------------------------------------------------------
// login_manager.php with lockout system
// ---------------------------------------------------------------------

require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure.</p>");
}

session_start();

// Lockout settings
$max_attempts = 3;
$lockout_time = 300; // 5 minutes

// Init session values
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_until'])) {
    $_SESSION['lockout_until'] = 0;
}

// Check lockout
if (time() < $_SESSION['lockout_until']) {
    $remaining = $_SESSION['lockout_until'] - time();
    die("<!DOCTYPE html><html lang='en'><head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='../styles/styles.css'>
    <title>Login Failed</title>
    </head><body>
    <div class='response-container'>
        <h2>Login Failed</h2>
        <div class='error-message'>
        <p>Manager's website has been disabled.</p>
            <p>You must wait {$remaining} seconds before trying again.</p>
        </div>
        <a href='index.php' class='return-link'>Go Back to Website</a>
    </div>
    </body></html>");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: manager_login_form.php");
    exit();
}

function sanitise_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$username = sanitise_input($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$stmt = mysqli_prepare($conn, "SELECT password FROM managers WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $hashedPassword);
    mysqli_stmt_fetch($stmt);

    if (password_verify($password, $hashedPassword)) {
        // Successful login, reset attempts
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_until'] = 0;
        $_SESSION['username'] = $username;

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
        exit();

    } else {
        // Wrong password, increase attempts
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $_SESSION['lockout_until'] = time() + $lockout_time;
            die("<!DOCTYPE html><html lang='en'><head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='../styles/styles.css'>
    <title>Login Failed</title>
    </head><body>
    <div class='response-container'>
        <h2>Login Failed</h2>
        <div class='error-message'>
            <p>Too many attempts. Login page has been deactivated to 5 minutes.</p>
        </div>
        <a href='index.php' class='return-link'>Go Back to Website</a>
    </div>
    </body></html>");
        }

        echo "<!DOCTYPE html><html lang='en'><head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../styles/styles.css'>
        <title>Login Failed</title>
        </head><body>
        <div class='response-container'>
            <h2>Login Failed</h2>
            <div class='error-message'>
                <p>Incorrect password. Attempt {$_SESSION['login_attempts']} of {$max_attempts}.</p>
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
