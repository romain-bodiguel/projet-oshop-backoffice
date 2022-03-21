<?php

namespace App\Controllers;

abstract class CoreController
{
    // Le constructeur de CoreController sera utilisé par tout ses enfants
    // Grace à setControllersArguments() dans index.php, on peut recevoir le router et le match en paramètres !
    public function __construct($router, $match) 
    {
        //==================================================
        // Access Control List
        //==================================================

        // 1. Récupérer le nom de la route courante
        $currentRouteName = $match['name'];

        // 2. Définir une liste de permissions pour chaque route (uniquement celles necessitant d'être connecté)
        $acl = [
            "main-home"       => ["admin", "catalog-manager"],
            "main-home-order" => ["admin", "catalog-manager"],
            "main-select"     => ["admin", "catalog-manager"],

            "user-add"        => ["admin"],
            "user-create"     => ["admin"],
            "user-list"       => ["admin"],
            "user-edit"       => ["admin"],
            "user-update"     => ["admin"],
            "user-delete"     => ["admin"],

            "category-add"    => ["admin", "catalog-manager"],
            "category-create" => ["admin", "catalog-manager"],
            "category-list"   => ["admin", "catalog-manager"],
            "category-edit"   => ["admin", "catalog-manager"],
            "category-update" => ["admin", "catalog-manager"],
            "category-delete" => ["admin", "catalog-manager"],

            "product-add"     => ["admin", "catalog-manager"],
            "product-create"  => ["admin", "catalog-manager"],
            "product-list"    => ["admin", "catalog-manager"],
            "product-edit"    => ["admin", "catalog-manager"],
            "product-update"  => ["admin", "catalog-manager"],
            "product-delete"  => ["admin", "catalog-manager"],
        // Et ainsi de suite pour toutes les routes
        ];

        // 3. Vérifier si la route actuelle est dans la liste
        if(array_key_exists($currentRouteName, $acl))
        {
            // 4. Si c'est le cas, vérifier que l'user est connecté et que son rôle correspond
            //  On récupère les rôles autorisés à accéder à la route actuelle
            $authorizedRolesForCurrentRoute = $acl[$currentRouteName];

            // On vérifie que notre user à les droits avec checkAuthorization
            $this->checkAuthorization($authorizedRolesForCurrentRoute);

            // Et c'est tout ! checkAuthorization s'occupera de rediriger ou d'afficher une 403 si l'utilisateur n'est pas connecté / n'a pas le bon role 
        }

        // 5. Sinon, laisse la page s'executer normalement car c'est une page qui ne necessite pas de connexion

        //==================================================
        // Token anti-CSRF
        //==================================================

        // Liste des routes en POST pour lesquels il faut vérifier le token
        $csrfTokenCheckPost = [
            "user-create",
            "category-create",
            "category-update",
            // etc...
        ];
    
        // La même chose pour les routes en GET
        $csrfTokenCheckGet = [
            "user-delete",
            "category-delete",
            // etc...
        ];

        // Si la route actuelle necessite une vérification de Token en POST
        if(in_array($currentRouteName, $csrfTokenCheckPost)) {

            // On récupère le token en POST
            $token = $_POST['token'] ?? "";

            // On récupère le token stocké en SESSION
            $sessionToken = $_SESSION['token'] ?? "";

            // On compare les deux
            if($token !== $sessionToken || empty($token)) {
                // TODO : Erreur 403
                dd("ERREUR DE TOKEN");
            } else {
                dd("TOKEN OK");
                // Sinon, tout va bien, on en profite pour retirer le token de la session
                // Ca permettra de s'assurer que le formulaire ne sera pas soumis plusieurs fois
                unset( $_SESSION['token'] );
            }
        }

        // TODO Faire les même vérif avec GET

        // Une fois les vérifications faites, on peut générer un token pour la nouvelle page
        // Pour bien comprendre, on va ajouter le nom de la route actuelle a chaque token
        $_SESSION['token'] = $currentRouteName . "-" . bin2hex(random_bytes(16));
    }

    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewData Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewData = [])
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewData est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewData['currentPage'] = $viewName;

        // définir l'url absolue pour nos assets
        $viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewData['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewData);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewData est disponible dans chaque fichier de vue
        require_once __DIR__ . '/../views/layout/header.tpl.php';
        require_once __DIR__ . '/../views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../views/layout/footer.tpl.php';
    }

    protected function checkAuthorization($authorizedRoles = []) 
    {
        // Vérifier si l'user est connecté
        if(isset($_SESSION['userObject'])) :
            // S'il est connecté, on récupère l'user, et donc son rôle
            $currentUserRole = $_SESSION['userObject']->getRole();

            // Vérifier si le rôle est autorisé à accéder à la page
            // c'est à dire, vérifier si le rôle est dans le tableau $authorizedRoles
            // Si oui, alors j'ai le droit d'accéder à la page, on return true
            if(in_array($currentUserRole, $authorizedRoles)) :
                // On retourne true et la page va s'afficher normalement vu qu'on a le droit
                return true;

            // Sinon
            else :
                // on envoi le header 403 Forbidden
                http_response_code(403);
                // on affiche l'erreur
                $this->show('error/err403');
                // on arrête le script, sinon la page demandée va s'afficher et potentiellement executer du code auquel l'user n'est pas autorisé à accéder
                exit();
  
          endif;
        // Sinon, on l'envoi vers la page de connexion
        else :

            header("Location: /connexion");
            exit();

        endif;
    }   
}
