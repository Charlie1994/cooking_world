<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 10/01/2018
 * Time: 1:45 PM
 */

namespace app\models;
use core\Model;

/**
 * Class RecipeComment
 * @package app\models
 * @property integer recipe_id
 * @property integer commentid
 * @property integer userid
 * @property string username
 * @property string userphoto
 * @property string create_time
 * @property string content
 */
class RecipeComment extends Model
{
    /**
     * RecipeComment constructor.
     * @param int $recipe_id
     * @param int $commentid
     * @param int $userid
     * @param string $username
     * @param string $create_time
     * @param string $content
     * @param string $userphoto
     */
    public function __construct($recipe_id, $commentid, $userid, $username, $create_time, $content, $userphoto)
    {
        $this->commentid = $commentid;
        $this->userid = $userid;
        $this->username = $username;
        $this->create_time = $create_time;
        $this->content = $content;
        $this->userphoto = $userphoto;
        $this->recipe_id = $recipe_id;
    }
}