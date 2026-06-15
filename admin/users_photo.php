<?php
	include 'includes/session.php';

	if(isset($_POST['upload'])){
		if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
			$_SESSION['error'] = 'CSRF token validation failed.';
			header('location: users.php');
			exit();
		}
		$id = $_POST['id'];
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			// Image validation
			$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);
			finfo_close($finfo);

			if(!in_array($mime, $allowed_types) || !getimagesize($_FILES['photo']['tmp_name'])){
				$_SESSION['error'] = 'Invalid image format. Only JPG, PNG, and GIF are allowed.';
				header('location: users.php');
				exit();
			}

			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
		
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE users SET photo=:photo WHERE id=:id");
			$stmt->execute(['photo'=>$filename, 'id'=>$id]);
			$_SESSION['success'] = 'User photo updated successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Select user to update photo first';
	}

	header('location: users.php');
?>