<?php

class Cart {


	public static function addToCart($id, $quantity = 1)
	{
		if(isset($_SESSION['cart']['products'][$id])) {
			$_SESSION['cart']['products'][$id]['quantity'] += $quantity;
		}
		else {
			$product = db('Product')->select('SELECT * FROM products WHERE id = :id', ['id' => $id])
				->first();

			$_SESSION['cart']['products'][$id] = [
				'quantity' => 1,
				'title' => $product->title,
				'price' => $product->price,
				'id' => $product->id
			];


		}

		self::calculate();
	}


	public static function removeFromCart($id)
	{
		if($_SESSION['cart']['products'][$id]['quantity'] > 1) {
			$_SESSION['cart']['products'][$id]['quantity']--;
		}
		else {
			unset($_SESSION['cart']['products'][$id]);
		}

		self::calculate();
	}


	public static function get()
	{
		return $_SESSION['cart'];
	}


	private static function calculate()
	{
		$totalPrice = 0;
		foreach($_SESSION['cart']['products'] as $key => $value) {
			$totalPrice += $value['price'] * $value['quantity'];
		}

		$_SESSION['cart']['total'] = $totalPrice;
	}
}
