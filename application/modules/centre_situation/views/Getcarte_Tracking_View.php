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

	var donn = '<?= $donnees_vehicule?>';

	var donn = donn.split('@');

	for (var i = 0; i < (donn.length)-1; i++) {
		var index = donn[i].split('<>');

		var marker = L.marker([index[1],index[2]], {
			icon: L.mapbox.marker.icon({
				'marker-color': '#0000FF',
				'marker-symbol': 'car',
				'marker-size': 'large'

			})


		});

		marker.bindPopup
		('<h3><strong>Détail du véhicule </strong></h3><p><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"></p> <p> <div class="table-responsive"><table class="table table-borderless"> <tr><td class="text-muted small pt-2 ps-1">Véhicule</td><th class="text-muted small pt-2 ps-1">'+ index[4] +'&nbsp;-&nbsp;'+ index[5] +'</th></tr> <tr><td class="text-muted small pt-2 ps-1">Code(device uid)</td><th class="text-muted small pt-2 ps-1">'+ index[3] +'</th></tr> <tr><td class="text-muted small pt-2 ps-1">Plaque</td><th class="text-muted small pt-2 ps-1">'+ index[6] +'</th></tr> <tr><td class="text-muted small pt-2 ps-1">Couleur</td><th class="text-muted small pt-2 ps-1">'+ index[7] +'</th></tr> <tr><td class="text-muted small pt-2 ps-1">Consommation/km</td><th class="text-muted small pt-2 ps-1">'+ index[8] +'</th></tr> <tr><td class="text-muted small pt-2 ps-1">Propriétaire</td><th class="text-muted small pt-2 ps-1">'+ index[9] +'</th></tr> <tr><td class="text-muted small pt-2 ps-1">Chauffeur</td><th class="text-muted small pt-2 ps-1">'+ index[12] +'</th></tr> </table></div></p><p style="text-align:center;"><label class = "text-center fa fa-info-circle"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'">Informations trajet</a></label></p>');
		clusterGroup.addLayer(marker);

	}
	map.addLayer(clusterGroup);

//.....Fin...........

</script>