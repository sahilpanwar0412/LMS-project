<?php 
session_start(); 
require 'dbcon.php';

if(isset($_SESSION['userid'])) {
    $sql = "SELECT * FROM user WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute([':id' => $_SESSION['userid']]);
    $user    = $statement->fetch();
    $name = $user['name'];
} else {
  header('Location: login.php');
}


// get the courses from the database -> get all the courses for now
$stmt = $pdo->prepare("SELECT * FROM course");
$stmt->execute();
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Improve Me LMS</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
    <div class="w3-right w3-hide-small">
      <a href="index.php" class="w3-bar-item w3-button">Home</a>
      <a href="logout.php" class="w3-bar-item w3-button">Logout</a>
    </div> 
  </div>
</div>

<!-- Header -->
<header class="w3-display-container w3-content w3-wide" style="max-width:1600px;min-width:500px" id="home"></header>

<!-- Page content -->
<div class="w3-content" style="max-width:1100px">
  <!-- About Section  -->
  <div class="w3-row w3-padding-64" id="about">		
    <?php if(isset($_SESSION['userid'])) { ?>    
    <p>Hi <?= $name ?>, Welcome!</p>
    <?php } else { ?>
    <p>Login <a href="login.php">Here</a></p>
    <?php } ?>  

    <!-- course data -->
    <h2>Available Courses</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>Course ID</th><th>Course Name</th><th>View Details</th></tr>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['cid']); ?></td>
                <td><?php echo htmlspecialchars($course['cname']); ?></td>
                <td><a href="course_details.php?cid=<?= urlencode($course['cid']) ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
  </div>
<!-- End page content -->

  
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>

</body>
</html>
