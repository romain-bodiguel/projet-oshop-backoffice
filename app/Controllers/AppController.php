<?php

namespace App\Controllers;
use App\Models\AppModel;
use App\Controllers\CoreController;

class AppController extends CoreController {
    
    public function login() {

        $this->show('user/connection');
    }

    public function list() {

        $userModel = new AppModel;
        $userData = $userModel->findAll();

        $params['userData'] = $userData;

        $this->show('user/list', $params);
    }

    public function connect() {

        //je récupère la saisie utilisateur dans le champ email
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        //je crée un tableau vide qui va récupérer les éventuelles erreurs
        $errorsList = [];

        //si l'email ou mdp sont vides, je mets une erreur dans le $errorsList
        if(empty($email) || $email === false || empty($password) || $password === false) {
            $errorsList[] = "L'adresse email ou le mot de passe saisi est vide ou erroné";
            dump($errorsList);
            exit();
        }
        
        //si le $errorsList est vide, je crée un objet qui appelle la function findByEmail
        if(empty($errorsList)) {
            $connectModel = AppModel::findByEmail($email);
            //je vérifie juste que l'email est bien valide, sinon j'arrête le code
            if($connectModel === false) {
                echo "Email ou mot de passe incorrect";
                exit();
            }
        }
        
        //je récupère le mot de passe
        $passwordInDB = $connectModel->getPassword();
        
        //je vérifie si le mot de passe entré et celui haché dans la BDD correspondent
        if(password_verify($_POST['password'], $passwordInDB)) {
            
            // BONUS : Mise en session
            // On enregistre dans la session l'id de l'user connecté
            $_SESSION['userId'] = $connectModel->getId();
            //... et même tout l'objet
            $_SESSION['userObject'] = $connectModel;

            header('Location: /');
            exit();
        } else {
            echo "Email ou mot de passe incorrect";
        }
    }

    public function logout() {

        // Pour "déconnecter" l'utilisateur, plusieurs solutions :
        // 1- Détruire le "coffre" avec ses données : https://www.php.net/manual/fr/function.session-destroy.php

        // 2- Conserver le coffre, mais le vider : 
        $_SESSION = [];
        // On le redirige ensuite vers la page d'accueil
        header("Location: /");
        exit();
    }

    public function add()
    {
        $this->show('user/add');
    }

    public function create() 
    {
        // On rente de récupérer les données entrées dans le formulaire
        $email     = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password  = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $name      = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $role      = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $status    = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);


        // On créé un tableau vide dans lequel on va ajouter toutes les erreurs rencontrées
        $errorsList = [];

        // Ensuite, on vérifie les valeurs avec des conditions successives
        if(empty($email) || $name === false) {
            $errorsList[] = "L'email est vide ou invalide";
        }
        
        if(empty($password) || $name === false) {
            $errorsList[] = "Le mot de passe est vide ou invalide";
        }
        
        if(empty($firstname) || $name === false) {
            $errorsList[] = "Le prénom est vide ou invalide";
        }
        
        if(empty($name) || $name === false) {
            $errorsList[] = "Le nom est vide ou invalide";
        }

        // Si on arrive jusqu'ici avec $errorsList vide, c'est que tout s'est bien passé, on peut ajouter en BDD
        if(empty($errorsList)) {
            // 1. Instancier une nouvelle Categorie "vide"
            $appModel = new AppModel;

            // 2. Renseigner les valeurs grace aux setters
            $appModel->setEmail($email);
            $appModel->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $appModel->setFirstname($firstname);
            $appModel->setLastname($name);
            $appModel->setRole($role);
            $appModel->setStatus($status);

            // 3. Appeller la méthode insert pour effectuer l'ajout en BDD
            $insertIsOK = $appModel->insert();

            if($insertIsOK) {
                // Redirection vers la liste des catégories
                // TODO BONUS : Utiliser $router->generate plutot que l'URL en dur
                header("Location: /liste-utilisateurs");

                // on stoppe l'execution du script pour éviter que du code essaie de se lancer pendant la redirection
                exit();
            } else {
              $errorsList[] = "Une erreur est survenue lors de l'ajout de la catégorie :(";
            }
          }
          
          // Si on arrive jusqu'ici, c'est qu'on a pas été redirigé
          // et donc que l'enregistrement n'a pas fonctionné
          dump( $errorsList );
    }

}