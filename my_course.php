<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['userid'];

// Fetch enrolled courses for the user
$sql = "
    SELECT c.cid, c.cname, c.desc, c.duration, c.keywords, ucs.completed
    FROM user_course_status ucs
    JOIN course c ON ucs.cid = c.cid
    WHERE ucs.uid = :uid
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':uid' => $uid]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Enrolled Courses</title>
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

        .nav{
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <!-- Navbar (sit on top) -->
    <div class="w3-top">
    <div class="w3-bar w3-white w3-padding w3-card nav" style="letter-spacing:4px;">
        <div id='logo'></div>
        <a href="#home" class="w3-bar-item w3-button">Improve Me LMS</a>
        
        <!-- Student role -->
        <a href="my_course.php" class="w3-bar-item w3-button">My Courses</a>
        <a href="getCourse.php" class="w3-bar-item w3-button">Enroll Me</a>

        <?php if(isset($_SESSION['userid'])) { ?>
        <a href="logout.php" class="w3-bar-item w3-button">Logout</a>
        <?php } ?>
        </div> 
    </div>
    </div>
    <br><br><br>

    <h2>My Enrolled Courses</h2>

    <?php if (count($courses) > 0): ?>
    <table>
        <tr>
            <th>Course ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Duration</th>
            <th>Keywords</th>
            <th>Status</th>
        </tr>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= htmlspecialchars($course['cid']) ?></td>
            <td><?= htmlspecialchars($course['cname']) ?></td>
            <td><?= htmlspecialchars($course['desc']) ?></td>
            <td><?= htmlspecialchars($course['duration']) ?></td>
            <td><?= htmlspecialchars($course['keywords']) ?></td>
            <td><?= $course['completed'] ? 'Completed' : 'Not Completed' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>You are not enrolled in any courses yet.</p>
    <?php endif; ?>
</body>
</html>
