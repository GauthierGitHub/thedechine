<?php
class OrderModel
{
    // Méthode pour trouver le nombre de commandes en cours
    public function countOrder()
    {
        $database = new Database;
        return $database->query(
            'SELECT COUNT(*) AS totalorders FROM orders WHERE "send" <> 1 '
        );
    }

    // Méthode pour trouver le nombre de commande déjà livré
    public function archives()
    {
        $database = new Database;
        return $database->query(
            'SELECT COUNT(*) AS totalorders FROM orders WHERE "send" <> 0 '
        );
    }

    // Méthode pour trouver toutes les commandes
    public function findAll()
    {
        $database = new Database();
        return $database->query(
            "SELECT orders.id, products.name AS product_name,`price`,`date`,`quantity`,`lastname`,`firstname`, `mail`,`adress`,`cp`,`city`, categories.name, (`price`*`quantity`) AS soustotal, `send`
      FROM `orders`
      LEFT OUTER JOIN orderdetails ON orders.id = orderdetails.order_id 
      LEFT OUTER JOIN products ON products.id = orderdetails.product_id 
      LEFT OUTER JOIN customers ON customers.id = orders.customer_id 
      LEFT OUTER JOIN categories ON categories.id =products.category_id
      ORDER BY orders.id "
        );
    }

    // Méthode pour trouver le prix total de chaque commande
    public function ordersTotal()
    {
        $database = new Database();
        return $database->query(
            "SELECT order_id, SUM((quantity*products.price)) AS total
        FROM `orderdetails`
        LEFT OUTER JOIN products ON products.id = orderdetails.product_id
        GROUP BY order_id"
        );
    }

    // Méthode pour trouver une seule commande
    public function findOne($orderId)
    {
        $database = new Database();
        return $database->query(
            "SELECT orders.id AS order_id, `send`, `date`,`lastname`,`firstname`,`mail`,`adress`,`cp`,`city`, categories.name AS category_name, customers.id AS customer_id, products.name AS product_name,`price`,`quantity`, (`price`*`quantity`) AS soustotal
      FROM `orders`
      LEFT OUTER JOIN orderdetails ON orders.id = orderdetails.order_id 
      LEFT OUTER JOIN products ON products.id = orderdetails.product_id 
      LEFT OUTER JOIN customers ON customers.id = orders.customer_id 
      LEFT OUTER JOIN categories ON categories.id =products.category_id
      WHERE orders.id = ?",
            $orderId
        );
    }

    // Ajout d'une commande
    public function addOrder($ProductQuantityCustomer)
    {
        $database = new Database;

        $sql =
            "INSERT INTO `orders`(`customer_id`, `send`) VALUES (?, 0)
        ";
        $orderNumber = $database->executeSql($sql, [
            htmlspecialchars($ProductQuantityCustomer['customer_id'])
        ]);

        for ($i = 0; $i < count($ProductQuantityCustomer['product']); $i++) {
            if ($i % 2 == 0) {
                $sql =
                    "INSERT INTO `orderdetails`(`order_id`,`product_id`,`quantity`) VALUES (?, ?, ?)";
                $database->executeSql($sql, [
                    htmlspecialchars($orderNumber),
                    htmlspecialchars($ProductQuantityCustomer['product'][$i]),
                    htmlspecialchars($ProductQuantityCustomer['product'][$i + 1])
                ]);
                $sql =
                    "UPDATE `products` SET `stock`= `stock` - ? WHERE id = ?";
                $database->executeSql($sql, [
                    htmlspecialchars($ProductQuantityCustomer['product'][$i + 1]),
                    htmlspecialchars($ProductQuantityCustomer['product'][$i]),
                ]);
            }
        }
    }

    // Edition d'une commande
    public function editOrder($formFields)
    {
        $database = new Database;

        //récupération des données de quantité de l'ancienne commande pour la gestion du stock
        $sql =
            "SELECT quantity, product_id
    FROM orderdetails
    WHERE order_id = ?";
        $value =  [
            $formFields['id']
        ];
        $quantity = $database->query($sql, $value);

        //remise des stocks de l'ancienne commande
        for ($i = 0; $i < count($quantity); $i++) {
            if ($i % 2 == 0) {
                $sql =
                    "UPDATE `products` 
                    SET `stock`= `stock` + ? 
                    WHERE id = ?";
                $database->executeSql($sql, [
                    htmlspecialchars($quantity[$i]['quantity']),
                    htmlspecialchars($quantity[$i]['product_id'])
                ]);
            }
        }
        //suppression des anciens produits commandés
        $sql =
            "DELETE FROM `orderdetails` 
            WHERE order_id = ?";
        $value = [htmlspecialchars($formFields['id'])];
        $database->executeSql($sql, $value);

        //nouvelle commande
        $sql =
            "UPDATE `orders` 
            SET `customer_id`= ?,
            WHERE id = ?";
        $values = [
            htmlspecialchars($formFields['customer_id']),
            htmlspecialchars(intval($formFields['id'])),
        ];
        $database->executeSql($sql, $values);

        //rajout des nouveaux produits et qté commandé
        for ($i = 0; $i < count($formFields['product']); $i++) {
            if ($i % 2 == 0) {
                $sql =
                    "INSERT INTO `orderdetails`(`order_id`, `product_id`,`quantity`) 
                    VALUES (?, ?, ?)";
                $database->executeSql($sql, [
                    htmlspecialchars($formFields['id']),
                    htmlspecialchars($formFields['product'][$i]),
                    htmlspecialchars($formFields['product'][$i + 1])
                ]);
                $sql =
                    "UPDATE `products` SET `stock`= `stock` - ? WHERE id = ?";
                $database->executeSql($sql, [
                    htmlspecialchars($formFields['product'][$i + 1]),
                    htmlspecialchars($formFields['product'][$i]),
                ]);
            }
        }
    }

    //suppression d'une commande
    public function deleteOrder($formFields)
    {
        $database = new Database;
        $value = [htmlspecialchars($formFields['orderid'])];
        $sql =
            "DELETE FROM `orders` 
            WHERE `orders`.`id` = ?;";
        $database->executeSql($sql, $value);
    }

    //changer le status d'une commande
    public function changeStatus($formFields)
    {
        $database = new Database;
        $sql =
            "UPDATE `orders` SET `send`= ? WHERE id = ?";
        $values = [
            htmlspecialchars($formFields['send']),
            htmlspecialchars(intval($formFields['orderid'])),
        ];
        $database->executeSql($sql, $values);
    }
}
