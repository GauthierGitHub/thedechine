<?php

class ListingproductsController 
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);
        
        //tout les produits
        $productModel = new ProductModel;
        $productModel = $productModel->findAll();

        return [
            'productModel' => $productModel
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        if(isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            //suppression d'un produit
            $product = new ProductModel;
            $product->deleteProduct($formFields);

            $flashBag = new FlashBag();
            $flashBag->add('Le produit a bien été supprimé!');

            $http->redirectTo('admin/listingproducts');
            
        }else {
            die("ID ou jeton de session invalide.");
        }
    }
}