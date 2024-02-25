<script>
      // initalize leaflet map

      // add OpenStreetMap basemap

		L.mapbox.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc1MmozajAwcGczZW1sMjMwZWxtZDQifQ.u8xhrt1Wn4A82X38f5_Iyw';

		var coord ='-3.3944616,29.3726466';

		var coord = coord.split(',');

		var zoom = '9';

		var map = L.mapbox.map('mapinfo')
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
			('<h3><strong>Détail du véhicule </strong></h3><p><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 200px;height: 200px;border-radius:20px;"></p> <p> <table class="table table-borderless"> <tr><td class="btn-sm">Véhicule</td><th class="btn-sm">'+ index[4] +' - '+ index[5] +'</th></tr> <tr><td class="btn-sm">Code(device uid)</td><th class="btn-sm">'+ index[3] +'</th></tr> <tr><td class="btn-sm">Plaque</td><th class="btn-sm">'+ index[6] +'</th></tr> <tr><td class="btn-sm">Couleur</td><th class="btn-sm">'+ index[7] +'</th></tr> <tr><td class="btn-sm">Consommation/km</td><th class="btn-sm">'+ index[8] +'</th></tr> <tr><td class="btn-sm">Propriétaire</td><th class="btn-sm">'+ index[9] +'</th></tr> </table></p>');
			clusterGroup.addLayer(marker);

		}
		map.addLayer(clusterGroup);

//.....Fin...........

	</script>