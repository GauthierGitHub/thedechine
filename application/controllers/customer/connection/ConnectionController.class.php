<?php

class ConnectionController
{
    public function httpGetMethod(Http $http, array $queryFields)
    { }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $http->getRacineDirectory();
        $newCustomer = new CustomerModel;

        //vérification du password correspondant au mail
        $newCustomer = $newCustomer->mailVerify($formFields['login']['usermail']);
        if (password_verify($formFields['login']['password'], $newCustomer['password'])) {
            $flashBag = new FlashBag();
            $flashBag->add('Bienvenue ' . $newCustomer['nickname'] . ' !');

            $_SESSION["customer_nickname"] = $newCustomer['nickname'];
            $_SESSION["customer_id"] = $newCustomer['id'];
            $_SESSION["customer_mail"] = $newCustomer['mail'];
            $http->redirectTo('');
        } else {
            $flashBag = new FlashBag();
            $flashBag->add(
                'Mail ou mot de passe invalide, réessayer ou inscrivez-vous.'
            );
            $http->redirectTo('customer');
        }
    }
}
