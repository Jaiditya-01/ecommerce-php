<?php
	include 'includes/session.php';

	$output = array('date'=>'', 'transaction'=>'', 'list'=>'', 'total'=>'0.00');

	if(!isset($_SESSION['user'], $_POST['id'])){
		echo json_encode($output);
		exit();
	}

	$conn = $pdo->open();

	try{
		$stmt = $conn->prepare("SELECT * FROM sales WHERE id=:id AND user_id=:user_id");
		$stmt->execute(['id'=>$_POST['id'], 'user_id'=>$user['id']]);
		$sale = $stmt->fetch();

		if($sale){
			$output['date'] = date('M d, Y', strtotime($sale['sales_date']));
			$output['transaction'] = $sale['pay_id'];

			$stmt = $conn->prepare("SELECT details.quantity, products.name, products.price FROM details LEFT JOIN products ON products.id=details.product_id WHERE details.sales_id=:id");
			$stmt->execute(['id'=>$sale['id']]);
			$total = 0;

			foreach($stmt as $row){
				$subtotal = $row['price'] * $row['quantity'];
				$total += $subtotal;
				$output['list'] .= "<tr class='prepend_items'><td>".$row['name']."</td><td>&#8377; ".number_format($row['price'], 2)."</td><td>".$row['quantity']."</td><td>&#8377; ".number_format($subtotal, 2)."</td></tr>";
			}

			$output['total'] = '&#8377; '.number_format($total, 2);
		}
	}
	catch(PDOException $e){
		$output['list'] = "<tr class='prepend_items'><td colspan='4'>Unable to load transaction.</td></tr>";
	}

	$pdo->close();
	echo json_encode($output);
?>
