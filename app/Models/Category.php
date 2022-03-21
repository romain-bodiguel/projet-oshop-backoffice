<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */
    public function setHomeOrder($home_order) {
        $this->home_order = $home_order;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
        return $category;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     *
     * @return Category[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     *
     * @return Category[]
     */
    public static function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $categories;
    }

    public function insert()
    {
        $pdo = Database::getPDO();
        $sql = "INSERT INTO `category` (name, subtitle, picture) 
                VALUES ( :name, :subtitle, :picture )";

        // peut aussi s'écrire :
        // $sql = "INSERT INTO `category` (name, subtitle, picture) VALUES ( '".$this->name."', '".$this->subtitle."', '".$this->picture."' )";

        // ou encore :
        // $sql = "INSERT INTO category (name, subtitle, picture) VALUES ('{$this->name}', '{$this->subtitle}', '{$this->picture}')";

        // $statement = $pdo->exec($sql);

        // Solution avec les requetes preparées
        // @see https://www.php.net/manual/fr/pdo.prepared-statements.php
        $pdoStatement = $pdo->prepare($sql);

        // A ce stade, on a une requete préparée SANS les valeurs
        // On va demander a PDO de remplacer les "étiquette" par les vraies valeurs
        // en les sécurisants au passage
        $insertIsOK = $pdoStatement->execute([
            ":name" => $this->name,
            ":subtitle" => $this->subtitle,
            ":picture" => $this->picture,
        ]);

        // Si au moins une ligne ajoutée
        if ($insertIsOK) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return $insertIsOK;
    }

    public function update()
    {   
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "UPDATE `category` SET                
                  `name`       = :name,
                  `subtitle`   = :subtitle,
                  `picture`    = :picture,
                  `home_order` = :home_order
                WHERE `id` = :id";
        
        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->bindValue( ":name",       $this->name,       PDO::PARAM_STR );
        $pdoStatement->bindValue( ":subtitle",   $this->subtitle,   PDO::PARAM_STR );
        $pdoStatement->bindValue( ":picture",    $this->picture,    PDO::PARAM_STR );
        $pdoStatement->bindValue( ":home_order", $this->home_order, PDO::PARAM_INT );
        $pdoStatement->bindValue( ":id",         $this->id,         PDO::PARAM_INT );

        $updatedRows = $pdoStatement->execute(
            // le tableau n'a pas besoin d'être déclaré dans notre execute car on a déjà déclaré les valeurs avec le bindValue
            
            // [
            // ":name"     => $this->name,
            // ":subtitle" => $this->subtitle,
            // ":picture"  => $this->picture,
            // ":id"       => $this->id,
            // ]

        );

        // On retourne true si $updatedRows > 0, false si $updatedRows <= 0
        // Ce return "compressé" correspond exactement à :
        //  if( $updatedRows > 0 )
        //  {
        //    return true;
        //  }
        //  else
        //  {
        //    return false;
        //  }
        return ($updatedRows > 0);
    }

    /**
     * Méthode permettant de supprimer un enregistrement dans la table category
     * L'objet courant doit contenir l'id
     * @return bool
     */
    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "DELETE FROM `category`
                WHERE `id` = :id";

        // Je prépare toujours la requête de la même façon
        $statement = $pdo->prepare( $sql );

        // Je remplace successivement chaque étiquette par sa valeur
        $statement->bindValue(":id", $this->id, PDO::PARAM_INT);

        // J'appelle execute, cette fois sans paramètre car les étiquette sont déjà remplacées ;)
        $deletedRows = $statement->execute();

        // On retourne VRAI, si au moins une ligne supprimée
        return ($deletedRows > 0);
    }
}
