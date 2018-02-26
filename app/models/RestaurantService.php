<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 28/01/2018
 * Time: 3:10 PM
 */

namespace app\models;


use core\Model;

class RestaurantService extends Model
{

    function toRad($d)
    {
        return $d*M_PI / 180.0;
    }

    /**
     * calculate the distance(meters) between two points on map
     * point 1
     * @param $lng1
     * @param $lat1
     * point 2
     * @param $lng2
     * @param $lat2
     * @return float
     */
    public function getDistance($lng1, $lat1, $lng2, $lat2) {
         $EARTH_RADIUS = 6378.137; //earth radius
         $radLat1 = $this->toRad($lat1);
         $radLat2 = $this->toRad($lat2);
         $a = $radLat1 - $radLat2;
         $b = $this->toRad($lng1) - $this->toRad($lng2);
         $s = 2 * asin(sqrt(pow(sin($a/2), 2)
                 + cos($radLat1) * cos($radLat2)
                 * pow(sin($b / 2), 2)));
         $s = $s * $EARTH_RADIUS;
         $s = round($s * 10000) / 10;
         return $s;
    }

    /**
     * @reference http://janmatuschek.de/LatitudeLongitudeBoundingCoordinates#AngularRadius
     * @param $lng
     * @param $lat
     * @param $distance integer
     * distance killometers
     */
    public function search_nearby_restaurants($userlng, $userlat, $distance)
    {
        $lng = $this->toRad($userlng);
        $lat = $this->toRad($userlat);
        $EARTH_RADIUS = 6378.137; //earth radius
//        $r = 1000/$EARTH_RADIUS;
        $r = $distance/$EARTH_RADIUS;
        $lat_min = $lat - $r;
        $lat_max = $lat + $r;
        $latT = asin(sin($lat)/cos($r));
        $lng_offset = asin(sin($r)/cos($lat));
        $lng_min = $lng - $lng_offset;
        $lng_max = $lng + $lng_offset;
//        printf("%f %f %f %f %f %f %f %f ", $lat_min, $lat_max, $lng_min, $lng_max, $lat, $lat, $lng, $r);
        $sql ="SELECT * FROM (
    SELECT * FROM restaurants WHERE
        (lat_rad >= ? AND lat_rad <= ?) AND (lng_rad >= ? AND lng_rad <= ?)
) AS t WHERE
    acos(sin(?) * sin(lat_rad) + cos(?) * cos(lat_rad) * cos(lng_rad - (?))) <= ? ORDER BY rating DESC limit 0,5";

        $connection = self::getDB();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("dddddddd", $lat_min, $lat_max, $lng_min, $lng_max, $lat, $lat, $lng, $r);
        $stmt->execute();
        $result = $stmt->get_result();
        $restaurants = array();
        while ($row = $result->fetch_assoc())
        {
            $restaurant = new Restaurant();
            $restaurant->restaurant_id = $row['restaurant_id'];
            $restaurant->restaurant_name = $row['restaurant_name'];
            $restaurant->restaurant_url = $row['restaurant_url'];
            $restaurant->phone = $row['phone'];
            $restaurant->address = $row['address'];
            $restaurant->extend_address = $row['extended_address'];
            $restaurant->average_price = $row['average_price'];
            $restaurant->price_range = $row['price_range'];
            $restaurant->description = $row['description'];
            $restaurant->latitude = $row['latitude'];
            $restaurant->longitude = $row['longitude'];
            $restaurant->rating = $row['rating'];
            $restaurant->category = $row['category'];
            $restaurants[$restaurant->restaurant_id] = $restaurant->get_property_assoc_array();
        }
        $result->free_result();
        $stmt->close();
        return $restaurants;
    }
}