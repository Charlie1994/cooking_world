<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 27/01/2018
 * Time: 11:14 AM
 */

namespace app\controllers;


use app\models\RestaurantService;
use core\Controller;
use core\View;

class Restaurant extends Controller
{
    private $service;
    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
        $this->service = new RestaurantService();
    }


    /**
     * @throws \Exception
     */
    public function mapPageAction()
    {
        echo View::render("restaurant/nearby_restaurants.html.twig", array("user"=>isset($_SESSION['user'])?$_SESSION['user']:null));
    }

    public function getNearbyRestaurantsAction()
    {
        $userpos = $_GET['userpos'];
        $userlat = doubleval($userpos['lat']);
        $userlng = doubleval($userpos['lng']);
        $distance = (int)$_GET['distance'];
        $pagenum = $_GET['pagenum'];
        $result = $this->service->search_nearby_restaurants($userlng, $userlat, $distance);
        echo json_encode($result);
    }
}