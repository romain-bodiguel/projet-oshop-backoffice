<?php

// POINT D'ENTRÉE UNIQUE :
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require_once '../vendor/autoload.php';

// On démarre le système de gestion des sessions de PHP
session_start();

/* ------------
--- ROUTAGE ---
-------------*/

// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
} else { // sinon
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter,
// afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"
$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => '\App\Controllers\MainController' // On indique le FQCN de la classe
    ],
    'main-home'
);

$router->map(
    'GET',
    '/connexion',
    [
        'method' => 'login',
        'controller' => '\App\Controllers\AppController' // On indique le FQCN de la classe
    ],
    'connection'
);

$router->map(
    'GET',
    '/deconnexion',
    [
        'method' => 'logout',
        'controller' => '\App\Controllers\AppController' // On indique le FQCN de la classe
    ],
    'deconnection'
);

$router->map(
    'POST',
    '/connexion',
    [
        'method' => 'connect',
        'controller' => '\App\Controllers\AppController' // On indique le FQCN de la classe
    ],
    'connection-submit'
);

$router->map('GET', '/liste-utilisateurs', '\App\Controllers\AppController::list', 'user-list');

$router->map('GET', '/ajout-utilisateurs', '\App\Controllers\AppController::add', 'user-add');

$router->map('POST', '/creer-utilisateurs', '\App\Controllers\AppController::create', 'user-create');

$router->map(
    'GET',
    '/category/categories',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    ],
    'category-list'
);

$router->map(
    'GET',
    '/category/modifier-categories/[i:id]',
    [
        'method' => 'edit',
        'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    ],
    'category-edit'
);

$router->map(
    'POST',
    '/category/modifier-categories/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    ],
    'category-update'
);

$router->map(
    'GET',
    '/category/ajout-categories',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    ],
    'category-add'
);

$router->map(
    'POST',
    '/category/ajout-categories',
    '\App\Controllers\CategoryController::create',
    //l'écriture ci-dessus équivaut à celle ci-dessous :
    // [
    //     'method' => 'create',
    //     'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    // ],
    'category-create'
);

$router->map(
    'GET',
    '/ordre-categories',
    [
        'method' => 'homeOrder',
        'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    ],
    'category-home-order'
);

$router->map(
    'POST',
    '/ordre-categories',
    [
        'method' => 'select',
        'controller' => '\App\Controllers\CategoryController' // On indique le FQCN de la classe
    ],
    'category-select'
);

$router->map(
    'GET',
    '/product/produits',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\ProductController' // On indique le FQCN de la classe
    ],
    'product-list'
);

$router->map(
    'GET',
    '/category/modifier-produit/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\ProductController' // On indique le FQCN de la classe
    ],
    'product-update'
);

$router->map(
    'POST',
    '/category/modifier-produit/[i:id]',
    [
        'method' => 'addTag',
        'controller' => '\App\Controllers\ProductController' // On indique le FQCN de la classe
    ],
    'product-add-tag'
);

$router->map(
    'GET',
    '/product/ajout-produits',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\ProductController' // On indique le FQCN de la classe
    ],
    'product-add'
);

$router->map(
    'POST',
    '/product/ajout-produits',
    [
        'method' => 'create',
        'controller' => '\App\Controllers\ProductController' // On indique le FQCN de la classe
    ],
    'product-create'
);

$router->map(
    'GET',
    '/tags/liste-tags',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\TagController' // On indique le FQCN de la classe
    ],
    'tags-list'
);

$router->map(
    'GET',
    '/tags/ajout-tags',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\TagController' // On indique le FQCN de la classe
    ],
    'tags-add'
);

$router->map(
    'GET',
    '/tags/modifier-tags/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\TagController' // On indique le FQCN de la classe
    ],
    'tags-update'
);


/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();
// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

// ATTENTION : Pensez à mettre à jour AltDispatcher avec `composer update` ;)
// Grace à cette méthode, on peut transmettre des valeurs au constructeur du controlleur
// Ici, c'est le constructeur de CoreController qui s'en charge
$dispatcher->setControllersArguments($router, $match);

// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();