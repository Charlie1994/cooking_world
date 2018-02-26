<!DOCTYPE html>
<html>
<head>
    <?php include "default_head_resource.html" ?>
    <title>Cooking World</title>
</head>
<body>
<?php include "user_nav_bar.php"?>
<?php include "logo_banner.php" ?>
<div class="container-fluid">
    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Create Recipe</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="recipe-title" placeholder="Recipe Title">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-img-upload text-center">
                            <div class="upload-hint">
                                <h1><span class="glyphicon glyphicon-plus" style="color: #797979"></span> </h1><br>
                                <p style="color: #797979">Add Main Photo For This Recipe</p>
                            </div>
                            <img src="#">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 1em">
                    <div class="col-md-3">
                        <select class="form-control" id="create-cook-time">
                            <option value="0">Cook time</option>
                            <option value="1">Around 10 minutes</option>
                            <option value="2">10-30 minutes</option>
                            <option value="3">30-60 minutes</option>
                            <option value="4">Over an hour</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="create-level">
                            <option value="0">Level</option>
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="create-course">
                            <option value="0">Course</option>
                            <option value="1">Appetizers</option>
                            <option value="2">Starters</option>
                            <option value="3">Main Courses</option>
                            <option value="4">Side Dishes</option>
                            <option value="5">Desserts</option>
                            <option value="6">Drinks</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea placeholder="Introduction" class="form-control" id="create-intro"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Ingredients</h3>
                    </div>
                    <div class="col-md-10 create-ingredients">
                        <?php
                            for($i=0;$i<3;$i++){?>
                            <div class="col-md-12 create-ingredient">
                                <div class="col-md-4">
                                    <input type="text" placeholder="Amount" class="form-control create-ing-amount">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" placeholder="Ingredient" class="form-control create-ing-name">
                                </div>
                            </div>
                        <?php    }
                        ?>
                    </div>
                    <div class="col-md-10 text-right">
                        <br>
                        <a href="#" class="add-ingredient"><span class="glyphicon glyphicon-plus"></span>&nbsp;add a row</a>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Procedure</h3>
                    </div>
                    <div class="col-md-12 create-steps">
                        <?php
                            for($i=1;$i<5;$i++){?>
                            <div class="create-step">
                                <div class="col-md-1 step-num text-center">
                                    <h2><?=$i?></h2>
                                </div>
                                <div class="col-md-7">
                                    <textarea placeholder="direction" class="form-control create-direction"></textarea>
                                </div>
                                <div class="col-md-2 step-img text-center">
                                    <div class="upload-hint">
                                        <h1><span class="glyphicon glyphicon-plus" style="color: #797979"></span> </h1>
                                    </div>
                                    <img src="#">
                                </div>
                            </div>
                        <?php    }
                        ?>
                        </div>
                    </div>
                    <div class="col-md-10 text-right">
                        <br>
                        <a href="#" class="add-step"><span class="glyphicon glyphicon-plus"></span>&nbsp;add a step</a>
                    </div>
                    <div class="clearfix"></div><hr>
                    <div class="row">
                        <div class="col-md-10">
                            <h4>Tags(You may use comma to separate tags)</h4>
                        </div>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="create-tags">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <button class="create-submit btn btn-default">Release</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="img-upload-form">
    <input name="file" type="file" id="img-upload" style="display: none">
</form>
</body>
</html>

<script>
    var container;
    var step_num = 4;
    var ingredient_num = 3;
    $(function () {
        var mainPicWidth = $('.main-img-upload').width();
        //create layout for main photo
        $('div.main-img-upload').css({'height':mainPicWidth/4*3+'px'});
        $('div.main-img-upload').click(function () {
            container = $(this);
            $('#img-upload').trigger('click');
        });
        $('.main-img-upload img').css({'height':mainPicWidth/4*3+'px','display':'none'});
        if($(window).width()<992){
            $('.main-img-upload div.upload-hint').css({'padding-top':mainPicWidth/8+'px'});
        }else{
            $('.main-img-upload div.upload-hint').css({'padding-top':mainPicWidth/8*2.5+'px'});
        }
        initStepLayout(step_num);
        $('#img-upload').change(function () {
            var data = new FormData($('#img-upload-form')[0]);
            $.ajax({
                url: '../services/uploadFile.php',
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                data: data,
                success: function (data) {
                    var r = $.parseJSON(data);
                    if(r.status == 1){
                        showMainPhoto(r.result);
                    }else{
                        alert(r.result);
                    }
                    console.log(r);
                }
            });
        });
        $('.create-submit').click(function () {
            var ingredients = [];
            var steps = [];
            var ingElms = $('.create-ingredient');
            var stepElms = $('.create-step');
            for(i=0;i<ingElms.length;i++){
                ingredients[i] = {'amount':$(ingElms[i]).find('.create-ing-amount').val(),
                                    'name':$(ingElms[i]).find('.create-ing-name').val()};
            }
            for(i=0;i<stepElms.length;i++){
                steps[i] = {'stepNum':i+1,
                    'direction':$(stepElms[i]).find('img').attr('src'),
                    'name':$(stepElms[i]).find('textarea').val()};
            }
            var j = {
                'title':$('#recipe-title').val(),
                'mainImg':$('div.main-img-upload').children('img').attr('src'),
                'cookTime':$('#create-cook-time').val(),
                'level':$('#create-level').val(),
                'course':$('#create-course').val(),
                'introduction':$('#create-intro').val(),
                'steps':steps,
                'ingredients':ingredients,
                'tags':$('#create-tags').val()
            };
            $.ajax({
                url: '../services/create_recipe_service.php',
                type: 'POST',
                data: {
                    json: JSON.stringify(j)
                },
                success: function (result) {
                    var obj = JSON.parse(result);
                    if(obj.result == 1){
                        alert('Upload successfully! Click \"OK\" to view created recipe.');
                        window.location.href = 'view_recipe.php?recipeid='+obj.message;
                    }else{
                        alert('Fail to upload, please try again');
                    }
                }
            });
        });
        $('a.add-ingredient').click(function (e) {
            $('.create-ingredients').append("<div class=\"col-md-12 create-ingredient\">"+
            "<div class=\"col-md-4\">"+
                "<input type=\"text\" placeholder=\"Amount\" class=\"form-control create-ing-amount\">"+
                "</div>"+
                "<div class=\"col-md-6\">"+
                "<input type=\"text\" placeholder=\"Ingredient\" class=\"form-control create-ing-name\">"+
                "</div>"+
                "</div>");
            ingredient_num++;
            e.preventDefault();
            return false;
        });
        $('a.add-step').click(function (e) {
            step_num++;
            $('.create-steps').append("<div class=\"create-step\">"+
            "<div class=\"col-md-1 step-num text-center\">"+
                "<h2>"+step_num+"</h2>"+
                "</div>"+
                "<div class=\"col-md-7\">"+
                "<textarea placeholder=\"direction\" class=\"form-control create-direction\"></textarea>"+
                "</div>"+
                "<div class=\"col-md-2 step-img text-center\">"+
                "<div class=\"upload-hint\">"+
                "<h1><span class=\"glyphicon glyphicon-plus\" style=\"color: #797979\"></span> </h1>"+
                "</div>"+
                "<img src=\"#\">"+
                "</div>"+
                "</div>");
            initStepLayout(step_num);
            e.preventDefault();
            return false;
        });
    });
    function showMainPhoto(url) {
        $(container).children('div.upload-hint').css({'display':'none'});
        $(container).children('img').css({'display':'inline'}).attr('src',url);
    }
    function initStepLayout(stepnum) {
        var steps = $('.create-step');
        if (stepnum>4){
            var element1 = steps[stepnum-1];
            var imgElm1 = $(element1).children('.step-img');
            var imgWidth1 = imgElm.width();
            $(element1).css({'height':imgWidth1/4*3+'px'});
            $(imgElm1).css({'height':imgWidth1/4*3+'px'});
            $(imgElm1).children('img').css({'height':imgWidth1/4*3+'px','display':'none'});
            $(imgElm1).children('.step-hint').css({'padding-top':imgWidth1/9+'px'});
            $(element1).children().eq(1).children('.create-direction').css({'height':imgWidth1/4*3+'px'});
            $(imgElm1).click(function () {
                container = $(this);
                $('#img-upload').trigger('click');
            });
        }else{
            for(i=0;i<steps.length;i++){
                var element = steps[i];
                var imgElm = $(element).children('.step-img');
                var imgWidth = imgElm.width();
                $(element).css({'height':imgWidth/4*3+'px'});
                $(imgElm).css({'height':imgWidth/4*3+'px'});
                $(imgElm).children('img').css({'height':imgWidth/4*3+'px','display':'none'});
                $(imgElm).children('.step-hint').css({'padding-top':imgWidth/9+'px'});
                $(element).children().eq(1).children('.create-direction').css({'height':imgWidth/4*3+'px'});
                $(imgElm).click(function () {
                    container = $(this);
                    $('#img-upload').trigger('click');
                });
            }
        }
        
    }
</script>