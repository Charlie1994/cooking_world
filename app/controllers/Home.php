<?php
/**
 * Class Home: controls all the operations about homepage
 *
 */
namespace app\controllers;
use app\models\Image;
use app\models\RecipeService;
use app\models\RecipeStep;
use app\models\Test;
use app\models\User;
use app\models\UserService;
use core\Controller;
use core\View;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Home extends Controller
{
    private $recipe_service;
    private $user_service;
    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
        $this->recipe_service = new RecipeService();
        $this->user_service = new UserService();
    }
    /**
     * Show the index page
     * @throws \Exception
     * @return void
     */
    public function homepageAction()
    {
        $carousel_cards = $this->recipe_service->get_carousel_recipes();
        $popular_recipes = $this->recipe_service->get_popular_recipes();
        $recent_recipes = $this->recipe_service->get_recent_recipes();
        $starter_left_recipe = $this->recipe_service->get_starter_left_recipe();
        $starter_right_recipes = $this->recipe_service->get_starter_right_recipes();
        $users = $this->user_service->getActiveUsers();
        echo View::render("home/homepage.html.twig", array("user"=>isset($_SESSION['user'])?$_SESSION['user']:null, "carousel_cards"=>$carousel_cards, "popular_recipes"=>$popular_recipes, "recent_recipes"=>$recent_recipes, "starter_left_recipe"=>$starter_left_recipe, "starter_right_recipes"=>$starter_right_recipes,"users"=>$users));
    }
    public function testAction()
    {
//        $user = new User();
        $test = isset($test) ? $test : "null";
        echo $test;
//        echo View::render("home/mytest.html.twig", array());
    }

    public function changeValue(&$v){
        $v = "hello world";
        echo $v;
    }

    public function donationSuccessAction()
    {
        echo View::render("home/donation_success.html.twig", array());
    }

    public function donationCancelAction()
    {
        echo View::render("home/donation_cancel.html.twig", array());
    }
}