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

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
		.info_trajet:hover {
			color: cadetblue;
			cursor:pointer;
		}

		#place{
			font-family:tahoma;
		}

		.custom-marker-icon {
			border:solid 2px;
			border-radius: 50%;
			background-color: rgba(95, 158, 160,0.3);
		}

		#eye{
			color: black;
		}
		#eye:hover {
			color: blue;
		}

		.zoomable-image {
			transition: transform 0.3s ease;
		}

		.zoomable-image:hover {
			transform: scale(4.2);
		}

		#image-container{
			position: relative;
			width: 475px; 
			height: 350px; 
			overflow: hidden;
		}

		#phot_v {
			position: absolute;
			cursor: grab;
			transition: transform 0.2s;
			border-radius: 10px; 
/*			height: 99%;*/
margin-right: -50px; 
}


</style>
</head>

<body onclick = "">

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
								<h4 class="text-dark">Centre de situation</h4>
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

							<select class="form-control" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" onchange="getmap();get_vehicule();" style="border-radius:15px;">
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

							<select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID" onchange="getmap();" style="border-radius:15px;">
								<option value="" selected>-- Sélectionner --</option>
							</select>
						</div>
					</div>

					<input type="hidden" value="<?=$VEHICULE_TRACK?>" id="VEHICULE_TRACK">
					<?php
					if(!empty($COORD_TRACK))
					{
						?>
						<input type="hidden" value="<?=$COORD_TRACK['latitude'].','.$COORD_TRACK['longitude']?>" id="COORD_TRACK">
						<?php
					}
					?>

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

								<div class="col-lg-4">
									<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
									<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-08.png')?>">
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_proprietaire" style="position:relative;top: 10px;margin-left:-70%;">145</strong>
								</div>
								<div class="col-lg-6">
									<b class="small pt-2 ps-1" style="position:relative;top: 10px;margin-left:-10%;">Propriétaires enregistrés<i  title="Voir la liste" ></i></b>
								</div>

							</div>

							
						</div>

					</div>
				</div>
				<?php
			}

			?>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 95%" onclick="GetVehicule($('#VEHICULE_ID').val());get_nbr_vehicule($('#VEHICULE_ID').val());" title="Cliquer ici pour visualiser la liste">

					<input type="hidden" value="V_ENREGITRE" id="V_ENREGITRE" name="V_ENREGITRE">

					<div class="card-body">

						<div class="d-flex align-items-center">

							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-01.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbr_vehicule" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules enregistrés<i  title="Voir la liste" ></i></b>
							</div>

						</div>


					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ATTENTE');get_nbr_vehicule('V_ATTENTE');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-10.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbrDemandeEntente" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Demandes en attente<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-3 ">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_REFUSE');get_nbr_vehicule('V_REFUSE');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-13.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbrDemandeRefusee" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Demandes refusées<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ACTIF');get_nbr_vehicule('V_ACTIF');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-03.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title vehiculeActif" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules activés<i  title="Voir la liste" ></i></b>
							</div>
						</div>

					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_INACTIF');get_nbr_vehicule('V_INACTIF');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-04.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title vehiculeInactif" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules désactivés<i  title="Voir la liste" ></i></b>
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
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-02.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbrChauffeur" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Chauffeurs enregistrés<i  title="Voir la liste" ></i></b>
							</div>

						</div>

					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_MOUVEMENT');get_nbr_vehicule('V_MOUVEMENT');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-06.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeMouvement" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules en mouvement<i  title="Voir la liste" ></i></b>
							</div>
						</div>

					</div>

				</div>
			</div>

			<div class="col-lg-3 autres_infos">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_CREVAISON');get_nbr_vehicule('V_CREVAISON');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-05.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeAvecAccident" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules en accident<i  title="Voir la liste" ></i></b>
							</div>
						</div>

					</div>

				</div>
			</div>


			<div class="col-lg-3 autres_infos">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ETEINT');get_nbr_vehicule('V_ETEINT');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-07.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeEteint" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules éteints<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-1" id="voirPlus">

				<label class="btn btn-outline-secondary rounded-pill" title="Voir plus" onclick="voirPlus();" style="font-size: 10px;margin-top: -30px;"><i class="fa fa-plus"></i></label>

			</div>

			<div class="col-lg-3 autres_infos">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ALLUME');get_nbr_vehicule('V_ALLUME');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-09.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeAllume" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules allumés<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="col-lg-3 autres_infos">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_STATIONNE');get_nbr_vehicule('V_STATIONNE');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">
								<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-11.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="vehiculeStationnement" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules stationnés<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div>

			<!--<div class="col-lg-3 autres_infos">
				<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_APROUVE');get_nbr_vehicule('V_APROUVE');" title="Cliquer ici pour visualiser la liste">

					<div class="card-body">

						<div class="d-flex align-items-center">
							<div class="col-lg-4">

								<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-12.png')?>">
							</div>

							<div class="col-lg-2">
								<strong class="card-title" id="nbrDemandeApprouvee" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
							</div>
							<div class="col-lg-6">
								<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Demandes approuvées<i  title="Voir la liste" ></i></b>
							</div>
						</div>
					</div>

				</div>
			</div> -->


			<div class="col-lg-1" id="voirMoins">

				<label class="btn btn-outline-secondary rounded-pill" title="Voir moins" onclick="voirMoins();" style="font-size: 10px;margin-top: -30px;"><i class="fa fa-minus"></i></label>

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
										<div id="mapView">
										</div>
									</div>

									<!-- <div class="col-md-3">
										<h3>Légende</h3><hr>

											<p>
												<icon class="fa fa-map-marker text-info"></icon>&nbsp;
												<label class="text-muted small pt-2 ps-1">Véhicule actif (<b class="vehiculeActif text-info" id="vehiculeActif">9</b>)</label>
											</p>

											<p>
												<icon class="fa fa-map-marker text-danger"></icon>&nbsp;
												<label class="text-muted small pt-2 ps-1">Véhicule inactif (<b class="vehiculeInactif text-danger" id="vehiculeActif">9</b>)</label>
											</p>

										</div> -->

									</div>
								</div>
							</div>
						</div>
					</section>

					<!-- Modal liste des proprietaires -->

					<div class="modal fade" id="ModalProprietaire" tabindex="-1" style='border-radius:100px;'>
						<div class="modal-dialog modal-xl">
							<div class="modal-content">
								<div class="modal-header" style='background:cadetblue;color:white;'>
									<h6 class="modal-title">Liste des propriétaires</h6>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">

									<div class="table-responsive" style="padding-top: 20px;">
										<table id="table_proprietaire" class="table table-hover" style="min-width: 100%">
											<thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
												<tr>
													<th class="text-dark">#</th>

													<th class="text-dark">IDENTIFICATION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
													<th class="text-dark">EMAIL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
													<th class="text-dark">TELEPHONE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
													<th>STATUT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
													<th>NBR&nbsp;VEHICULE</th>

												</tr>
											</thead>
											<tbody class="text-dark">
											</tbody>
										</table>
									</div>

								</div>
                    <!-- <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                  </div> -->
              </div>
          </div>
      </div>

      <!-- Modal liste des vehicules -->

      <div class="modal fade" id="ModalVehicule" tabindex="-1" style='border-radius:100px;'>
      	<div class="modal-dialog modal-xl">
      		<div class="modal-content">
      			<div class="modal-header" style='background:cadetblue;color:white;'>
      				<h6 class="modal-title">Liste des véhicules</h6>
      				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick = "reunitialise_filtre();"></button>
      			</div>
      			<div class="modal-body">

      				<div class="row">

      					<div class="col-md-6">
      						<label class="text-dark" style="font-weight: 1000; color:#454545">Filtrage selon la validité des documments</label>
      						<select class="form-control" id="CHECK_VALIDE" name="CHECK_VALIDE" onchange="GetVehicule();get_nbr_vehicule();">
      							<option value="0"> Tous les véhicules</option>
      							<option value="1"> Véhicules avec assurances valides </option>
      							<option value="2"> Véhicules avec assurances invalides </option>
      							<option value="3"> Véhicules avec contrôles techniques valides </option>
      							<option value="4"> Véhicules avec contrôles techniques invalides </option>

      						</select>

      						<label class="fa fa-check text-success" id="check" style="position: relative;top: -33%;left: 90%;"></label>

      						<label class="fa fa-ban text-danger" id="close" style="position: relative;top: -33%;left: 90%;"></label>

      					</div>

      					<div class="col-md-6">
      						<span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:6px;left:-23px;">4</span>
      					</div>


      				</div>

      				<div class="table-responsive" style="padding-top: 20px;">
      					<table id="table_vehicule" class="table table-hover" style="min-width: 100%">
      						<thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
      							<tr>
      								<th class="">#</th>
      								<th class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      								<th class="">MARQUE</th>
      								<th class="">MODELE</th>
      								<th class="">PLAQUE</th>
      								<th class="">COULEUR</th>
      								<th class="">CONSOMMATION</th>
      								<!-- <th class="">PROPRIETAIRE</th> -->
      								<th class="">DATE&nbsp;ENREGISTREMENT</th>
      								<th class="">STATUT</th>
      								<th class=""></th>
      								
      							</tr>
      						</thead>
      						<tbody class="text-dark">
      						</tbody>
      					</table>
      				</div>

      			</div>
      			<!-- footer here -->
      		</div>
      	</div>
      </div>


     <!-- <div class="modal fade" id="mypicture' .$row->CHAUFFEUR_ID. '">
				<div class="modal-dialog modal-dialog-centered ">
				<div class="modal-content">
				<div class="modal-header" style="background:cadetblue;color:white;">
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<img src = "'.base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT).'"" height="50%"  width="50%" >
				</div>
				</div>
				</div>
			</div> -->


			<!-- Modal photo du vehicule-->

			<div class="modal fade" id="Modal_photo_vehicule">
				<div class="modal-dialog modal-dialog-centered ">
					<div class="modal-content">
						<div class="modal-header" style='background:cadetblue;color:white;'>
							<h6 class="modal-title">Photo véhicule</h6>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
						</div>
						<div class="modal-body">

							<div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

								<div class="col-md-2">

								</div>

								<div class="col-md-1">
									<i onclick="zoomIn()" class="fa fa-plus-circle text-muted"></i>

									<input type="hidden" id="rotation" value="0">
								</div>

								<div class="col-md-1">
									<i onclick="zoomOut()" class="fa fa-minus-circle text-muted"></i>
								</div>

								<div class="col-md-1">
									<i onclick="moveX(-1)" class="fa fa-arrow-circle-left text-muted"></i>
								</div>

								<div class="col-md-1">
									<i onclick="moveX(1)" class="fa fa-arrow-circle-right text-muted"></i>
								</div>

								<div class="col-md-1">
									<i onclick="moveY(-1)" class="fa fa-arrow-circle-up text-muted"></i>
								</div>

								<div class="col-md-1">
									<i onclick="moveY(1)" class="fa fa-arrow-circle-down text-muted"></i>
								</div>

								<div class="col-md-1">
									<i onclick="rotate_op()" class="fa fa-rotate-right text-muted"></i>
								</div>


							</div>

							<div class="row">

								<div class="col-md-12" id="image-container">
									<img src="" id="phot_v" alt="Description de l'image">
								</div>

							</div>

						</div>
						<!-- footer here -->
					</div>
				</div>
			</div>



			<!-- Modal liste des chauffeurs -->

			<div class="modal fade" id="ModalChauffeur" tabindex="-1" style='border-radius:100px;'>
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header" style='background:cadetblue;color:white;'>
							<h6 class="modal-title">Liste des chauffeurs</h6>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">

							<div class="table-responsive" style="padding-top: 20px;">
								<table id="table_chauffeur" class="table table-hover" style="min-width: 100%">
									<thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
										<tr>

											<th class="text-dark">#</th>
											<th class="text-dark">CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

											<!-- <th class="text-dark">ADRESSE</th> -->
                          <!--   <th class="text-dark">Province&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">Commune&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">Zonne&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">Colline&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
                            <th class="text-dark">TELEPHONE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">EMAIL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

                            <th class="text-dark">STATUT&nbsp;&nbsp;&nbsp;</th>

                            <th class="text-dark"></th>
                        </tr>
                    </thead>
                    <tbody class="text-dark">
                    </tbody>
                </table>
            </div>

        </div>
        <!-- footer here -->
    </div>
</div>
</div>



<!-- Modal liste des chauffeurs pour le proprietaire connecte-->

<div class="modal fade" id="ModalChauffeurPro" tabindex="-1" style='border-radius:100px;'>
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header" style='background:cadetblue;color:white;'>
				<h6 class="modal-title">Liste des chauffeurs</h6>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

				<div class="table-responsive" style="padding-top: 20px;">
					<table id="table_chauffeur_pro" class="table table-hover" style="min-width: 100%">
						<thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
							<tr>

								<th class="text-dark">#</th>
								<th class="text-dark">CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

								<!-- <th class="text-dark">ADRESSE</th> -->
                          <!--   <th class="text-dark">Province&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">Commune&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">Zonne&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">Colline&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
                            <th class="text-dark">TELEPHONE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-dark">EMAIL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            

                            <th class="text-dark">OPTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark">
                    </tbody>
                </table>
            </div>

        </div>
        <!-- footer here -->
    </div>
</div>
</div>


</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

</body>

<script>
	$(document).ready(function(){

		getmap();

		$('.autres_infos').hide();
		$('#voirMoins').hide();

	});

</script>


<script>
	function voirPlus()
	{
		$('.autres_infos').show();
		$('#voirMoins').show();
		$('#voirPlus').hide();
	}
</script>


<script>
	function voirMoins()
	{
		$('.autres_infos').hide();
		$('#voirMoins').hide();
		$('#voirPlus').show();
	}
</script>


<script>

		// Fonction pour afficher la carte

	function getmap(id=2){

            // var searchString = $('#search').val();ENQUETEUR_ID
		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
		var VEHICULE_ID = $('#VEHICULE_ID').val();
		var VEHICULE_TRACK = $('#VEHICULE_TRACK').val();
		var COORD_TRACK = $('#COORD_TRACK').val();

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

				$('#mapView').html(data.carte_view);
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

			},
		});
	}

</script>

      <!--  <script>
          	const timer = setInterval(async () => {
          		getmap(2);
          	}, 10000);
          </script> -->

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

          <script>
          	// Fonction pour afficher les proprietaire

          	function GetProprietaire(id)
          	{
          		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
          		var VEHICULE_ID = $('#VEHICULE_ID').val();
          		
          		$('#ModalProprietaire').modal('show');
          		var row_count ="1000000";
          		table=$("#table_proprietaire").DataTable({
          			"processing":true,
          			"destroy" : true,
          			"serverSide":true,
          			"oreder":[[ 0, 'desc' ]],
          			"ajax":{
          				url:"<?=base_url()?>centre_situation/Centre_situation/GetProprietaire/"+id,
          				type:"POST"
          			},
          			lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
          			pageLength: 10,
          			"columnDefs":[{
          				"targets":[],
          				"orderable":false
          			}],
          			dom: 'Bfrtlip',
          			buttons: ['excel', 'pdf'],  

          			language: {
          				"sProcessing": "Traitement en cours...",
          				"sSearch": "Recherche&nbsp;:",
          				"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
          				"sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          				"sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          				"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          				"sInfoPostFix": "",
          				"sLoadingRecords": "Chargement en cours...",
          				"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
          				"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
          				"oPaginate":
          				{
          					"sFirst": "Premier",
          					"sPrevious": "Pr&eacute;c&eacute;dent",
          					"sNext": "Suivant",
          					"sLast": "Dernier"
          				},
          				"oAria":
          				{
          					"sSortAscending": ": activer pour trier la colonne par ordre croissant",
          					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
          				}
          			}

          		});

          	}
          </script>

          <script>
          	// Fonction pour afficher les véhicules
          	
          	function GetVehicule(id)
          	{
          		var V_ENREGITRE = '';

          		if(id == '')
          		{
          			V_ENREGITRE = $('#V_ENREGITRE').val();
          		}

          		//get_nbr_vehicule();

          		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
          		var VEHICULE_ID = $('#VEHICULE_ID').val();
          		var CHECK_VALIDE = $('#CHECK_VALIDE').val();

          		if(CHECK_VALIDE == 1 || CHECK_VALIDE == 3)
          		{
          			$('#close').hide();
          			$('#check').show();
          		}
          		else if(CHECK_VALIDE == 2 || CHECK_VALIDE == 4)
          		{
          			$('#close').show();
          			$('#check').hide();
          		}
          		else
          		{
          			$('#close').hide();
          			$('#check').hide();
          		}

          		$('#ModalVehicule').modal('show');
          		var row_count ="1000000";
          		table=$("#table_vehicule").DataTable({
          			"processing":true,
          			"destroy" : true,
          			"serverSide":true,
          			"oreder":[[ 0, 'desc' ]],
          			"ajax":{
          				url:"<?=base_url()?>centre_situation/Centre_situation/GetVehicule/"+id,
          				type:"POST",
          				data: {

          					PROPRIETAIRE_ID:PROPRIETAIRE_ID,
          					VEHICULE_ID:VEHICULE_ID,
          					CHECK_VALIDE:CHECK_VALIDE,
          					V_ENREGITRE:V_ENREGITRE,
          				},
          			},
          			lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
          			pageLength: 10,
          			"columnDefs":[{
          				"targets":[],
          				"orderable":false
          			}],
          			dom: 'Bfrtlip',
          			buttons: ['excel', 'pdf'],  

          			language: {
          				"sProcessing": "Traitement en cours...",
          				"sSearch": "Recherche&nbsp;:",
          				"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
          				"sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          				"sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          				"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          				"sInfoPostFix": "",
          				"sLoadingRecords": "Chargement en cours...",
          				"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
          				"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
          				"oPaginate":
          				{
          					"sFirst": "Premier",
          					"sPrevious": "Pr&eacute;c&eacute;dent",
          					"sNext": "Suivant",
          					"sLast": "Dernier"
          				},
          				"oAria":
          				{
          					"sSortAscending": ": activer pour trier la colonne par ordre croissant",
          					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
          				}
          			}

          		});

          	}
          </script>


          <script>
          	function get_nbr_vehicule(id)
          	{
          		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
          		var VEHICULE_ID = $('#VEHICULE_ID').val();
          		var CHECK_VALIDE = $('#CHECK_VALIDE').val();

          		var V_ENREGITRE = '';

          		if(id == '')
          		{
          			V_ENREGITRE = $('#V_ENREGITRE').val();
          		}

          		$.ajax({
          			url: "<?= base_url() ?>centre_situation/Centre_situation/get_nbr_vehicule/" + id,
          			type: "POST",
          			data: {
          				   PROPRIETAIRE_ID:PROPRIETAIRE_ID,
          					VEHICULE_ID:VEHICULE_ID,
          					CHECK_VALIDE:CHECK_VALIDE,
          					V_ENREGITRE:V_ENREGITRE,
          				},
          			dataType: "JSON",
          			success: function(data) {
          				$('.nbr_vehicule').text(data);
          			},

          		});
          	}
          </script>


          <script>
          	//reunitialisation de condition de filtrage des vehicules
          	function reunitialise_filtre()
          	{
          		$('#CHECK_VALIDE').val(0);
          	}
          </script>


          <script>
          	function show_image(VEHICULE_ID)
          	{
          		var imgElement = document.getElementById("phot_v");

          		$.ajax({
          			url: "<?= base_url() ?>centre_situation/Centre_situation/get_image_v/" + VEHICULE_ID,
          			type: "POST",
          			dataType: "JSON",
          			success: function(data) {

          				$('#Modal_photo_vehicule').modal('show');

          				//$('#phot_v').html(data.photo);

          				imgElement.src = data.photo;
          			},
          		});

          		//phot_v
          	}
          </script>


          <script>
             //Operations photo avec les boutons

			  var scale = 1; // Facteur de zoom initial
			  var translateX = 0; // Décalage horizontal initial
			  var translateY = 0; // Décalage vertical initial

			  var photo = document.getElementById('phot_v');

			  // Fonction pour zoomer la photo
			  function zoomIn() {
			  	scale += 0.1;
			  	updateTransform();

			  }

			  // Fonction pour dézoomer la photo
			  function zoomOut() {
			  	scale -= 0.1;
			  	updateTransform();
			  }

			  // Fonction pour déplacer la photo horizontalement
			  function moveX(direction) {
			    translateX += direction * 50; // Changer la valeur de décalage
			    updateTransform();
			}

			  // Fonction pour déplacer la photo verticalement
			function moveY(direction) {
			    translateY += direction * 50; // Changer la valeur de décalage
			    updateTransform();
			}

			  // Fonction pour mettre à jour la transformation CSS de la photo
			function updateTransform() {
				photo.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
			}

			//Rotation de l'image

			function rotate_op()
			{
				const image = document.getElementById('phot_v');
			// const rotateBtn = document.getElementById('rotate-btn');
				let rotation = Number($('#rotation').val());

			//rotateBtn.addEventListener('click', () => {
				rotation += 90;
				image.style.transform = `rotate(${rotation}deg)`;
				$('#rotation').val(rotation)
			//});
			}


		</script>


		<script>
          	 //Operations photo avec la sourie

			let container = document.getElementById('image-container');
			let image = document.getElementById('phot_v');
			let lastX, lastY;
			let isDragging = false;
			let rotationAngle = 0;

    // Zoomer/dézoomer sur double clic
			document.getElementById('phot_v').addEventListener('dblclick', function() {
            if (this.style.transform === "scale(2)") {
                this.style.transform = "scale(1)";
            } else {
                this.style.transform = "scale(2)";
            }
        });
    // Déplacer en maintenant le clic gauche
			image.addEventListener('mousedown', function(event) {
				if (event.button === 0) {
					isDragging = true;
					lastX = event.clientX;
					lastY = event.clientY;
					image.style.cursor = 'grabbing';
				}
			});

			document.addEventListener('mousemove', function(event) {
				if (isDragging) {
					let deltaX = event.clientX - lastX;
					let deltaY = event.clientY - lastY;
					let newX = image.offsetLeft + deltaX;
					let newY = image.offsetTop + deltaY;
					image.style.left = newX + 'px';
					image.style.top = newY + 'px';
					lastX = event.clientX;
					lastY = event.clientY;
				}
			});

			document.addEventListener('mouseup', function(event) {
				if (event.button === 0) {
					isDragging = false;
					image.style.cursor = 'grab';
				}
			});

    // Pivoter avec la molette de la souris
			document.addEventListener('wheel', function(event) {
				if (event.deltaY < 0) {
					rotationAngle += 10;
				} else {
					rotationAngle -= 10;
				}
				image.style.transform = `rotate(${rotationAngle}deg)`;
			});


          	 // Fonction pour mettre à jour la transformation CSS de la photo
			function updateTransform() {
				photo.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
			}
		</script>


		<script>
          	// Fonction pour afficher les chauffeurs

			function GetChauffeurPro(id)
			{
				var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
				var VEHICULE_ID = $('#VEHICULE_ID').val();

				$('#ModalChauffeurPro').modal('show');
				var row_count ="1000000";
				table=$("#table_chauffeur_pro").DataTable({
					"processing":true,
					"destroy" : true,
					"serverSide":true,
					"oreder":[[ 0, 'desc' ]],
					"ajax":{
						url:"<?=base_url()?>centre_situation/Centre_situation/GetChauffeurPro/"+id,
						type:"POST",
						data: {

							PROPRIETAIRE_ID:PROPRIETAIRE_ID,
							VEHICULE_ID:VEHICULE_ID,
						},
					},
					lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
					pageLength: 10,
					"columnDefs":[{
						"targets":[],
						"orderable":false
					}],
					dom: 'Bfrtlip',
					buttons: ['excel', 'pdf'],  

					language: {
						"sProcessing": "Traitement en cours...",
						"sSearch": "Recherche&nbsp;:",
						"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
						"sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
						"sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
						"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
						"sInfoPostFix": "",
						"sLoadingRecords": "Chargement en cours...",
						"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
						"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
						"oPaginate":
						{
							"sFirst": "Premier",
							"sPrevious": "Pr&eacute;c&eacute;dent",
							"sNext": "Suivant",
							"sLast": "Dernier"
						},
						"oAria":
						{
							"sSortAscending": ": activer pour trier la colonne par ordre croissant",
							"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
						}
					}

				});

			}
		</script>


		</html>