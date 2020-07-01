<?php

include '../db/config.php';

if(isset($_POST['login'])){
	$user = $_POST['username'];
	$password = $_POST['password'];

	$query = "SELECT * FROM hrms_accounts WHERE username = '$user' AND user_id = '$password'";
	$account = $conn->query($query);
	$result = $account->fetch_assoc();
	// echo $id;
	if($result != ''){	
		session_start();
		$_SESSION['user'] = $user;
		header('location: ../stock-status.php');
	}else{
		header('location: ../index.php?success=false');
	}
}if(isset($_GET['logout'])){
	session_start();
	session_destroy();
	header("location: ../index.php");
} 