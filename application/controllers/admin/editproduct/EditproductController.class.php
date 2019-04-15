<?php

class EditproductController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        if (!(isset($_GET['jeton']) && ($_GET['jeton'] == $_SESSION['jeton']))) {
            die("Jeton de session invalide.");
        }

        //un seul produit
        $_GET['productid'];
        $productId = [$_GET['productid']];
        $productData = new ProductModel;
        $productData = $productData->findOne($productId);

        //Catégories pour l'affichage
        $categories = new ProductModel;
        $categories = $categories->findCategoryLite();

        return [
            'productData' => $productData,
            'categories' => $categories
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {

        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            //édition d'un produit
            $product = new ProductModel;
            $product->editProduct($formFields);

            $flashBag = new FlashBag();
            $flashBag->add('Les données du produits ont bien été modifiées!');

            $http->redirectTo('admin/listingproducts');
        } else {
            die("ID ou jeton de session invalide.");
        }
        //modification de la photo avec le controller "upload"
    }
}
