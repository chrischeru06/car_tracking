<div id ="map"></div>

<script>

	mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A';


	var coord = '<?= $coordinates; ?>';
	var coord = coord.split(",");
	var zoom = '<?= $zoom; ?>';

	//alert("jhhkjl")


   var map = new mapboxgl.Map({
		container: 'map',
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
		style: 'mapbox://styles/mapbox/streets-v12',
		center: [coord[0],coord[1]],
		zoom: zoom,
		bounds: [29.383188,-3.384438, 29.377566,-3.369615],
    projection: "globe" // display the map as a 3D globe
});



	map.addControl(new mapboxgl.NavigationControl());
	map.addControl(new mapboxgl.FullscreenControl());




	var donn = '<?= $donnees_vehicule?>';

	var donn = donn.split('@');

	for (var i = 0; i < (donn.length)-1; i++) {
		var index = donn[i].split('<>');


		var color = ' ';

		if(index[13] == 1) //Vehicule actif
		{
			color = '#3bb2d0';
		}
		else              //Vehicule inactif
		{
			color = '#FF0000';
		}


		const marker2 = new mapboxgl.Marker({ color:color, rotation: 45 })
        .setLngLat([index[2],index[1]])
        .addTo(map);
	}


	//Create a default Marker and add it to the map.


    // const marker1 = new mapboxgl.Marker()
    //     .setLngLat([29.383188,-3.384438])
        //.addTo(map);

    // Create a default Marker, colored black, rotated 45 degrees.
    // const marker2 = new mapboxgl.Marker({ color: 'black', rotation: 45 })
    //     .setLngLat([29.377566,-3.369615])
    //     .addTo(map);


// 	const geojson = {
// 		type: 'FeatureCollection',
// 		features: [


// 		{
// 			type: 'Feature',
// 			geometry: {
// 				type: 'Point',
// 				coordinates: [-3.3861416,29.3619433]
// 			},
// 			properties: {
// 				title: 'Mapbox',
// 				description: 'Washington, D.C.'
// 			}
// 		},
// 		{
// 			type: 'Feature',
// 			geometry: {
// 				type: 'Point',
// 				coordinates: [29.377566,-3.369615]
// 			},
// 			properties: {
// 				title: 'Mapbox',
// 				description: 'San Francisco, California'
// 			}
// 		}


// 		]
// 	};



// 				// add markers to map
// 	for (const feature of geojson.features) {
  // // create a HTML element for each feature
// 		const el = document.createElement('div');
// 		el.className = 'marker';

  // // make a marker for each feature and add to the map
// 		new mapboxgl.Marker(el).setLngLat(feature.geometry.coordinates).addTo(map);
// 	}


</script>