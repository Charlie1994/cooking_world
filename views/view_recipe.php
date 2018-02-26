




<html>
<head>
    <?php include "default_head_resource.html";
    include "../services/view_recipe_service.php";
    $obj = new ViewRecipe();
    $recipeid = $_GET["recipeid"];?>

    <title>View Recipe</title>
</head>
<body>
<?php
    if (!isset($_GET["mobile"]))
        include "user_nav_bar.php"?>
<?php
    if (!isset($_GET["mobile"]))
        include "logo_banner.php" ?>
<?php
    $riResult = $obj->getRecipeProfile($recipeid);
    $recipeInfo = mysqli_fetch_assoc($riResult);
?>
<div class="container-fluid">
    <div class="wrap">
        <ol class="breadcrumb">
            <li><a href="../app/views/home/homepage.html.twig">Homepage</a></li>
            <li><a href="#"><?=$recipeInfo["RecipeName"]?></a></li>
        </ol>
        <div class="single-page">
            <div class="col-md-8 content-left single-post">
                <div class="blog-posts">
                    <img class="img-rounded" src="<?=$recipeInfo["ImgUrl"]?>"/>
                    <h3 class="post text-center" id="title"><?=$recipeInfo["RecipeName"]?></h3>
                    <div class="article-time">
                        <h4><?=$recipeInfo["ViewCount"]?>&nbsp;Views&nbsp;&nbsp;
                        <?=$recipeInfo["FavoriteCount"]?>&nbsp;stars</h4>
                        <?php
                        if (isset($_SESSION["userid"])) {
                            $isFavorited = $obj->checkIsFavorited($recipeid);
                            if ($isFavorited) {
                                ?>
                                <button class="pull-right collect-btn btn btn-default" style="margin-bottom: 1em"><span
                                            class="glyphicon glyphicon-star"></span> Favorited
                                </button>
                                <?php
                            } else {
                                ?>
                                <button class="pull-right collect-btn btn btn-warning" style="margin-bottom: 1em"><span
                                            class="glyphicon glyphicon-star-empty"></span> Add to favorites
                                </button>
                            <?php }
                        }
                        ?>
                    </div>
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <td colspan="2">
                                <img class="md-avatar" src="<?=$recipeInfo["ImageURL"]?>"/>
                                <a href="#"><?=$recipeInfo["UserName"]?></a>
                                <p class="text-right"><?=$recipeInfo["createdate"]?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4 style="color: #1b6d85">Introduction:</h4>
                                <p><?=$recipeInfo["Introduction"]?></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-6">
                                <p><strong class="text-capitalize view-recipe">level:&nbsp;</strong>
                                <?php
                                    if($recipeInfo["Level"]==1)
                                        echo "Easy";
                                    else if($recipeInfo["Level"]==1)
                                        echo "Medium";
                                    else
                                        echo "Hard";
                                ?></p>
                            </td>
                            <td class="col-md-6">
                                <p><strong class="text-capitalize view-recipe">Cook time:&nbsp;</strong>
                                    <?php
                                    if($recipeInfo["CookTime"]==1)
                                        echo "Around 10 minutes";
                                    else if($recipeInfo["CookTime"]==2)
                                        echo "10-30 minutes";
                                    else if($recipeInfo["CookTime"]==3)
                                        echo "30-60 minutes";
                                    else
                                        echo "Over an hour";
                                    ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><strong class="text-capitalize view-recipe">Course:&nbsp;</strong>
                                    <?php
                                    if($recipeInfo["Course"]==1)
                                        echo "Appetizers";
                                    else if($recipeInfo["Course"]==2)
                                        echo "Starters";
                                    else if($recipeInfo["Course"]==3)
                                        echo "Main Courses";
                                    else if($recipeInfo["Course"]==4)
                                        echo "Side Dishes";
                                    else if($recipeInfo["Course"]==5)
                                        echo "Desserts";
                                    else
                                        echo "Drinks";
                                    ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><strong class="text-capitalize view-recipe">ingredients</strong></h4>
                            </td>
                        </tr>

                        <?php
                            $ingredients = $obj->getIngredients($recipeid);
                            $times = 1;
                            $row_num = $ingredients->num_rows;
                            $times = $row_num/2;
                            $flag = 1;
                            if($row_num%2==0){
                                $times = (int)$times;
                                $flag = 2;
                            }else{
                                $times = (int)$times+1;
                            }
                            for ($i=0;$i<$times;$i++){
                                if ($i!=$times-1||$flag==2){
                                    $ing1 = $ingredients->fetch_assoc();
                                    $ing2 = $ingredients->fetch_assoc();
                                    echo "<tr>
                                            <td>
                                                <div class=\"col-md-6 text-left\">
                                                    <i class=\"ingredient-amount\">".$ing1["Amount"]."</i>
                                                </div>
                                                <div class=\"col-md-6 text-right\">
                                                    <p class=\"\">".$ing1["IngredientName"]."</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class=\"col-md-6 text-left\">
                                                    <i class=\"ingredient-amount\">".$ing2["Amount"]."</i>
                                                </div>
                                                <div class=\"col-md-6 text-right\">
                                                    <p class=\"\">".$ing2["IngredientName"]."</p>
                                                </div>
                                            </td>
                                        </tr>";
                                }else{
                                    $ing1 = $ingredients->fetch_assoc();
                                    echo "<tr>
                                            <td>
                                                <div class=\"col-md-6 text-left\">
                                                    <i class=\"ingredient-amount\">".$ing1["Amount"]."</i>
                                                </div>
                                                <div class=\"col-md-6 text-right\">
                                                    <p class=\"\">".$ing1["IngredientName"]."</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class=\"col-md-6 text-left\"></div>
                                                <div class=\"col-md-6 text-right\"></div>
                                            </td>
                                        </tr>";
                                }
                            }
                        ?>
                    </table>
                    <div class="recipe-steps">
                        <h3 class="text-capitalize">procedure</h3>
                        <?php
                        $steps = $obj->getRecipeSteps($recipeid);
                        if($steps->num_rows>0){
                        $i = 1;
                        while($row = $steps->fetch_assoc()) {?>
                            <div class="recipe-step">
                                <div class="recipe-step-left">
                                    <img src="<?=$row["ImgUrl"]?>" class="img-rounded">
                                </div>
                                <div class="recipe-step-right">
                                    <div class="recipe-step-title">
                                        <p class="title" href="single.html"><?=$i?>.</p>
                                    </div>
                                    <div class="recipe-step-text">
                                        <p><?=$row["Introduction"]?></p>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
            <?php       $i++;
                        }
                        $steps->close();
                    }
                ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <hr>
                <!--//related-posts-->
                <div class="response">
                    <h4>Recipe Comments</h4>
                    <?php
                        $comments = $obj->getComments($recipeid);
                        while ($comment = $comments->fetch_assoc()){?>
                            <div class="media response-info">
                                <div class="media-left response-text-left">
                                    <a href="#">
                                        <img class="media-object" src="<?=$comment["ImageURL"]?>"/>
                                    </a>
                                    <h5><a href="#"><?=$comment["UserName"]?></a></h5>
                                </div>
                                <div class="media-body response-text-right">
                                    <?=$comment["content"]?>
                                    <ul>
                                        <li><?=$comment["commenttime"]?></li>
                                        <li><a href="#">Comment</a></li>
                                    </ul>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                    <?php }
                    ?>
                </div>
                <div class="coment-form">
                    <h4>My Comment</h4>
                    <form>
                        <textarea id="commentContent" placeholder="Please write your comment here..."></textarea>
                        <input type="button" value="Comment" id="commentBtn">
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php if (!isset($_GET["mobile"])){?>
    <div class="col-md-4 side-bar">
        <div class="first_half">
            <div class="categories">
                <header>
                    <h3 class="side-title-head">Tags</h3>
                </header>
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <?php
                                $tags = $obj->getTags();
                                for($i=0;$i<3;$i++){
                                    $tag = $tags->fetch_assoc()?>
                                    <li><a href="#"><?=$tag["TagName"]?></a></li>
                                <?php }
                                ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <?php
                                for($i=0;$i<3;$i++){
                                    $tag = $tags->fetch_assoc()?>
                                    <li><a href="#"><?=$tag["TagName"]?></a></li>
                                <?php }
                                $tags->close();
                                ?>
                            </ul>
                        </div>
                    </div>
            </div>
            <div class="list_vertical">
                <section class="accordation_menu">
                    <div>
                        <input id="label-1" name="lida" type="radio" checked/>
                        <label for="label-1" id="item1"><i class="ferme"> </i>Most Popular<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
                        <div class="content" id="a1">
                            <div class="scrollbar style-2">
                                <div class="force-overflow">
                                    <div class="popular-post-grids">
                                        <?php
                                        $i=0;
                                        $rec_comments=$obj->getPopularRecipes();
                                        while($rec_comment=$rec_comments->fetch_assoc()){
                                            ?>
                                            <div class="popular-post-grid">
                                                <div class="post-img">
                                                    <a href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>"><img src="<?=$rec_comment["ImgUrl"]?>"/></a>
                                                </div>
                                                <div class="post-text">
                                                    <a class="pp-title" href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>"><?=$rec_comment["RecipeName"]?></a>
                                                    <p><?=$rec_comment["createdate"]?> <a class="span_link" href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>"><span class="glyphicon glyphicon-comment"></span>&nbsp;<?=$rec_comment["CommentCount"]?></a><a class="span_link" href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>">&nbsp;&nbsp;<span class="glyphicon glyphicon-eye-open"></span>&nbsp;<?=$rec_comment["ViewCount"]?></a></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input id="label-2" name="lida" type="radio"/>
                        <label for="label-2" id="item2"><i class="icon-leaf" id="i2"></i>Most Recent<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
                        <div class="content" id="a2">
                            <div class="scrollbar" id="style-2">
                                <div class="force-overflow">
                                    <div class="popular-post-grids">
                                        <?php
                                        $i=0;
                                        $rec_comments=$obj->getRecentRecipes();
                                        while($rec_comment=$rec_comments->fetch_assoc()){
                                            ?>
                                            <div class="popular-post-grid">
                                                <div class="post-img">
                                                    <a href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>"><img src="<?=$rec_comment["ImgUrl"]?>"/></a>
                                                </div>
                                                <div class="post-text">
                                                    <a class="pp-title" href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>"><?=$rec_comment["RecipeName"]?></a>
                                                    <p><?=$rec_comment["createdate"]?> <a class="span_link" href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>"><span class="glyphicon glyphicon-comment"></span>&nbsp;<?=$rec_comment["CommentCount"]?></a><a class="span_link" href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>">&nbsp;&nbsp;<span class="glyphicon glyphicon-eye-open"></span>&nbsp;<?=$rec_comment["ViewCount"]?></a></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input id="label-3" name="lida" type="radio"/>
                        <label for="label-3" id="item3"><i class="icon-trophy" id="i3"></i>User Comment<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
                        <div class="content" id="a3">
                            <div class="scrollbar style-2">
                                <div class="force-overflow">
                                    <div class="response">
                                        <?php
                                        $rec_comments = $obj->getRecentComments();
                                        for($i=0;$i<6;$i++){
                                            $rec_comment = $rec_comments->fetch_assoc()?>
                                            <div class="media response-info">
                                                <div class="media-left response-text-left">
                                                    <a href="view_recipe.php?recipeid=<?=$rec_comment["RecipeID"]?>">
                                                        <img class="media-object" src="<?=$rec_comment["ImageURL"]?>"/>
                                                    </a>
                                                    <h5><a href="#"><?=$rec_comment["UserName"]?></a></h5>
                                                </div>
                                                <div class="media-body response-text-right">
                                                    <?=$rec_comment["content"]?>
                                                    <ul>
                                                        <li><?=$rec_comment["commenttime"]?></li>
                                                    </ul>
                                                </div>
                                                <div class="clearfix"> </div>
                                            </div>
                                        <?php }
                                        $rec_comments->close();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="secound_half">
            <div class="popular-news">
                <header>
                    <h3 class="title-popular">Similar Recipes</h3>
                </header>
                <div class="side-bar-articles">
                    <?php
                    $similars = $obj->getSimilarRecipes($recipeid);
                    while($similar = $similars->fetch_assoc()){?>
                        <div class="side-bar-article">
                            <a href="view_recipe.php?recipeid=<?=$similar["RecipeID"]?>"><img src="<?=$similar["ImgUrl"]?>"/></a>
                            <div class="side-bar-article-title">
                                <a href="view_recipe.php?recipeid=<?=$similar["RecipeID"]?>"><?=$similar["RecipeName"]?></a>
                            </div>
                        </div>
                    <?php }
                    $similars->close();
                    ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php }?>
    <div class="clearfix"></div>
</div>
<input type="hidden" value="<?=$recipeid?>" id="recipeid">
<?php
    $riResult->close();
?>
<!-- content-section-ends-here -->
<script>
    $(function(){
        $('.last-article p').addClass('artext');
        $('#commentBtn').click(function () {
            var content = $('#commentContent').val();
            var recipeid = $('#recipeid').val();
            $.ajax({
                url: '../services/recipe_comment.php' ,
                data:{
                    content: content ,
                    recipeid: recipeid
                } ,
                success: function (result) {
                    if(result=='success'){
                        location.reload();
                    }else{
                        alert('Fail to send the comment, please try again.');
                    }
                }
            });
        });
        $('.collect-btn').click(function () {
            var userid = $('#myuser').val();
            var passageid = $('#passage-passageid').val();
            $.ajax({
                url: 'addCollection.html' ,
                data: {
                    userid: userid ,
                    passageid: passageid
                } ,
                success: function (result) {
                    if(result=='success')
                        location.reload();
                    else{
                        alert('Fail to add to your favorites, please try again.');
                    }
                } ,
                error: function () {
                    alert('Network connection error');
                }
            });
        });
    });
</script>
</body>
<?php include "footer.php"?>
</html>
