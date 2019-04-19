<?php

class ListingOrdersController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        //commandes
        $orderModel = new OrderModel();
        $orders = $orderModel->findAll();
        //remplacement des  valeurs null & affichage livraison
        for ($i = 0; $i < count($orders); $i++) {
            $orders[$i] = preg_replace('/^$/', 'Donnée supprimée', $orders[$i]);
            $orders[$i] = preg_replace('/^null$/', 'Donnée supprimée', $orders[$i]);
            if ($orders[$i]['send'] == 0) {
                $orders[$i]['send'] = 'en cours';
            } else {
                $orders[$i]['send'] = 'livrée';
            }
        }
        //total de la facture des commandes
        $totalOrders = $orderModel->ordersTotal();

        //remplacement des  valeurs null
        for ($i = 0; $i < count($totalOrders); $i++) {
            $totalOrders[$i] = preg_replace('/^$/', "Manque de données produits, vérifiez l'inventaire de vos produits.", $totalOrders[$i]);
        }
        //transformation du tableau pour l'affichage
        $totalAmount = [];
        foreach ($totalOrders as $order) {
            $array = [$order['order_id'] => $order['total']];
            $totalAmount = $totalAmount + $array;
        }

        return [
            'orders' => $orders,
            'totalAmount' => $totalAmount,
            'is_order' => true
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            $order = new OrderModel;
            $flashBag = new FlashBag();

            if (isset($formFields['send'])) {
                $order->changeStatus($formFields);
                $flashBag->add('Status changé !');
            } else {
                $order->deleteOrder($formFields);
                $flashBag->add('Les données de la commande ont bien été supprimées!');
            }

            $http->redirectTo('admin/listingorders');
            
        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
