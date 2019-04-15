<?php
class CustomerModel
{
/**
 * 
 *Une faille de sécurité si simple... mais potentiellement très dangereuse
 *
 *if (isset($_GET["test"])) {
 *  echo htmlspecialchars($_GET["test"]);
 *  On affiche le contenu du champ du formulaire
 *}
 * Faire un test en tapant dans le champ de texte <script>window.alert('coucou');</script>
 *
 *=> Faille de sécurité XSS (Cross Side Scripting)
 */


  // Requête pour l'affichage
  public function findlite()
  {
    $database = new Database;
    $sql =
      "SELECT `id`,`lastname`,`firstname`
    FROM customers WHERE `id`<> '9'";
    return $database->query($sql);
  }

  // Méthode pour trouver tout les clients
  public function findAll()
  {
    $database = new Database();
    return $database->query("SELECT * FROM customers WHERE `id`<> '9'");
  }

  // Méthode pour trouver un seul client
  public function findOne($customerId)
  {
    $database = new Database();
    return $database->queryOne("SELECT * FROM customers WHERE id = ?", $customerId);
  }

  // Ajouter un client
  public function addCustomer($formFields)
  {
    $database = new Database;
    $sql =
      "INSERT INTO `customers`(`firstname`, `lastname`, `mail`, `nickname`, `password`, `adress`, `cp`, `city`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    return $database->executeSql($sql, [
      htmlspecialchars($formFields['firstname']),
      htmlspecialchars($formFields['lastname']),
      htmlspecialchars($formFields['mail']),
      htmlspecialchars($formFields['nickname']),
      password_hash($formFields['password'], PASSWORD_DEFAULT),
      htmlspecialchars($formFields['adress']),
      htmlspecialchars(intval($formFields['cp'])),
      htmlspecialchars($formFields['city'])
    ]);
  }

  // Méthode pour voir si un mail exite déjà
  public function mailVerify($customerMail)
  {
    $database = new Database();
    return $database->queryOne("SELECT * FROM customers WHERE mail = ?", [$customerMail]);
  }

  // méthode pour l'édition d'un client
  public function edit($formFields)
  {
    $database = new Database;
    $sql =
      "UPDATE `customers` 
    SET `firstname`= ?,`lastname`= ?,`mail`= ?,`nickname`= ?,`adress`= ?,`cp`= ?,`city`= ? 
    WHERE id = ? ";
    $values = [
      htmlspecialchars($formFields['firstname']),
      htmlspecialchars($formFields['lastname']),
      htmlspecialchars($formFields['mail']),
      htmlspecialchars($formFields['nickname']),
      htmlspecialchars($formFields['adress']),
      htmlspecialchars($formFields['cp']),
      htmlspecialchars($formFields['city']),
      htmlspecialchars(intval($formFields['id']))
    ];
    $database->executeSql($sql, $values);
  }

  // suppression d'un client
  public function deleteCustomer($customer)
  {
    $database = new Database;
    $value = [htmlspecialchars($customer['customerid'])];
    $sql =
      "DELETE 
    FROM `customers` 
    WHERE `customers`.`id` = ?;";
    $database->executeSql($sql, $value);
  }

  // changement de password
  // méthode pour l'édition d'un client
  public function editPassword($formFields)
  {
    $database = new Database;
    $sql =
      "UPDATE `customers` 
      SET `password`= ?
      WHERE id = ? ";
    $values = [
      password_hash($formFields['password'], PASSWORD_DEFAULT),
      htmlspecialchars(intval($formFields['id']))
    ];
    $database->executeSql($sql, $values);
  }
}
