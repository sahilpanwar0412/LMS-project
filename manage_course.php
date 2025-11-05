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

    
    // Add Course
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
             $cname = $_POST['cname'];
             $desc = $_POST['desc'];
             $duration = ($_POST['duration']);
             $keywords = $_POST['keywords'];
        

            $stmt = $pdo->prepare("INSERT INTO course(`cname`, `desc`, `duration`, `keywords`) VALUES (?, ?, ?, ?);");
            $stmt->execute([$cname, $desc, $duration, $keywords]);
        
            echo "<h3>Course added successfully!</h3>";
    }

?>
<!DOCTYPE html>
<html>
<head>
<title>ImproveMe LMS</title>
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
  </div>

  <!-- Add user section -->
    <h2>Add a New Course</h2>
    <form action="manage_course.php" method="POST">

         <label>Course name:</label><br>
         <input type="text" name="cname" required><br><br>

         <label>Description:</label><br>
         <input type="text" name="desc" required><br><br>

         <label>Duration:</label><br>
         <input type="text" name="duration" required><br><br>

         <label>Keywords:</label><br>
         <input type="text" name="keywords" required><xbr><br>

         <input type="submit" value="Add Course">
    </form>

<!-- End page content -->
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>

</body>
</html>
