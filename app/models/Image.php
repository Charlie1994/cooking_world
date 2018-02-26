<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 14/12/2017
 * Time: 3:19 PM
 */

namespace app\models;


use app\Config;
use core\Model;

class Image extends Model
{
    public function getDirname()
    {
        return sprintf("%s/cooking_world/resource/images/%s.%s", "a");
    }
    public function upload_recipe_image()
    {}
}