<?php
use Stripe\Product;

class AddorderController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        //requêtes pour l'affichage
        $dataCustomers = new CustomerModel;
        $dataCustomers = $dataCustomers->findlite();

        $dataProducts = new ProductModel;
        $dataProducts = $dataProducts->findlite();

        return [
            'dataProducts' => $dataProducts,
            'dataCustomers' => $dataCustomers,
            'is_addOrder' => true
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            // ajout d'une commande
            $order = new OrderModel;
            $order->addOrder($formFields);

            $flashBag = new FlashBag();
            $flashBag->add('La commande a bien été enregistrée!');


            $http->redirectTo('admin/listingorders');
        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
