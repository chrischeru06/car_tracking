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

	var styleLayerDefault = layers.Streets.addTo(map);

	//map.onchange()

	// if(styleLayerDefault == layers.Streets.addTo(map))
	// {
	// 	alert(1)
	// }
	// else
	// {
	// 	alert(2)
	// }

	L.control.layers(null,layers,{position: 'topleft'}).addTo(map);
	L.control.fullscreen().addTo(map);

      //......debut boucle.......
	var clusterGroup = new L.MarkerClusterGroup();

	//alert(clusterGroup)

	var donn = '<?= $donnees_vehicule?>';

	var donn = donn.split('@');

	for (var i = 0; i < (donn.length)-1; i++) {
		var index = donn[i].split('<>');

		// var color = ' ';

		// if(index[13] == 1) //Vehicule actif
		// {
		// 	color = '#50FF50';
		// }
		// else              //Vehicule inactif
		// {
		// 	color = '#FF0000';
		// }

		// var marker = L.marker([index[1],index[2]], {
		// 	icon: L.mapbox.marker.icon({
		// 		'marker-color': color,
		// 		'marker-symbol': 'car',
		// 		'marker-size': 'medium'

		// 	})


		// });


		var icon_vehicule = ' ';

		if(index[13] == 1 && index[15] == 0) //Vehicule actif
		{
			icon_vehicule = '<?=base_url()?>/upload/iconecartracking-03.png';
		}
		else if(index[13] == 2 && index[15] == 0)             //Vehicule inactif
		{
			icon_vehicule = '<?=base_url()?>/upload/iconecartracking-04.png';
		}
		else if(index[13] == 1 && index[15] == 1)             //Vehicule en accident
		{
			icon_vehicule = '<?=base_url()?>/upload/iconecartracking-05.png';
		}
		else if(index[13] == 2 && index[15] == 1)             //Vehicule en accident
		{
			icon_vehicule = '<?=base_url()?>/upload/iconecartracking-05.png';
		}


		var markerIcon = L.icon({
            iconUrl: icon_vehicule, // Spécifiez le chemin vers votre image locale
            iconSize: [70, 70], // Définissez la taille de l'icône
            iconAnchor: [25, 50], // Définissez l'ancre de l'icône (position où le marqueur pointe)
            className: 'custom-marker-icon'
      });

		var marker = L.marker([index[1], index[2]], {
			icon: markerIcon
		});

		marker.bindPopup

		('<h3 class="text-center" style="background:cadetblue;color:white;padding:10px;margin-top:-10px;margin-left:-10px;margin-right:-10px;"><strong>Détail du véhicule </strong></h3> <p class="text-center"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"> </p> <p class="text-center text-muted small pt-2 ps-1">'+ index[4] +' - '+ index[5] +'</p> <table> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-code"></i>&nbsp;&nbsp;&nbsp;'+ index[3] +'</th><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;'+ index[6] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-circle"></i>&nbsp;&nbsp;&nbsp;'+ index[7] +'</th><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-beer"></i>&nbsp;&nbsp;&nbsp;'+ index[8] +' Litres / km</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-user"></i>&nbsp;&nbsp;&nbsp;'+ index[9] +'</th><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-user-md"></i>&nbsp;&nbsp;&nbsp;'+ index[12] +'</th></tr></table> <p class = "text-center"><label class = "text-center"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="btn btn-outline-primary rounded-pill" title="Informations trajet" ><i class="fa fa-info-circle"></i> </a></label></p>');

		
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



	// Fonction pour mettre à jour les coordonnées du marqueur
	function updateMarkerPosition() {
  // Ici, vous pouvez placer votre logique pour récupérer les nouvelles coordonnées du marqueur

		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
		var VEHICULE_ID = $('#VEHICULE_ID').val();
		var id = 2;

		$.ajax({
			url : "<?=base_url()?>centre_situation/Centre_situation/getUpdateMarker/",
			type : "POST",
			dataType: "JSON",
			cache:false,
			data: {

				PROPRIETAIRE_ID:PROPRIETAIRE_ID,
				VEHICULE_ID:VEHICULE_ID,
				id:id,
			},

			success:function(data) {

				// alert(data.donnees_vehicule)

				var donn = data.donnees_vehicule;

				var donn = donn.split('@');

				for (var i = 0; i < (donn.length)-1; i++) {
					var index = donn[i].split('<>');

					var newLatitude = index[1];
					var newLongitude = index[2];

					// alert(newLatitude)

					marker.setLatLng([newLatitude, newLongitude]);
				}

			},
		});

	}

// Mettre à jour les coordonnées initiales du marqueur
	updateMarkerPosition();

// Définir l'intervalle de mise à jour toutes les 10 secondes (10000 millisecondes)
	// setInterval(updateMarkerPosition, 10000);

	const timer = setInterval(async () => {
          		updateMarkerPosition();
          	}, 10000);

//.....Fin...........




</script>