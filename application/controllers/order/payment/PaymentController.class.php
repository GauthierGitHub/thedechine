<?php

class PaymentController
{
	public function httpGetMethod(Http $http, array $queryFields)
	{
		if (!(isset($_GET['jeton']) && ($_GET['jeton'] == $_SESSION['jeton']))) {
			die("Jeton de session invalide.");
		}

		$products = new ProductModel;
		$products = $products->findSome($queryFields);
		$total = 0;

		for ($i = 0; $i < count($products); $i++) {
			$total = $total + $products[$i]['price'] * $queryFields[$products[$i]['id']];
		}

		return [
			'products' => $products,
			'quantity' => $queryFields,
			'total' => $total * 100, //stripe converti en centimes
			'is_order' => true
		];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		// stripe nécessite l'extension php curl : pour php7 : sudo apt-get install php-curl
		// stripe protège contre le changement du html

		if (!(isset($formFields['jeton']) && ($formFields['jeton'] == $_SESSION['jeton']))) {
			die("Jeton de session invalide.");
		}

		// Essayer de "charger" le client
		try {
			\Stripe\Stripe::setApiKey('sk_test_bUcIDkwMX0SFU6BxYardFzsQ'); // Clé secrète d'API Dashboard > Developers > API keys
			$charge = \Stripe\Charge::create([
				'amount' => $formFields['total'],
				'currency' => 'eur',
				'source' => $formFields['stripeToken']
			]);
			// Le paiement s'est bien passé, on redirige l'utilisateur vers la page de succès
			$http->redirectTo('order/payment/success');
		} catch (Exception $error) {
			// Le paiement s'est mal passé, on redirige l'utilisateur vers la page d'erreur
			$http->redirectTo('order/payment/error');
		}
	}
}
