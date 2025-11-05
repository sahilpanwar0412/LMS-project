<?php
session_start();
require 'dbcon.php';

// Optional: restrict access to admins or instructors
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch all courses for dropdown
$courseListStmt = $pdo->prepare("SELECT cid, cname FROM course");
$courseListStmt->execute();
$courseList = $courseListStmt->fetchAll();

// Handle course selection
$selectedCourse = null;
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    $courseStmt = $pdo->prepare("SELECT * FROM course WHERE cid = :cid");
    $courseStmt->execute([':cid' => $cid]);
    $selectedCourse = $courseStmt->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $cid = $_POST['cid'];
    $cname = $_POST['cname'];
    $desc = $_POST['desc'];
    $duration = $_POST['duration'];
    $keywords = $_POST['keywords'];

    $updateStmt = $pdo->prepare("
        UPDATE course 
        SET cname = :cname, `desc` = :desc, duration = :duration, keywords = :keywords 
        WHERE cid = :cid
    ");
    $updateStmt->execute([
        ':cname' => $cname,
        ':desc' => $desc,
        ':duration' => $duration,
        ':keywords' => $keywords,
        ':cid' => $cid
    ]);

    echo "<p style='color:green;'>Course updated successfully.</p>";
    // Refresh selected course
    $courseStmt = $pdo->prepare("SELECT * FROM course WHERE cid = :cid");
    $courseStmt->execute([':cid' => $cid]);
    $selectedCourse = $courseStmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
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

    <h2>Edit Course Details</h2>

    <form method="get">
        <label for="cid">Select Course:</label>
        <select name="cid" id="cid" onchange="this.form.submit()">
            <option value="">-- Choose a course --</option>
            <?php foreach ($courseList as $course): ?>
                <option value="<?= $course['cid'] ?>" <?= isset($_GET['cid']) && $_GET['cid'] == $course['cid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($course['cname']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selectedCourse): ?>
    <form method="post">
        <input type="hidden" name="cid" value="<?= htmlspecialchars($selectedCourse['cid']) ?>">
        <p><strong>Course ID:</strong> <?= htmlspecialchars($selectedCourse['cid']) ?></p>
        <p>
            <label>Name:</label><br>
            <input type="text" name="cname" value="<?= htmlspecialchars($selectedCourse['cname']) ?>" required>
        </p>
        <p>
            <label>Description:</label><br>
            <textarea name="desc" required><?= htmlspecialchars($selectedCourse['desc']) ?></textarea>
        </p>
        <p>
            <label>Duration:</label><br>
            <input type="text" name="duration" value="<?= htmlspecialchars($selectedCourse['duration']) ?>" required>
        </p>
        <p>
            <label>Keywords:</label><br>
            <input type="text" name="keywords" value="<?= htmlspecialchars($selectedCourse['keywords']) ?>" required>
        </p>
        <button type="submit" name="update">Update Course</button>
    </form>
    <?php endif; ?>
    <!-- Footer -->
    <footer class="w3-center w3-light-grey w3-padding-32">
    <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
    </footer>
</body>
</html>
