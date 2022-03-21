<div class="container my-4">
        <a href="<?= $router->generate('tags-list'); ?>" class="btn btn-success float-end">Retour</a>
        <h2>Modifier un tag</h2>
        <form action="<?= $router->generate("tags-update", [ 'id' => $tagSelected->getId() ]); ?>" method="POST" class="mt-5">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" placeholder="Nom de la catégorie" value="<?= $tagSelected->getName(); ?>">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Valider</button>
            </div>
        </form>
    </div>