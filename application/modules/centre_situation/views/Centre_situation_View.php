<!DOCTYPE html>
<html lang="en">

<head>
	<?php include VIEWPATH . 'includes/header.php'; ?>

	<script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.4.10/leaflet.draw.css' rel='stylesheet' />
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.4.10/leaflet.draw.js'></script>
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-geodesy/v0.1.0/leaflet-geodesy.js'></script>

	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />


	<script src="https://unpkg.com/georaster"></script>
	<script src="https://unpkg.com/proj4"></script>
	<script src="https://unpkg.com/georaster-layer-for-leaflet"></script>


	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


	<style type="text/css">
		.mapbox-improve-map{
			display: none;
		}

		.leaflet-control-attribution{
			display: none !important;
		}
		.leaflet-control-attribution{
			display: none !important;
		}
		.mapbox-logo{
			display: none !important;
		}

		.card-body {
			padding: 0.2rem;
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

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Propriétaire</label>

					<select class="form-control" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID">
						<option value="" selected>-- Séléctionner --</option>
						<?php
						foreach ($proprio as $proprio)
						{
							?>
							<option value="<?=$proprio['PROPRIETAIRE_ID']?>"><?=$proprio['proprio_desc']?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>

		<br>

		<section class="section">
			<!-- <div class="container text-center"> -->
				<div class="row">
					<div class="text-left col-sm-12">
						<div class="card" style="border-radius: 20px;">
							<!-- <h5 class="card-title">Centre de situation</h5> -->
							<br>

							<div class="card-body">
								<div class="row">
									<div class="col-md-9">
										<div id="mapinfo" style="width: 100%;height: 600px;border-radius: 20px; margin-top: -24px;"></div>
									</div>

									<div class="col-md-3">
										<p class="card-text"><big class="text-muted">Légende</big></p><hr>

										<table class="table table-borderless">
											<tr>
												<td class="btn-sm"><font class="fa fa-check text-primary"></font> Véhicules enrégistrés</td>
												<td class="btn-sm"><strong><?=$nbrVehicule?></strong></td>
											</tr>
										</table>
									</div>
									
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</section>


		</main><!-- End #main -->

		<?php include VIEWPATH . 'includes/footer.php'; ?>

	</body>


	<script type="text/javascript">

// Fonction pour le chargement de donnees par defaut
		$(document).ready( function ()
		{


		});
	</script>

	<script>
      // initalize leaflet map

      // add OpenStreetMap basemap

		L.mapbox.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc1MmozajAwcGczZW1sMjMwZWxtZDQifQ.u8xhrt1Wn4A82X38f5_Iyw';

		var coord ='-2.84551,30.3337';

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


	</html>