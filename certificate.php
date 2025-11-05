<?php
session_start();
require 'dbcon.php';
 
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}
 
if (!isset($_GET['cid'])) {
    echo "Course ID not provided.";
    exit;
}
 
$cid = $_GET['cid'];
$uid = $_SESSION['userid'];
 
// Fetch user name
$userStmt = $pdo->prepare("SELECT name FROM user WHERE id = :id");
$userStmt->execute([':id' => $uid]);
$user = $userStmt->fetch();
 
if (!$user) {
    echo "User not found.";
    exit;
}
 
// Fetch course name
$courseStmt = $pdo->prepare("SELECT cname FROM course WHERE cid = :cid");
$courseStmt->execute([':cid' => $cid]);
$course = $courseStmt->fetch();
 
if (!$course) {
    echo "Course not found.";
    exit;
}
 
$userName = htmlspecialchars($user['name']);
$courseName = htmlspecialchars($course['cname']);
$completionDate = date("F j, Y");
 
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f9f9f9;
            text-align: center;
            padding: 50px;
        }
        .certificate {
            border: 10px solid rgb(165, 0, 254);
            padding: 50px;
            background-color: #fff;
            display: inline-block;
            width: 80%;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 0;
        }
        h2 {
            font-size: 32px;
            margin-top: 10px;
        }
        p {
            font-size: 20px;
        }
        .date {
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This is to certify that</p>
        <h2><?php echo $userName; ?></h2>
        <p>has successfully completed the course</p>
        <h2><?php echo $courseName; ?></h2>
        <p class="date">Date: <?php echo $completionDate; ?></p>
    </div>
</body>
</html>
 