<div id="map"></div>

<!-- <input type="text" id="place"> -->

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

	var markers = [];

	// Fonction pour effectuer une requête de géocodage inversé
	function reverseGeocode(latLng, callback) {
		var apiUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + latLng.lng + ',' + latLng.lat + '.json?access_token=' + L.mapbox.accessToken;

		$.getJSON(apiUrl, function(data) {
			var placeName = data.features[0].place_name;
			callback(placeName);
		}).fail(function(error) {
			console.log('Une erreur s\'est produite :', error);
		});
	}

	for (var i = 0; i < (donn.length)-1; i++) 
	{
		var index = donn[i].split('<>');
		let adress = ' ';

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
            iconSize: [30, 30], // Définissez la taille de l'icône
            iconAnchor: [25, 50], // Définissez l'ancre de l'icône (position où le marqueur pointe)
            className: 'custom-marker-icon' });

		var marker = L.marker([index[1], index[2]], {
			icon: markerIcon
		});

		
		// adress = '';

		marker.on('click', function(e) 
		{
			reverseGeocode(e.latlng, function(placeName) 
			{
				adress = placeName;

				$('#place').html(adress)
				// alert(adress)

			});
		});


		if(index[16] != "")
		{
			marker.bindPopup ('<h3 class="text-center" style="background:cadetblue;color:white;padding:10px;margin-top:-11px;margin-left:-11px;margin-right:-11px;"><strong>Détail du véhicule </strong></h3> <p class="text-center"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"> </p> <p class="text-center text-muted small pt-2 ps-1"> <i class="text-muted small pt-2 ps-1 fa fa-map-marker"></i><label class="text-muted small pt-2 ps-1" id="place"></label></p> <p class = "text-center"><label class = "text-center"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="card dash_card fa fa-info-circle" style="border-radius:20px;padding:10px; margin-bottom:-8px;" title=""> Informations trajet </a></label></p>');
		}
		else
		{
			marker.bindPopup ('<h3 class="text-center" style="background:cadetblue;color:white;padding:10px;margin-top:-11px;margin-left:-11px;margin-right:-11px;"><strong>Détail du véhicule </strong></h3> <p class="text-center"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" alt="" style="width: 50px;height: 50px;border-radius:20px;"> </p> <p class="text-center text-muted small pt-2 ps-1">'+ index[4] +' - '+ index[5] +'</p> <table> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-code"></i>&nbsp;&nbsp;&nbsp;'+ index[3] +'</th><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;'+ index[6] +'</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-circle"></i>&nbsp;&nbsp;&nbsp;'+ index[7] +'</th><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-beer"></i>&nbsp;&nbsp;&nbsp;'+ index[8] +' Litres / km</th></tr> <tr><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-user"></i>&nbsp;&nbsp;&nbsp;'+ index[9] +'</th><th class="text-muted small pt-2 ps-1"><i class="text-muted small pt-2 ps-1 fa fa-user-md"></i>&nbsp;&nbsp;&nbsp;'+ index[12] +'</th></tr></table> <p class="text-center text-muted small pt-2 ps-1"> <i class="text-muted small pt-2 ps-1 fa fa-map-marker"></i><label class="text-muted small pt-2 ps-1" id="place"></label></p> <p class = "text-center"><label class = "text-center"> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="card dash_card fa fa-info-circle" style="border-radius:20px;padding:10px; margin-bottom:-8px;" title=""> Informations trajet </a></label></p>');
		}


		//alert($('#place').html())

		markers.push(marker);

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
			url : "<?=base_url()?>centre_situation/Centre_situation/getmap/",
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

				$('#nbr_vehicule').html(data.nbrVehicule);
				$('#nbr_proprietaire').html(data.nbrProprietaire);
				$('#nbrChauffeur').html(data.nbrChauffeur);
				$('.vehiculeActif').html(data.vehiculeActif);
				$('.vehiculeInactif').html(data.vehiculeInactif);
				$('#vehiculeAllume').html(data.vehiculeAllume);
				$('#vehiculeEteint').html(data.vehiculeEteint);
				$('#vehiculeMouvement').html(data.vehiculeMouvement);
				$('#vehiculeStationnement').html(data.vehiculeStationnement);
				$('#vehiculeAvecAccident').html(data.vehiculeAvecAccident);
				$('#vehiculeSansAccident').html(data.vehiculeSansAccident);

				$('#nbrDemandeEntente').html(data.nbrDemandeEntente);
				$('#nbrDemandeApprouvee').html(data.nbrDemandeApprouvee);
				$('#nbrDemandeRefusee').html(data.nbrDemandeRefusee);

				var donn = data.donnees_vehicule;

				var donn = donn.split('@');



				for (var i = 0; i < markers.length-1; i++) {
					for (var i = 0; i < (donn.length)-1; i++)
					{
						var index = donn[i].split('<>');
						var marker = markers[i];
						var newLatitude = index[1];
						var newLongitude = index[2];
						marker.setLatLng([newLatitude, newLongitude]);
					}
				}

				markerClusterGroup.refreshClusters();

			},
		});

	}

// Mettre à jour les coordonnées initiales du marqueur
	updateMarkerPosition();

// Définir l'intervalle de mise à jour toutes les 10 secondes (10000 millisecondes)
	// setInterval(updateMarkerPosition, 10000);

	var timer = setInterval(async () => {
		// alert('jdshjhk')
		updateMarkerPosition();
	}, 10000);

//.....Fin...........




</script>