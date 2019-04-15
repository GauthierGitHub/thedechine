<?php
class ProductModel
{
  // Requête pour l'affichage
  public function findlite()
  {
    $database = new Database;
    $sql =
      'SELECT `id`,`name`
      FROM products';
    return $database->query($sql);
  }

  // Requête pour l'affichage des catégories de produits
  public function findCategoryLite()
  {
    $database = new Database;
    $sql =
      "SELECT `id`,`name`
    FROM `categories`";
    return $database->query($sql);
  }

  // Méthode pour trouver tout les produits
  public function findAll()
  {
    $database = new Database();
    return $database->query("SELECT * FROM products");
  }

  // Méthode pour trouver tout les produits
  public function findAllTea()
  {
    $database = new Database();
    return $database->query("SELECT * FROM products WHERE category_id<>8");
  }

  // Méthode pour trouver les produits de type accessoires (id=9)
  public function findAllAccessories()
  {
    $database = new Database();
    return $database->query("SELECT * FROM products WHERE category_id=8");
  }

  // Méthode pour trouver un seul produit
  public function findOne($productId)
  {
    $database = new Database();
    return $database->queryOne("SELECT * FROM products WHERE id = ?", $productId);
  }

  // Méthode pour trouver des produits
  public function findSome($queryFields)
  {
    $database = new Database();
    $ids = '';
    $values = [];
    foreach ($queryFields as $key => $value) {
      if ($key != 'jeton') {
        $key = intval($key);
        $ids = '?,' . $ids;
        $values[] = $key;
      }
    }
    $ids = substr($ids, 0, -1);  // on enlève la dernière virgule

    return $database->query("SELECT * FROM products WHERE id IN ($ids)", $values);
  }

  // Méthode pour trouver les produits en fonction d'une catégorie particulière. Méthode pas encore utilisé, en attente d'une fonction tri
  public function findAllWithCategory($categoryId)
  {
    $database = new Database();
    return $database->query("SELECT * FROM products WHERE category_id = ?", $categoryId);
  }

  // Ajout d'un produit
  public function addProduct(array $product)
  {
    $database = new Database;
    $sql =
      "INSERT INTO `products`(`name`, `category_id`, `description`, `image`, `stock`, `price`) VALUES (?, ?, ?, ?, ?, ?)";

    //On formate le nom du fichier de la photo
    $fichier = $_FILES['photoToUpload']['name'];
    $fichier = strtr(
      $fichier,
      'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
      'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
    );
    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

    $database->executeSql($sql, [
      htmlspecialchars($product['name']),
      htmlspecialchars($product['category']),
      htmlspecialchars($product['description']),
      htmlspecialchars($fichier),
      htmlspecialchars($product['stock']),
      htmlspecialchars($product['price'])
    ]);
  }

  //Edition d'un produit
  public function editProduct($formFields)
  {
    $database = new Database;
    $sql =
      "UPDATE `products` 
    SET `name`= ?,`category_id`= ?,`description`= ?,`image`= ?,`stock`= ?,`price`= ? WHERE id = ? ";
    $values = [
      htmlspecialchars($formFields['name']),
      htmlspecialchars($formFields['category_id']),
      htmlspecialchars($formFields['description']),
      htmlspecialchars($formFields['image']),
      htmlspecialchars($formFields['stock']),
      htmlspecialchars($formFields['price']),
      htmlspecialchars(intval($formFields['id']))
    ];
    $database->executeSql($sql, $values);
  }

  //suppression d'un produit
  public function deleteProduct($formFields)
  {
    $database = new Database;
    $value = [htmlspecialchars($formFields['productid'])];
    $sql =
      "DELETE 
    FROM `products` 
    WHERE `products`.`id` = ?;";
    $database->executeSql($sql, $value);
  }

  //édition nouvelle photo
  public function editPic($formFields)
  {
    $database = new Database;

    //On formate le nom du fichier de la photo
    $fichier = $_FILES['photoToUpload']['name'];
    $fichier = strtr(
      $fichier,
      'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
      'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
    );
    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

    $sql =
      "UPDATE `products`
    SET `image` = ? WHERE id = ?";
    $values = [htmlspecialchars($fichier), htmlspecialchars($formFields['id'])];
    $database->executeSql($sql, $values);
  }
}
