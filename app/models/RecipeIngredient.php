<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 10/01/2018
 * Time: 1:37 PM
 */

namespace app\models;
use core\Model;

/**
 * Class RecipeIngredient
 * @package app\models
 * @property string name
 * @property string quantity
 */
class RecipeIngredient extends Model
{
    public function __construct($name, $quantity)
    {
        $this->name = $name;
        $this->quantity = $quantity;
    }
}