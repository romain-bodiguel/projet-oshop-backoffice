<div class="container my-4">
        <a href="<?= $router->generate('user-list'); ?>" class="btn btn-success float-end">Retour</a>
        <h2>Ajouter un utilisateur</h2>
        <form action="<?= $router->generate('user-create'); ?>" method="POST" class="mt-5">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
            </div>
            <div class="mb-3">
                <label for="firstname" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom">
            </div>
            <div class="mb-3">
                <label for="role">Choisissez le rôle</label>
                <select class="form-select" name="role" id="role">
                    <option value="">-</option>
                    <option value="catalog-manager">Catalog Manager</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status">Choisissez le statut</label>
                <select class="form-select" name="status" id="status">
                    <option value="0">-</option>
                    <option value="1">Actif</option>
                    <option value="2">Désactivé</option>
                </select>
            </div>

            <input type="hidden" class="form-select" name="token" value="<?= $_SESSION['token'] ?>">
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Valider</button>
            </div>
        </form>
    </div>