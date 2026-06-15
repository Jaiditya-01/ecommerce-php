<?php
	include 'includes/session.php';
	$conn = $pdo->open();

	if(isset($_POST['login'])){
		if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
			$_SESSION['error'] = 'CSRF token validation failed.';
			header('location: login.php');
			exit();
		}
		
		$email = $_POST['email'];
		$password = $_POST['password'];

		try{

			$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email = :email");
			$stmt->execute(['email'=>$email]);
			$row = $stmt->fetch();
			if($row['numrows'] > 0){
					if(password_verify($password, $row['password']) || hash_equals($row['password'], $password)){
						if($row['type']){
							$_SESSION['admin'] = $row['id'];
							header("location: admin/products.php");
							exit();
						}
						else{
							$_SESSION['user'] = $row['id'];
							header("location:index.php");
							exit();
						}
					}
					else{
						$_SESSION['error'] = 'Incorrect Password';
					}
				
			}
			else{
				$_SESSION['error'] = 'Email not found';
			}
		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}

	}
	else{
		$_SESSION['error'] = 'Input login credentails first';
	}

	$pdo->close();

	header('location: login.php');

?>
