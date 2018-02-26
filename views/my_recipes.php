<!DOCTYPE html>
<html>
<head>
    <?php
    include "../services/error.php";
    include "default_head_resource.html";
          include "../services/user_service.php";
            $service = new MyUser();
            $myuser = $service->getUserInformation($_GET["userid"]);?>
    <title>Cooking World</title>
</head>
<?php
if(!isset($_GET["mobile"])){
    include "user_nav_bar.php";
}?>
<body class="user-profile-body">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <div class="user-profile-tabs">
                <img src="<?=$myuser["ImageURL"]?>" class="user-avatar-lg img-responsive img-circle"><br>
                <?php
                if(isset($_SESSION["userid"])){
                    if( $_SESSION["userid"] == $_GET["userid"] ){
                        ?>
                        <a id="changePhoto" class="pull-right" href="#" style="font-size: smaller;color: #ff5e3e" >[change]</a>
                        <?php
                    }
                }
                ?>
                <h3 class="user-profile-name"><?=$myuser["UserName"]?> </h3>
                <div class="user-item-list">
                    <ul class="list-group">
                        <a href="my_recipes.php?userid=<?=$_GET["userid"]?>" class="list-group-item active" id="my-comment-btn">My Shared Recipes</a>
                        <a href="my_favorites.php?userid=<?=$_GET["userid"]?>" class="list-group-item" id="my-reply-btn">My Favorites</a>
                        <a href="my_activities.php?userid=<?=$_GET["userid"]?>" class="list-group-item" id="my-post-btn">My Activities</a>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-md-offset-1">
            <div class="user-item-body">
                <table class="table table-hover myTable">
                    <thead>
                    <tr>
                        <td width="200">Recipe Name</td>
                        <td width="50" class="text-center">Operation</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $myrecipes = $service->getMyRecipes($_GET["userid"]);
                        while ($row = $myrecipes->fetch_assoc()){
                            ?>
                            <tr>
                                <td><a href="view_recipe.php?recipeid=<?=$row["RecipeID"]?>"><?=$row["RecipeName"]?></a> </td>
                                <td class="text-center"><a style="color: #ff5e3e;" href="deleteRecipe.php?recipeid=<?=$row["RecipeID"]?>&userid=<?=$_GET["userid"]?>"><span class="glyphicon glyphicon-trash"></span></a></td>
                            </tr>
                    <?php
                        }
                        $myrecipes->close();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form id="img-upload-form">
    <input name="file" type="file" id="img-upload" style="display: none">
</form>
</body>
<script>
    $(function(){
        var userid = $('#userid').val();
        var container = $('.user-item-body');

    });
</script>
</html>