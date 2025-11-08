<?php include 'header.inc'; ?>
<?php include 'manage-nav.inc'; ?>
<?php require_once("manage_settings.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage EOIs</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    .section-box {
        border: 1px solid #ccc;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        background: #f9f9f9;
    }
    .section-box h2 {
        margin-top: 0;
    }
    label { font-weight: bold; }
    input[type=text], select {
        padding: 8px;
        width: 250px;
    }
    button {
        padding: 8px 14px;
        cursor: pointer;
        background: #007bff;
        border: none;
        color: #fff;
        border-radius: 5px;
    }
    button:hover { background: #0056b3; }
</style>
</head>

<body>

<h1>EOI Management Panel</h1>

<!-- ----------------------------------------- -->
<!-- ✅ 1. LIST ALL EOIs -->
<!-- ----------------------------------------- -->
<div class="section-box">
    <h2>List ALL EOIs</h2>
    <form action="manage_search_all_eoi.php" method="get">
        <button type="submit">Show All EOIs</button>
    </form>
</div>


<!-- ----------------------------------------- -->
<!-- ✅ 2. LIST EOIs BY JOB REFERENCE -->
<!-- ----------------------------------------- -->
<div class="section-box">
    <h2>List EOIs by Job Reference</h2>
    <form action="manage_search_job.php" method="get">
        <label>Job Reference:</label><br>
        <input type="text" name="jobRef" required>
        <br><br>
        <button type="submit">Search</button>
    </form>
</div>


<!-- ----------------------------------------- -->
<!-- ✅ 3. SEARCH EOIs BY NAME -->
<!-- ----------------------------------------- -->
<div class="section-box">
    <h2>Search EOIs by Applicant Name</h2>
    <form action="manage_search_name.php" method="get">
        <label>Search Category:</label><br>
        <select name="category" required>
            <option value="firstname">First Name</option>
            <option value="lastname">Last Name</option>
            <option value="fullname">Full Name</option>
        </select>
        <br><br>

        <label>Enter Name:</label><br>
        <input type="text" name="value" required>
        <br><br>

        <button type="submit">Search</button>
    </form>
</div>


<!-- ----------------------------------------- -->
<!-- ✅ 4. DELETE EOIs BY JOB REFERENCE -->
<!-- ----------------------------------------- -->
<div class="section-box">
    <h2>Delete EOIs with a Job Reference</h2>
    <form action="manage_delete_job.php" method="post">
        <label>Job Reference:</label><br>
        <input type="text" name="jobRef" required>
        <br><br>
        <button type="submit" onclick="return confirm('Are you sure you want to delete ALL EOIs for this job reference?');">
            Delete EOIs
        </button>
    </form>
</div>


<!-- ----------------------------------------- -->
<!-- ✅ 5. CHANGE STATUS OF AN EOI -->
<!-- ----------------------------------------- -->
<div class="section-box">
    <h2>Change Status of an EOI</h2>
    <form action="manage_update_status.php" method="post">

        <label>EOI Number:</label><br>
        <input type="text" name="eoinumber" required>
        <br><br>

        <label>New Status:</label><br>
        <select name="status" required>
            <option value="New">New</option>
            <option value="Current">Current</option>
            <option value="Final">Final</option>
        </select>
        <br><br>

        <button type="submit">Update Status</button>
    </form>
</div>

</body>
</html>

<?php include 'footer.inc'; ?>
