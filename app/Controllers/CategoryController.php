<?php

namespace App\Controllers;
use App\Models\Category;
use App\Controllers\CoreController;

class CategoryController extends CoreController {
    public function list()
    {
        $categoryModel = Category::findAll();
        $params['categoryData'] = $categoryModel;

        $this->show('category/list', $params);
    }

    public function edit(int $id)
    {
        $categoryModel = Category::find($id);
        $params['categorySelected'] = $categoryModel;

        // TODO : On pourrait gérer le cas ou on ne trouve pas de catégorie correspondant à l'id, dans ce cas il faut vérifier que $categoryObject !== false

        $this->show('category/update', $params);
    }

    public function update(int $id) 
    {
        // On rente de récupérer les données entrées dans le formulaire
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);

        // On créé un tableau vide dans lequel on va ajouter toutes les erreurs rencontrées
        $errorsList = [];

        // Ensuite, on vérifie les valeurs avec des conditions successives
        if(empty($name) || $name === false) {
            $errorsList[] = "Le nom de la catégorie est vide ou invalide";
        }

        if(empty($subtitle) || $subtitle === false) {
            $errorsList[] = "Le sous-titre de la catégorie est vide ou invalide";
        }

        if($picture === false) {
            $errorsList[] = "L'URL de l'image est vide ou invalide";
        }

        // Si on arrive jusqu'ici avec $errorsList vide, c'est que tout s'est bien passé, on peut ajouter en BDD
        if(empty($errorsList)) {
            // 1. Instancier une nouvelle Categorie "vide"
            $categoryModel = Category::find($id);

            // 2. Renseigner les valeurs grace aux setters
            $categoryModel->setName($_POST['name']);
            $categoryModel->setSubtitle($_POST['subtitle']);
            $categoryModel->setPicture($_POST['picture']);

            // 3. Appeller la méthode update pour effectuer l'ajout en BDD
            $updateIsOK = $categoryModel->update();

            if($updateIsOK) {
                header("Location: /category/update/".$id);
                exit();

            } else {
              $errorsList[] = "Une erreur est survenue lors de la modification de la catégorie :(";
            }
        }
    }

    public function add()
    {
        $this->show('category/add');
    }

    public function create() 
    {
        // On rente de récupérer les données entrées dans le formulaire
        $name     = filter_input(INPUT_POST, 'name',     FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $picture  = filter_input(INPUT_POST, 'picture',  FILTER_VALIDATE_URL);

        // On créé un tableau vide dans lequel on va ajouter toutes les erreurs rencontrées
        $errorsList = [];

        // Ensuite, on vérifie les valeurs avec des conditions successives
        if(empty($name) || $name === false) {
            $errorsList[] = "Le nom de la catégorie est vide ou invalide";
        }

        if(empty($subtitle) || $subtitle === false) {
            $errorsList[] = "Le sous-titre de la catégorie est vide ou invalide";
        }

        if($picture === false) {
            $errorsList[] = "L'URL de l'image est vide ou invalide";
        }

        // Si on arrive jusqu'ici avec $errorsList vide, c'est que tout s'est bien passé, on peut ajouter en BDD
        if(empty($errorsList)) {
            // 1. Instancier une nouvelle Categorie "vide"
            $categoryModel = new Category;

            // 2. Renseigner les valeurs grace aux setters
            $categoryModel->setName($name);
            $categoryModel->setSubtitle($subtitle);
            $categoryModel->setPicture($picture);

            // 3. Appeller la méthode insert pour effectuer l'ajout en BDD
            $insertIsOK = $categoryModel->insert();

            if($insertIsOK) {
                // Redirection vers la liste des catégories
                // TODO BONUS : Utiliser $router->generate plutot que l'URL en dur
                header("Location: /category/categories");

                // on stoppe l'execution du script pour éviter que du code essaie de se lancer pendant la redirection
                exit();
            } else {
              $errorsList[] = "Une erreur est survenue lors de l'ajout de la catégorie :(";
            }
          }
          
          // Si on arrive jusqu'ici, c'est qu'on a pas été redirigé
          // et donc que l'enregistrement n'a pas fonctionné
      
          dump( $errorsList );
      
          // TODO : Afficher les erreurs précédemment stockées
          // dans le tableau $errorsList sur la page du formulaire
          // Solution 1 : Afficher la vue et passer le tableau (sans redirection)
          // Solution 2 : Rediriger mais conserver les infos entre les pages (:o)
    }

    public function homeOrder() {

        // création d'un objet Category
        $categoryModel = new Category;
                
        $categoryDataForHome = $categoryModel->findAll();
        $params['categoryDataForHome'] = $categoryDataForHome;

        $this->show('category/order', $params);
    }

    public function select() { 

        $categoryModel = new Category;

        // On récupère toutes les catégories actuellement sur l'accueil  pour remettre leur home_order à 0
        $categories = $categoryModel->findAllHomepage();
        
        // Ensuite, pour chacune d'elle, je modifie leur home_order
        foreach ($categories as $categoryObject) {
                $categoryObject->setHomeOrder(0);
                $categoryObject->update();
        }

        // 3. Maintenant, je peux mettre les home_order sur les nouvelles catégories de la home    
        foreach( $_POST["emplacement"] as $emplacement => $categoryID ) :
        // Je vérifie que $categoryID est renseigné et supérieur à 0 (pour éviter un find(''))
            if( $categoryID > 0 ) :
                $category = Category::find( $categoryID );
                // Je vérifie si la catégorie existe avant d'essayer de la modifier
                if( $category !== false ) :
                    $category->setHomeOrder( $emplacement + 1 );
                    $category->update();
                endif;
            endif;
        endforeach;
  
        header("Location: /category/categories");
        exit();
    }
}