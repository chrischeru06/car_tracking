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
							<!-- <div class="d-flex align-items-center">
								<div class="col-lg-3">
									<i class="fa fa-user text-dark" style="font-size: 30px;margin-top: 17px;"></i>
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_proprietaire" style="position: relative;top: 12px;margin-left:-50%;">145</strong>
								</div>
								<div class="col-lg-7">
									<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Propriétaires<i  title="Voir la liste" ></i></b>
								</div>

							</div> -->

							<div class="d-flex align-items-center">

								<div class="col-lg-3">
									<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
									<img class="card-icon" style="margin-bottom: -30%;margin-left:-10%;" class="img-fluid" src="<?=base_url('upload/user_32.png')?>">
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_proprietaire" style="position:relative;top: 10px;margin-left:-30%;">145</strong>
								</div>
								<div class="col-lg-7">
									<b class="small pt-2 ps-1" style="position:relative;top: 20px;">Propriétaires &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i  title="Voir la liste" ></i></b>
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

							<!-- <div class="d-flex align-items-center">

								<div class="col-lg-5">
									<i class="fa fa-bus text-secondary" style="font-size: 30px;margin-top: 17px;"></i>
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_vehicule" style="position: relative;top: 12px;margin-left:-50%;">145</strong>
								</div>
								<div class="col-lg-5">
									<b class="small pt-2 ps-1" style="position: relative;top: 12px;">Véhicules<i  title="Voir la liste" ></i></b>
								</div>

							</div> -->

							<div class="d-flex align-items-center">

								<div class="col-lg-4">
									<!-- <i class="fa fa-user-circle-o text-dark" style="font-size: 50px;margin-top: 17px;"></i> -->
									<img class="card-icon" style="margin-bottom: -30%;margin-left:-10%;" class="img-fluid" src="<?=base_url('upload/vehicule_icon_32.png')?>">
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_vehicule" style="position:relative;top: 10px;margin-left:-40%;">145</strong>
								</div>
								<div class="col-lg-6">
									<b class="small pt-2 ps-1" style="position:relative;top: 20px;">Véhicules &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i  title="Voir la liste" ></i></b>
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
									<b class="small pt-2 ps-1" style="position:relative;top: 20px;">Chauffeurs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i  title="Voir la liste" ></i></b>
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
									<b class="small pt-2 ps-1" style="position:relative;top: 10px;">Véhicules en crevaison<i  title="Voir la liste" ></i></b>
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
                        <th class="">PROPRIETAIRE</th>
                        <th class="">DATE&nbsp;D'ENREGISTREMENT</th>
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

			});

		</script>


		<script>

		// Fonction pour afficher la carte

			async function getmap(){

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
          				url:"<?=base_url()?>centre_situation/Centre_situation2/GetProprietaire/"+id,
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
          				url:"<?=base_url()?>centre_situation/Centre_situation2/GetVehicule/"+id,
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
          				url:"<?=base_url()?>centre_situation/Centre_situation2/GetChauffeur/"+id,
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
          				url:"<?=base_url()?>centre_situation/Centre_situation2/GetChauffeurPro/"+id,
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

// 	mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A';


// 	var coord = '<?= $coordinates; ?>';
// 	var coord = coord.split(",");
// 	var zoom = '<?= $zoom; ?>';

// 	//alert("jhhkjl")


//    var map = new mapboxgl.Map({
// 		container: 'map',
//         // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
// 		style: 'mapbox://styles/mapbox/streets-v12',
// 		center: [coord[0],coord[1]],
// 		zoom: zoom,
// 		bounds: [29.383188,-3.384438, 29.377566,-3.369615],
//     projection: "globe" // display the map as a 3D globe
// });



// 	map.addControl(new mapboxgl.NavigationControl());
// 	map.addControl(new mapboxgl.FullscreenControl());




// 	var donn = '<?= $donnees_vehicule?>';

// 	var donn = donn.split('@');


// 	map.on('load', async () => {
//         // Get the initial location of the International Space Station (ISS).
//         const geojson = await getLocation();
//         // Add the ISS location as a source.
//         map.addSource('iss', {
//             type: 'geojson',
//             data: geojson
//         });
//         // Add the rocket symbol layer to the map.
//         map.addLayer({
//             'id': 'iss',
//             'type': 'symbol',
//             'source': 'iss',
//             'layout': {
//                 // This icon is a part of the Mapbox Streets style.
//                 // To view all images available in a Mapbox style, open
//                 // the style in Mapbox Studio and click the "Images" tab.
//                 // To add a new image to the style at runtime see
//                 // https://docs.mapbox.com/mapbox-gl-js/example/add-image/
//                 'icon-image': 'car'
//             }
//         });

//         // Update the source from the API every 2 seconds.
//         const updateSource = setInterval(async () => {
//             const geojson = await getLocation(updateSource);
//             map.getSource('iss').setData(geojson);
//         }, 2000);

//         async function getLocation(updateSource) {
//             // Make a GET request to the API and return the location of the ISS.
//             try {
//                 const response = await fetch(
//                     '<?= base_url() ?>centre_situation/Centre_situation2/getmap/',
//                     { method: 'GET' }
//                 );
//                 const { latitude, longitude } = await response.json();
//                 // Fly the map to the location.
//                 map.flyTo({
//                     center: [longitude, latitude],
//                     speed: 0.5
//                 });
//                 // Return the location of the ISS as GeoJSON.
//                 return {
//                     'type': 'FeatureCollection',
//                     'features': [
//                         {
//                             'type': 'Feature',
//                             'geometry': {
//                                 'type': 'Point',
//                                 'coordinates': [longitude, latitude]
//                             }
//                         }
//                     ]
//                 };
//             } catch (err) {
//                 // If the updateSource interval is defined, clear the interval to stop updating the source.
//                 if (updateSource) clearInterval(updateSource);
//                 throw new Error(err);
//             }
//         }
//     });


	// for (var i = 0; i < (donn.length)-1; i++) {
	// 	var index = donn[i].split('<>');


	// 	var color = ' ';

	// 	if(index[13] == 1) //Vehicule actif
	// 	{
	// 		color = '#3bb2d0';
	// 	}
	// 	else              //Vehicule inactif
	// 	{
	// 		color = '#FF0000';
	// 	}


	// 	const marker2 = new mapboxgl.Marker({ color:color, rotation: 45 })
  //       .setLngLat([index[2],index[1]])
  //       .addTo(map);
	// }


	//Create a default Marker and add it to the map.


    // const marker1 = new mapboxgl.Marker()
    //     .setLngLat([29.383188,-3.384438])
        //.addTo(map);

    // Create a default Marker, colored black, rotated 45 degrees.
    // const marker2 = new mapboxgl.Marker({ color: 'black', rotation: 45 })
    //     .setLngLat([29.377566,-3.369615])
    //     .addTo(map);


// 	const geojson = {
// 		type: 'FeatureCollection',
// 		features: [


// 		{
// 			type: 'Feature',
// 			geometry: {
// 				type: 'Point',
// 				coordinates: [-3.3861416,29.3619433]
// 			},
// 			properties: {
// 				title: 'Mapbox',
// 				description: 'Washington, D.C.'
// 			}
// 		},
// 		{
// 			type: 'Feature',
// 			geometry: {
// 				type: 'Point',
// 				coordinates: [29.377566,-3.369615]
// 			},
// 			properties: {
// 				title: 'Mapbox',
// 				description: 'San Francisco, California'
// 			}
// 		}


// 		]
// 	};



// 				// add markers to map
// 	for (const feature of geojson.features) {
  // // create a HTML element for each feature
// 		const el = document.createElement('div');
// 		el.className = 'marker';

  // // make a marker for each feature and add to the map
// 		new mapboxgl.Marker(el).setLngLat(feature.geometry.coordinates).addTo(map);
// 	}


</script>

		</html>