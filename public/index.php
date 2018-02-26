<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('core\Error::errorHandler');
set_exception_handler('core\Error::exceptionHandler');

session_start();

/**
 * Routing
 */
$router = new \core\Router();

// Home controller
$router->add('index', ['controller' => 'Home', 'action' => 'index']);
$router->add('home', ['controller' => 'Home', 'action' => 'homepage']);
$router->add('donation/success', ['controller' => 'Home', 'action' => 'donationSuccess']);
$router->add('donation/cancel', ['controller' => 'Home', 'action' => 'donationCancel']);
$router->add("home/test", ['controller' => 'Home', 'action' => 'test']);

// Recipe controller
$router->add("recipe/create-recipe", ["controller"=>"Recipe", "action"=>"create_recipe_page"]);
$router->add("recipe/upload/step", ["controller"=>"Recipe", "action"=>"uploadStep"]);
$router->add("recipe/upload/ingredient", ["controller"=>"Recipe", "action"=>"uploadIngredient"]);
$router->add("recipe/publish", ["controller"=>"Recipe", "action"=>"publishRecipe"]);
$router->add("recipes", ["controller"=>"Recipe", "action"=>"recipeSearchPage"]);
$router->add("recipe/search", ["controller"=>"Recipe", "action"=>"searchRecipe"]);
$router->add("recipe", ["controller"=>"Recipe", "action"=>"viewRecipe"]);

// User controller
$router->add("user/sign-up", ['controller' => 'User', 'action' => 'registerPage']);
$router->add("user/register", ['controller' => 'User', 'action' => 'register']);
$router->add("user/getVerificationCode", ['controller' => 'User', 'action' => 'getVerificationCode']);
$router->add("user/validateUsername", ['controller' => 'User', 'action' => 'validateUsername']);
$router->add("user/validateEmail", ['controller' => 'User', 'action' => 'validateEmail']);
$router->add("user/login", ['controller' => 'User', 'action' => 'login']);

// Map Controller
$router->add("restaurant/map", ['controller' => 'Restaurant', 'action' => 'mapPage']);
$router->add("restaurant/nearby", ['controller' => 'Restaurant', 'action' => 'getNearbyRestaurants']);

$router->dispatch($_SERVER['QUERY_STRING']);
