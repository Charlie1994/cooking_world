<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 14/12/2017
 * Time: 7:33 AM
 */

namespace app\controllers;


use app\models\RecipeService;
use app\models\RecipeStep;
use core\Controller;
use core\View;

class Recipe extends Controller
{
    private $service;
    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
        $this->service = new RecipeService();
    }

    /** Loads the create recipe page(create_recipe.html)
     * @throws \Exception(Controller not found)
     */
    public function create_recipe_pageAction()
    {
        echo View::render("recipe/create_recipe.html.twig", array("user"=>isset($_SESSION['user'])?$_SESSION['user']:null));
    }

    public function publishRecipeAction()
    {
        if(isset($_SESSION['user']))
            $userid = $_SESSION['user']['user_id'];
        else {
            $result = Array("status" => 401, "msg" => "You have not logged in.");
            echo json_encode($result);
            return;
        }
        $recipe = new \app\models\Recipe();
        $recipe->recipe_name = $_POST['recipename'];
        $recipe->course = $_POST['course'];
        $recipe->cooking_time = $_POST['cooktime'];
        $recipe->description = $_POST['description'];
        $recipe->level = $_POST['level'];
        $recipe->serving_number = $_POST['serving'];
        $recipe->userid = $userid;
        $recipe->recipe_photo = $_POST['photo'];
        $recipe->tags = $_POST['tags'];
        $result = $this->service->insert_recipe($recipe);
        echo json_encode($result);
    }

    public function uploadIngredientAction()
    {
        $recipeid = $_POST['recipeid'];
        $ingredients = $_POST['ingredients'];
        $result = $this->service->insert_ingredient($ingredients, $recipeid);
        echo json_encode($result);
    }

    public function uploadStepAction()
    {
        $steps = $_POST['steps'];
        $recipeid = $_POST['recipeid'];
        $result = $this->service->insert_step($steps, $recipeid);
        echo json_encode($result);
    }

    /**
     * @throws \Exception
     */
    public function viewRecipeAction()
    {
        $recipeid = $_GET['id'];
        $results = $this->service->getRecipe($recipeid);
        echo View::render('recipe/view_recipe.html.twig', array("recipe"=>$results['recipe'], "ingredients"=>$results['ingredients'], "steps"=>$results['steps'], "comments"=>$results['comments'], "courses", "user"=>isset($_SESSION['user'])?$_SESSION['user']:null, "popular_recipes"=>$this->service->get_popular_recipes(), "recent_recipes"=>$this->service->get_recent_recipes(), "similar_recipes"=>$this->service->get_similar_recipes()));
    }

    public function submitCommentAction()
    {
        $userid = $_SESSION['userid'];
        $result = $this->service->insert_comment($userid, $_POST['content'], $_POST['recipeid']);
        echo json_encode($result);
    }

    /**
     * @throws \Exception
     */
    public function buyRecipePlanPageAction()
    {
        echo View::render('recipe/create_recipe.html.twig', array("user"=>isset($_SESSION['user'])?$_SESSION['user']:null));
    }

    public function recipePlanPaymentAction()
    {

    }

    /**
     * @throws \Exception
     */
    public function recipeSearchPageAction()
    {
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:"";
        $pagenum = isset($_GET['pagenum'])?$_GET['pagenum']:1;
        $recipes = $this->service->search_recipe($keyword, $pagenum);
        echo View::render("recipe/search_recipe.html.twig", array("keyword"=>$keyword, "pagecount"=>10, "recipes"=>$recipes, "popular_recipes"=>$this->service->get_popular_recipes(), "recent_recipes"=>$this->service->get_recent_recipes(), "similar_recipes"=>$this->service->get_similar_recipes(), "user"=>isset($_SESSION['user'])?$_SESSION['user']:null));
    }

    public function searchRecipeAction()
    {
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:"";
        $pagenum = isset($_GET['pagenum'])?$_GET['pagenum']:1;
        $recipes = $this->service->search_recipe($keyword, $pagenum);
        echo json_encode(["recipes"=>$recipes]);
    }
}