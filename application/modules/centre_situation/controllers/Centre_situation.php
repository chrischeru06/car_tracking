<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 21/02/2024
	Desc      : gestion du centre de situation
*/
	class Centre_situation extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->out_application();
			$this->load->helper('email');
		}

		function out_application()
		{
			if(empty($this->session->userdata('USER_ID')))
			{
				redirect(base_url('Login/logout'));

			}
		}
		

		//la fonction index visualise la liste des vehicules
		function index()
		{
			$VEHICULE_TRACK = $this->uri->segment(4);

			$COORD_TRACK = "";

			if(!empty($VEHICULE_TRACK))
			{
				$COORD_TRACK = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude FROM tracking_data JOIN vehicule ON vehicule.CODE = tracking_data.device_uid WHERE vehicule.VEHICULE_ID = '.$VEHICULE_TRACK.' ORDER BY id DESC LIMIT 1');
			}

			$critere = ' ';

			$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');

			$filtre_pro = $this->Model->getOne('proprietaire',array('PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID));

			$USER_ID = $this->session->userdata('USER_ID');


			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critere.= ' AND users.USER_ID = '.$USER_ID;
			}
			else
			{
				if(!empty($PROPRIETAIRE_ID))
				{
					$critere.= ' AND proprietaire.PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID;
				}
			}

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

            // Selection des proprietaires
			$proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID',' 1 '.$critere.'','proprio_desc ASC');

			$proprio=str_replace('\"', '"', $proprio);
			$proprio=str_replace('\n', '', $proprio);
			$proprio=str_replace('\"', '', $proprio);

			$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);

           // Selection des vehicules

			$vehicule = $this->getBindParms('vehicule.VEHICULE_ID,PLAQUE','vehicule JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID',' 1 '.$critere.'','PLAQUE ASC');

			$vehicule=str_replace('\"', '"', $vehicule);
			$vehicule=str_replace('\n', '', $vehicule);
			$vehicule=str_replace('\"', '', $vehicule);

			$vehicule = $this->ModelPs->getRequete($psgetrequete, $vehicule);

			$data['proprio'] = $proprio;
			$data['vehicule'] = $vehicule;
			$data['VEHICULE_TRACK'] = $VEHICULE_TRACK;
			$data['COORD_TRACK'] = $COORD_TRACK;

			$this->load->view('Centre_situation_View',$data);
		}

		//Fonction pour la selection des provinces
		function get_vehicule($PROPRIETAIRE_ID)
		{
			$html="<option value=''> Sélectionner </option>";
			$vehicules=$this->Model->getRequete("SELECT VEHICULE_ID,PLAQUE FROM vehicule WHERE PROPRIETAIRE_ID =".$PROPRIETAIRE_ID." ORDER BY PLAQUE ASC");

			foreach ($vehicules as $value)
			{
				$html.="<option value='".$value['VEHICULE_ID']."'>".$value['PLAQUE']."</option>";
			}
			echo json_encode($html);
		}

		//Fonction pour le chargement de la carte
		function getmap() {

			$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');
			$id = $this->input->post('id');

			$VEHICULE_TRACK = $this->input->post('VEHICULE_TRACK');
			$COORD_TRACK = $this->input->post('COORD_TRACK');

			$coordinates = '-3.3944616,29.3726466';
			$zoom = 12;

			$critere_proprietaire = '';
			$critere_vehicule = '';
			$critere_user = '';

			$critere_vehicule_track = '';

			$USER_ID = $this->session->userdata('USER_ID');

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critere_user.= ' AND users.USER_ID = '.$USER_ID;
			}

			if($PROPRIETAIRE_ID > 0){
				$critere_proprietaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID;
				$zoom = 18; 
			}

			if($VEHICULE_ID > 0){
				$critere_vehicule.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;
				$zoom = 18; 
			}


			if(!empty($VEHICULE_TRACK) && !empty($COORD_TRACK))
			{

				$critere_vehicule_track.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_TRACK;
				$coordinates = $COORD_TRACK;
				$zoom = 12; 
			}

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			// Recherche des propriétaires

			$proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID',' 1 '.$critere_proprietaire.''.$critere_user.'','proprio_desc ASC');

			$proprio=str_replace('\"', '"', $proprio);
			$proprio=str_replace('\n', '', $proprio);
			$proprio=str_replace('\"', '', $proprio);

			$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);

            // Recherche des chauffeurs

			$nbrChauffeur = 0;

			if($this->session->userdata('PROFIL_ID') != 1 || $PROPRIETAIRE_ID > 0)
			{
				$nbrChauffeur = $this->getBindParms('CHAUFFEUR_VEHICULE_ID','chauffeur_vehicule LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID LEFT JOIN vehicule ON vehicule.CODE = chauffeur_vehicule.CODE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID',' 1 '.$critere_proprietaire.''.$critere_user.'','CHAUFFEUR_VEHICULE_ID ASC');
			}
			else
			{
				$nbrChauffeur = $this->getBindParms('CHAUFFEUR_ID','chauffeur',' 1 ','CHAUFFEUR_ID ASC');
			}

			$nbrChauffeur = $this->ModelPs->getRequete($psgetrequete, $nbrChauffeur);

			if(!empty($nbrChauffeur)){
				$nbrChauffeur = count($nbrChauffeur);
			}
			else{$nbrChauffeur = 0;}

             // Recherche des tous les vehicules pour la carte

			$get_vihicule = $this->Model->getRequete('SELECT * FROM vehicule LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID WHERE 1 '.$critere_proprietaire.''.$critere_vehicule.''.$critere_user.''.$critere_vehicule_track.'');

			$donnees_vehicule = ' ';

			$nbrProprietaire = count($proprio);
			$nbrVehicule = 0;
			$nbrVehiculeMouvement = 0;
			$nbrVehiculeStationnement = 0;
			$nbrVehiculeAllume = 0;
			$nbrVehiculeEteint = 0;
			$nbrVehiculeActif = 0;
			$nbrVehiculeInactif = 0;
			$nbrVehiculeAvecAccident = 0;
			$nbrVehiculeSansAccident = 0;
			$nbrDemandeEntente = 0;
			$nbrDemandeApprouvee = 0;
			$nbrDemandeRefusee = 0;

			if(!empty($get_vihicule))
			{
				foreach ($get_vihicule as $key) {

					$track_data = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.IS_ACTIVE,proprietaire.PROPRIETAIRE_ID,STATUT_VEH_AJOUT,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,proprietaire.PROPRIETAIRE_ID,proprietaire.PHOTO_PASSPORT AS photo_pro,proprietaire.LOGO,COULEUR,KILOMETRAGE,PHOTO,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,chauffeur.CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT AS photo_chauf,tracking_data.accident FROM tracking_data RIGHT JOIN vehicule ON vehicule.CODE = tracking_data.device_uid JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE  JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 AND chauffeur_vehicule.STATUT_AFFECT=1 '.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.' AND device_uid = "'.$key['CODE'].'" '.$critere_vehicule_track.' ORDER BY id DESC LIMIT 1');

					// Nbr véhicules enregistrés
					$nbrVehicule += 1;

						if($key['STATUT_VEH_AJOUT'] == 1)    // demande en attente
						{
							$nbrDemandeEntente += 1;
						}
						else if($key['STATUT_VEH_AJOUT'] == 2)  // demande approuvee/vehicule activé
						{
							$nbrDemandeApprouvee += 1;
							$nbrVehiculeActif += 1;
						}
						else if($key['STATUT_VEH_AJOUT'] == 3)  //demande refusee
						{
							$nbrDemandeRefusee += 1;
						}
						else if($key['STATUT_VEH_AJOUT'] == 4) //vehicule desactive
						{
							$nbrVehiculeInactif += 1;
						}

						if(!empty($track_data))
						{
							$VEHICULE_ID = $track_data['VEHICULE_ID'];

						// Nbr véhicules en mouvements et en stationnements

							if($track_data['mouvement'] == 1)
							{
								$nbrVehiculeMouvement += 1;
							}
							else
							{
								$nbrVehiculeStationnement += 1;
							}

						// Nbr véhicules allumés et éteints

							if($track_data['ignition'] == 1)
							{
								$nbrVehiculeAllume += 1;
							}
							else
							{
								$nbrVehiculeEteint += 1;
							}

						// Nbr véhicules avec accident et sans accident

							if($track_data['accident'] == 1)
							{
								$nbrVehiculeAvecAccident += 1;
							}
							else if($track_data['accident'] == 0)
							{
								$nbrVehiculeSansAccident += 1;
							}



							if (empty($track_data['latitude'])) {
								$latitude ='-1';
							}else
							{
								$latitude = $track_data['latitude'];  
							}

							if (empty($track_data['longitude'])) {
								$longitude = '-1';
							}else
							{
								$longitude = $track_data['longitude'];  
							}

							$CODE=trim($track_data['CODE']);
							$CODE = str_replace("\n","",$CODE);
							$CODE = str_replace("\r","",$CODE);
							$CODE = str_replace("\t","",$CODE);
							$CODE = str_replace('"','',$CODE);
							$CODE = str_replace("'",'',$CODE);

							$DESC_MARQUE=trim($track_data['DESC_MARQUE']);
							$DESC_MARQUE = str_replace("\n","",$DESC_MARQUE);
							$DESC_MARQUE = str_replace("\r","",$DESC_MARQUE);
							$DESC_MARQUE = str_replace("\t","",$DESC_MARQUE);
							$DESC_MARQUE = str_replace('"','',$DESC_MARQUE);
							$DESC_MARQUE = str_replace("'",'',$DESC_MARQUE);

							$DESC_MODELE=trim($track_data['DESC_MODELE']);
							$DESC_MODELE = str_replace("\n","",$DESC_MODELE);
							$DESC_MODELE = str_replace("\r","",$DESC_MODELE);
							$DESC_MODELE = str_replace("\t","",$DESC_MODELE);
							$DESC_MODELE = str_replace('"','',$DESC_MODELE);
							$DESC_MODELE = str_replace("'",'',$DESC_MODELE);

							$PLAQUE=trim($track_data['PLAQUE']);
							$PLAQUE = str_replace("\n","",$PLAQUE);
							$PLAQUE = str_replace("\r","",$PLAQUE);
							$PLAQUE = str_replace("\t","",$PLAQUE);
							$PLAQUE = str_replace('"','',$PLAQUE);
							$PLAQUE = str_replace("'",'',$PLAQUE);

							$proprio_desc=trim($track_data['proprio_desc']);
							$proprio_desc = str_replace("\n","",$proprio_desc);
							$proprio_desc = str_replace("\r","",$proprio_desc);
							$proprio_desc = str_replace("\t","",$proprio_desc);
							$proprio_desc = str_replace('"','',$proprio_desc);
							$proprio_desc = str_replace("'",'',$proprio_desc);

							$chauffeur_desc=trim($track_data['chauffeur_desc']);
							$chauffeur_desc = str_replace("\n","",$chauffeur_desc);
							$chauffeur_desc = str_replace("\r","",$chauffeur_desc);
							$chauffeur_desc = str_replace("\t","",$chauffeur_desc);
							$chauffeur_desc = str_replace('"','',$chauffeur_desc);
							$chauffeur_desc = str_replace("'",'',$chauffeur_desc);

							$COULEUR=trim($track_data['COULEUR']);
							$COULEUR = str_replace("\n","",$COULEUR);
							$COULEUR = str_replace("\r","",$COULEUR);
							$COULEUR = str_replace("\t","",$COULEUR);
							$COULEUR = str_replace('"','',$COULEUR);
							$COULEUR = str_replace("'",'',$COULEUR);

							$KILOMETRAGE=trim($track_data['KILOMETRAGE']);
							$KILOMETRAGE = str_replace("\n","",$KILOMETRAGE);
							$KILOMETRAGE = str_replace("\r","",$KILOMETRAGE);
							$KILOMETRAGE = str_replace("\t","",$KILOMETRAGE);
							$KILOMETRAGE = str_replace('"','',$KILOMETRAGE);
							$KILOMETRAGE = str_replace("'",'',$KILOMETRAGE);

					// $PHOTO = "";
					// if(empty($key['PHOTO'])){
					// 	$PHOTO = 'vehicule_icon.png';
					// }

							$PHOTO=trim($track_data['PHOTO']);
							$PHOTO = str_replace("\n","",$PHOTO);
							$PHOTO = str_replace("\r","",$PHOTO);
							$PHOTO = str_replace("\t","",$PHOTO);
							$PHOTO = str_replace('"','',$PHOTO);
							$PHOTO = str_replace("'",'',$PHOTO);

							$photo_pro=trim($track_data['photo_pro']);
							$photo_pro = str_replace("\n","",$photo_pro);
							$photo_pro = str_replace("\r","",$photo_pro);
							$photo_pro = str_replace("\t","",$photo_pro);
							$photo_pro = str_replace('"','',$photo_pro);
							$photo_pro = str_replace("'",'',$photo_pro);

							$LOGO = trim($track_data['LOGO']);
							$LOGO = str_replace("\n","",$LOGO);
							$LOGO = str_replace("\r","",$LOGO);
							$LOGO = str_replace("\t","",$LOGO);
							$LOGO = str_replace('"','',$LOGO);
							$LOGO = str_replace("'",'',$LOGO);

							$photo_chauf=trim($track_data['photo_chauf']);
							$photo_chauf = str_replace("\n","",$photo_chauf);
							$photo_chauf = str_replace("\r","",$photo_chauf);
							$photo_chauf = str_replace("\t","",$photo_chauf);
							$photo_chauf = str_replace('"','',$photo_chauf);
							$photo_chauf = str_replace("'",'',$photo_chauf);

							$IS_ACTIVE = $track_data['IS_ACTIVE']; 
							$accident = $track_data['accident'];

							$PROPRIETAIRE_ID = md5($track_data['PROPRIETAIRE_ID']);
							$CHAUFFEUR_ID = md5($track_data['CHAUFFEUR_ID']);
							$mouvement = $track_data['mouvement'];
							$ignition = $track_data['ignition'];

							$donnees_vehicule = $donnees_vehicule.$VEHICULE_ID.'<>'.$latitude.'<>'.$longitude.'<>'.$CODE.'<>'.$DESC_MARQUE.'<>'.$DESC_MODELE.'<>'.$PLAQUE.'<>'.$COULEUR.'<>'.$KILOMETRAGE.'<>'.$proprio_desc.'<>'.$PHOTO.'<>'.md5($CODE).'<>'.$chauffeur_desc.'<>'.$IS_ACTIVE.'<>'.$id.'<>'.$accident.'<>'.$VEHICULE_TRACK.'<>'.$PROPRIETAIRE_ID.'<>'.$CHAUFFEUR_ID.'<>'.$photo_pro.'<>'.$photo_chauf.'<>'.$mouvement.'<>'.$LOGO.'<>'.$ignition.'<>@';
						}
					}
				}

				$data['proprio'] = $proprio;
				$data['donnees_vehicule'] = $donnees_vehicule;
				$data['nbrVehicule'] = $nbrVehicule; 
				$data['nbrProprietaire'] = $nbrProprietaire;
				$data['nbrChauffeur'] = $nbrChauffeur;
				$data['vehiculeActif'] = $nbrVehiculeActif;
				$data['vehiculeInactif'] = $nbrVehiculeInactif;
				$data['vehiculeMouvement'] = $nbrVehiculeMouvement;
				$data['vehiculeStationnement'] = $nbrVehiculeStationnement;
				$data['vehiculeAllume'] = $nbrVehiculeAllume;
				$data['vehiculeEteint'] = $nbrVehiculeEteint;
				$data['vehiculeAvecAccident'] = $nbrVehiculeAvecAccident;
				$data['vehiculeSansAccident'] = $nbrVehiculeSansAccident;
				$data['coordinates'] = $coordinates;
				$data['zoom'] = $zoom;
				$data['id'] = $id;

				$data['nbrDemandeEntente'] = $nbrDemandeEntente;
				$data['nbrDemandeApprouvee'] = $nbrDemandeApprouvee;
				$data['nbrDemandeRefusee'] = $nbrDemandeRefusee;



				$map = $this->load->view('Getcarte_Tracking_View',$data,TRUE);

				$output = array('carte_view'=>$map,'proprio'=>$proprio,'donnees_vehicule'=>$donnees_vehicule,'nbrVehicule'=>$nbrVehicule,'nbrProprietaire'=>$nbrProprietaire,'nbrChauffeur'=>$nbrChauffeur,'vehiculeActif'=>$nbrVehiculeActif,'vehiculeInactif'=>$nbrVehiculeInactif,'vehiculeAllume'=>$nbrVehiculeAllume,'vehiculeEteint'=>$nbrVehiculeEteint,'vehiculeStationnement'=>$nbrVehiculeStationnement,'vehiculeMouvement'=>$nbrVehiculeMouvement,'vehiculeAvecAccident'=>$nbrVehiculeAvecAccident,'vehiculeSansAccident'=>$nbrVehiculeSansAccident,'coordinates'=>$coordinates,'zoom'=>$zoom,'id'=>$id,'nbrDemandeEntente'=>$nbrDemandeEntente,'nbrDemandeApprouvee'=>$nbrDemandeApprouvee,'nbrDemandeRefusee'=>$nbrDemandeRefusee);
				echo json_encode($output);
			}

// Fonction pour la liste des proprietaire
			function GetProprietaire($PROPRIETAIRE_ID = '')
			{
				$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
				$var_search = str_replace("'", "\'", $var_search);
				$group = "";

				$limit = 'LIMIT 0,1000';
				if ($_POST['length'] != -1) {
					$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
				}
				$order_by='';
				$order_column=array('','NOM_PROPRIETAIRE','PERSONNE_REFERENCE','EMAIL','TELEPHONE','','');
				if ($_POST['order']['0']['column'] != 0) {
					$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' PROPRIETAIRE_ID DESC';
				}

				$search = !empty($_POST['search']['value']) ? (' AND (`NOM_PROPRIETAIRE` LIKE "%' . $var_search . '%" 
					OR PRENOM_PROPRIETAIRE LIKE "%' . $var_search . '%"
					OR CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE) LIKE "%' . $var_search . '%" 
					OR CNI_OU_NIF LIKE "%' . $var_search . '%" 
					OR PERSONNE_REFERENCE LIKE "%' . $var_search . '%" OR EMAIL LIKE "%' . $var_search . '%" OR TELEPHONE LIKE "%' . $var_search . '%"
					OR DATE_FORMAT(`DATE_INSERTION`,"%d-%m-%Y") LIKE "%' . $var_search . '%")') : '';

				$query_principal='SELECT PROPRIETAIRE_ID,TYPE_PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS info_personne,CNI_OU_NIF,PERSONNE_REFERENCE,EMAIL,TELEPHONE,DATE_INSERTION,IS_ACTIVE,PHOTO_PASSPORT,COUNTRY_ID,LOGO FROM proprietaire WHERE 1';

				$critaire = ' ';

				if(!empty($PROPRIETAIRE_ID))
				{
					$critaire = ' AND PROPRIETAIRE_ID ='.$PROPRIETAIRE_ID;
				}

        //condition pour le query principale
				$conditions = $critaire . ' ' . $search . ' ' . $group . ' ' . $order_by . '   ' . $limit;

        // condition pour le query filter
				$conditionsfilter = $critaire . ' ' . $group;
				$requetedebase=$query_principal.$conditions;
				$requetedebasefilter=$query_principal.$conditionsfilter;
				$query_secondaire = "CALL `getTable`('".$requetedebase."');";
        // echo $query_secondaire;
				$fetch_data = $this->ModelPs->datatable($query_secondaire);
				$data = array();
				$i=0;

				foreach ($fetch_data as $row) {
					$i=$i+1;
					$sub_array=array();
					$sub_array[]=$i;

					if($row->TYPE_PROPRIETAIRE_ID == 1 && empty($row->LOGO)){
						$sub_array[] ='<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><i class="bi bi-info-square h5" ></i>
						style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->info_personne .'</td></tr></tbody></a>
						';

					}elseif(!empty($row->LOGO)){

						$sub_array[] = ' <tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></td><td> '.'      '.' ' . $row->info_personne . '</td></tr></tbody></a>

						<div class="modal fade" id="mypicture' .$row->PROPRIETAIRE_ID.'">
						<div class="modal-dialog">
						<div class="modal-content">
						<div class="modal-body">
						<img src = "'.base_url('upload/proprietaire/photopassport/'.$row->LOGO).'" height="100%"  width="100%" >
						</div>
						<div class="modal-footer">
						<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">Fermer</button>
						</div>
						</div>
						</div>
						</div>';

					}else{

						$sub_array[] = ' <tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></td><td> '.'     '.' ' . $row->info_personne . ' </td></tr></tbody></a>

						<div class="modal fade" id="mypicture' .$row->PROPRIETAIRE_ID.'">
						<div class="modal-dialog">
						<div class="modal-content">
						<div class="modal-body">
						<img src = "'.base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT).'" height="100%"  width="100%" >
						</div>
						<div class="modal-footer">
						<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">Fermer</button>
						</div>
						</div>
						</div>
						</div>';
					}



					if (!empty($row->EMAIL)) 
					{
						$sub_array[]=$row->EMAIL;	
					}else{
						$sub_array[]='N/A';	
					}						
					$sub_array[]=$row->TELEPHONE;


					if($row->IS_ACTIVE==1){
						$sub_array[]='
						<label class="text-primary">Activé</label>

						';
					}else{
						$sub_array[]='
						<label class="text-danger">Désactivé</label>


						';
					}

					$proce_requete = "CALL `getRequete`(?,?,?,?);";
					$my_select = $this->getBindParms('COUNT(VEHICULE_ID) as nombre', 'proprietaire LEFT JOIN vehicule ON vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID', '1 AND proprietaire.PROPRIETAIRE_ID ='.$row->PROPRIETAIRE_ID.'', 'proprietaire.PROPRIETAIRE_ID ASC');
					$NOMBRE = $this->ModelPs->getRequeteOne($proce_requete, $my_select);

					if(!empty($NOMBRE))
					{
						$sub_array[]="<center><a class='btn btn-outline-primary rounded-pill' href='" . base_url('proprietaire/Proprietaire/Detail_vehicule/'.md5($row->PROPRIETAIRE_ID)). "' style='font-size:10px;'><label>".$NOMBRE['nombre']."</label></a></center>";
					}

					$data[]=$sub_array;
				}
				$recordsTotal = $this->ModelPs->datatable("CALL `getTable`('" . $query_principal . "')");
				$recordsFiltered = $this->ModelPs->datatable(" CALL `getTable`('" . $requetedebasefilter . "')");
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => count($recordsTotal),
					"recordsFiltered" => count($recordsFiltered),
					"data" => $data,
				);
				echo json_encode($output);


			}

      // Fonction pour la liste des vehicules

			function GetVehicule($id = '')
			{
				$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');
				$VEHICULE_ID = $this->input->post('VEHICULE_ID');
				$V_ENREGITRE = $this->input->post('V_ENREGITRE');
				$CHECK_VALIDE = $this->input->post('CHECK_VALIDE');

				$critere_proprietaire = '';
				$critere_vehicule = '';
				$critere_user = '';

				$critaireVehicule2= '';

				$USER_ID = $this->session->userdata('USER_ID');

			if($this->session->userdata('PROFIL_ID') != 1) // Si c'est pas l'admin : condition 
			{
				$critere_user.= ' AND users.USER_ID = '.$USER_ID;
			}

			if($PROPRIETAIRE_ID > 0){ // Condition selon le proprieataire selectionné
				$critere_proprietaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID;
				$zoom = 10; 
			}

			if($VEHICULE_ID > 0){ // Condition selon le vehicule selectionné
				$critere_vehicule.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;
			}

			$critaire_select = ' ';

			if(!empty($VEHICULE_ID) && $id >= 0) // Condition selon le vehicule selectionné
			{
				$critaire_select = ' AND vehicule.VEHICULE_ID ='.$VEHICULE_ID;
			}
			if(empty($VEHICULE_ID) && $id == 'V_ACTIF') // vehicule actif
			{
				$critaire_select = ' AND vehicule.STATUT_VEH_AJOUT = 2';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_INACTIF') // vehicule inactif
			{
				$critaire_select = ' AND vehicule.STATUT_VEH_AJOUT = 4';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_CREVAISON') // vehicule en accident
			{
				$critaire_select = ' AND accident = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_MOUVEMENT') // vehicule en mouvent
			{
				$critaire_select = ' AND mouv = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ETEINT') // vehicule eteint
			{
				$critaire_select = ' AND ignition = 0';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ALLUME') // vehicule allumé
			{
				$critaire_select = ' AND ignition = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_STATIONNE') // vehicule stationné
			{
				$critaire_select = ' AND mouv = 0 ';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ATTENTE') // Demande en attente
			{
				$critaire_select = ' AND STATUT_VEH_AJOUT = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_APROUVE') // Demande approuvee
			{
				$critaire_select = ' AND STATUT_VEH_AJOUT = 2';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_REFUSE') // Demande refusee
			{
				$critaire_select = ' AND STATUT_VEH_AJOUT = 3';
			}

			$critaire_doc_valide = '' ;
			$date_now = date('Y-m-d');

			if($CHECK_VALIDE == 1) // Assurance valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 2) // Assurance invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE < "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 3) // Controle technique valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 4) // Controle technique invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK < "'.$date_now.'"';
			}

			$query_principal = '';
			$order_by = ' ';

			if($id == 'V_ACTIF' || $id == 'V_INACTIF' || $id == 'V_ATTENTE' || $id == 'V_APROUVE' || $id == 'V_REFUSE' || $V_ENREGITRE == 'V_ENREGITRE')
			{
				$query_principal = 'SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,TYPE_PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,proprietaire.PHOTO_PASSPORT AS photo_pro,proprietaire.EMAIL AS mail_pro,proprietaire.ADRESSE AS adress_pro,proprietaire.TELEPHONE AS telephone_pro,DATE_SAVE,vehicule.STATUT_VEH_AJOUT AS vehicule_is_active,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,STATUT_VEH_AJOUT,`LOGO`,vehicule.FILE_ASSURANCE,vehicule.FILE_CONTRO_TECHNIQUE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire_select.''.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.''.$critaire_doc_valide.'';

				$order_by = ' ORDER BY VEHICULE_ID DESC';
			}
			else
			{
				$query_principal = 'SELECT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,proprietaire.PHOTO_PASSPORT AS photo_pro,proprietaire.EMAIL AS mail_pro,proprietaire.ADRESSE AS adress_pro,proprietaire.TELEPHONE AS telephone_pro,DATE_SAVE,vehicule.STATUT_VEH_AJOUT AS vehicule_is_active,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,STATUT_VEH_AJOUT FROM vehicule JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.mouvement as mouv,tracking_data.accident as accident,tracking_data.ignition as ignition FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire_select.''.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.''.$critaire_doc_valide.'';

				$order_by = ' ORDER BY id DESC';
			}


			$critaire = ' ';

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$limit = ' LIMIT 0,10';
			if($_POST['length'] != -1)
			{
				$limit = ' LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}

			$order_by=' ';
			$order_column=array('CODE','DESC_MARQUE','DESC_MODELE','PLAQUE','COULEUR','PHOTO','DATE_SAVE');

			// $order_by=isset($_POST['order']) ? ' ORDER BY '.$order_column[$_POST['order']['0']['column']].' ' .$_POST['order']['0']['dir'] : ' ORDER BY id ASC';


			$search=!empty($_POST['search']['value']) ? (" AND (vehicule.CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

			$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. '';

			$query_filter=$query_principal.''.$critaire.''.$search;

			//print_r($query_filter);die();

			$fetch_data=$this->Model->datatable($query_secondaire);

			$data=array();
			$u=1;
			
			foreach ($fetch_data as $row)
			{
				$sub_array=array();
				$sub_array[]=$u++;
				if($this->session->userdata('PROFIL_ID') == 1)
                {
                	$sub_array[]=$row->CODE;
                }
				$sub_array[]=$row->DESC_MARQUE;
				$sub_array[]=$row->DESC_MODELE;
				$sub_array[]=$row->PLAQUE;
				$sub_array[]=$row->COULEUR;
				$sub_array[]=(isset($row->KILOMETRAGE)?$row->KILOMETRAGE.' litres / KM' : 'N/A');

				// $sub_array[]= "<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='120px' width='120px' ></a>";

				// $sub_array[]=' <table><tr><td style = "width:5000px;"><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->VEHICULE_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->photo_pro.'"></a></td><td> '.'     '.' ' . $row->desc_proprio . '</td></tr></table></a>';

				// $sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'>&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a href='".base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID."'>&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				if($row->vehicule_is_active == 1){
					$sub_array[]='<center><label class="text-warning"><i class="text-warning small pt-2 ps-1 dash_v fa fa-spinner fa-spin" title="demande en attente"></i></label></center>';
				}else if($row->vehicule_is_active == 2){
					$sub_array[]='<center><label class="text-success"><i class="text-success small pt-2 ps-1 dash_v fa fa-check" title="Vécule activé"></i></label></center>';
				}
				else if($row->vehicule_is_active == 3){
					$sub_array[]='<center><label class="text-danger"><i class="text-danger small pt-2 ps-1 dash_v fa fa-ban" title="demande refusé"></i></label></center>';
				}
				else if($row->vehicule_is_active == 4){
					$sub_array[]='<center><label class="text-danger"><i class="text-danger small pt-2 ps-1 dash_v fa fa-close" title="Vécule désactivé"></i></label></center>';
				}

				$option = '<div class="dropdown text-center">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>	
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if($this->session->userdata('PROFIL_ID') == 1)
				{
					if($row->STATUT_VEH_AJOUT == 1 || $row->STATUT_VEH_AJOUT == 3)
					{
						$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->VEHICULE_ID . ",".$row->STATUT_VEH_AJOUT.")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;Traiter</li></a>";
					}
				}


				if(!empty($row->DATE_FIN_ASSURANCE))
				{
					if($row->DATE_FIN_ASSURANCE >= date('Y-m-d'))
					{
						$sub_array[] = '<center><i class="fa fa-check text-success small" title="Valide"></i><font class="text-success small" title="Valide"> </font></center>';
					}
					else
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger small" title="Expirée"></i><font class="text-danger small" title="Expirée"> </font></center>';
					}
				}
				else
				{
					$sub_array[] = '<center><font class="small" title="">N/A</font></center>';
				}
				
				if(!empty($row->DATE_FIN_CONTROTECHNIK))
				{
					if($row->DATE_FIN_CONTROTECHNIK >= date('Y-m-d'))
					{
						$sub_array[] = '<center><i class="fa fa-check text-success small" title="Valide"></i><font class="text-success small" title="Valide"> </font></center>';
					}
					else
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger small" title="Expirée"></i><font class="text-danger small" title="Expirée"> </font></center>';
					}
				}
				else
				{
					$sub_array[] = '<center><font class="small" title="">N/A</font></center>';
				}

				$option .="
				</div>
				<div class='modal fade' id='mypicture" .$row->VEHICULE_ID."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Détail du véhicule</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<h4 class=''></h4>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<table class='table table-borderless'>

				<tr>
				<td class='btn-sm'>Code</td>
				<th class='btn-sm'>".$row->CODE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Marque</td>
				<th class='btn-sm'>".$row->DESC_MARQUE."</th>
				</tr>

				<tr class='btn-sm'>
				<td>Modèle</td>
				<th class='btn-sm'>".$row->DESC_MODELE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Plaque</td>
				<th class='btn-sm'>".$row->PLAQUE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Couleur</td>
				<th class='btn-sm'>".$row->COULEUR."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Consommation / km</td>
				<th class='btn-sm'>".$row->KILOMETRAGE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Propriétaire</td>
				<td class='btn-sm' style='display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				overflow: hidden;'><strong>".$row->desc_proprio." </strong></td>
				</tr>



				</table>

				</div>
				</div>

				</div>
				</div>
				</div>";


				$option .="
				</div>
				<div class='modal fade' id='proprio" .$row->VEHICULE_ID."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Information du propriétaire</h6>
				<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<h4 class=''></h4>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/proprietaire/photopassport/'.$row->photo_pro)."' height='100%'  width='100%'  style= 'border-radius:50%;'>
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<table class='table table-borderless'>

				<tr>
				<td class='btn-sm'>Nom</td>
				</tr>

				<tr>
				<th class='btn-sm'>".$row->desc_proprio."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Adresse</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->adress_pro."</th>
				</tr>

				<tr class='btn-sm'>
				<td>Email</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->mail_pro."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Téléphone</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->telephone_pro."</th>
				</tr>

				</table>

				</div>
				</div>

				</div>
				</div>
				</div>";

				$sub_array[]=$option;
				$data[]=$sub_array;

			}
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => $this->Model->all_data($query_principal),
				"recordsFiltered" => $this->Model->filtrer($query_filter),
				"data" => $data
			);

			echo json_encode($output);



		}


		//fonction pour recuperer le nombre des vehicules selon la validation des documments

		function get_nbr_vehicule($id = '')
		{
			$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');
			$V_ENREGITRE = $this->input->post('V_ENREGITRE');
			$CHECK_VALIDE = $this->input->post('CHECK_VALIDE');

			$critere_proprietaire = '';
			$critere_vehicule = '';
			$critere_user = '';

			$critaireVehicule2= '';

			$USER_ID = $this->session->userdata('USER_ID');

			if($this->session->userdata('PROFIL_ID') != 1) // Si c'est pas l'admin : condition 
			{
				$critere_user.= ' AND users.USER_ID = '.$USER_ID;
			}

			if($PROPRIETAIRE_ID > 0){ // Condition selon le proprieataire selectionné
				$critere_proprietaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID;
				$zoom = 10; 
			}

			if($VEHICULE_ID > 0){ // Condition selon le vehicule selectionné
				$critere_vehicule.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;
			}

			$critaire_select = ' ';

			if(!empty($VEHICULE_ID) && $id >= 0) // Condition selon le vehicule selectionné
			{
				$critaire_select = ' AND vehicule.VEHICULE_ID ='.$VEHICULE_ID;
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ACTIF') // vehicule actif
			{
				$critaire_select = ' AND vehicule.STATUT_VEH_AJOUT = 2';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_INACTIF') // vehicule inactif
			{
				$critaire_select = ' AND vehicule.STATUT_VEH_AJOUT = 4';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_CREVAISON') // vehicule en accident
			{
				$critaire_select = ' AND accident = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_MOUVEMENT') // vehicule en mouvent
			{
				$critaire_select = ' AND mouv = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ETEINT') // vehicule eteint
			{
				$critaire_select = ' AND ignition = 0';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ALLUME') // vehicule allumé
			{
				$critaire_select = ' AND ignition = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_STATIONNE') // vehicule stationné
			{
				$critaire_select = ' AND mouv = 0 ';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ATTENTE') // Demande en attente
			{
				$critaire_select = ' AND STATUT_VEH_AJOUT = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_APROUVE') // Demande approuvee
			{
				$critaire_select = ' AND STATUT_VEH_AJOUT = 2';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_REFUSE') // Demande refusee
			{
				$critaire_select = ' AND STATUT_VEH_AJOUT = 3';
			}
			
			$critaire_doc_valide = '' ;
			$date_now = date('Y-m-d');

			if($CHECK_VALIDE == 1) // Assurance valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 2) // Assurance invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE < "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 3) // Controle technique valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 4) // Controle technique invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK < "'.$date_now.'"';
			}



			if($id == 'V_ACTIF' || $id == 'V_INACTIF' || $id == 'V_ATTENTE' || $id == 'V_APROUVE' || $id == 'V_REFUSE' || $V_ENREGITRE == 'V_ENREGITRE')
			{
				$vehicule = $this->Model->getRequeteOne('SELECT COUNT(VEHICULE_ID) AS nombre_v FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire_select.''.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.''.$critaire_doc_valide.'');

				echo $vehicule['nombre_v'];
			}
			else
			{
				$vehicule = $this->Model->getRequeteOne('SELECT COUNT(VEHICULE_ID) AS nombre_v FROM vehicule JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.mouvement as mouv,tracking_data.accident as accident,tracking_data.ignition as ignition FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire_select.''.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.''.$critaire_doc_valide.'');

				echo $vehicule['nombre_v'];
			}

		}


		// Fonction pour requiperer la photo du vehicule
		function get_image_v($VEHICULE_ID)
		{
			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$my_query = $this->getBindParms('PHOTO', 'vehicule', '1 AND VEHICULE_ID = '.$VEHICULE_ID.'', '`VEHICULE_ID` ASC');
			$vehicule = $this->ModelPs->getRequeteOne($proce_requete, $my_query);

			$html = " ";

			if(!empty($vehicule))
			{
				$PHOTO = $vehicule['PHOTO'];

				//$html.="<img id='phot_v_dash' src = '".base_url('upload/photo_vehicule/'.$PHOTO)."' height='100%'  width='100%'  style= 'border-radius:20px;'>";

				$html.= base_url('upload/photo_vehicule/'.$PHOTO);
			}

			$output = array('photo'=>$html);

			echo json_encode($output);
		}

		// Fonction pour la liste des chauffeurs

		function GetChauffeur($PROPRIETAIRE_ID = '')
		{
			$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critere_proprietaire = '';
			$critere_vehicule = '';
			$critere_user = '';

			$USER_ID = $this->session->userdata('USER_ID');

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critere_user.= ' AND users.USER_ID = '.$USER_ID;
			}

			if($PROPRIETAIRE_ID > 0){
				$critere_proprietaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID;
				$zoom = 10; 
			}

			if($VEHICULE_ID > 0){
				$critere_vehicule.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;
			}

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";
			$critaire = "";
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','chauffeur.NOM','chauffeur.PRENOM','chauffeur.ADRESSE_PHYSIQUE ','provinces.PROVINCE_NAME','communes.COMMUNE_NAME','zones.ZONE_NAME','collines.COLLINE_NAME','chauffeur.NUMERO_TELEPHONE','chauffeur.ADRESSE_MAIL','chauffeur.NUMERO_CARTE_IDENTITE','chauffeur.PERSONNE_CONTACT_TELEPHONE','chauffeur.DATE_INSERTION');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%"
				OR chauffeur.ADRESSE_PHYSIQUE LIKE "%' . $var_search . '%" 
				OR provinces.PROVINCE_NAME LIKE "%' . $var_search . '%" 
				OR communes.COMMUNE_NAME LIKE "%' . $var_search . '%"
				OR zones.ZONE_NAME  LIKE "%' . $var_search . '%"
				OR collines.COLLINE_NAME LIKE "%' . $var_search . '%"
				OR chauffeur.NUMERO_TELEPHONE LIKE "%' . $var_search . '%"
				OR chauffeur.ADRESSE_MAIL LIKE "%' . $var_search . '%"
				OR chauffeur.NUMERO_CARTE_IDENTITE LIKE "%' . $var_search . '%"
				OR chauffeur.DATE_INSERTION LIKE "%' . $var_search . '%")') : '';

			$query_principal='SELECT chauffeur.CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,chauffeur.FILE_PERMIS FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID LEFT JOIN chauffeur_vehicule ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID LEFT JOIN vehicule ON vehicule.CODE = chauffeur_vehicule.CODE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID WHERE 1 '.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.' GROUP BY chauffeur.CHAUFFEUR_ID ';

            //condition pour le query principale
			$conditions = $critaire . ' ' . $search . ' ' . $group . ' ' . $order_by . '   ' . $limit;

          // condition pour le query filter
			$conditionsfilter = $critaire . ' ' . $group;
			$requetedebase=$query_principal.$conditions;
			$requetedebasefilter=$query_principal.$conditionsfilter;

			$query_secondaire = "CALL `getTable`('".$requetedebase."');";
         // echo $query_secondaire;
			$fetch_data = $this->ModelPs->datatable($query_secondaire);
			$data = array();
			$u=1;
			foreach ($fetch_data as $row) 
			{
				$sub_array=array();
				$sub_array[]=$u++;
				
				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('chauffeur/Chauffeur_New/Detail/'.md5($row->CHAUFFEUR_ID)).'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->NOM . ' ' . $row->PRENOM . '</td></tr></tbody></a>

				</div>
				<div class="modal fade" id="mypicture' .$row->CHAUFFEUR_ID. '">
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
				</div>

				';

				//fin modal

				$sub_array[] = $row->NUMERO_TELEPHONE;
				$sub_array[] = $row->ADRESSE_MAIL;

				$option = '
				';

				if($row->IS_ACTIVE==1){
					$sub_array[]=' 
					<td><label class="text-primary">Activé</label></td>

					';
				}else{
					$sub_array[]='
					<td><label class="text-danger">Désactivé</label></td>
					';
				}


					//fin activer desactiver
					//DEBUT modal pour retirer la voiture
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_retirer" .$row->CHAUFFEUR_ID. "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous retirer la voiture à <b>" . $row->NOM .' '.$row->PRENOM. " ? </b></h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('chauffeur/Chauffeur/retirer_voit/'.$row->CHAUFFEUR_ID)."' >Retirer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";

					//fin modal retire voiture
					//debut Detail cahuffeur
				if ($row->STATUT_VEHICULE==2)
				{
					$option .="
					</div>
					<div class='modal fade' tabindex='-1' data-bs-backdrop='false' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog modal-dialog-centered modal-lg'>

					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Détails du chauffeur&nbsp;&nbsp;" .$row->NOM." "." ".$row->PRENOM."</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='row'>
					<div class='col-md-4'>
					<img src = '".base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT)."' height='80%'  width='80%'  style= 'border-radius:20px;'>
					</div>
					<div class='col-md-8'>
					<div class='table-responsive'>
					<table class='table table-borderless'>
					<tr>
					<td>Carte d'identité</td>
					<th>".$row->NUMERO_CARTE_IDENTITE."</th>
					</tr>

					<tr>
					<td>Email</td>
					<th>".$row->ADRESSE_MAIL."</th>
					</tr>

					<tr>
					<td>Téléphone</td>
					<th>".$row->NUMERO_TELEPHONE."</th>
					</tr>

					<tr>
					<td>Date naissance</td>
					<th>".$row->DATE_NAISSANCE."</th>
					</tr>

					<tr>
					<td>Aresse physique</td>
					<th>".$row->ADRESSE_PHYSIQUE."</th>
					</tr>
					<tr>
					<td>Localité</td>
					<th>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME." </th>
					</tr>

					<tr>
					<td>Information&nbsp;du&nbsp;vehicule</td>
					<th><a href='#' data-dismiss='modal' data-toggle='modal' data-target='#info_voitu" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a></th>
					</tr>

					<tr>
					<td><strong>Voir documents</strong></td>
					</tr>
					<tr>
					<td>CNI</td>
					<td><a href='#' data-toggle='modal' data-target='#info_documa" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
					</td>
					</tr>
					<tr>
					<td>PERMIS</td>
					<td><a href='#'data-toggle='modal' data-target='#info_documa2" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
					</td>
					</tr>



					</table>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>

					<div class='modal fade' id='info_documa" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog'>
					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Carte d'identité</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='scroller'>
					<div class='table-responsive'>

					<img src = '".base_url('upload/chauffeur/'.$row->FILE_CARTE_IDENTITE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>



					<div class='modal fade' id='info_documa2" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog'>
					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Permis de conduire</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='scroller'>
					<div class='table-responsive'>

					<img src = '".base_url('upload/chauffeur/'.$row->FILE_PERMIS)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					";
				}else
				{
					$option .="
					</div>
					<div class='modal fade' tabindex='-1' data-bs-backdrop='false' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog modal-dialog-centered modal-lg'>

					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Détails du chauffeur:" .$row->NOM." "." ".$row->PRENOM."</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='row'>
					<div class='col-md-4'>
					<img src = '".base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT)."' height='80%'  width='80%'  style= 'border-radius:20px;'>
					</div>
					<div class='col-md-8'>
					<div class='table-responsive'>
					<table class='table table-borderless'>
					<tr>
					<td>Carte d'identité</td>
					<th>".$row->NUMERO_CARTE_IDENTITE."</th>
					</tr>

					<tr>
					<td>Email</td>
					<th>".$row->ADRESSE_MAIL."</th>
					</tr>

					<tr>
					<td>Téléphone</td>
					<th>".$row->NUMERO_TELEPHONE."</th>
					</tr>

					<tr>
					<td>Date naissance</td>
					<th>".$row->DATE_NAISSANCE."</th>
					</tr>

					<tr>
					<td>Aresse physique</td>
					<th>".$row->ADRESSE_PHYSIQUE."</th>
					</tr>
					<tr>
					<td>Localité</td>
					<th>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME." </th>
					</tr>

					<tr>
					<td><strong>Voir documents</strong></td>
					</tr>
					<tr>
					<td>CNI</td>
					<td><a href='#'data-toggle='modal' data-target='#info_documa" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
					</td>
					</tr>
					<tr>
					<td>PERMIS</td>
					<td><a href='#'data-toggle='modal' data-target='#info_documa2" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
					</td>
					</tr>



					</table>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>

					<div class='modal fade' id='info_documa" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog'>
					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Carte d'identité</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='scroller'>
					<div class='table-responsive'>
					<img src = '".base_url('upload/chauffeur/'.$row->FILE_CARTE_IDENTITE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>



					<div class='modal fade' id='info_documa2" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog'>
					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Permis de conduire</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='scroller'>
					<div class='table-responsive'>

					<img src = '".base_url('upload/chauffeur/'.$row->FILE_PERMIS)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					



					";
				}
						//fin debut Detail cahuffeur
				$info_vehicul=$this->ModelPs->getRequeteOne('SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR FROM chauffeur_vehicule  join vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE WHERE chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CHAUFFEUR_ID='.$row->CHAUFFEUR_ID.'');
						//debut modal de info voiture(id=info_voitu)
				if (!empty($info_vehicul)) 
				{
					$option .="
					</div>
					<div class='modal fade' id='info_voitu" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog'>
					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Détails du véhicule</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='row'>
					<div class='col-md-6' >
					<img src = '".base_url('upload/photo_vehicule/').$info_vehicul['PHOTO']."' height='100%' width='100%' >
					</div>
					<div class='col-md-6'>
					<table class='table table-borderless table-hover text-dark'>

					<tr>
					<td>Marque
					<th>".$info_vehicul['DESC_MARQUE']."</th>
					</tr>

					<tr>
					<td>Modèle</td>
					<th>".$info_vehicul['DESC_MODELE']."</th>
					</tr>

					<tr>
					<td>Couleur</td>
					<th>".$info_vehicul['COULEUR']."</th>
					</tr>

					<tr>
					<td>Plaque</td>
					<th>".$info_vehicul['PLAQUE']."</th>
					</tr>
					</table>
					</div>
					</div>
					</div>

					</div>
					</div>
					</div>";
				}

						//fin modal de info voiture(id=info_voitu)
				$sub_array[]=$option;
				$data[] = $sub_array;
			}
			$recordsTotal = $this->ModelPs->datatable("CALL `getTable`('" . $query_principal . "')");
			$recordsFiltered = $this->ModelPs->datatable(" CALL `getTable`('" . $requetedebasefilter . "')");
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => count($recordsTotal),
				"recordsFiltered" => count($recordsFiltered),
				"data" => $data,
			);
			echo json_encode($output);
		}


		// Fonction pour la liste des chauffeurs selon le proprietaire connecte

		function GetChauffeurPro($PROPRIETAIRE_ID = '')
		{
			$USER_ID=$this->session->userdata('USER_ID');
			$get_user=$this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";
			$critaire = " AND proprietaire.PROPRIETAIRE_ID=".$get_user['PROPRIETAIRE_ID'];
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','chauffeur.NOM','chauffeur.PRENOM','chauffeur.ADRESSE_PHYSIQUE ','provinces.PROVINCE_NAME','communes.COMMUNE_NAME','zones.ZONE_NAME','collines.COLLINE_NAME','chauffeur.NUMERO_TELEPHONE','chauffeur.ADRESSE_MAIL','chauffeur.NUMERO_CARTE_IDENTITE','chauffeur.PERSONNE_CONTACT_TELEPHONE','chauffeur.DATE_INSERTION');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%"
				OR chauffeur.ADRESSE_PHYSIQUE LIKE "%' . $var_search . '%" 
				OR provinces.PROVINCE_NAME LIKE "%' . $var_search . '%" 
				OR communes.COMMUNE_NAME LIKE "%' . $var_search . '%"
				OR zones.ZONE_NAME  LIKE "%' . $var_search . '%"
				OR collines.COLLINE_NAME LIKE "%' . $var_search . '%"
				OR chauffeur.NUMERO_TELEPHONE LIKE "%' . $var_search . '%"
				OR chauffeur.ADRESSE_MAIL LIKE "%' . $var_search . '%"
				OR chauffeur.NUMERO_CARTE_IDENTITE LIKE "%' . $var_search . '%"
				OR chauffeur.DATE_INSERTION LIKE "%' . $var_search . '%")') : '';

			$query_principal='SELECT  chauffeur.CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,proprietaire.PROPRIETAIRE_ID,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE FROM `chauffeur_vehicule` join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID join vehicule on vehicule.CODE=chauffeur_vehicule.CODE join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID WHERE `STATUT_AFFECT`=1';

            //condition pour le query principale
			$conditions = $critaire . ' ' . $search . ' ' . $group . ' ' . $order_by . '   ' . $limit;

          // condition pour le query filter
			$conditionsfilter = $critaire . ' ' . $group;
			$requetedebase=$query_principal.$conditions;
			$requetedebasefilter=$query_principal.$conditionsfilter;

			$query_secondaire = "CALL `getTable`('".$requetedebase."');";
         // echo $query_secondaire;
			$fetch_data = $this->ModelPs->datatable($query_secondaire);
			$data = array();
			$u=1;
			foreach ($fetch_data as $row) 
			{
				$sub_array=array();
				$sub_array[]=$u++;
				$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->CHAUFFEUR_ID. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->NOM . ' ' . $row->PRENOM . '</td></tr></tbody></a>

				</div>
				<div class="modal fade" id="mypicture' .$row->CHAUFFEUR_ID. '">
				<div class="modal-dialog modal-dialog-centered ">
				<div class="modal-content">
				<div class="modal-header" style="background:cadetblue;color:white;">
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<img src = "'.base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT).'"" height="100%"  width="100%" >
				</div>
				</div>
				</div>
				</div>

				';


				//fin modal

				// $sub_array[] = $row->ADRESSE_PHYSIQUE;
				// $sub_array[] = $row->PROVINCE_NAME;
				// $sub_array[] = $row->COMMUNE_NAME;
				// $sub_array[] = $row->ZONE_NAME;
				// $sub_array[] = $row->COLLINE_NAME;
				$sub_array[] = $row->NUMERO_TELEPHONE;
				$sub_array[] = $row->ADRESSE_MAIL;

				$option = '<div class="dropdown ">
				<a class=" text-black btn-sm" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-left">
				';


				$option.= "<li><a class='btn-md' href='#' data-toggle='modal' data-target='#info_chauf" . $row->CHAUFFEUR_ID. "'><i class='bi bi-info-square h5' ></i>&nbsp;Détails</a></li>";


			//fin activer desactiver
			//DEBUT modal pour retirer la voiture
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_retirer" .$row->CHAUFFEUR_ID. "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous retirer la voiture à <b>" . $row->NOM .' '.$row->PRENOM. " ? </b></h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('chauffeur/Chauffeur/retirer_voit/'.$row->CHAUFFEUR_ID)."' >Retirer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";


				$option .="
				</div>
				<div class='modal fade' tabindex='-1' data-bs-backdrop='false' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>

				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Détails du chauffeur ".$row->NOM." ".$row->PRENOM."</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<div class='row'>
				<div class='col-md-6'>
				<img src = '".base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT)."' height='100%'  width='100%' >
				</div>
				<div class='col-md-6'>

				<div class='table-responsive'>
				<table class= 'table table-borderless'>
				<tr>
				<td>Carte d'identité</td>
				<td><strong>".$row->NUMERO_CARTE_IDENTITE."</strong></td>
				</tr>

				<tr>
				<td>Email</td>
				<td><strong>".$row->ADRESSE_MAIL."</strong></td>
				</tr>

				<tr>
				<td>Téléphone</td>
				<td><strong>".$row->NUMERO_TELEPHONE."</strong></td>
				</tr>

				<tr>
				<td>Date naissance</td>
				<td><strong>".$row->DATE_NAISSANCE."</strong></td>
				</tr>

				<tr>
				<td>Adresse physique</td>
				<td><strong>".$row->ADRESSE_PHYSIQUE."</strong></td>
				</tr>
				<tr>
				<td>Localité</td>
				<td><strong>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME."</strong></td>
				</tr>

				<tr>
				<td>Information&nbsp;du&nbsp;vehicule</td>
				<td><a href='#' data-dismiss='modal' data-toggle='modal' data-target='#info_voitu" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a></td>
				</tr>
				</table>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>";

						//fin debut Detail cahuffeur
			// $info_vehicul=$this->ModelPs->getRequeteOne('SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR FROM chauffeur_vehicule  join vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE WHERE chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CHAUFFEUR_ID='.$row->CHAUFFEUR_ID.'');

				$proce_requete = "CALL `getRequete`(?,?,?,?);";

				$my_selectinfo_vehicul= $this->getBindParms('vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR', 'chauffeur_vehicule  join vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE', 'chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CHAUFFEUR_ID="'.$row->CHAUFFEUR_ID.'"' , '`CHAUFFEUR_VEHICULE_ID` ASC');
				$my_selectinfo_vehicul=str_replace('\"', '"', $my_selectinfo_vehicul);
				$my_selectinfo_vehicul=str_replace('\n', '', $my_selectinfo_vehicul);
				$my_selectinfo_vehicul=str_replace('\"', '', $my_selectinfo_vehicul);

				$info_vehicul = $this->ModelPs->getRequeteOne($proce_requete, $my_selectinfo_vehicul);


			//debut modal de info voiture(id=info_voitu)
				if (!empty($info_vehicul)) 
				{
					$option .="
					</div>
					<div class='modal fade' id='info_voitu" .$row->CHAUFFEUR_ID. "'>
					<div class='modal-dialog'>
					<div class='modal-content'>
					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h6 class='modal-title'>Détails du véhicule</h6>
					<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>
					<div class='row'>
					<div class='col-md-6' >
					<img src = '".base_url('upload/photo_vehicule/').$info_vehicul['PHOTO']."' height='100%' width='100%' >
					</div>
					<div class='col-md-6'>
					<div class='table-responsive'>

					<table class='table table-borderless'>

					<tr>
					<td>Marque
					<td><strong>".$info_vehicul['DESC_MARQUE']."</strong></td>
					</tr>

					<tr>
					<td>Modèle</td>
					<td><strong>".$info_vehicul['DESC_MODELE']."</strong></td>
					</tr>

					<tr>
					<td>Couleur</td>
					<td><strong>".$info_vehicul['COULEUR']."</strong></td>
					</tr>

					<tr>
					<td>Plaque</td>
					<td><strong>".$info_vehicul['PLAQUE']."</strong></th>
					</tr>
					</table>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>";
				}

			//fin modal de info voiture(id=info_voitu)
				$sub_array[]=$option;
				$data[] = $sub_array;
			}
			$recordsTotal = $this->ModelPs->datatable("CALL `getTable`('" . $query_principal . "')");
			$recordsFiltered = $this->ModelPs->datatable(" CALL `getTable`('" . $requetedebasefilter . "')");
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => count($recordsTotal),
				"recordsFiltered" => count($recordsFiltered),
				"data" => $data,
			);
			echo json_encode($output);
		}





		//fonction pour la selection des collonnes de la base de données en utilisant les procedures stockées
		public function getBindParms($columnselect, $table, $where, $orderby)
		{
        // code...
			$bindparams = array(
				'columnselect' => mysqli_real_escape_string($this->db->conn_id,$columnselect),
				'table' => mysqli_real_escape_string($this->db->conn_id,$table) ,
				'where' => mysqli_real_escape_string($this->db->conn_id,$where) ,
				'orderby' => mysqli_real_escape_string($this->db->conn_id,$orderby) ,
			);
			return $bindparams;
		}





	}


?>