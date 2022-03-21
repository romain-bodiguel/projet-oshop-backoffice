<div class="container my-4">

    <a href="<?= $router->generate( "category-list" ) ?>" class="btn btn-success float-end">
      Retour
    </a>
    <h2>Catégories en page d'accueil</h2>

    <form action="<?= $router->generate("category-home-order") ?>" method="POST" class="mt-5">
        <div class="row">
        <?php for ($i=1; $i<=5; $i++) : ?>
        <div class="col">
            <div class="form-group">
                <label for="emplacement<?= $i ?>">Emplacement #<?= $i ?></label>
                <select class="form-control" id="emplacement<?= $i ?>" name="emplacement[]">
                    <option value="">Choisissez :</option>
                    <?php foreach ($categoryDataForHome as $id => $currentCategory) : ?>
                    <option 
                        value="<?= $currentCategory->getId(); ?>"
                        <?= $currentCategory->getHomeOrder() == $i ? "selected" : "" ?>
                    >
                        <?= $currentCategory->getName(); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endfor; ?>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" >
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </div>
</form>