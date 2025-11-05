<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['cid'])) {
    echo "No course selected.";
    exit;
}

$uid = $_SESSION['userid'];
$cid = $_GET['cid'];

// Fetch course details
$stmt = $pdo->prepare("SELECT * FROM course WHERE cid = :cid");
$stmt->execute([':cid' => $cid]);
$course = $stmt->fetch();

if (!$course) {
    echo "Course not found.";
    exit;
}

// Check enrollment status
$checkStmt = $pdo->prepare("SELECT * FROM user_course_status WHERE uid = :uid AND cid = :cid");
$checkStmt->execute([':uid' => $uid, ':cid' => $cid]);
$enrollment = $checkStmt->fetch();

$enrolled = $enrollment ? true : false;
$completed = $enrollment ? $enrollment['completed'] : 0;

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if (!$enrolled) {
        $enrollStmt = $pdo->prepare("INSERT INTO user_course_status (uid, cid, completed) VALUES (:uid, :cid, 0)");
        $enrollStmt->execute([':uid' => $uid, ':cid' => $cid]);
        $enrolled = true;
        $completed = 0;
    }
}

// Handle mark as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_completed'])) {
    if ($enrolled && !$completed) {
        $updateStmt = $pdo->prepare("UPDATE user_course_status SET completed = 1 WHERE uid = :uid AND cid = :cid");
        $updateStmt->execute([':uid' => $uid, ':cid' => $cid]);
        $completed = 1;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
    <link rel="stylesheet" href="mystyles.css">
    <style>
body {font-family: "Times New Roman", Georgia, Serif;}
h1, h2, h3, h4, h5, h6 {
  font-family: "Playfair Display";
  letter-spacing: 5px;
}
button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 10px auto;
    display: block;
}

button:hover {
    background-color: #45a049;
}

.certificate-link {
    text-align: center;
    margin-top: 10px;
    font-weight: bold;
    color: green;
}
.wrapper{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
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
</div>
<br><br><br>
<div class="wrapper">


<h2>Course Details</h2>
<table style="border-collapse: collapse; width: 70%; margin: 0 auto 20px auto; background-color: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <tr>
        <th style="text-align: left; padding: 12px; background-color: #f2f2f2;">Field</th>
        <th style="text-align: left; padding: 12px; background-color: #f2f2f2;">Value</th>
    </tr>
    <tr><td style="padding: 10px;">Course ID</td><td style="padding: 10px;"><?= htmlspecialchars($course['cid']) ?></td></tr>
    <tr><td style="padding: 10px;">Name</td><td style="padding: 10px;"><?= htmlspecialchars($course['cname']) ?></td></tr>
    <tr><td style="padding: 10px;">Description</td><td style="padding: 10px;"><?= htmlspecialchars($course['desc']) ?></td></tr>
    <tr><td style="padding: 10px;">Duration</td><td style="padding: 10px;"><?= htmlspecialchars($course['duration']) ?></td></tr>
    <tr><td style="padding: 10px;">Keywords</td><td style="padding: 10px;"><?= htmlspecialchars($course['keywords']) ?></td></tr>
    <tr><td style="padding: 10px;">Enrollment Status</td><td style="padding: 10px;"><?= $enrolled ? 'Enrolled' : 'Not Enrolled' ?></td></tr>
    <tr><td style="padding: 10px;">Completion Status</td><td style="padding: 10px;"><?= $completed ? 'Completed' : 'Not Completed' ?></td></tr>
</table>


    <?php if (!$enrolled): ?>
        <form method="post">
            <button type="submit" name="enroll">Enroll</button>
        </form>
    <?php elseif (!$completed): ?>
        <form method="post">
            <button type="submit" name="mark_completed">Mark as Completed</button>
        </form>
    <?php else: ?>
        <p style="color: green;"><strong>You have completed this course.</strong></p>
        <?php $checkStatus = $pdo->prepare("SELECT completed FROM user_course_status WHERE uid = :uid AND cid = :cid");
            $checkStatus->execute([':uid' => $uid, ':cid' => $cid]);
            $statusRow = $checkStatus->fetch();
 
            if ($statusRow && $statusRow['completed'] === 1):
                echo "<p>
                        <a href='certificate.php?cid=" . $course['cid'] . "' class='w3-bar-item w3-button' >View Certificate</a>
                      </p>";?>
            <?php endif; ?>
    <?php endif; ?>

    <p><a href="getCourse.php">Back to Courses</a></p>
    </div>
</body>
</html>
