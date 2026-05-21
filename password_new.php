<?php
	include 'includes/session.php';

	if(!isset($_POST['reset'], $_GET['code'], $_GET['user'])){
		$_SESSION['error'] = 'Invalid password reset request';
		header('location: password_reset.php');
		exit();
	}

	$password = $_POST['password'];
	$repassword = $_POST['repassword'];
	$code = $_GET['code'];
	$userId = $_GET['user'];

	if($password !== $repassword){
		$_SESSION['error'] = 'Passwords did not match';
		header('location: password_reset.php?code='.$code.'&user='.$userId);
		exit();
	}

	$conn = $pdo->open();

	try{
		$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id AND reset_code=:code");
		$stmt->execute(['id'=>$userId, 'code'=>$code]);
		$user = $stmt->fetch();

		if($user){
			$stmt = $conn->prepare("UPDATE users SET password=:password, reset_code='' WHERE id=:id");
			$stmt->execute(['password'=>password_hash($password, PASSWORD_DEFAULT), 'id'=>$userId]);
			$_SESSION['success'] = 'Password updated. You can now sign in.';
			header('location: login.php');
		}
		else{
			$_SESSION['error'] = 'Invalid or expired reset code';
			header('location: password_reset.php');
		}
	}
	catch(PDOException $e){
		$_SESSION['error'] = $e->getMessage();
		header('location: password_reset.php');
	}

	$pdo->close();
	exit();
?>
