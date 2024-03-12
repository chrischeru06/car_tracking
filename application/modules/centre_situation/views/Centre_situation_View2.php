<!DOCTYPE html>
<html lang="en">

<head>
	<?php include VIEWPATH . 'includes/header.php'; ?>

	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
	<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
	<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>
		#map {width: 102%;height: 600px;border-radius: 20px; margin-top: -14px; margin-bottom: -10px;margin-left:-10px;z-index: 1;}

		.mapbox-improve-map{
			display: none;
		}

		.leaflet-control-attribution{
			display: none !important;
		}
		.leaflet-control-attribution{
			display: none !important;
		}
		.search-ui {

		}

		.circle-green {
			background-color: #829b35;
			border-radius: 50%
		}

		.dash_card:hover {
			color: cadetblue;
			background-color: rgba(95, 158, 160,0.3);
		}


		.marker {
			background-image: url('<?= base_url() ?>/upload/vehicule_icon_marker.png');
			background-size: cover;
			width: 50px;
			height: 50px;
			border-radius: 50%;
			cursor: pointer;
		}

	</style>
</head>

<body>

	<!-- ======= Header ======= -->
	<?php include VIEWPATH . 'includes/nav_bar.php'; ?>
	<!-- End Header -->

	<!-- ======= Sidebar ======= -->
	<?php include VIEWPATH . 'includes/menu_left.php'; ?>
	<!-- End Sidebar-->

	<main id="main" class="main">

		<div class="row page-titles mx-0">
			<div class="col-sm-10 p-md-0">
				<div class="welcome-text">
					<table>
						<tr>
							<td> 
								<!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
							</td>
							<td>  
								<h4 class="text-dark">Véhicule</h4>
								<nav aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="#">Centre de situation</a></li>
										<li class="breadcrumb-item"><a href="#">Carte</a></li>
										<!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
									</ol>
								</nav>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-md-2">

				<!-- <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('vehicule/Vehicule')?>" class="nav-link position-relative"><i class="bi bi-list"></i> Liste</a> -->

			</div>
		</div>

		<!-----------------filtre-------------------------->

		

		<?php

			if($this->session->userdata('PROFIL_ID') == 1) // Admin
			{
				?>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label>Propriétaire</label>

							<select class="form-control" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" onchange="getmap();get_vehicule();">
								<option value="" selected>-- Sélectionner --</option>
								<?php
								foreach ($proprio as $key_pro)
								{

									if($filtre_pro['PROPRIETAIRE_ID'] == $key_pro['PROPRIETAIRE_ID']){
										echo '<option selected value="'.$key_pro['PROPRIETAIRE_ID'].'">'.$key_pro['proprio_desc'].'</option>'; 
									}else{
										echo '<option value="'.$key_pro['PROPRIETAIRE_ID'].'">'.$key_pro['proprio_desc'].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>Véhicule</label>

							<select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID" onchange="getmap();">
								<option value="" selected>-- Sélectionner --</option>
							</select>
						</div>
					</div>

				</div>
				<?php
			}
			else if($this->session->userdata('PROFIL_ID') == 2) // Proprietaire
			{
				?>
				<div class="col-md-4">
					<div class="form-group">
						<label>Véhicule</label>
						<select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID" onchange="getmap();">
							<option value="" selected>-- Sélectionner --</option>
							<?php
							foreach ($vehicule as $key_vehicule)
							{
								echo '<option value="'.$key_vehicule['VEHICULE_ID'].'">'.$key_vehicule['PLAQUE'].'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<?php
			}

			?>


			<br>

			<div class="row">

				<?php

			if($this->session->userdata('PROFIL_ID') == 1) // Admin
			{
				?>
				<div class="col-lg-3">
					<div class="card dash_card" style="border-radius:20px; width: 95%" onclick="GetProprietaire($('#PROPRIETAIRE_ID').val());" title="Cliquer ici pour visualiser la liste">

						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="col-lg-3">
									<i class="fa fa-user text-dark" style="font-size: 50px;margin-top: 17px;"></i>
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_proprietaire" style="position: relative;top: 12px;">145</strong>
								</div>
								<div class="col-lg-7">
									<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Propriétaires<i  title="Voir la liste" ></i></b>
								</div>

							</div>

							
						</div>

					</div>
				</div>
				<?php
			}

			?>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 95%" onclick="GetVehicule($('#VEHICULE_ID').val());" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-5">
								<i class="fa fa-bus text-secondary" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbr_vehicule" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-5">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 95%" onclick="<?php if($this->session->userdata('PROFIL_ID') == 1){echo "GetChauffeur($('#PROPRIETAIRE_ID').val());";}else{echo "GetChauffeurPro($('#PROPRIETAIRE_ID').val());";}?>" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbrChauffeur" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Chauffeurs<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ACTIF');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<i class="fa fa-car text-primary" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title vehiculeActif" id="vehiculeActif" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules actifs<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_INACTIF');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<i class="fa fa-car text-danger" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title vehiculeInactif" id="" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules inactifs<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_CREVAISON');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<i class="fa fa-taxi text-warning" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title " id="vehiculeAvecAccident" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules en crevaison<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_MOUVEMENT');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<i class="fa fa-truck text-success" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeMouvement" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules en mouvement<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ETEINT');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<i class="fa fa-taxi text-secondary" style="font-size: 50px;margin-top: 17px;"></i>
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeEteint" style="position: relative;top: 12px;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules éteints<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>
			
		</div>



		<section class="section">
			<!-- <div class="container text-center"> -->
				<div class="row">
					<div class="text-left col-sm-12">
						<div class="card" style="border-radius: 20px;">
							<!-- <h5 class="card-title">Centre de situation</h5> -->
							<br>

							<div class="card-body">
								<div class="row">

									<div class="col-md-12">
										<div id="mapview">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</section>


			</main><!-- End #main -->

			<?php include VIEWPATH . 'includes/footer.php'; ?>

			<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

		</body>


		<script>
			$(document).ready(function(){

				getmap();

			});

		</script>


		<script>

		// Fonction pour afficher la carte

			function getmap(){

            // var searchString = $('#search').val();ENQUETEUR_ID
				var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
				var VEHICULE_ID = $('#VEHICULE_ID').val();

				$.ajax({
					url : "<?=base_url()?>centre_situation/Centre_situation2/getmap/",
					type : "POST",
					dataType: "JSON",
					cache:false,
					data: {

						PROPRIETAIRE_ID:PROPRIETAIRE_ID,
						VEHICULE_ID:VEHICULE_ID,
					},

					success:function(data) {						

						$('#mapview').html(data.carte_view);

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

					},
				});
			}

		</script>

		<script>
          	function get_vehicule()
          	{
          		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();

          		if (PROPRIETAIRE_ID == '') {
          			$('#VEHICULE_ID').html('<option value="">Sélectionner</option>');
          		} else {
          			$.ajax({
          				url: "<?= base_url() ?>centre_situation/Centre_situation2/get_vehicule/" + PROPRIETAIRE_ID,
          				type: "GET",
          				dataType: "JSON",
          				success: function(data) {
          					$('#VEHICULE_ID').html(data);
          				}
          			});

          		}
          	}
          </script>


		<script>

	// 		mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A';
	// 		const map = new mapboxgl.Map({
	// 			container: 'map',
  //       // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
	// 			style: 'mapbox://styles/mapbox/streets-v12',
	// 			bounds: [29.383188,-3.384438, 29.377566,-3.369615],
  //   projection: "globe" // display the map as a 3D globe
  // });

	// 		map.addControl(new mapboxgl.NavigationControl());
	// 		map.addControl(new mapboxgl.FullscreenControl());


	// 		map.on('load', async () => {
  //       // Get the initial location of the International Space Station (ISS).
	// 			const geojson = await getLocation();

  //       // Add the ISS location as a source.
	// 			map.addSource('iss', {
	// 				type: 'geojson',
	// 				data: geojson
	// 			});


  //       // Add the rocket symbol layer to the map.   
	// 			map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });

	// 			map.addSource('dot-point', {
	// 				'type': 'geojson',
	// 				'data': {
	// 					'type': 'FeatureCollection',
	// 					'features': [
	// 					{
	// 						'type': 'Feature',
	// 						'geometry': {
	// 							'type': 'Point',
  //           'coordinates': [-77.4144, 25.0759] // icon position [lng, lat]
  //         }
  //       }
  //       ]
	// 				}
	// 			});

	// 			map.addLayer({
	// 				'id': 'iss',
	// 				'type': 'symbol',
	// 				'source': 'iss',
	// 				'layout': {
	// 					'icon-image': 'pulsing-dot',


	// 				},

	// 			});

  //       // Update the source from the API every 2 seconds.
	// 			const updateSource = setInterval(async () => {
	// 				const geojson = await getLocation(updateSource);
	// 				map.getSource('iss').setData(geojson);
	// 			}, 2000);

	// 			async function getLocation(updateSource) {
  //           // Make a GET request to the API and return the location of the ISS.
	// 				try {

	// 					const response = await fetch(
	// 						'<?= base_url() ?>tracking/Dashboard/getmap/',
	// 						{ method: 'GET' }
	// 						);
	// 					const { latitude, longitude, vitesse } = await response.json();
  //               // Fly the map to the location.
	// 					map.flyTo({
	// 						center: [longitude, latitude],
	// 						speed: 0.5
	// 					});

	// 					const popup = new mapboxgl.Popup({ closeOnClick: true })
	// 					.setLngLat([longitude, latitude])
	// 					.setHTML(' '+[vitesse]+' Km/h')
	// 					.addTo(map);

  //               // Return the location of the ISS as GeoJSON.
	// 					return {
	// 						'type': 'FeatureCollection',
	// 						'features': [
	// 						{
	// 							'type': 'Feature',
	// 							'geometry': {
	// 								'type': 'Point',
	// 								'coordinates': [longitude, latitude]
	// 							}
	// 						}
	// 						]
	// 					};


	// 				} catch (err) {
  //               // If the updateSource interval is defined, clear the interval to stop updating the source.
	// 					if (updateSource) clearInterval(updateSource);
	// 					throw new Error(err);
	// 				}


	// 			}
	// 		});



	// 			const geojson = {
	// 				type: 'FeatureCollection',
	// 				features: [
	// 				{
	// 					type: 'Feature',
	// 					geometry: {
	// 						type: 'Point',
	// 						coordinates: [29.3619433,-3.3861416]
	// 					},
	// 					properties: {
	// 						title: 'Mapbox',
	// 						description: 'Washington, D.C.'
	// 					}
	// 				},
	// 				{
	// 					type: 'Feature',
	// 					geometry: {
	// 						type: 'Point',
	// 						coordinates: [29.377566,-3.369615]
	// 					},
	// 					properties: {
	// 						title: 'Mapbox',
	// 						description: 'San Francisco, California'
	// 					}
	// 				}
	// 				]
	// 			};



	// 			// add markers to map
	// 			for (const feature of geojson.features) {
  // // create a HTML element for each feature
	// 				const el = document.createElement('div');
	// 				el.className = 'marker';

  // // make a marker for each feature and add to the map
	// 				new mapboxgl.Marker(el).setLngLat(feature.geometry.coordinates).addTo(map);
	// 			}


	// 			 // Create a default Marker and add it to the map.
  //   const marker1 = new mapboxgl.Marker()
  //       .setLngLat([29.3619433,-3.3861416])
  //       .addTo(map);

  //   // Create a default Marker, colored black, rotated 45 degrees.
  //   const marker2 = new mapboxgl.Marker({ color: 'black', rotation: 45 })
  //       .setLngLat([29.377566,-3.369615])
  //       .addTo(map);



		</script>

		</html>