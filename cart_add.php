<?php
	include 'includes/session.php';

	$output = array('error'=>false, 'message'=>'');

	if(!isset($_SESSION['user'])){
		$output['error'] = true;
		$output['message'] = 'Please sign in before adding products to your cart.';
		echo json_encode($output);
		exit();
	}

	$productId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
	$quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;

	$conn = $pdo->open();

	try{
		$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM products WHERE id=:id");
		$stmt->execute(['id'=>$productId]);
		$product = $stmt->fetch();

		if($product['numrows'] < 1){
			$output['error'] = true;
			$output['message'] = 'Product not found.';
		}
		else{
			$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=:user_id AND product_id=:product_id");
			$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$productId]);
			$cart = $stmt->fetch();

			if($cart){
				$stmt = $conn->prepare("UPDATE cart SET quantity=quantity+:quantity WHERE id=:id");
				$stmt->execute(['quantity'=>$quantity, 'id'=>$cart['id']]);
			}
			else{
				$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
				$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$productId, 'quantity'=>$quantity]);
			}

			$output['message'] = 'Product added to cart.';
		}
	}
	catch(PDOException $e){
		$output['error'] = true;
		$output['message'] = $e->getMessage();
	}

	$pdo->close();
	echo json_encode($output);
?>
