<!DOCTYPE html>
<html lang="en">

<head>
	<?php include VIEWPATH . 'includes/header.php'; ?>

	<script src='https://api.mapbox.com/mapbox.js/v3.2.0/mapbox.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/v3.2.0/mapbox.css' rel='stylesheet' />
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-zoomslider/v0.7.0/L.Control.Zoomslider.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-zoomslider/v0.7.0/L.Control.Zoomslider.css' rel='stylesheet'/>

	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>
		#map {width: 102%;height: 600px;border-radius: 20px; margin-top: -14px; margin-bottom: -10px;margin-left:-10px;}

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
						<option value="" selected>-- Séléctionner --</option>
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
						<option value="" selected>-- Séléctionner --</option>
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
						<option value="" selected>-- Séléctionner --</option>
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
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<i class="fa fa-user text-primary"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title" id="nbr_proprietaire">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Nombre propriétaires&nbsp;&nbsp;<a href="<?= base_url()?>proprietaire/Proprietaire/liste " class="fa fa-eye text-dark" title="Voir la liste"></a></small>
					</div>

				</div>
			</div>
				<?php
			}

			?>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<i class="fa fa-car text-primary"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title" id="nbr_vehicule">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Nombre véhicules &nbsp;&nbsp;<a href="<?= base_url()?>vehicule/Vehicule " class="fa fa-eye text-dark" title="Voir la liste"></a></small>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<i class="fa fa-users text-primary"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title" id="nbrChauffeur">145</strong>
							</div>

						</div>
						<?php
						if($this->session->userdata('PROFIL_ID') == 1) // Admin
						{
							?>
							<small class="text-muted small pt-2 ps-1">Nombre chauffeurs &nbsp;&nbsp;<a href="<?= base_url()?>chauffeur/Chauffeur " class="fa fa-eye text-dark" title="Voir la liste"></a></small>
							<?php
						}
						else
						{
							?>
							<small class="text-muted small pt-2 ps-1">Nombre chauffeurs &nbsp;&nbsp;<a href="<?= base_url()?>proprietaire/Proprietaire_chauffeur" class="fa fa-eye text-dark" title="Voir la liste"></a></small>
							<?php
						}
						?>

						

					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<i class="fa fa-car text-primary"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeActif">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules actifs</small>
					</div>

				</div>
			</div>
			
		</div>

		<div class="row">
			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<!-- <h6 class="text-center">Véhicules en mouvement</h6> -->

								<img  class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/vehicule1.png')?>">
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeMouvement">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules en mouvement</small>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<!-- <h6 class="text-center">Véhicules en mouvement</h6> -->

								<img  class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/vehicule2.png')?>">
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeStationnement">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules en stationnement</small>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<!-- <h6 class="text-center">Véhicules en mouvement</h6> -->

								<img  class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/vehicule3.png')?>">
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeAllume">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules allumés</small>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<!-- <h6 class="text-center">Véhicules en mouvement</h6> -->

								<img  class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/vehicule5.png')?>">
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeEteint">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules éteints</small>
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
									<div class="col-12 col-lg-12">
										<div id="mapview">
										</div>
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
				url : "<?=base_url()?>centre_situation/Centre_situation/getmap/",
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
				  $('#vehiculeActif').html(data.vehiculeActif);
				  $('#vehiculeAllume').html(data.vehiculeAllume);
				  $('#vehiculeEteint').html(data.vehiculeEteint);
				   $('#vehiculeMouvement').html(data.vehiculeMouvement);
				  $('#vehiculeStationnement').html(data.vehiculeStationnement);
				 
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
					url: "<?= base_url() ?>centre_situation/Centre_situation/get_vehicule/" + PROPRIETAIRE_ID,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('#VEHICULE_ID').html(data);
					}
				});

			}
		}
	</script>

	</html>