<html>
<head>
    <?php include "default_head_resource.html";
    include "../services/search_recipe_service.php";
    include "../services/view_recipe_service.php";
    $obj = new ViewRecipe();
    $keywordobj = new ViewRecipeKeyword();
    $keyword = $_GET["keyword"];
    $pagenum = $_GET["pagenum"];?>

    <title>Search Recipe</title>
</head>
<body>
<?php
if (!isset($_GET["mobile"]))
    include "user_nav_bar.php"?>
<?php
if (!isset($_GET["mobile"]))
    include "logo_banner.php" ?>
<div class="container-fluid">
    <div class="wrap">
        <?php if (!isset($_GET["mobile"])){?>
            <ol class="breadcrumb">
                <li><a href="../app/views/home/homepage.html.twig">Homepage</a></li>
                <li><a href="search_recipe.php?pagenum=1&keyword=<?=$keyword?>"><?=$keyword?></a></li>
            </ol>
        <?php
        }?>

        <div class="single-page">
            <div class="col-md-8 content-left single-post">
                <div id="custom-search-input" class="col-md-10 col-md-offset-1">
                    <div class="input-group col-md-12">
                        <input type="text" class="search-query form-control" placeholder="Search Recipe" value="<?=$keyword?>" />
                        <span class="input-group-btn">
                            <button class="btn btn-danger" type="button" id="search-submit">
                                <span class=" glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="blog-posts">
                    <div class="recipe-steps">
                        <?php
                        $recipes = $keywordobj->getCurrentPage($keyword,$pagenum);
                        if($recipes->num_rows>0){
                            $i = 1;
                            while($row = $recipes->fetch_assoc()) {?>
                                <div class="recipe-step">
                                    <hr>
                                    <div class="recipe-step-left">
                                        <a href="view_recipe.php?recipeid=<?=$row["RecipeID"]?>">
                                            <img src="<?=$row["ImgUrl"]?>" class="img-rounded">
                                        </a>
                                    </div>
                                    <div class="recipe-step-right">
                                        <div class="recipe-step-title">
                                            <a href="view_recipe.php?recipeid=<?=$row["RecipeID"]?>" class="search-recipe-item">
                                                <p class="title" href="single.html"><?=$row["RecipeName"]?>.</p>
                                            </a>
                                        </div>
                                        <div class="recipe-step-text">
                                            <p><?=substr($row["Introduction"],0,60)?></p>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php       $i++;
                            }
                            $recipes->close();
                        }
                        ?>
                        <div class="clearfix"></div>
                        <hr>
                    </div>
                    <ul class="pagination search-pagination">
                        <?php if (!isset($_GET["mobile"])){?>
                            <li><a href="#"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Previous</a></li>
                            <?php
                        }?>
                        <?php
                            $count = $keywordobj->getPageNum($keyword);
                            for ($i=1;$i<=$count;$i++){
                                if ($pagenum == $i){
                                    ?>
                                    <li class="active"><a href="search_recipe.php?pagenum=<?=$i?>&keyword=<?=$keyword?>"><?=$i?></a></li>
                        <?php
                                }else{
                            ?>
                                    <li><a href="search_recipe.php?pagenum=<?=$i?>&keyword=<?=$keyword?>"><?=$i?></a></li>
                        <?php
                                }
                                ?>

                        <?php
                            }

                        ?>
                        <?php if (!isset($_GET["mobile"])){?>
                            <li><a href="#">Next&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a></li>
                            <?php
                        }?>
                    </ul>
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
            <div class="clearfix"></div>
        </div>
    <?php }?>
    <div class="clearfix"></div>
</div>
<!-- content-section-ends-here -->
<script>
    $(function(){
        $('#search-submit').click(function () {
            <?php if (!isset($_GET["mobile"])){?>
                window.location.href = 'search_recipe.php?pagenum=1&keyword='+$('.search-query').val();
            <?php
            }else{?>
                window.location.href = 'search_recipe.php?mobile=true&pagenum=1&keyword='+$('.search-query').val();
            <?php
        }
            ?>

        });
    });
</script>
</body>
</html>
