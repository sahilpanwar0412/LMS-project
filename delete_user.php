<?php
session_start();
require 'dbcon.php';

// Optional: restrict access to admins only
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch all users for dropdown
$userListStmt = $pdo->prepare("SELECT id, username FROM user");
$userListStmt->execute();
$userList = $userListStmt->fetchAll();

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Delete user from user table
    $deleteStmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
    $deleteStmt->execute([':id' => $id]);

    // Optionally delete related entries from user_course_status
    $cleanupStmt = $pdo->prepare("DELETE FROM user_course_status WHERE uid = :id");
    $cleanupStmt->execute([':id' => $id]);

    echo "<p style='color: red;'>User deleted successfully.</p>";

    // Refresh user list
    $userListStmt->execute();
    $userList = $userListStmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <link rel="stylesheet" href="mystyles.css">
    <style>
        body {font-family: "Times New Roman", Georgia, Serif;}
        h1, h2, h3, h4, h5, h6 {
        font-family: "Playfair Display";
        letter-spacing: 5px;
        }
    </style>
</head>
<body>
            <!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar w3-white w3-padding w3-card" style="letter-spacing:4px;">
  <a href="#home" class="w3-bar-item w3-button">ImproveMe LMS</a>
    
    <!-- Right-sided navbar links. Hide them on small screens -->
  <div class="w3-right w3-hide-small">
    <!-- Admin role -->
  <?php if(isset($_SESSION['userid']) && $_SESSION['roleid'] == 1) { ?>
  <a href="view_user.php" class="w3-bar-item w3-button">Manage User</a>
  <a href="view_course.php" class="w3-bar-item w3-button">Manage Course</a>
  <a href="reporting.php" class="w3-bar-item w3-button">Report</a>

  <!-- Intructor role -->
  <?php } elseif(isset($_SESSION['userid']) && $_SESSION['roleid'] == 2) { ?>
  <a href="view_course.php" class="w3-bar-item w3-button">Manage Course</a>
  <a href="reporting.php" class="w3-bar-item w3-button">Report</a>

  <!-- Student role -->
  <?php } elseif(isset($_SESSION['userid']) && $_SESSION['roleid'] == 3) { ?>
    <a href="getCourse.php" class="w3-bar-item w3-button">View Courses</a>
  <?php } ?>
  <?php if(isset($_SESSION['userid'])) { ?>
  <a href="logout.php" class="w3-bar-item w3-button">Logout</a>
  <?php } ?>
  </div>
</div>
        <br>
        <br>
    <h2>Delete a User</h2>

    <form method="post">
        <label for="id">Select User to Delete:</label>
        <select name="id" id="id" required>
            <option value="">-- Choose a user --</option>
            <?php foreach ($userList as $user): ?>
                <option value="<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
    </form>

    <!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>
</body>
</html>
