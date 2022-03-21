<div class="container my-4">
        <a href="<?= $router->generate('product-list'); ?>" class="btn btn-success float-end">Retour</a>
        <h2>Ajouter un produit</h2>
        
        <form action="" method="POST" class="mt-5">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom du produit">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Description du produit" aria-describedby="descriptionHelpBlock">
                <small id="descriptionHelpBlock" class="form-text text-muted">
                    Sera affiché sur la page d'accueil comme bouton devant l'image
                </small>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Image</label>
                <input type="text" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
                <small id="pictureHelpBlock" class="form-text text-muted">
                    URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
                </small>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Prix</label>
                <input type="text" class="form-control" id="name" name="price" placeholder="Prix">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Note</label>
                <input type="text" class="form-control" id="name" name="rate" placeholder="Note du produit">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Statut</label>
                <input type="text" class="form-control" id="name" name="status" placeholder="Statut du produit">
            </div>
            <div class="form-group">
                <label for="category">Catégorie</label>
                <select class="form-select" id="category" name="category_id" aria-describedby="categoryHelpBlock" value="<?= $product->getCategoryId() ?>">
                    <option value="1">Détente</option>
                    <option value="2">Au travail</option>
                    <option value="3">Cérémonie</option>
                </select>
                <small id="categoryHelpBlock" class="form-text text-muted">
                    La catégorie du produit 
                </small>
            </div>
            <div class="form-group">
                <label for="brand">Marque</label>
                <select  class="form-select" id="brand" name="brand_id" aria-describedby="brandHelpBlock" value="<?= $product->getBrandId() ?>">
                    <option value="1">oCirage</option>
                    <option value="2">BOOTstrap</option>
                    <option value="3">Talonette</option>
                </select>
                <small id="brandHelpBlock" class="form-text text-muted">
                    La marque du produit 
                </small>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-select" id="type" name="type_id" aria-describedby="typeHelpBlock" value="<?= $product->getTypeId() ?>">
                    <option value="1">Chaussures de ville</option>
                    <option value="2">Chaussures de sport</option>
                    <option value="3">Tongs</option>
                </select>
                <small id="typeHelpBlock" class="form-text text-muted">
                    Le type de produit 
                </small>
            </div>
            <div class="form-group">
                <label for="type">Tag</label>
                <select class="form-select" id="tag" name="tag_id[]" aria-describedby="tagHelpBlock" multiple>
                    <option value="1">Oclock</option>
                    <option value="2">Pokémon</option>
                    <option value="3">Films</option>
                    <option value="4">Séries</option>
                    <option value="5">Sport</option>
                    <option value="6">Animal</option>
                    <option value="7">Fun</option>
                </select>
                <small id="tagHelpBlock" class="form-text text-muted">
                    Les tags du produit (Ctrl + Clic pour sélectionner plusieurs éléments)
                </small>
            </div>
      
      <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
  </form>
</div>