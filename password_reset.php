<?php include 'includes/session.php'; ?>
<?php
  $hasResetCode = isset($_GET['code']) && isset($_GET['user']);
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }
    ?>
  	<div class="login-box-body">
      <?php if(!$hasResetCode): ?>
      <p class="login-box-msg">Enter your account email</p>

      <form action="reset.php" method="POST">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="row">
          <div class="col-xs-6">
                <button type="submit" class="btn btn-primary btn-block btn-flat" name="reset"><i class="fa fa-envelope"></i> Send Link</button>
            </div>
          </div>
      </form>
      <br>
      <a href="login.php">Back to login</a>
      <?php else: ?>
    	<p class="login-box-msg">Enter new password</p>

    	<form action="password_new.php?code=<?php echo htmlspecialchars($_GET['code']); ?>&user=<?php echo htmlspecialchars($_GET['user']); ?>" method="POST">
      		<div class="form-group has-feedback">
        		<input type="password" class="form-control" name="password" placeholder="New password" required>
        		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
      		</div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="repassword" placeholder="Re-type password" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
      		<div class="row">
    			<div class="col-xs-4">
          			<button type="submit" class="btn btn-primary btn-block btn-flat" name="reset"><i class="fa fa-check-square-o"></i> Reset</button>
        		</div>
    		</div>
    	</form>
      <?php endif; ?>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>
