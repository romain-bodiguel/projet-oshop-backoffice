<body> 

    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $router->generate('main-home') ?>">oShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= $router->generate('main-home') ?>">Accueil <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('category-list') ?>">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('product-list') ?>">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('user-list') ?>">Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Types</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Marques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('tags-list') ?>">Tags</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('category-home-order') ?>">Sélections Accueil &amp; Footer</a>
                    </li>
                    <?php if( isset( $_SESSION['userObject'] ) ) : ?>
                  <li class="nav-item">
                      <a class="nav-link" href="<?= $router->generate( "deconnection" ) ?>">
                        Déconnexion       
                      </a>
                  </li>
                <?php else : ?>
                  <li class="nav-item">
                      <a class="nav-link" href="<?= $router->generate( "connection" ) ?>">
                        Connexion       
                      </a>
                  </li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>