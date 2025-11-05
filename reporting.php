<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['userid'];

// Check if the user is an instructor (roleid = 2)
$roleCheck = $pdo->prepare("SELECT roleid, name FROM user WHERE id = :id");
$roleCheck->execute([':id' => $uid]);
$userData = $roleCheck->fetch();


$instructorName = $userData['name'];

// fetching the reporting data
$sql = "
    SELECT 
        ucs.uid,
        ucs.cid,
        c.cname AS course_name,
        u.username,
        ucs.completed
    FROM user_course_status ucs
    JOIN user u ON ucs.uid = u.id
    JOIN course c ON ucs.cid = c.cid
    ORDER BY ucs.cid, ucs.uid
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$report = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Completion Report</title>
    <link rel="stylesheet" href="mystyles.css">
    <style>
        body {font-family: "Times New Roman", Georgia, Serif;}
        h1, h2, h3, h4, h5, h6 {
        font-family: "Playfair Display";
        letter-spacing: 5px;
        }

        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { color: #333; }
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
</div>
            <br>
            <br>
    <h2>Course Completion Report</h2>
    <?php if(isset($_SESSION['userid']) && $_SESSION['roleid'] == 2) { ?>
    <p>Instructor: <strong><?= htmlspecialchars($instructorName) ?></strong></p>
    <?php } ?>
    <table>
        <tr>
            <th>User ID</th>
            <th>Course ID</th>
            <th>Username</th>
            <th>Course Name</th>
            <th>Completed</th>
        </tr>
        <?php foreach ($report as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['uid']) ?></td>
            <td><?= htmlspecialchars($row['cid']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['course_name']) ?></td>
            <td><?= $row['completed'] ? 'Completed' : 'In progress' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
