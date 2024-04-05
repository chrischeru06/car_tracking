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

		.custom-marker-icon {
			border:solid 2px;
     border-radius: 50%;
		 background-color: rgba(95, 158, 160,0.3);

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
					<div class="card dash_card" style="border-radius:20px; width: 95%" onclick="GetVehicule($('#VEHICULE_ID').val());" title="Cliquer ici pour visualiser la liste">

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
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ACTIF');" title="Cliquer ici pour visualiser la liste">

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
									<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules actifs<i  title="Voir la liste" ></i></b>
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
									<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
									<img class="card-icon" style="width: 100%;margin-bottom: -30%;margin-left:-30%;" class="img-fluid" src="<?=base_url('upload/iconecartracking-04.png')?>">
								</div>

								<div class="col-lg-2">
									<strong class="card-title vehiculeInactif" style="position:relative;top: 10px;margin-left:-50%;">145</strong>
								</div>
								<div class="col-lg-6">
									<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules inactifs<i  title="Voir la liste" ></i></b>
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

			<div class="col-lg-3">
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_MOUVEMENT');" title="Cliquer ici pour visualiser la liste">

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


			<div class="col-lg-3">
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ETEINT');" title="Cliquer ici pour visualiser la liste">

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
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ALLUME');" title="Cliquer ici pour visualiser la liste">

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
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_STATIONNE');" title="Cliquer ici pour visualiser la liste">

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

			<div class="col-lg-3 autres_infos">
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_ATTENTE');" title="Cliquer ici pour visualiser la liste">

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

			<div class="col-lg-3 autres_infos">
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_APROUVE');" title="Cliquer ici pour visualiser la liste">

						<div class="card-body">

							<div class="d-flex align-items-center">
								<div class="col-lg-4">
									<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
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
			</div>

			<div class="col-lg-3 autres_infos">
					<div class="card dash_card" style="border-radius:20px; width: 100%" onclick="GetVehicule('V_REFUSE');" title="Cliquer ici pour visualiser la liste">

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


			<div class="col-lg-1" id="voirMoins">

				<label class="btn btn-outline-secondary rounded-pill" title="Voir moins" onclick="voirMoins();" style="font-size: 10px;margin-top: 20px;"><i class="fa fa-minus"></i></label>

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
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">

								<div class="table-responsive" style="padding-top: 20px;">
									<table id="table_vehicule" class="table table-hover" style="min-width: 100%">
										<thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
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
          		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
          		var VEHICULE_ID = $('#VEHICULE_ID').val();

          	//	alert(id)

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
          	// Fonction pour afficher les chauffeurs

          	function GetChauffeur(id)
          	{
          		var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
          		var VEHICULE_ID = $('#VEHICULE_ID').val();
          		
          		$('#ModalChauffeur').modal('show');
          		var row_count ="1000000";
          		table=$("#table_chauffeur").DataTable({
          			"processing":true,
          			"destroy" : true,
          			"serverSide":true,
          			"oreder":[[ 0, 'desc' ]],
          			"ajax":{
          				url:"<?=base_url()?>centre_situation/Centre_situation/GetChauffeur/"+id,
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