<?php

class DisconnectionController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $http->getRacineDirectory();

        $flashBag = new FlashBag();
        $flashBag->add('<h3>Vous vous êtes déconnecté !</h3>');

        $_SESSION["customer_nickname"] = null;
        $_SESSION["customer_id"] = null;
        $_SESSION["customer_mail"] = null;
        $http->redirectTo('');
    }

    public function httpPostMethod(Http $http, array $formFields)
    { }
}
