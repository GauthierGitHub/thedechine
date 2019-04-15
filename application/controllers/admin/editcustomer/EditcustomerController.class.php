<?php

class EditcustomerController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        if (!(isset($_GET['jeton']) && ($_GET['jeton'] == $_SESSION['jeton']))) {
            die("Jeton de session invalide.");
        }

        //affichage d'un seul client
        $_GET['customerid'];
        $customerId = [$_GET['customerid']];
        $customerData = new CustomerModel;
        $customerData = $customerData->findOne($customerId);
        return [
            'customerData' => $customerData
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {

        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            //changement du mdp
            if (isset($formFields['password'])) {

                $customer = new CustomerModel;
                $customer->editPassword($formFields);

                $flashBag = new FlashBag();
                $flashBag->add('Le mot de passe du client a bien été modifié!');

                $http->redirectTo('admin/listingcustomers');
            } else {
                //édition d'un client
                $customer = new CustomerModel;
                $customer->edit($formFields);

                $flashBag = new FlashBag();
                $flashBag->add('Les coordonnées du client ont bien été modifiées!');

                $http->redirectTo('admin/listingcustomers');
            }
        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
