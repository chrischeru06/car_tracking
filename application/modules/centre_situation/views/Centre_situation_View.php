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
			color: white;
			background-color: cadetblue;
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
					<div class="card dash_card" style="border-radius:20px;" onclick="GetProprietaire($('#PROPRIETAIRE_ID').val());" title="Cliquer ici pour visualiser la liste">

						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="col-lg-3">
									<i class="fa fa-user text-primary" style="font-size: 50px;"></i>
								</div>

								<div class="col-lg-2">
									<strong class="card-title" id="nbr_proprietaire" >145</strong>
								</div>
								<div class="col-lg-7">
									<small class="text-muted small pt-2 ps-1 dash_text">Propriétaires&nbsp;&nbsp;<i  title="Voir la liste" ></i></small>
								</div>

							</div>

							
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

						<small class="text-muted small pt-2 ps-1">Nombre véhicules&nbsp;&nbsp;<i class="fa fa-eye dash_eye" title="Voir la liste" onclick="GetVehicule($('#VEHICULE_ID').val());"></i></small>
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
							<small class="text-muted small pt-2 ps-1">Nombre chauffeurs &nbsp;&nbsp;<i href="<?= base_url()?>chauffeur/Chauffeur " class="fa fa-eye dash_eye" title="Voir la liste"></i></small>
							<?php
						}
						else
						{
							?>
							<small class="text-muted small pt-2 ps-1">Nombre chauffeurs &nbsp;&nbsp;<i href="<?= base_url()?>proprietaire/Proprietaire_chauffeur" class="fa fa-eye dash_eye" title="Voir la liste"></i></small>
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
								<strong class="card-title vehiculeActif" id="vehiculeActif">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules actifs</small>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<i class="fa fa-car text-danger"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title vehiculeInactif" id="vehiculeActif">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicule inactifs</small>
					</div>

				</div>
			</div>


			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<!-- <h6 class="text-center">Véhicules en mouvement</h6> -->

								<!-- <img  class="img-fluid"  width="20px" src="<?=base_url('/upload/accident2.png')?>"> -->
								<i class="fa fa-car text-warning"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeAvecAccident">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules avec accident</small>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="card" style="border-radius:20px;">

					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
								<!-- <h6 class="text-center">Véhicules en mouvement</h6> -->

								<i class="fa fa-car text-success"></i>
							</div>
							<div class="ps-3">
								<strong class="card-title" id="vehiculeSansAccident">145</strong>
							</div>

						</div>
						<small class="text-muted small pt-2 ps-1">Véhicules sans accident</small>
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

									<div class="col-md-12">
										<div id="mapview">
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

          	function getmap(id=1){

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
          				id:id,
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
          	const timer = setInterval(() => {
          		getmap(2);
          	}, 10000);
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


          </html>