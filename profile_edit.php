<?php
	include 'includes/session.php';

	if(!isset($_SESSION['user'])){
		header('location: login.php');
		exit();
	}

	if(isset($_POST['edit'])){
		if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
			$_SESSION['error'] = 'CSRF token validation failed.';
			header('location: profile.php');
			exit();
		}
		$curr_password = $_POST['curr_password'];
		$email = trim($_POST['email']);
		$password = $_POST['password'];
		$firstname = trim($_POST['firstname']);
		$lastname = trim($_POST['lastname']);
		$contact = trim($_POST['contact']);
		$address = trim($_POST['address']);
		$photo = $_FILES['photo']['name'];

		if(password_verify($curr_password, $user['password']) || hash_equals($user['password'], $curr_password)){
			$filename = $user['photo'];
			if(!empty($photo)){
				// Image validation
				$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);
				finfo_close($finfo);

				if(!in_array($mime, $allowed_types) || !getimagesize($_FILES['photo']['tmp_name'])){
					$_SESSION['error'] = 'Invalid image format. Only JPG, PNG, and GIF are allowed.';
					header('location: profile.php');
					exit();
				}

				move_uploaded_file($_FILES['photo']['tmp_name'], 'images/'.$photo);
				$filename = $photo;
			}

			$newPassword = ($password === $user['password'] || $password === '') ? $user['password'] : password_hash($password, PASSWORD_DEFAULT);
			$conn = $pdo->open();

			try{
				$stmt = $conn->prepare("UPDATE users SET email=:email, password=:password, firstname=:firstname, lastname=:lastname, address=:address, contact_info=:contact, photo=:photo WHERE id=:id");
				$stmt->execute(['email'=>$email, 'password'=>$newPassword, 'firstname'=>$firstname, 'lastname'=>$lastname, 'address'=>$address, 'contact'=>$contact, 'photo'=>$filename, 'id'=>$user['id']]);
				$_SESSION['success'] = 'Account updated successfully';
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}

			$pdo->close();
		}
		else{
			$_SESSION['error'] = 'Incorrect password';
		}
	}
	else{
		$_SESSION['error'] = 'Fill up required details first';
	}

	header('location: profile.php');
?>
