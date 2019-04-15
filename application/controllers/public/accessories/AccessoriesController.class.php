<?php

class AccessoriesController
{
	public function httpGetMethod(Http $http, array $queryFields)
	{

		$productModel = new ProductModel;
		$productModel = $productModel->findAllAccessories();

		return [
			'products' => $productModel,
			'is_products' => true
		];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{ }
}
