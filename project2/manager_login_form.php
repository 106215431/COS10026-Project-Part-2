<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>
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
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
</form>
<?php include 'footer.inc'; ?>
</body>
</html>
