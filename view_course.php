<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch all courses
$sql = "SELECT cid, cname, `desc`, duration, keywords FROM course";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Courses</title>
    <link rel="stylesheet" href="mystyles.css">
    <style>
        body { font-family: "Times New Roman", Georgia, Serif; }
        h2 { font-family: "Playfair Display"; letter-spacing: 3px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        #add_course_btn{
            background-color: green;
            border: 2px solid black;
            padding: 0.5rem;
            color: white;
            text-decoration: none;
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

<h2>Course List</h2>
<table>
<tr>
    <th>Course ID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Duration</th>
    <th>Keywords</th>
    <th>Actions</th>
</tr>
<?php foreach ($courses as $course): ?>
<tr>
    <td><?= htmlspecialchars($course['cid']) ?></td>
    <td><?= htmlspecialchars($course['cname']) ?></td>
    <td><?= htmlspecialchars($course['desc']) ?></td>
    <td><?= htmlspecialchars($course['duration']) ?></td>
    <td><?= htmlspecialchars($course['keywords']) ?></td>
    <td>
        <a href="edit_course.php?cid=<?= urlencode($course['cid']) ?>">Edit</a> |
        <a href="delete_course.php?cid=<?= urlencode($course['cid']) ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
<br>
<a id="add_course_btn" href="manage_course.php">Add a New Course</a>

<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by https://www.cognizant.com/</p>
</footer>

</body>
</html>
