<?php
	include 'includes/session.php';

	$output = array('list'=>'', 'count'=>0);

	if(!isset($_SESSION['user'])){
		echo json_encode($output);
		exit();
	}

	$conn = $pdo->open();

	try{
		$stmt = $conn->prepare("SELECT cart.quantity, products.name, products.price, products.photo FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE cart.user_id=:user_id");
		$stmt->execute(['user_id'=>$user['id']]);
		$total = 0;

		foreach($stmt as $row){
			$output['count'] += $row['quantity'];
			$total += $row['price'] * $row['quantity'];
			$photo = !empty($row['photo']) ? 'images/'.htmlspecialchars($row['photo']) : 'images/noimage.jpg';
			$output['list'] .= "
				<li>
					<a href='#'>
						<div class='pull-left'><img src='".$photo."' class='img-circle' alt='Product Image'></div>
						<h4>".htmlspecialchars($row['name'])."</h4>
						<p>".$row['quantity']." x &#8377; ".number_format($row['price'], 2)."</p>
					</a>
				</li>
			";
		}

		if($output['count'] > 0){
			$output['list'] .= "<li class='footer'><a href='cart_view.php'>View cart - &#8377; ".number_format($total, 2)."</a></li>";
		}
		else{
			$output['list'] = "<li class='footer'><a href='#'>Your cart is empty</a></li>";
		}
	}
	catch(PDOException $e){
		$output['list'] = "<li class='footer'><a href='#'>Unable to load cart</a></li>";
	}

	$pdo->close();
	echo json_encode($output);
?>
