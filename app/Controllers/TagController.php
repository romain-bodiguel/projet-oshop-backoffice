<?php

namespace App\Controllers;
use App\Models\Tag;
use App\Controllers\CoreController;

class TagController extends CoreController {

    public function list() {

        $tagModel = new Tag;
        $tagData = $tagModel->findAll();

        $params['tagData'] = $tagData;

        $this->show('tags/list', $params);
    }

    public function add() {

        $this->show('tags/add');
    }

    public function update(int $id) {
        $tagModel = Tag::find($id);
        $params['tagSelected'] = $tagModel;

        $this->show('tags/update', $params);
    }
}