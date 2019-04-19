<?php

class EditorderController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);

        if (!(isset($_GET['jeton']) && ($_GET['jeton'] == $_SESSION['jeton']))) {
            die("Jeton de session invalide.");
        }

        //affichage d'une seule commande
        $_GET['orderid'];
        $orderId = [intval($_GET['orderid'])];
        $orderData = new OrderModel;
        $orderData = $orderData->findOne($orderId);
        //remplacement des  valeurs null
        for ($i = 0; $i < count($orderData); $i++) {
            $orderData[$i] = preg_replace('/^$/', 'Donnée supprimée', $orderData[$i]);
        }

        //transformation du tableau  
        for ($i = 0; $i < count($orderData); $i++) {
            $orderData[$i]['product_name'] = [$orderData[$i]['product_name']];
            $orderData[$i]['price'] = [$orderData[$i]['price']];
            $orderData[$i]['quantity'] = [$orderData[$i]['quantity']];
            $orderData[$i]['soustotal'] = [$orderData[$i]['soustotal']];
        }
        for ($i = 1; $i < count($orderData); $i++) {
            $orderData[0]['product_name'] = array_merge($orderData[0]['product_name'], $orderData[$i]['product_name']);
            $orderData[0]['price'] = array_merge($orderData[0]['price'], $orderData[$i]['price']);
            $orderData[0]['quantity'] = array_merge($orderData[0]['quantity'], $orderData[$i]['quantity']);
            $orderData[0]['soustotal'] = array_merge($orderData[0]['soustotal'], $orderData[$i]['soustotal']);
        }
        $orderData = $orderData[0];

        //requêtes complémentaires nécessaires aux données affichées sur la page
        $database = new Database;
        $sql =
            'SELECT `id`,`lastname`,`firstname`
        FROM customers';
        $dataCustomers = $database->query($sql);

        $sql =
            'SELECT `id`,`name`
        FROM products';
        $dataProducts = $database->query($sql);

        return [
            //nécessaire à l'édition
            'orderData' => $orderData,
            //nécessaire à l'affichage
            'dataProducts' => $dataProducts,
            'dataCustomers' => $dataCustomers,
            'is_addOrder' => true
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {
            var_dump($formFields);
            //édition d'une commande
            $order = new OrderModel;
            $order->editOrder($formFields);

            $flashBag = new FlashBag();
            $flashBag->add('La commande a bien été éditée!');

            $http->redirectTo('admin/listingorders');
        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
