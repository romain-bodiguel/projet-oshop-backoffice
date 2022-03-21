<div class="container my-4">
        <a href="<?= $router->generate('main-home'); ?>" class="btn btn-success float-end">Retour</a>
        <h2>Se connecter</h2>
        <form action="" method="POST" class="mt-5">
            <div class="mb-3">
                <label for="name" class="form-label">Identifiant</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Adresse e-mail">
            </div>
            <div class="mb-3">
                <label for="subtitle" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" aria-describedby="subtitleHelpBlock" placeholder="Mot de passe">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Se connecter</button>
            </div>
        </form>
    </div>