<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Play map locations as a slideshow</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
<style>
body { margin: 0; padding: 0; }
#map { position: absolute; top: 0; bottom: 0; width: 100%; }
</style>
</head>
<body>
<style>
    .map-overlay-container {
        position: absolute;
        width: 25%;
        top: 0;
        right: 0;
        padding: 10px;
        z-index: 1;
    }

    .map-overlay {
        font:
            12px/20px 'Helvetica Neue',
            Arial,
            Helvetica,
            sans-serif;
        background-color: #fff;
        border-radius: 3px;
        padding: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .map-overlay h2,
    .map-overlay p {
        margin: 0 0 10px;
    }
</style>

<div id="map"></div>

<div class="map-overlay-container">
    <div class="map-overlay">
        <h2 id="location-title"></h2>
        <p id="location-description"></p>
        <small>Text credit:
            <a target="_blank" href="http://www.nycgo.com/neighborhoods">nycgo.com</a></small>
    </div>
</div>

<script>
	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
	mapboxgl.accessToken = 'YOUR_MAPBOX_ACCESS_TOKEN';
    const map = new mapboxgl.Map({
        container: 'map',
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [-74.0315, 40.6989],
        maxZoom: 16,
        minZoom: 9,
        zoom: 9.68
    });

    const title = document.getElementById('location-title');
    const description = document.getElementById('location-description');

    const locations = [
        {
            'title': 'Boroughs of new york',
            'description':
                'New York City is made up of five boroughs: the Bronx, Brooklyn, Manhattan, Queens and Staten Island. Each one has enough attractions—and enough personality—to be a city all its own.',
            'camera': {
                center: [-74.0315, 40.6989],
                zoom: 9.68,
                bearing: 0,
                pitch: 0
            }
        }
    ];

    function highlightBorough(code) {
        // Only show the polygon feature that corresponds to `borocode` in the data.
        map.setFilter('highlight', ['==', 'borocode', code]);
    }

    function playback(index) {
        title.textContent = locations[index].title;
        description.textContent = locations[index].description;

        highlightBorough(locations[index].id ? locations[index].id : '');

        // Animate the map position based on camera properties.
        map.flyTo(locations[index].camera);

        map.once('moveend', () => {
            // Duration the slide is on screen after interaction.
            window.setTimeout(() => {
                // Increment index, looping back to the first after the last location.
                index = (index + 1) % locations.length;
                playback(index);
            }, 3000); // After callback, show the location for 3 seconds.
        });
    }

    // Display the last title/description first.
    title.textContent = locations[locations.length - 1].title;
    description.textContent = locations[locations.length - 1].description;

    map.on('load', () => {
        map.addSource('boroughs', {
            'type': 'vector',
            'url': 'mapbox://mapbox.8ibmsn6u'
        });
        map.addLayer(
            {
                'id': 'highlight',
                'type': 'fill',
                'source': 'boroughs',
                'source-layer': 'original',
                'paint': {
                    'fill-color': '#fd6b50',
                    'fill-opacity': 0.25
                },
                'filter': ['==', 'borocode', '']
            },
            'road-label' // Place polygon under labels.
        );

        // Start the playback animation for each borough.
        playback(0);
    });
</script>

</body>
</html>