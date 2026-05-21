<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	include 'includes/session.php';

	if(isset($_POST['reset'])){
		$email = $_POST['email'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email=:email");
		$stmt->execute(['email'=>$email]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			//generate code
			$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$code=substr(str_shuffle($set), 0, 15);
			try{
				$stmt = $conn->prepare("UPDATE users SET reset_code=:code WHERE id=:id");
				$stmt->execute(['code'=>$code, 'id'=>$row['id']]);
				
				$resetUrl = rtrim(APP_URL, '/')."/password_reset.php?code=".$code."&user=".$row['id'];
				$message = "
					<h2>Password Reset</h2>
					<p>Your Account:</p>
					<p>Email: ".$email."</p>
					<p>Please click the link below to reset your password.</p>
					<a href='".$resetUrl."'>Reset Password</a>
				";

				//Load phpmailer
	    		require 'vendor/autoload.php';

	    		$mail = new PHPMailer(true);                             
			    try {
			        //Server settings
			        if(!defined('SMTP_HOST') || SMTP_HOST === '' || SMTP_USER === '' || SMTP_PASS === ''){
			        	$_SESSION['error'] = 'SMTP is not configured. Copy includes/config.example.php to includes/config.php and add mail settings.';
			        	header('location: password_reset.php');
			        	exit();
			        }
			        $mail->isSMTP();
			        $mail->Host = SMTP_HOST;
			        $mail->SMTPAuth = true;
			        $mail->Username = SMTP_USER;
			        $mail->Password = SMTP_PASS;
			        $mail->SMTPOptions = array(
			            'ssl' => array(
			            'verify_peer' => false,
			            'verify_peer_name' => false,
			            'allow_self_signed' => true
			            )
			        );                         
			        $mail->SMTPSecure = SMTP_SECURE;
			        $mail->Port = SMTP_PORT;

			        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
			        
			        //Recipients
			        $mail->addAddress($email);              
			        $mail->addReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
			       
			        //Content
			        $mail->isHTML(true);                                  
			        $mail->Subject = 'ECommerce Site Password Reset';
			        $mail->Body    = $message;

			        $mail->send();

			        $_SESSION['success'] = 'Password reset link sent';
			     
			    } 
			    catch (Exception $e) {
			        $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
			    }
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}
		else{
			$_SESSION['error'] = 'Email not found';
		}

		$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Input email associated with account';
	}

	header('location: password_reset.php');

?>
