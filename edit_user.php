<?php
session_start();
require 'dbcon.php';

// Optional: restrict access to admins only
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch all users for dropdown
$userListStmt = $pdo->prepare("SELECT id, username FROM user");
$userListStmt->execute();
$userList = $userListStmt->fetchAll();

// Fetch roles for dropdown
$roleListStmt = $pdo->prepare("SELECT rid, rname FROM role");
$roleListStmt->execute();
$roleList = $roleListStmt->fetchAll();

// Handle user selection
$selectedUser = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $userStmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
    $userStmt->execute([':id' => $id]);
    $selectedUser = $userStmt->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $roleid = $_POST['roleid'];

    $updateStmt = $pdo->prepare("
        UPDATE user 
        SET name = :name, username = :username, email = :email, roleid = :roleid 
        WHERE id = :id
    ");
    $updateStmt->execute([
        ':name' => $name,
        ':username' => $username,
        ':email' => $email,
        ':roleid' => $roleid,
        ':id' => $id
    ]);

    echo "<p style='color:green;'>User updated successfully.</p>";

    // Refresh selected user
    $userStmt->execute([':id' => $id]);
    $selectedUser = $userStmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
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
    <br>
    <br>

    <h2>Edit User Details</h2>

    <form method="get">
        <label for="id">Select User:</label>
        <select name="id" id="id" onchange="this.form.submit()">
            <option value="">-- Choose a user --</option>
            <?php foreach ($userList as $user): ?>
                <option value="<?= $user['id'] ?>" <?= isset($_GET['id']) && $_GET['id'] == $user['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selectedUser): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($selectedUser['id']) ?>">
        <p><strong>User ID:</strong> <?= htmlspecialchars($selectedUser['id']) ?></p>
        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($selectedUser['name']) ?>" required>
        </p>
        <p>
            <label>Username:</label><br>
            <input type="text" name="username" value="<?= htmlspecialchars($selectedUser['username']) ?>" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($selectedUser['email']) ?>" required>
        </p>
        <p>
            <label>Role:</label><br>
            <select name="roleid" required>
                <?php foreach ($roleList as $role): ?>
                    <option value="<?= $role['rid'] ?>" <?= $selectedUser['roleid'] == $role['rid'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role['rname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <button type="submit" name="update">Update User</button>
    </form>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="w3-center w3-light-grey w3-padding-32">
    <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
    </footer>
</body>
</html>
