<html lang="en">
    <head>
        <meta name="viewport" content="initial-scale=0.5">
        <meta charset="utf-8">
        <style>
          #map {
            height: 50%;
          }
          html, body {
            height: 100%;
            margin: 0;
            padding: 0;
          }
        </style>
    </head>
    <body>
        <h2>Geographic location provider </h2>
        
        <form method="get">
            <label>Specify address</label>
            <input type="text" name="address" placeholder="Address">
            <input type="submit" name="find" value="Find">
            </input>
        </form>
    
<?php

$mapLat = null;
$mapLon = null;
 

if (isset($_GET['address']) && !empty($_GET['address']))
{
    require('vendor/autoload.php');
    
    $address = $_GET['address'];
    
    $api = new \Yandex\Geo\Api();

    $api->setQuery($address);
    
    $api->setLang(\Yandex\Geo\Api::LANG_RU)->load();
    
    $response = $api->getResponse();
    $foundLocations = $response->getList();
    
    if (empty($foundLocations))
    {
        echo '<div style="color:red">No locations found</div>';
    }
    else
    {
        $locCount = count($foundLocations);
        $locationIdx = 0;
        foreach ($foundLocations as $location)
        {
            $lat = $location->getLatitude();
            $lon = $location->getLongitude();
            $prettyAddress = $location->getAddress();
            
            if ($locationIdx == 0)
            {
                $mapLat = $lat;
                $mapLon = $lon;
                $mapAddr = $prettyAddress;
            }
            
            if ($locationIdx == 1)
            {
                echo '<h3>Other possible locations:</h3>';
            }

            echo 'Address: <a href="?address=', $address, '&lat=', $lat, '&lon=', $lon, '&addr=', $prettyAddress, '">', $prettyAddress, '</a><br/>';
            echo 'Latitude: ', $lat, '<br/>';
            echo 'Longitude: ', $lon, '<br/>';
            
            ++$locationIdx;
        }
    }
}

$mapLat = isset($_GET['lat']) ? $_GET['lat'] : $mapLat;
$mapLon = isset($_GET['lon']) ? $_GET['lon'] : $mapLon;
$mapAddr = isset($_GET['addr']) ? $_GET['addr'] : $mapAddr;

?>
        <?php if (isset($mapLat) && isset($mapLon)): ?>
            <div id="map"></div>
            <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
            <script>
                ymaps.ready(function()
                {
                    map = new ymaps.Map("map", 
                    {
                        center: [<?php echo $mapLat, ',', $mapLon ?>],
                        zoom: 15
                    });
                    
                    placemark = new ymaps.Placemark(map.getCenter(), {
                        hintContent: <?php echo $mapAddr ?>,
                        balloonContent: <?php echo $mapAddr ?>
                    }, {
                        iconLayout: 'default#image',
                        iconImageSize: [30, 42],
                        iconImageOffset: [-5, -38]
                    }),
                    
                    map.geoObjects.add(placemark);
                });  
            
            </script>
        
        <?php endif; ?>
        

    </body>
</html>