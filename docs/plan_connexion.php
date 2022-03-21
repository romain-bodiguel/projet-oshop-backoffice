<?php

  // Etape 1 
  //   - Créer une route + un controleur + une méthode + une vue
  //   Pour gérer l'affichage du formulaire de connexion
  //   (Pour l'instant, on ne bloque pas l'accès aux pages si on est pas connecté pour simplifier)

  // Etape 2
  //   - Créer une route EN POST + une méthode
  //  Pour traiter les données du formulaire

    // Etape 2.0 : optionelle
    // Vérifier le bon remplissage du formulaire (champ non vide etc)

    // Etape 2.1
    // Vérifier les identifiants
    // Correspondance email / password avec la BDD
    //   => Coder une méthode du model AppUser pour trouver 
    //      un user via son email (findByEmail ?)

  // Etape 3
  // Connecter l'utilisateur =>
  // Conserver son status d'une page à l'autre pour ne pas
  // lui redemander ses identifiants a chaque nouvelle page
  // => On va utiliser les Sessions en PHP
  // https://www.php.net/manual/fr/function.session-start.php

    // Etape 3.1
    // Une fois l'utilisateur authentifié, on va stocker l'objet
    // AppUser qui lui correspond dans sa session

  // Etape 4 
  // Vérifier le contenu de $_SESSION pour controler
  // si un utilisateur est connecté, et si oui lequel ainsi que son role