<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
// Ici CoreModel est une classe abtraite, il est interdit de l'instancier
// https://www.php.net/manual/fr/language.oop5.abstract.php
abstract class CoreModel
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;

    // Ici, j'indique que toute classe quyi hérite de CoreModel DOIT ABSOLUMENT implémenter une méthode find correspondant à la function ici indiquée comme abstraite
    // On défini uniquement la SIGNATURE des fonction abstraites, c'est à dire, le nom, les paramètres (et l'éventuel type de retour), mais aussi leur caractère static
    abstract static function find($id);
    abstract static function findAll();
    abstract function insert();
    abstract function update();
    abstract function delete();

    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
}
