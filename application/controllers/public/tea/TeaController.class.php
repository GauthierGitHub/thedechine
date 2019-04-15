<?php

class TeaController
{
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$productModel = new ProductModel;
		$productModel = $productModel->findAllTea();

		return [
			'products' => $productModel,
			'is_products' => true
		];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{ }
}
