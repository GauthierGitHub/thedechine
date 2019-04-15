<?php

class ListingcustomersController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        // affichage de toute les commandes
        $customerModel = new CustomerModel;
        $customerModel = $customerModel->findAll();

        return [ 
            'customerModel' => $customerModel,
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {

        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            //suppression d'un client
            $customer = new CustomerModel;
            $customer->deleteCustomer($formFields);
            $flashBag = new FlashBag();
            $flashBag->add('Les données du client ont bien été supprimées!');
            $http->redirectTo('admin/listingcustomers');

        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
