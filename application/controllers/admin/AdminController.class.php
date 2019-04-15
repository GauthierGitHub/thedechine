<?php

class AdminController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        //affichage du nombre de commandes
        $countOrder = new OrderModel;
        $archives = $countOrder->archives();
        $countOrder = $countOrder->countOrder();

        return [
            'archives' => $archives[0]['totalorders'],
            'countOrder' => $countOrder[0]['totalorders'],
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    { }
}
