<div id="map"></div>

<!-- <input type="text" id="place"> -->

<script>
      // initalize leaflet map

      // add OpenStreetMap basemap

	L.mapbox.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc1MmozajAwcGczZW1sMjMwZWxtZDQifQ.u8xhrt1Wn4A82X38f5_Iyw';

	var coord = '<?= $coordinates;?>';
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
			console.log('<?=lang('erreur_produite')?> :', error);
		});
	}

	for (var i = 0; i < (donn.length)-1; i++) 
	{
		var index = donn[i].split('<>');
		var adress = ' ';

		var icon_vehicule = ' ';

		if(index[10] != '')
		{
			icon_vehicule = '<?=base_url()?>/upload/photo_vehicule/'+ index[10] +'';
		}

		// if(index[13] == 1 && index[15] == 0) //Vehicule actif
		// {
		// 	icon_vehicule = '<?=base_url()?>/upload/iconecartracking-03.png';
		// }
		// else if(index[13] == 2 && index[15] == 0)             //Vehicule inactif
		// {
		// 	icon_vehicule = '<?=base_url()?>/upload/iconecartracking-04.png';
		// }
		// else if(index[13] == 1 && index[15] == 1)             //Vehicule en accident
		// {
		// 	icon_vehicule = '<?=base_url()?>/upload/iconecartracking-05.png';
		// }
		// else if(index[13] == 2 && index[15] == 1)             //Vehicule en accident
		// {
		// 	icon_vehicule = '<?=base_url()?>/upload/iconecartracking-05.png';
		// }

		var className = '';
		if(index[21] == 1){
			className = 'custom-marker-icon';
		}
		else{
			className = 'custom-marker-icon2';
		}

		var markerIcon = L.icon({
            iconUrl: icon_vehicule, // Spécifiez le chemin vers votre image locale
            iconSize: [30, 30], // Définissez la taille de l'icône
            iconAnchor: [25, 50], // Définissez l'ancre de l'icône (position où le marqueur pointe)
            className: className });

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

		var photo_pro = '';

		if(index[19] != ''){
			photo_pro = index[19];
		}else{
			photo_pro = index[22];
		}

		marker.bindPopup ('<div class="row" style="width:400px;"><div class="col-md-4"><img src="<?= base_url()?>/upload/photo_vehicule/'+ index[10] +'" style="width: 135px;height: 135px;border-radius: 10px; margin-top:25px;margin-left:5px; cursor:pointer;" class="  " onclick = "show_image('+index[0]+');"></div> <div class="col-md-8"> <table class="table table-borderless" style="position:relative;left:6%;top:10%;"> <tr> <td class="text-muted small pt-2 ps-1"><?=lang('p_chauffeur')?><br><img src="<?= base_url()?>/upload/chauffeur/'+ index[20] +'" style="width: 15px;height: 15px;border-radius: 50%;" class="zoomable-image" title="<?=lang('p_chauffeur')?>">&nbsp;&nbsp;<a href="<?= base_url()?>chauffeur/Chauffeur_New/Detail/'+index[18]+'" class="" style="" title="<?=lang('dtl_dtl_chauffeur')?>">'+index[12]+'</a><br><?=lang('title_proprio_list')?><br><img src="<?= base_url()?>/upload/proprietaire/photopassport/'+ photo_pro +'" style="width: 15px;height: 15px;border-radius: 50%;" class="zoomable-image" title="<?=lang('title_proprio_list')?>">&nbsp;&nbsp;<a href="<?= base_url()?>proprietaire/Proprietaire/Detail/'+index[17]+'" class="" style="" title="<?=lang('dtl_dtl_proprio')?>">'+index[9]+'</a><br><?=lang('label_plaque')?><br><i class="text-muted small pt-2 ps-1 fa fa-credit-card"></i>&nbsp;&nbsp;'+ index[6] +'</td> </tr>  </table> </div> </div>  <p class="text-center text-muted small " style=" "> <a href="<?= base_url()?>tracking/Dashboard/tracking_chauffeur/'+index[11]+'" class="info_trajet fa fa-map-marker" style="border-radius:20px;padding:10px;" title="<?=lang('info_trajet')?>"><br><label class="info_trajet small " id="place"></label> </a></p> ',{maxWidth : 5000});

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
		var VEHICULE_TRACK = $('#VEHICULE_TRACK').val();
          	var COORD_TRACK = $('#COORD_TRACK').val();
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
				VEHICULE_TRACK:VEHICULE_TRACK,
          			COORD_TRACK:COORD_TRACK,
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

				for (var i = 0; i < markers.length; i++) {
					//alert(i)
					for (var i = 0; i < (donn.length)-1; i++)
					{
						var index = donn[i].split('<>');
						var marker = markers[i];
						var newLatitude = index[1];
						var newLongitude = index[2];
						marker.setLatLng([newLatitude, newLongitude]);
					}
				}


				$('#COORD_TRACK').val(newLatitude+','+newLongitude);

				L.mapbox.map('map').setView([newLatitude,newLongitude], zoom);

				markerClusterGroup.refreshClusters();

			},
		});

	}

// Mettre à jour les coordonnées initiales du marqueur
	updateMarkerPosition();

// Définir l'intervalle de mise à jour toutes les 10 secondes (10000 millisecondes)
	//setInterval(updateMarkerPosition, 10000);

	var timer = setInterval(async () => {
		// alert('jdshjhk')
		updateMarkerPosition();
	}, 10000);

//.....Fin...........




</script>