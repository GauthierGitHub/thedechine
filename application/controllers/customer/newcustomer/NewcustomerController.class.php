<?php

class NewcustomerController
{
    public function httpGetMethod(Http $http, array $queryFields)
    { }

    public function httpPostMethod(Http $http, array $formFields)
    {


        $http->getRacineDirectory();

        $newCustomer = new CustomerModel;

        //vérification de l'email unique et redirection au cas où existant
        $verifiedMail = $newCustomer->mailVerify($formFields['mail']);
        if (!empty($verifiedMail)) {
            $flashBag = new FlashBag();
            $flashBag->add('Votre mail est déjà enregistré à un compte!');
            $http->redirectTo('');
        }

        //préparation du password
        $formFields['password'] = password_hash($formFields['password'], PASSWORD_DEFAULT);

        $id = $newCustomer->addCustomer($formFields);

        $flashBag = new FlashBag();
        $flashBag->add('Votre compte a bien été enregistré !');

        $_SESSION["customer_id"] = $id;
        $_SESSION["customer_nickname"] = $formFields['nickname'];
        $_SESSION["customer_mail"] = $formFields['mail'];
        $http->redirectTo('');
    }
}
