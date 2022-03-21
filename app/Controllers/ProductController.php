<?php

namespace App\Controllers;
use App\Models\Product;
use App\Controllers\CoreController;

class ProductController extends CoreController {
    public function list()
    {
        // On récupère tous les produits
        $products = Product::findAll();
        
        // On les envoie à la vue
        $this->show('product/list', ["products" => $products]);
    }

    public function update($id)
    {
        $product = Product::find($id);
        $params['productSelected'] = $product;

        $this->show('product/update', $params);
    }

    public function addTag($id)
    {
        $tag = filter_input(INPUT_POST, 'tag', FILTER_VALIDATE_INT);

        // On vérifie l'existence et la validité de ces données (gestion d'erreur).
        $errorList = [];

        // Pour le "name", faut vérifier si la chaîne est présente *et* si elle
        // a passé le filtre de validation.
        if (empty($tag)) {
            $errorList[] = 'Le tag est vide';
        }

        if(empty($errorList)) {
            foreach ($_POST['tag'] as $emplacement => $tagId) {
                $product = new Product;
                $productTag = $product->addTag($id, $tagId);
            }
        }



        // if ($product->insert()) {
        //     // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
        //     header('Location: /product/produits');
        // }
        // else {
        //     // Sinon, on ajoute un message d'erreur à la page actuelle, et on laisse
        //     // l'utilisateur retenter la création.
        //     $errorList[] = 'La sauvegarde a échoué';
        // }
        dump($errorList);
    }

    public function add()
    {
        $this->show('product/add', ['product' => new Product()]);
        dump(['product' => new Product()]);
    }

    public function create() {

    // On tente de récupèrer les données venant du formulaire.
    $name        = filter_input(INPUT_POST, 'name',         FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description',  FILTER_SANITIZE_STRING);
    $picture     = filter_input(INPUT_POST, 'picture',      FILTER_VALIDATE_URL);
    $price       = filter_input(INPUT_POST, 'price',        FILTER_VALIDATE_FLOAT);
    $rate        = filter_input(INPUT_POST, 'rate',         FILTER_VALIDATE_INT);
    $status      = filter_input(INPUT_POST, 'status',       FILTER_VALIDATE_INT);
    $brand_id    = filter_input(INPUT_POST, 'brand_id',     FILTER_VALIDATE_INT);
    $category_id = filter_input(INPUT_POST, 'category_id',  FILTER_VALIDATE_INT);
    $type_id     = filter_input(INPUT_POST, 'type_id',      FILTER_VALIDATE_INT);

    // On vérifie l'existence et la validité de ces données (gestion d'erreur).
    $errorList = [];

    // Pour le "name", faut vérifier si la chaîne est présente *et* si elle
    // a passé le filtre de validation.
    if (empty($name)) {
        $errorList[] = 'Le nom est vide';
    }
    if ($name === false) {
        $errorList[] = 'Le nom est invalide';
    }
    // Pareil pour la "description".
    if (empty($description)) {
        $errorList[] = 'La description est vide';
    }
    if ($description === false) {
        $errorList[] = 'La description est invalide';
    }
    // Pour l'URL de l'image "picture", le filtre vérifie forcément sa présence aussi.
    if ($picture === false) {
        $errorList[] = 'L\'URL d\'image est invalide';
    }
    // Etc.
    if ($price === false) {
        $errorList[] = 'Le prix est invalide';
    }
    if ($rate === false) {
        $errorList[] = 'La note est invalide';
    }
    if ($status === false) {
        $errorList[] = 'Le statut est invalide';
    }
    if ($brand_id === false) {
        $errorList[] = 'La marque est invalide';
    }
    if ($category_id === false) {
        $errorList[] = 'La catégorie est invalide';
    }
    if ($type_id === false) {
        $errorList[] = 'Le type est invalide';
    }
    // NOTE: clairement, ces validations ne sont pas suffisantes
    // (ex. relations par clé étrangère : comment vérifier que les autres ressources
    // existent vraiment ?)

    // S'il n'y a aucune erreur dans les données...
    if (empty($errorList)) {
        // On instancie un nouveau modèle de type Category.
        $product = new Product();

        // On met à jour les propriétés de l'instance.
        $product->setName($name);
        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setRate($rate);
        $product->setStatus($status);
        $product->setBrandId($brand_id);
        $product->setCategoryId($category_id);
        $product->setTypeId($type_id);

        // On tente de sauvegarder les données en DB...
        if ($product->insert()) {
            // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
            header('Location: /product/produits');
        }
        else {
            // Sinon, on ajoute un message d'erreur à la page actuelle, et on laisse
            // l'utilisateur retenter la création.
            $errorList[] = 'La sauvegarde a échoué';
        }
    }

    // Si on arrive jusqu'ici, c'est qu'on a pas été redirigé
    // et donc que l'enregistrement n'a pas fonctionné
    // Donc pour le moment, on affiche la ou les erreurs

    dump( $errorList );

    // TODO : Afficher les erreurs précédemment stockées
    // dans le tableau $errorsList sur la page du formulaire
    // Solution 1 : Afficher la vue et passer le tableau (sans redirection)
    // Solution 2 : Rediriger mais conserver les infos entre les pages (:o)
    }
}