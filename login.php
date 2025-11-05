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
  </div>
</div>

<!-- Header -->
<header class="w3-display-container w3-content w3-wide" style="max-width:1600px;min-width:500px" id="home">

</header>

<!-- Page content -->
<div class="w3-content" style="max-width:1100px">
  <!-- About Section  -->
  <div class="w3-row w3-padding-64" id="about">
	</br>
	<form action="validate.php" method="post"> 
    <?php if(isset($_GET['error'])) { ?>
    <span style="color:red">Invalid User</span> </br>
    <?php } ?>                                          
		<label for="inputEmail">Username</label>
		<input class="form-control" name="username" type="text" placeholder="Username"  required/></br></br>
    <label for="inputPassword">Password</label>
		<input class="form-control" name="password" type="password" placeholder="Password" required /></br></br>
		<button class="btn btn-primary" name="login" type="submit">Login</button>
	</form>
  </div>
<!-- End page content -->
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>

</body>
</html>
