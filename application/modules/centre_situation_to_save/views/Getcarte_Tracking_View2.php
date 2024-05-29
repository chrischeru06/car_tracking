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
        center: [coord[1],coord[0]],
        zoom: zoom,
        bounds: [29.383188,-3.384438, 29.377566,-3.369615],
    projection: "globe" // display the map as a 3D globe
});

    map.addControl(new mapboxgl.NavigationControl());
    map.addControl(new mapboxgl.FullscreenControl());


    map.on('load', async () => {

        //// Fly the map to the location.
                // map.flyTo({
                //     center: [coord[1],coord[0]],
                //     speed: 0.5
                // });


    var donn = '<?= $donnees_vehicule?>';

    var donn = donn.split('@');

    for (var i = 0; i < (donn.length)-1; i++) {
     var index = donn[i].split('<>');


      // Créez le popup
const popup = new mapboxgl.Popup({ offset: 25 }).setHTML('<div><h5>Détail du véhicule</h5><p class="text-center"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"> </p> <p class="text-muted small pt-2 ps-1">'+ index[4] +' - '+ index[5] +'</p> <table> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-code"></i>&nbsp;&nbsp;&nbsp;'+ index[3] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;'+ index[6] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-circle"></i>&nbsp;&nbsp;&nbsp;'+ index[7] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-beer"></i>&nbsp;&nbsp;&nbsp;'+ index[8] +' Litres / km</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-user"></i>&nbsp;&nbsp;&nbsp;'+ index[9] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-user-md"></i>&nbsp;&nbsp;&nbsp;'+ index[12] +'</th></tr></table> <p class = "text-center"><label class = "text-center"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="btn btn-outline-primary rounded-pill" title="Informations trajet" ><i class="fa fa-info-circle"></i> </a></label></p></div>');


     var color = ' ';

     if(index[13] == 1) //Vehicule actif
     {
         color = '#3bb2d0';
     }
     else              //Vehicule inactif
     {
         color = '#FF0000';
     }


     const marker = new mapboxgl.Marker({ color:color, rotation: 360})
        .setLngLat([index[2],index[1]])
        .setPopup(popup)
        .addTo(map);
    }








    });


    //Create a default Marker and add it to the map.


    // const marker1 = new mapboxgl.Marker()
    //     .setLngLat([29.383188,-3.384438])
        //.addTo(map);

    // Create a default Marker, colored black, rotated 45 degrees.
    // const marker2 = new mapboxgl.Marker({ color: 'black', rotation: 45 })
    //     .setLngLat([29.377566,-3.369615])
    //     .addTo(map);


//  const geojson = {
//      type: 'FeatureCollection',
//      features: [


//      {
//          type: 'Feature',
//          geometry: {
//              type: 'Point',
//              coordinates: [-3.3861416,29.3619433]
//          },
//          properties: {
//              title: 'Mapbox',
//              description: 'Washington, D.C.'
//          }
//      },
//      {
//          type: 'Feature',
//          geometry: {
//              type: 'Point',
//              coordinates: [29.377566,-3.369615]
//          },
//          properties: {
//              title: 'Mapbox',
//              description: 'San Francisco, California'
//          }
//      }


//      ]
//  };



//              // add markers to map
//  for (const feature of geojson.features) {
  // // create a HTML element for each feature
//      const el = document.createElement('div');
//      el.className = 'marker';

  // // make a marker for each feature and add to the map
//      new mapboxgl.Marker(el).setLngLat(feature.geometry.coordinates).addTo(map);
//  }



</script>
