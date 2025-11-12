<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manager Login</title>
<link rel='stylesheet' href='http://localhost/COS10026-Project-Part-2/styles/styles.css'>
</head>
<body>

<h1 class="formname">Manager Login</h1>

<form action="manager_login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <div class="submit-btn">
        <button type="submit">Login</button>
    </div>
</form>

</body>
</html>
