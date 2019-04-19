<?php

class UploadController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //vérification que l'utilisateur est bien l'admin
        $admin = new AdminModel;
        $admin->verifyAdmin($http);
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        /* Dans le cas de fichiers à  upload
            Ici le formulaire contient 'enctype="multipart/form-data"' qui découpe le post en plusieurs parties plus facilement manipulables.
                $photo = $http->moveUploadedFile('image', '/images');
            En local il est nécessaire de changer les droits d'accès et d'écriture au fichier image 
            https://doc.ubuntu-fr.org/permissions
        */

        if (isset($_POST['jeton']) && ($_POST['jeton'] == $_SESSION['jeton'])) {

            // Importation des photos inspiré de: https://antoine-herault.developpez.com/tutoriels/php/upload/
            if (isset($_FILES['photoToUpload'])) {
                /*version locale*/
                $dossier = '/home/barbet/Devellopement/3wa/Developpement/projetPersonnel/thedechine/application/www/images/teapic/';
                //$dossier = '/var/www/html/thedechine/application/images/teapic/';
                $fichier = basename($_FILES['photoToUpload']['name']);
                $taille_maxi = 10000000;
                $taille = filesize($_FILES['photoToUpload']['tmp_name']);
                $extensions = array('.png', '.gif', '.jpg', '.jpeg');
                $extension = strrchr($_FILES['photoToUpload']['name'], '.');
                //Début des vérifications de sécurité...
                if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                    {
                        $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
                    }
                if ($taille > $taille_maxi) {
                    $erreur = 'Le fichier est trop lourd, la taille maximale est 10Mo...';
                }
                if (!isset($erreur)) //S'il n'y a pas d'erreur, on upload
                    {
                        //On formate le nom du fichier ici...
                        $fichier = strtr(
                            $fichier,
                            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
                            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
                        );
                        $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

                        if (move_uploaded_file($_FILES['photoToUpload']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                $flashBag = new FlashBag();
                                $flashBag->add('Upload effectué avec succès !');
                                $product = new ProductModel;
                                $product->editPic($formFields);
                                $flashBag->add('Photo modifiée et enregistrée dans la base de données.');
                                $http->redirectTo('admin/listingproducts');
                            } else //Sinon (la fonction renvoie FALSE).
                            {
                                $flashBag = new FlashBag();
                                $flashBag->add('Echec de l\'upload !');
                                $http->redirectTo('admin/listingproducts');
                            }
                    } else {
                    $flashBag = new FlashBag();
                    $flashBag->add($erreur);
                    $http->redirectTo('admin/listingproducts');
                }
            }
        } else {
            die("ID ou jeton de session invalide.");
        }
    }
}
