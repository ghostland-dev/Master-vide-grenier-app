<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use Exception;

/**
 * API controller
 */
class Api extends \Core\Controller
{


    public function ProductsAction()
    {
        $query = $_GET['sort'];

        $articles = Articles::getAll($query);

        header('Content-Type: application/json');
        echo json_encode($articles);
    }
    public function productsallAction()
    {
        $articles = Articles::getAll('');

        header('Content-Type: application/json');
        echo json_encode($articles);
    }

    public function CitiesAction()
    {

        $cities = Cities::search($_GET['query']);

        header('Content-Type: application/json');
        echo json_encode($cities);
    }
    public function CitiesallAction()
    {

        $cities = Cities::getAll();

        header('Content-Type: application/json');
        echo json_encode($cities);
    }


    public function ProductAction()
    {
        $id = $this->route_params['id'];

        $article = Articles::getOne($id);

        header('Content-Type: application/json');
        echo json_encode($article);
    }
    public function CityAction()
    {
        $id = $this->route_params['id'];

        $city = Cities::getOne($id);

        header('Content-Type: application/json');
        echo json_encode($city);
    }
}
