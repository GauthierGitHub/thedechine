<?php

class AddcustomerController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        /*
        * Méthode appelée en cas de requête HTTP GET
        *
        * L'argument $http est un objet permettant de faire des redirections etc.
        * L'argument $queryFields contient l'équivalent de $_POST en PHP natif.
        */

        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        /*
         * Méthode appelée en cas de requête HTTP POST
         *
         * L'argument $http est un objet permettant de faire des redirections etc.
         * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
        */

        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            // ajout de client
            $newCustomer = new CustomerModel;
            $newCustomer->addCustomer($formFields);

            $flashBag = new FlashBag();
            $flashBag->add('Compte enregistré!');

            $http->redirectTo('admin/listingcustomers');
        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
