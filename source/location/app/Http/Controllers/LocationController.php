<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    const ROUND_DECIMALS = 2;
    const MAX_CLOSEST_SECRETS = 3;
    const DEFAULT_CACHE_TIME = 1;

    public static $conversionRates = [ 
       'km'    => 1.853159616, 
       'mile'  => 1.1515 
    ]; 

    public static $cacheSecrets = [ 
       [ 
           'id' => 1, 
           'name' => 'amber', 
           'location' => ['latitude'  => 42.8805, 'longitude' => -8.54569, 
           'name'      => 'Santiago de Compostela'] 
       ], 
       [ 
           'id' => 2, 
           'name' => 'diamond', 
           'location' => ['latitude'  => 38.2622, 'longitude' => -0.70107,
           'name'      => 'Elche'] 
       ], 
       [ 
           'id' => 3, 
           'name' => 'pearl', 
           'location' => ['latitude'  => 41.8919, 'longitude' => 12.5113, 
           'name'      => 'Rome'] 
       ], 
       [ 
           'id' => 4, 
           'name' => 'ruby', 
           'location' => ['latitude'  => 53.4106, 'longitude' => -2.9779, 
           'name'      => 'Liverpool'] 
       ], 
       [ 
           'id' => 5, 
           'name' => 'sapphire', 
           'location' => ['latitude'  => 50.08804, 'longitude' => 14.42076, 
           'name'      => 'Prague'] 
       ], 
    ]; 

    public function closestSecrets(Request $request, $latitude, $longitude)
    {
        $closestSecrets = $this->getClosestSecrets([
            'latitude'  => $latitude, 
            'longitude' => $longitude 
        ]);

        return $closestSecrets;
    }


    public function getEuclideanDistance($pointA, $pointB, $unit = 'km')
    {
        $distance = sqrt(
            pow(abs($pointA['latitude'] - $pointB['latitude']), 2) + pow(abs($pointA['longitude'] - $pointB['longitude']), 2)
        );

        return $this->convertDistance($distance, $unit);
    }

    public function getHaversineDistance($pointA, $pointB, $unit = 'km') 
    { 
      $distance = rad2deg( 
           acos( 
               (sin(deg2rad($pointA['latitude'])) * 
               sin(deg2rad($pointB['latitude']))) + 
               (cos(deg2rad($pointA['latitude'])) * 
               cos(deg2rad($pointB['latitude'])) * 
               cos(deg2rad($pointA['longitude'] - 
               $pointB['longitude']))) 
           ) 
       ) * 60; 
 
      return $this->convertDistance($distance, $unit); 
    } 

    public function getDistance($pointA, $pointB, $unit = 'km')
    {
        return $this->getHaversineDistance($pointA, $pointB, $unit);
    }

    public function getClosestSecrets($originPoint)
    {
        $cacheKey = 'L' . $originPoint['latitude'] . $originPoint['longitude'];
        $closestSecrets = Cache::Remember(
            $cacheKey,
            self::DEFAULT_CACHE_TIME,
            function() use ($originPoint) {
                $calculatedClosestSecret = [];
                $distances = array_map(
                      function($item) use ($originPoint) {
                          return $this->getDistance(
                              $item['location'],
                              $originPoint
                          );
                      },
                      self::$cacheSecrets
                );
                asort($distances);
                $distances = array_slice(
                    $distances,
                    0,
                    self::MAX_CLOSEST_SECRETS,
                    true
                );
                foreach ($distances as $key => $distance) {
                    $calculatedClosestSecret[] = self::$cacheSecrets[$key];
                }
                return $calculatedClosestSecret;
            }
        );
        return $closestSecrets;
    }


    // $distance should be Nautical Miles (long, lat default value)
    protected function convertDistance($distance, $unit = 'km')
    {
        switch (strtolower($unit)) {
            case 'mile':
                $distance = $distance * self::$conversionRates['mile'];
                break;
            
            default:
                $distance = $distance * self::$conversionRates['km'];
                break;
        }

        return round($distance, self::ROUND_DECIMALS);
    }
}
