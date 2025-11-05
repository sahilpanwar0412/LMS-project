<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch all courses for dropdown
$courseListStmt = $pdo->prepare("SELECT cid, cname FROM course");
$courseListStmt->execute();
$courseList = $courseListStmt->fetchAll();

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $cid = $_POST['cid'];

    // Delete course from course table
    $deleteStmt = $pdo->prepare("DELETE FROM course WHERE cid = :cid");
    $deleteStmt->execute([':cid' => $cid]);

    // Optionally delete related entries from user_course_status
    $cleanupStmt = $pdo->prepare("DELETE FROM user_course_status WHERE cid = :cid");
    $cleanupStmt->execute([':cid' => $cid]);

    echo "<p style='color: red;'>Course deleted successfully.</p>";

    // Refresh course list
    $courseListStmt->execute();
    $courseList = $courseListStmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Course</title>
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
    <br><br>

    <h2>Delete a Course</h2>

    <form method="post">
        <label for="cid">Select Course to Delete:</label>
        <select name="cid" id="cid" required>
            <option value="">Choose a course</option>
            <?php foreach ($courseList as $course): ?>
                <option value="<?= $course['cid'] ?>">
                    <?= htmlspecialchars($course['cname']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this course?')">Delete Course</button>
    </form>

    <!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>
</body>
</html>
