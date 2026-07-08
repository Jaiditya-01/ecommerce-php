<?php include 'includes/session.php'; ?> // for checking the current session
<?php
	if(!isset($_SESSION['user'])){
		header('location: login.php');
		exit();
	}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
	<?php include 'includes/navbar.php'; ?>
	<div class="content-wrapper">
		<div class="container">
			<section class="content">
				<div class="box box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Shopping Cart</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered">
							<thead>
								<th>Product</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Subtotal</th>
							</thead>
							<tbody>
							<?php
								$conn = $pdo->open();
								$total = 0;
								$stmt = $conn->prepare("SELECT cart.quantity, products.name, products.price FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE cart.user_id=:user_id");
								$stmt->execute(['user_id'=>$user['id']]);
								foreach($stmt as $row){
									$subtotal = $row['price'] * $row['quantity'];
									$total += $subtotal;
									echo "<tr><td>".htmlspecialchars($row['name'])."</td><td>&#8377; ".number_format($row['price'], 2)."</td><td>".$row['quantity']."</td><td>&#8377; ".number_format($subtotal, 2)."</td></tr>";
								}
								$pdo->close();
							?>
								<tr>
									<td colspan="3" align="right"><b>Total</b></td>
									<td><b>&#8377; <?php echo number_format($total, 2); ?></b></td>
								</tr>
							</tbody>
						</table>
						<a href="index.php" class="btn btn-default btn-flat"><i class="fa fa-shopping-bag"></i> Continue Shopping</a>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
