<?php

class HomeController
{
	public function httpGetMethod(Http $http, array $queryFields)
	{
		//commandes
		$productModel = new ProductModel();
		$products = $productModel->findAll();

		return [
			'products' => $products,
			'is_home' => true
		];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{ }
}
