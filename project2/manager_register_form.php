<<<<<<< HEAD
<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>
=======
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manager Registration</title>
<link rel='stylesheet' href='../styles/styles.css'>
</head>
>>>>>>> 212e315a7b067e61f12e7807763f01f3c39984a4
<body>

<h1 class="formname">Manager Registration</h1>

<form action="manager_register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <div class="submit-btn">
        <button type="submit">Register</button>
    </div>
</form>

</body>
</html>
