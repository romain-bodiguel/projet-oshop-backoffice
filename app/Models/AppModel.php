<?php

namespace App\Models;

use PDO;
use App\Utils\Database;

class AppModel extends CoreModel {
    
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $role;

    /**
     * @var int
     */
    private $status;

    /**
     * Get the value of email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     */ 
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get the value of firstname
     *
     * @return  string
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  string  $firstname
     */ 
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the value of lastname
     *
     * @return  string
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  string  $lastname
     */ 
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the value of role
     *
     * @return  string
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param  string  $role
     */ 
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */ 
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public static function find($id) 
    {
        // TODO
    }

    public static function findAll() 
    {
        $pdo = Database::getPDO();

        $sql = "SELECT * FROM `app_user`";

        $statement = $pdo->query($sql);
        $userData = $statement->fetchAll(PDO::FETCH_CLASS, __CLASS__);

        return $userData;
    }

    public static function findByEmail($email) 
    {
        $pdo = Database::getPDO();

        $sql = "SELECT * FROM `app_user`
                WHERE `email` LIKE :email";

        // On utilise une requête préparée car $email provient d'une saisie de l'utilisateur, donc on se méfie des injections SQL !
        $statement = $pdo->prepare($sql);
        // On dit à PDO comment "remplacer" et sécuriser les "étiquettes" de ma requete préparée
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        // J'execute ma requête préparée. Attention, ça ne retourne pas un jeu de résultats !
        $statement->execute();

        if ($statement) {
            $userEmail = $statement->fetchObject('App\Models\AppModel');
            return $userEmail;
        } else {
            return false;
        }        
    }

    public function insert()
    {
        $pdo = Database::getPDO();
        $sql = "INSERT INTO `app_user` (`email`, `password`, `firstname`, `lastname`, `role`, `status`) 
                VALUES (:email, :password, :firstname, :name, :role, :status )";

        $pdoStatement = $pdo->prepare($sql);

        // A ce stade, on a une requete préparée SANS les valeurs
        // On va demander a PDO de remplacer les "étiquette" par les vraies valeurs
        // en les sécurisants au passage
        $insertIsOK = $pdoStatement->execute([
            ":email"     => $this->email,
            ":password"  => $this->password,
            ":firstname" => $this->firstname,
            ":name"      => $this->lastname,
            ":role"      => $this->role,
            ":status"    => $this->status, 
        ]);

        // Si au moins une ligne ajoutée
        if ($insertIsOK) {
            $this->id = $pdo->lastInsertId();
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return $insertIsOK;
    }

    public function update() 
    {
        // TODO
    }

    public function delete() 
    {
        // TODO
    }
}