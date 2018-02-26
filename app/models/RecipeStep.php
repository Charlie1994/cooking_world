<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 8/01/2018
 * Time: 4:35 PM
 */

namespace app\models;
use core\Model;

/**
 * Class RecipeStep
 * @property integer recipe_id
 * @property integer step_num
 * @property string image
 * @property string content
 * @property boolean has_photo
 */
class RecipeStep extends Model
{

    /**
     * RecipeStep constructor.
     * @param int $recipe_id
     * @param int $step_num
     * @param string $image
     * @param string $content
     */
    public function __construct($recipe_id, $step_num, $image, $content)
    {
        $this->recipe_id = $recipe_id;
        $this->step_num = $step_num;
        $this->image = $image;
        $this->content = $content;
        if ($image == "")
            $this->has_photo = false;
        else
            $this->has_photo = true;
    }
}