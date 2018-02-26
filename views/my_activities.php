<!DOCTYPE html>
<html>
<head>
    <?php include "default_head_resource.html";
          include "../services/user_service.php";
            $service = new MyUser();
            $myuser = $service->getUserInformation($_GET["userid"]);?>
    <title>Cooking World</title>
</head>
<body class="user-profile-body">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <div class="user-profile-tabs">
                <img src="<?=$myuser["ImageURL"]?>" class="user-avatar-lg img-responsive img-circle"><br>
                <h3 class="user-profile-name"><?=$myuser["UserName"]?> </h3>
                <div class="user-item-list">
                    <ul class="list-group">
                        <a href="my_recipes.php?userid=<?=$_GET["userid"]?>" class="list-group-item" id="my-comment-btn">My Shared Recipes</a>
                        <a href="my_favorites.php?userid=<?=$_GET["userid"]?>" class="list-group-item" id="my-reply-btn">My Favorites</a>
                        <a href="my_activities.php?userid=<?=$_GET["userid"]?>" class="list-group-item active" id="my-post-btn">My Activities</a>
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
                    <tr>
                        <td><a href="view_recipe.php?recipeid=">asnj</a> </td>
                        <td class="text-center"><a style="color: #ff5e3e;"  href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $(function(){
        var userid = $('#userid').val();
        var container = $('.user-item-body');
        myComments();
        function myComments(){
            var btn = $(this);
            btn.parent('.list-group').children().removeClass('active');
            btn.addClass('active');
            $.ajax({
                url: 'getMyComments.html',
                data: userid ,
                success: function(result){
                    container.children().remove();
                    container.append('<div class="user-comment-item">'+
                        '<p class="text-right">date210932</p>'+
                        '<div class="user-comment-item-content-block">'+
                        '<p>今天准备出去喝朋友梦一起网</p>'+
                        '</div>'+
                        '<div class="user-comment-article-name">'+
                        '<p>资讯:<a href="#">如何变得更加好</a></p>'+
                        '</div></div>');
                },
                error:function(){
                    setErrorMsg();
                }
            });
        }
        function getPostContent(html) {
            $('#hidden').hide().append(html);
            var str = $('#hidden').text();
            if(str.length>30)
                return str.substring(0,30);
            else
                return str;
        }
        function setErrorMsg() {
            $('#loading').empty().append('加载失败，请刷新重试');
        }
        $('#my-comment-btn').bind('click',myComments);
        $('#my-reply-btn').bind('click',function(){
            var btn = $(this);
            btn.parent('.list-group').children().removeClass('active');
            btn.addClass('active');
            $.ajax({
                url: 'getMyReplys.html',
                data: userid ,
                success: function(result){
                    container.children().remove();
                    container.append('<div class="user-comment-item">'+
                        '<div class="comment-item-left">'+
                        '<img src="images/1.jpg" class="user-avatar-sm img-circle img-responsive">'+
                        '</div>'+
                        '<div class="comment-item-right">'+
                        '<div class="reply-user-block">'+
                        '<h4 class="pull-left">用户名</h4>'+
                        '<p class="pull-right">20123-123</p>'+'<div class="clearfix"></div>'+
                        '</div>'+
                        '<div class="user-comment-item-content-block">'+
                        '<p>今天准备出去喝朋友梦一起网</p>'+
                        '</div>'+
                        '<div class="user-comment-article-name">'+'<p>文章名:如何变得更加好</p>'+
                        '</div>'+
                        '<a href="#"><p class="text-right">回复</p></a>'+
                        '</div>'+
                        '<div class="clearfix"></div>'+
                        '</div>');
                },
                error: function(){
                    setErrorMsg();
                }
            });
        });
        $('#my-post-btn').bind('click',function(){
            var btn = $(this);
            btn.parent('.list-group').children().removeClass('active');
            btn.addClass('active');
            $.ajax({
                url: 'getMyPosts.html',
                data: userid ,
                success: function(result){
                    container.children().remove();
                    container.append('<div class="user-comment-item">'+
                        '<div class="user-post-title">'+
                        '<h3>我的主题曲</h3>'+
                        '</div>'+
                        '<div class="user-post-content">'+
                        '<p>asfsafgs</p>'+
                        '</div>'+
                        '<div class="user-post-footer">'+
                        '<div class="pull-left">'+
                        '<a href="#">类别</a>'+
                        '</div>'+
                        '<div class="pull-right">'+
                        '<p class="text-right">datetime</p>'+
                        '</div>'+
                        '<div class="clearfix"></div>'+
                        '</div></div>');
                },
                error: function(){
                    setErrorMsg();
                }
            });
        });
        $('#my-repost-btn').bind('click',function(){
            var btn = $(this);
            btn.parent('.list-group').children().removeClass('active');
            btn.addClass('active');
            container.children().remove();
            $.ajax({
                url: 'getMyRePosts.html',
                data: userid ,
                success: function(result){
                    container.children().remove();
                    container.append('<div class="user-comment-item">'+
                        '<p class="text-right">date210932</p>'+
                        '<div class="user-comment-item-content-block">'+
                        '<p>今天准备出去喝朋友梦一起网</p>'+
                        '</div>'+
                        '<div class="user-comment-article-name">'+
                        '<p>主题帖:<a href="#">如何变得更加好</a></p>'+
                        '</div></div>');
                },
                error: function(){
                    setErrorMsg();
                }
            });

        });
    });
</script>
</html>