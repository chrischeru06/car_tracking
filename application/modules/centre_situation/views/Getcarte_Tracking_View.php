<div id="map"></div>

<script>
      // initalize leaflet map

      // add OpenStreetMap basemap

	L.mapbox.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc1MmozajAwcGczZW1sMjMwZWxtZDQifQ.u8xhrt1Wn4A82X38f5_Iyw';

	var coord = '<?= $coordinates; ?>';
	var coord = coord.split(",");
	var zoom = '<?= $zoom; ?>';

	var map = L.mapbox.map('map')
	.setView([coord[0],coord[1]], zoom);

	var layers = {
		Nuit: L.mapbox.styleLayer('mapbox://styles/mapbox/dark-v10'),
		Sombre: L.mapbox.styleLayer('mapbox://styles/mapbox/navigation-guidance-night-v4'),
		Streets: L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'),
		Satellite: L.mapbox.styleLayer('mapbox://styles/mapbox/satellite-streets-v11'),
	};

	layers.Streets.addTo(map);
	L.control.layers(null,layers,{position: 'topleft'}).addTo(map);
	L.control.fullscreen().addTo(map);

      //......debut boucle.......
	var clusterGroup = new L.MarkerClusterGroup();

	//alert(clusterGroup)

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

		var marker = L.marker([index[1],index[2]], {
			icon: L.mapbox.marker.icon({
				'marker-color': color,
				'marker-symbol': 'car',
				'marker-size': 'medium'

			})


		});

		marker.bindPopup
		// ('<h3><strong>Détail du véhicule </strong></h3><p class="text-center"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"> <label class = "text-center"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="btn btn-outline-primary rounded-pill" title="Informations trajet"><i class="fa fa-info-circle"></i> </a></label></p> <p> <div class="table-responsive"><table class="table table-borderless"> <tr><th class="text-muted small pt-2 ps-1"><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[4] +'&nbsp;-&nbsp;&nbsp;&nbsp;'+ index[5] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[3] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1" ><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[6] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[7] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[8] +' Litres / km</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[9] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="fa fa-car"></i>&nbsp;&nbsp;&nbsp;'+ index[12] +'</th></tr> </table></div></p>');

		('<h3><strong>Véhicule </strong></h3><p class="text-center"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"> </p> <p class="text-muted small pt-2 ps-1">'+ index[4] +' / '+ index[5] +'</p> <p class = "text-center"><label class = "text-center"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="btn btn-outline-primary rounded-pill" title="Informations trajet" ><i class="fa fa-info-circle"></i> </a></label></p>');

		
		if(index[14] == 1)
		{
		
			marker.addTo(map);
		}
		else
		{
			clusterGroup.addLayer(marker);
		}

	}
	map.addLayer(clusterGroup);

//.....Fin...........


	// async function refreshMarkers(){
      //     placeMarkers.forEach(marker => marker.remove());
      //     placeMarkers = await getPointsOfInterest([coord[0],coord[1]]);
      //     console.log("placemarkers " + placeMarkers);
      //     map.getSource('markers').setData(placeMarkers);
      //     placeMarkers.forEach(marker => marker.addTo(map));
      //   }

      //   //TODO: 3. Create lookups for appropriate marker colors
      //   const timer = setInterval(() => {
      //     refreshMarkers();
      //     // alert('test 3')
      //   }, 1000);



</script>