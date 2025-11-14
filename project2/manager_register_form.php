<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>
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
