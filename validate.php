<?php
session_start(); 
require 'dbcon.php';
// Code for login 
if(isset($_POST['login']))
{
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = :uname and password = :pass";    
    $statement = $pdo->prepare($sql);
    $statement->execute([':uname' => $user, ':pass' => $pass]);
    $user    = $statement->fetch();

    if($user) {
        $_SESSION['userid']=$user['id'];
        $_SESSION['roleid']=$user['roleid'];
        header('Location: index.php');
    } else {
        header('Location: login.php?error=1');
    }
}
?>