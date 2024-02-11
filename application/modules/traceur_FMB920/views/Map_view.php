
<!DOCTYPE html>
<html>
<head>
<?php include VIEWPATH . 'includes/header.php'; ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MapView</title>


<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <style type="text/css">
    body {
  margin: 0;
  padding: 0;
}
#map {top:-35px;bottom:0; width:100%;height:800px;z-index: 1; }

#animation-phase-container {
  position: absolute;
  top: 10px;
  left: 10px;
  background: white;
  padding: 10px;
  font-family: sans-serif;
  display: flex;
  align-items: center;
  border-radius: 8px;
}

#animation-phase {
  margin-left: 5px;
  font-weight: 600;
  font-size: 30px;
}


 .mapboxgl-ctrl-logo{
    display: none !important;
  }
  
   .mapboxgl-ctrl-attrib-inner{
    display: none !important;
  }
   
   .mapboxgl-ctrl mapboxgl-ctrl-attrib{
    display: none !important;
  }

  
  </style>
</head>
<body>
<?php include VIEWPATH . 'includes/preloader.php'; ?>
  <!--******************* Preloader end ********************-->

  <!--********************************** Main wrapper start ***********************************-->
  <div id="main-wrapper">
   <!--********************************** Header start ***********************************-->
     <?php include VIEWPATH . 'includes/menu_top.php'; ?>
    <!--********************************** Header end ***********************************-->

    <!--********************************** Sidebar start ***********************************-->
     <?php include VIEWPATH . 'includes/menu_left.php'; ?>
    <!--********************************** Sidebar end ***********************************-->

    
    <!--********************************** Content body start ***********************************-->


    <div id="map"></div>

<form method="POST" action="<?= base_url() ?>traceur_FMB920/Map/index">

<div id="menu"> 

    <?php $carte; ?>

    
        <input onchange="submit()" id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite" <?php if($info == 'satellite') echo "checked"; $carte = 'satellite-streets-v12'; ?>>

        <label for="satellite-streets-v12">satellite</label>
        
        <input onchange="submit()" id="streets-v12" type="radio" name="rtoggle" value="streets" <?php if($info == 'streets') echo "checked"; $carte = 'streets-v12'; ?> >
        <label for="streets-v12">streets</label>


    <br>
    <br>

    <!-- <img style="width: 100%;height: 150px;" src="<?= base_url() ?>upload/mbx2.jpeg">          -->

</div>
    </form>
<?php include VIEWPATH . 'includes/footer.php'; ?>
    <!--********************************** Footer end ***********************************-->
  </div>
  <!--********************************** Main wrapper end ***********************************-->

  <!--********************************** Scripts start ***********************************-->
  <?php include VIEWPATH . 'includes/footer_scripts.php'; ?>
  <!--********************************** Scripts End ***********************************-->

</body>

  
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A';
    const map = new mapboxgl.Map({
        container: 'map',
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
        style: 'mapbox://styles/mapbox/streets-v12',
    bounds: [29.383188,-3.384438, 29.377566,-3.369615],
    projection: "globe" // display the map as a 3D globe
    });

    map.addControl(new mapboxgl.NavigationControl());

    map.on('load', async () => {
        // Get the initial location of the International Space Station (ISS).
        const geojson = await getLocation();
        // Add the ISS location as a source.
        map.addSource('iss', {
            type: 'geojson',
            data: geojson
        });
        // Add the rocket symbol layer to the map.   
        // http://161.97.118.14/iotplatform/Map/getmap



        map.loadImage(
            '<?= base_url() ?>upload/voll.png',
        (error, image) => {
        if (error) throw error;
        
        // Add the image to the map style.
        map.addImage('care', image);
        
        // Add a data source containing one point feature.
        map.addSource('point', {
        'type': 'geojson',
        'data': {
        'type': 'FeatureCollection',
        'features': [
        {
        'type': 'Feature',
        'properties': {
        'description':
        '<strong>Make it Mount Pleasant</strong><p><a href="http://www.mtpleasantdc.com/makeitmtpleasant" target="_blank" title="Opens in a new window">Make it Mount Pleasant</a> is a handmade and vintage market and afternoon of live entertainment and kids activities. 12:00-6:00 p.m.</p>',
        'icon': 'theatre'
        },
        'geometry': {
        'type': 'Point',
        'coordinates': [-77.4144, 25.0759]
        }
        }
        ]
        }
        });
        
        // Add a layer to use the image to represent the data.
        // map.addLayer({
        // 'id': 'points',
        // 'type': 'symbol',
        // 'source': 'point', // reference the data source
        // 'layout': {
        // 'icon-image': 'cat', // reference the image
        // 'icon-size': 0.25
        // }
        // });


        map.addLayer({
            'id': 'iss',
            'type': 'symbol',
            'source': 'iss',
            'layout': {
                // This icon is a part of the Mapbox Streets style.
                // To view all images available in a Mapbox style, open
                // the style in Mapbox Studio and click the "Images" tab.
                // To add a new image to the style at runtime see
                // https://docs.mapbox.com/mapbox-gl-js/example/add-image/
                'icon-image': 'care',
                'icon-size': 0.05
            }
        });

        }
        );


        map.on('click', 'iss', (e) => {
        // Copy coordinates array.
        const coordinates = e.features[0].geometry.coordinates.slice();
        const description = e.features[0].properties.description;
        
        // Ensure that if the map is zoomed out such that multiple
        // copies of the feature are visible, the popup appears
        // over the copy being pointed to.
        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }
        
        new mapboxgl.Popup()
        .setLngLat(coordinates)
        .setHTML("TOYOTA TI :: C3625A<hr>Driver   <b>Donatien Ndahishimiye</b><br><a style='text-decoration:none' href='<?= base_url() ?>traceur_FMB920/Map/trajet'>Voir le trajet du Véhicule</a>")
        .addTo(map);
        });


// TOYOTA TI C3625A

        

        // Update the source from the API every 2 seconds.
        const updateSource = setInterval(async () => {
            const geojson = await getLocation(updateSource);
            map.getSource('iss').setData(geojson);
        }, 2000);

        async function getLocation(updateSource) {
            // Make a GET request to the API and return the location of the ISS.
            try {
                const response = await fetch(
                    '<?= base_url() ?>traceur_FMB920/Map/getmap',
                    { method: 'GET' }
                );
                const { latitude, longitude } = await response.json();
                // Fly the map to the location.
                map.flyTo({
                    center: [longitude, latitude],
                    speed: 0.5
                });
                // Return the location of the ISS as GeoJSON.
                return {
                    'type': 'FeatureCollection',
                    'features': [
                        {
                            'type': 'Feature',
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [longitude, latitude]
                            }
                        }
                    ]
                };
            } catch (err) {
                // If the updateSource interval is defined, clear the interval to stop updating the source.
                if (updateSource) clearInterval(updateSource);
                throw new Error(err);
            }
        }
    });

      map.setStyle('mapbox://styles/mapbox/<?= $carte; ?>');


</script>

  </script>

</html>