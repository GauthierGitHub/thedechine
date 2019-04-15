<?php
/*
 * Le "modèle" est une classe qui va contenir différentes méthodes pour accéder aux données.
 * On retrouve en général, un modèle par entité (table de la base de données)
 * on élimine des recherches l'admin
 */
class AdminModel {
    // Vérification de l'utilisateur comme admin
    public function verifyAdmin ($http){
        if(!(isset($_SESSION['customer_id']) && ($_SESSION['customer_id']) ==9)){
            $flashBag = new FlashBag();
            $flashBag->add('Erreur 404');
            $http->redirectTo('/');
        }
    }
}
