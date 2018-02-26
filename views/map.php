<!DOCTYPE html>
<html>
<head>
    <title>Activity map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "default_head_resource.html" ?>
</head>
<?php include "user_nav_bar.php"?>
<?php include "logo_banner.php" ?>
<body onload="getLocation()">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#view-activity-tab">Activities</a></li>
                <li><a data-toggle="tab" href="#menu1">Add Activity</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="view-activity-tab">
                    <div class="activty-btns">
                        <button class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span></button>
                        <button class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="activity-keywords">
                        <div class="col-md-2">
                            <label for="activity-keyword">Keyword:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" id="activity-keyword" type="text"/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ul class="activity-list">
                        <li><a href="#">activity 1</a><p>10-11 9:50</p></li>
                        <li><a href="#">activity 1</a><p>10-11 9:50</p></li>
                        <li><a href="#">activity 1</a><p>10-11 9:50</p></li>
                        <li><a href="#">activity 1</a><p>10-11 9:50</p></li>
                    </ul>
                    <hr>
                </div>
                <div id="menu1" class="tab-pane fade">
                    <label>Activity Name</label><input class="form-control"/>
                    <label>NAME</label><input class="form-control"/>
                    <label>ADDRESS</label><input id="latlng_input" class="form-control"/>
                    <label>Contact Phone</label><input class="form-control"/>
                    <br>
                    <button class="btn btn-primary">Add</button>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-8 activity-map">
            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
            <div id="map" onload="initCoords()"></div>
        </div>
    </div>
</div>
</body>
<script text="html/javascript">
    //user locations
    var locations = [
        ['Bondi Beach', -33.890542, 151.274856, 4],
        ['Coogee Beach', -33.923036, 151.259052, 5],
        ['Cronulla Beach', -34.028249, 151.157507, 3],
        ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
        ['Maroubra Beach', -33.950198, 151.259302, 1],
        ['60 Tribune St, South Brisbane QLD 4101',-27.481572 ,153.021205, 6]
    ];
    var map;


    <!--obtain_the_current_position-->
    function getLocation() {
        // Try HTML5 geolocation
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = new google.maps.LatLng(position.coords.latitude,
                    position.coords.longitude);
                var infowindow = new google.maps.InfoWindow({
                    map: map,
                    position: pos,
                    content: 'Location found using HTML5.'
                });
                map.setCenter(pos);
            }, function() {
                handleNoGeolocation(true);
            });
        } else {
// Browser doesn't support Geolocation
            handleNoGeolocation(false);
        }
    }
    // Adds a marker to the map.
    function addMarker(location, map) {
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        var marker = new google.maps.Marker({
            position: location,
//            label: labels[labelIndex++ % labels.length],
            map: map
        });
    }
    //show all users' location
    function showMarker(locations,map) {
        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }
    function initCoords() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(initialize, locationError);
        } else {
            showError("Your browser does not support Geolocation!");
        }
    }
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom:11
        });
        google.maps.event.addListener(map, 'click', function(event){
            var latlng = event.latLng;
            addMarker(latlng, map);
            alert(latlng);
            document.getElementById("latlng_input").value=latlng;
        });
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHfFPsmx6MXr2JmH4Kio_lwo2xv7c_ev8&callback=initAutocomplete&libraries=places"></script>
</html>