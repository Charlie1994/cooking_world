<!doctype html>
<html>
<head>
    <style>
        #map {
            width: 400px;
            height: 400px;
        }
    </style>
</head>
<body>
<div id="map"></div>

<!-- Google Maps JS API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHfFPsmx6MXr2JmH4Kio_lwo2xv7c_ev8"></script>
<!-- GMaps Library -->
<script src="../resource/asset/js/gmaps.js"></script>
<script>
    /* Map Object */
    var mapObj = new GMaps({
        el: '#map',
        lat: 48.857,
        lng: 2.295,
        click: function(e) {
            alert('You clicked on the map');
        },
        zoom_changed: function(e) {
            alert('You zoomed the map');
        }
    });
    var m = mapObj.addMarker({
        lat: 48.8583701,
        lng: 2.2944813,
        title: 'Eiffel Tower',
        infoWindow: {
            content: '<h4>Eiffel Tower</h4><div>Paris, France</div>',
            maxWidth: 100
        }
    });
</script>
</body>
</html>