<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 8/01/2018
 * Time: 8:47 PM
 */

namespace app\models;


use core\Model;

class Test extends Model
{
    public function __construct($name)
    {
        $this->a = $name;
    }
}