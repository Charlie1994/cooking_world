<!DOCTYPE html>
<html>
<head>
    <title>Activity map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "default_head_resource.html" ?>
</head>
<?php
    if(!isset($_GET["mobile"])){
        include "user_nav_bar.php";
        include "logo_banner.php";
    }?>
<?php
      include_once "../services/activity_service.php"; ?>
<?php
    $service = new ActivtyService();
?>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#view-activity-tab">Find Activities</a></li>
                <li><a data-toggle="tab" href="#view-activity-tab2">Add Activity</a></li>
<!--                <li><a data-toggle="tab" href="#view-activity-tab3">My Activities</a></li>-->
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="view-activity-tab">
                    <br>
                    <div class="clearfix"></div>
                    <ul class="list-group find-list">

                    </ul>
                    <ul class="pagination find-pagination pull-right">
                    </ul>
                    <hr>
                </div>
                <div id="view-activity-tab2" class="tab-pane fade">
                    <label for="ac_na">Activity Name</label><input id='ac_na' class="form-control"/>
                    <label for="adaytime">Start Time</label><input type="datetime-local" class="form-control" name="adaytime" id="adaytime">
                    <label for="na">NAME</label><input id='na' class="form-control"/>
                    <label for="cp">Contact Phone</label><input id='cp' class="form-control"/>
                    <label for="add">ADDRESS</label><input id="add" class="form-control"/>
                    (Latitude,Longtitude)<p id="addlalng"></p>
                    <textarea class="form-control" placeholder="Introduction" id="activity-introduction"></textarea>
                    <br>
                    <button id='csubmit' class="btn btn-default pull-right">Add</button>
                    <hr>
                </div>
<!--                <div class="tab-pane fade" id="view-activity-tab3">-->
<!--                    <div class="activity-btns">-->
<!--                        <button class="btn left"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Prev</button>-->
<!--                        <button class="btn pull-right right">Next&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>-->
<!--                    </div>-->
<!--                    <br>-->
<!--                    <div class="clearfix"></div>-->
<!--                    <ul class="list-group">-->
<!--                        <a href="#" class="list-group-item" id="my-comment-btn">My Shared Recipes&nbsp;&nbsp;&nbsp;&nbsp;datetime</a>-->
<!--                        <a href="my_favorites.php?userid=--><?//=$_GET["userid"]?><!--" class="list-group-item" id="my-reply-btn">My Favorites</a>-->
<!--                        <a href="my_activities.php?userid=--><?//=$_GET["userid"]?><!--" class="list-group-item list-group-item-info" id="my-post-btn">My Activities</a>-->
<!--                    </ul>-->
<!--                    <hr>-->
<!--                </div>-->
            </div>
        </div>
        <div class="col-xs-12 col-md-8 activity-map">
            <div id="map""></div>
        </div>
    </div>
</div>
<?php include "footer.php"?>
</body>
<!-- Google Maps JS API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHfFPsmx6MXr2JmH4Kio_lwo2xv7c_ev8"></script>
<!-- GMaps Library -->
<script src="../resource/asset/js/gmaps.js"></script>
<script>
    var clickMarker;
    var markers = [];
    var latitude,longtitude;
    /* Map Object */
    var mapObj = new GMaps({
        el: '#map',
        lat: -27.497,
        lng: 153.009,
        click: function(e) {
            latitude = e.latLng.lat();
            longtitude = e.latLng.lng();
            $('#addlalng').text(e.latLng.lat()+','+e.latLng.lng());
            if (clickMarker!=null){
                mapObj.removeMarker(clickMarker);
            }
            clickMarker = mapObj.addMarker({
                lat: e.latLng.lat(),
                lng: e.latLng.lng(),
                icon: "../resource/images/layout/click-marker.png",
                infoWindow: {
                    content: '<h5>Set an activity here</h5>',
                    maxWidth: 100
                }

            });
        }
    });

    GMaps.geolocate({
        success: function(position) {
            mapObj.setCenter(position.coords.latitude, position.coords.longitude);
            mapObj.addMarker({
                lat: position.coords.latitude,
                lng: position.coords.longitude,
                icon: "../resource/images/layout/mylocation.png"
            });
        },
        error: function(error) {
            alert('Geolocation failed: ' + error.message);
        },
        not_supported: function() {
            alert("Your browser does not support geolocation");
        },
        always: function() {
//            alert("Always");
        }
    });
    function updateActivities(pagenum) {
        $.ajax({
            method: 'GET',
            url: '../services/getActivities.php',
            data: {
                pagenum: pagenum
            },
            success: function(result){
                activities = JSON.parse(result);
                $('.find-list').empty();
                markers = [];
                mapObj.removeMarkers();
                for (i=0;i<activities.length;i++){

                    $('.find-list').append("<a href=\"#\" data-id=\""+i+"\" class=\"list-group-item\" class=\"my-comment-btn\">"+activities[i].ActivityName+"&nbsp;&nbsp;&nbsp;&nbsp;<p style=\"color:#fdb5a4\" class='pull-right'>"+activities[i].BeginTime+"</p></a>");
                    markers[i] = mapObj.addMarker({
                        lat: activities[i].Latitude,
                        lng: activities[i].Longtitude,
                        title: activities[i].ActivityName,
                        infoWindow: {
                            content: '<div style="width: 20em"> ' +
                            '<h5>'+activities[i].ActivityName+
                            '</h5><br><p>'+activities[i].Introduction+
                            '</p><br><p style="color: #ff5e3e">Start: '+activities[i].BeginTime+
                            '</p><a href="#" class="pull-right">Take part in</a></div>',
                            maxWidth: 400
                        }
                    });

                }
                $('ul.find-list a.list-group-item').click(function () {
                    var index = $(this).attr("data-id");
                    mapObj.setCenter(activities[index].Latitude,activities[index].Longtitude)
                });
            },
            error: function(){
                alert('connection error')
            }
        });
    }
    $(function () {

        updateActivities(1);
        $.ajax({
            method: 'GET',
            url: '../services/getActivityCount.php',
            success: function(result){
                var count = parseInt(result);
                $(".find-pagination").empty();
                for (i=1;i<=count;i++){
                    $(".find-pagination").append("<li class=\"page-item\"><a class=\"page-link\" href=\"#\" onclick='updateActivities("+i+")'>"+i+"</a></li>");
                }
            },
            error: function(){
                alert('connection error')
            }
        });
        $('#csubmit').click(function(){
            $.ajax({
                method: 'POST',
                url: '../services/activity_upload.php',
                data: {
                    activity:$('#ac_na').val(),
                    name:$('#na').val(),
                    address:$('#add').val(),
                    contact:$('#cp').val(),
                    latitude: latitude,
                    longtitude: longtitude,
                    introduction: $('#activity-introduction').val(),
                    time: $('#adaytime').val()
                },
                success: function(result){
                    var s_result = result.trim();
                    if(s_result=='Success'){
                        alert('Thanks for you registration!');
//                        window.location.href='homepage.php';
                    }else{
                        alert('Sorry, your registration is failed, please try again.');
                    }
                },
                error: function(){
                    alert('connection error')
                }
            });
        });

    });

</script>

</html>