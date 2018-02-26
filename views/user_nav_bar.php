<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
<nav class="navbar navbar-default top-navbar user_nav">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </button>
            <a class="navbar-brand" href="../app/views/home/homepage.html.twig">Cooking World</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Recipes
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="search_recipe.php?pagenum=1&keyword=">Find Recipe</a></li>
                        <li><a href="activity.php">Find Activity</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    if(isset($_SESSION["userid"])){
                ?>

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?=$_SESSION["userurl"]?>" class="sm-avatar img-circle">&nbsp;<?=$_SESSION["username"]?>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="create_recipe.php"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Share Recipe</a></li>
                                <li><a href="my_recipes.php?userid=<?=$_SESSION["userid"]?>"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;My Recipes</a></li>

<!--                                <li><a href="#"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Favorites</a></li>-->
<!--                                <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;Your Profile</a></li>-->
                                <li class="divider"></li>
                                <li><a href="../services/logout.php">Sign Out</a></li>
                            </ul>
                        </li>
                <?php
                    }else {
                ?>
                        <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Sign Up</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Sign In</a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
<div class="clearfix"></div>
<!-- login modal -->
<div class="modal fade" id="loginModal" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="loginModalTitle">Login</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="username" class="control-label col-md-2">Username: </label>
                        <div class="col-md-10">
                            <input type="text" name="username" class="form-control" id="username" placeholder="username or mobile phone number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-2 col-md-2" for="password">Password: </label>
                        <div class="col-md-10">
                            <input type="password" name="password" id="password" class="form-control" placeholder="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-8">
                            <div class="checkbox pull-right">
                                <label><input type="checkbox" name="checkbox" id="saveUser">Remember Me</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sign-in" onclick="login()">Sign In</button>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<!-- login modal ends -->
<script>
    function login() {
        var isRem = 0;
        if (document.getElementById("saveUser").checked)
            isRem = 1;
        $.ajax({
            method: 'POST',
            url: '../services/login.php' ,
            data:{
                username: $('#username').val() ,
                password: $('#password').val() ,
                isRem: isRem,
            } ,
            success: function (data) {
                result = data;
                if(result=='101'){
                    window.location.href = '../app/views/home/homepage.html.twig';
                }else if(result=='102'){
                    alert('The username or mobile phone is not registered');
                }else if(result=='103'){
                    alert('The password is wrong');
                }else if(result=='104'){
                    alert('The password or username can not be empty');
                }
            }
        });
    }
</script>