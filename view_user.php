<?php
session_start();
require 'dbcon.php';

// Optional: restrict access to admins or instructors
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch all users except password
$sql = "SELECT id, name, username, email, roleid FROM user";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
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

        #add_user_btn{
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

    <!-- User details table -->
    <h2>User Details</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php
            $roleNames = [
                1 => 'Admin',
                2 => 'Instructor',
                3 => 'Student'
            ];
            ?>

<?php foreach ($users as $user): ?>
<tr>
    <td><?= htmlspecialchars($user['id']) ?></td>
    <td><?= htmlspecialchars($user['name']) ?></td>
    <td><?= htmlspecialchars($user['username']) ?></td>
    <td><?= htmlspecialchars($user['email']) ?></td>
    <td><?= $roleNames[$user['roleid']] ?? 'Unknown' ?></td>
    <td>
        <a href="edit_user.php?id=<?= urlencode($user['id']) ?>">Edit</a> |
        <a href="delete_user.php?id=<?= urlencode($user['id']) ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

    </table>
    <br>
    <a id="add_user_btn" href="manage_user.php">Add a New User</a>
    <br>

    <!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>
</body>
</html>
