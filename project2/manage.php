<?php include 'header.inc'; ?>
<?php include 'manage-nav.inc'; ?>
<?php require_once("manage_settings.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage EOIs</title>
</head>
<body>

<h1>EOI Management Panel</h1>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: manager_login_form.php");
    exit();
}
?>

<!-- Helper: Sort Options (reuse this chunk across forms) -->
<?php function sort_controls() { ?>
  <fieldset class="sort">
    <legend>Sort options</legend>
    <div class="inline">
      <label for="sort_by">Field:</label>
      <select name="sort_by" id="sort_by">
        <option value="created">Created Date</option>
        <option value="eoinumber">EOI Number</option>
        <option value="jobref">Job Reference</option>
        <option value="firstname">First Name</option>
        <option value="lastname">Last Name</option>
        <option value="email">Email</option>
        <option value="status">Status</option>
      </select>

      <label for="sort_dir">Order:</label>
      <select name="sort_dir" id="sort_dir">
        <option value="ASC">Ascending (A→Z / Oldest)</option>
        <option value="DESC">Descending (Z→A / Newest)</option>
      </select>
    </div>
  </fieldset>
<?php } ?>

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
  <form action="manage_delete_job.php" method="post"
        onsubmit="return confirm('Are you sure you want to delete ALL EOIs for this job reference?');">
    <label>Job Reference:</label><br>
    <input type="text" name="jobRef" required>
    <br><br>
    <button type="submit">Delete EOIs</button>
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
