<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 21/02/2024
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

			$coordinates = '-3.3944616,29.3726466';
			$zoom = 18;

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
				$zoom = 18; 
			}

			if($VEHICULE_ID > 0){
				$critere_vehicule.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;
				$zoom = 18; 
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

			$get_vihicule = $this->Model->getRequete('SELECT CODE FROM vehicule JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID WHERE 1'.$critere_proprietaire.''.$critere_vehicule.''.$critere_user.'');

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

			if(!empty($get_vihicule))
			{
				foreach ($get_vihicule as $key) {

					$track_data = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.IS_ACTIVE,proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,COULEUR,KILOMETRAGE,PHOTO,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,tracking_data.accident FROM tracking_data JOIN vehicule ON vehicule.CODE = tracking_data.device_uid JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE  JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 '.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.' AND device_uid = "'.$key['CODE'].'" ORDER BY id DESC LIMIT 1');

					//print_r($track_data['VEHICULE_ID']);die();

					if(!empty($track_data))
					{
						$VEHICULE_ID = $track_data['VEHICULE_ID'];

						$nbrVehicule += 1;

						// Nbr véhicules actifs et inactifs

						if($track_data['IS_ACTIVE'] == 1)
						{
							$nbrVehiculeActif += 1;
						}
						else 
						{
							$nbrVehiculeInactif += 1;
						}

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

						$IS_ACTIVE = $track_data['IS_ACTIVE']; 
						$accident = $track_data['accident']; 


						$donnees_vehicule = $donnees_vehicule.$VEHICULE_ID.'<>'.$latitude.'<>'.$longitude.'<>'.$CODE.'<>'.$DESC_MARQUE.'<>'.$DESC_MODELE.'<>'.$PLAQUE.'<>'.$COULEUR.'<>'.$KILOMETRAGE.'<>'.$proprio_desc.'<>'.$PHOTO.'<>'.md5($CODE).'<>'.$chauffeur_desc.'<>'.$IS_ACTIVE.'<>'.$id.'<>'.$accident.'<>@';
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

			$map = $this->load->view('Getcarte_Tracking_View',$data,TRUE);

			$output = array('carte_view'=>$map,'proprio'=>$proprio,'donnees_vehicule'=>$donnees_vehicule,'nbrVehicule'=>$nbrVehicule,'nbrProprietaire'=>$nbrProprietaire,'nbrChauffeur'=>$nbrChauffeur,'vehiculeActif'=>$nbrVehiculeActif,'vehiculeInactif'=>$nbrVehiculeInactif,'vehiculeAllume'=>$nbrVehiculeAllume,'vehiculeEteint'=>$nbrVehiculeEteint,'vehiculeStationnement'=>$nbrVehiculeStationnement,'vehiculeMouvement'=>$nbrVehiculeMouvement,'vehiculeAvecAccident'=>$nbrVehiculeAvecAccident,'vehiculeSansAccident'=>$nbrVehiculeSansAccident,'coordinates'=>$coordinates,'zoom'=>$zoom,'id'=>$id);
			echo json_encode($output);
		}


		//Fonction pour mettre à jour les marquers en temps reel
		function getUpdateMarker()
		{
			$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');
			$id = $this->input->post('id');

			$coordinates = '-3.3944616,29.3726466';
			$zoom = 18;

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
				$zoom = 18; 
			}

			if($VEHICULE_ID > 0){
				$critere_vehicule.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;
				$zoom = 18; 
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

			$get_vihicule = $this->Model->getRequete('SELECT CODE FROM vehicule JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID WHERE 1'.$critere_proprietaire.''.$critere_vehicule.''.$critere_user.'');

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

			if(!empty($get_vihicule))
			{
				foreach ($get_vihicule as $key) {

					$track_data = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.IS_ACTIVE,proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,COULEUR,KILOMETRAGE,PHOTO,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,tracking_data.accident FROM tracking_data JOIN vehicule ON vehicule.CODE = tracking_data.device_uid JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE  JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 '.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.' AND device_uid = "'.$key['CODE'].'" ORDER BY id DESC LIMIT 1');

					//print_r($track_data['VEHICULE_ID']);die();

					if(!empty($track_data))
					{
						$VEHICULE_ID = $track_data['VEHICULE_ID'];

						$nbrVehicule += 1;

						// Nbr véhicules actifs et inactifs

						if($track_data['IS_ACTIVE'] == 1)
						{
							$nbrVehiculeActif += 1;
						}
						else 
						{
							$nbrVehiculeInactif += 1;
						}

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

						$IS_ACTIVE = $track_data['IS_ACTIVE']; 
						$accident = $track_data['accident']; 


						$donnees_vehicule = $donnees_vehicule.$VEHICULE_ID.'<>'.$latitude.'<>'.$longitude.'<>'.$CODE.'<>'.$DESC_MARQUE.'<>'.$DESC_MODELE.'<>'.$PLAQUE.'<>'.$COULEUR.'<>'.$KILOMETRAGE.'<>'.$proprio_desc.'<>'.$PHOTO.'<>'.md5($CODE).'<>'.$chauffeur_desc.'<>'.$IS_ACTIVE.'<>'.$id.'<>'.$accident.'<>@';
					}
				}
			}

			$output = array('donnees_vehicule'=>$donnees_vehicule,'proprio'=>$proprio,'donnees_vehicule'=>$donnees_vehicule,'nbrVehicule'=>$nbrVehicule,'nbrProprietaire'=>$nbrProprietaire,'nbrChauffeur'=>$nbrChauffeur,'vehiculeActif'=>$nbrVehiculeActif,'vehiculeInactif'=>$nbrVehiculeInactif,'vehiculeAllume'=>$nbrVehiculeAllume,'vehiculeEteint'=>$nbrVehiculeEteint,'vehiculeStationnement'=>$nbrVehiculeStationnement,'vehiculeMouvement'=>$nbrVehiculeMouvement,'vehiculeAvecAccident'=>$nbrVehiculeAvecAccident,'vehiculeSansAccident'=>$nbrVehiculeSansAccident,'coordinates'=>$coordinates,'zoom'=>$zoom,'id'=>$id);
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
					$sub_array[] ='<tbody><tr><td><a href="javascript:;" onclick="get_detail_pers_moral(' . $row->PROPRIETAIRE_ID . ')" style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->info_personne . '</td></tr></tbody></a>
					';

				}elseif(!empty($row->LOGO)){

					$sub_array[] = ' <tbody><tr><td><a href="javascript:;" onclick="get_detail(' . $row->PROPRIETAIRE_ID . ')"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></a></td><td> '.'     '.' ' . $row->info_personne . '</td></tr></tbody></a>

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

					$sub_array[] = ' <tbody><tr><td><a href="javascript:;" onclick="get_detail(' . $row->PROPRIETAIRE_ID . ')"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->info_personne . '</td></tr></tbody></a>

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

			$critere_proprietaire = '';
			$critere_vehicule = '';
			$critere_user = '';

			$critaireVehicule2= '';

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

			$critaire_select = ' ';

			if(!empty($VEHICULE_ID) && $id >= 0)
			{
				$critaire_select = ' AND vehicule.VEHICULE_ID ='.$VEHICULE_ID;
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ACTIF')
			{
				$critaire_select = ' AND vehicule.IS_ACTIVE = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_INACTIF')
			{
				$critaire_select = ' AND vehicule.IS_ACTIVE = 2';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_CREVAISON')
			{
				$critaire_select = ' AND accident = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_MOUVEMENT')
			{
				$critaire_select = ' AND mouv = 1';
			}
			else if(empty($VEHICULE_ID) && $id == 'V_ETEINT')
			{
				$critaire_select = ' AND ignition = 0';
			}

			$query_principal = 'SELECT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,proprietaire.PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,vehicule.IS_ACTIVE FROM vehicule JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.mouvement as mouv,tracking_data.accident as accident,tracking_data.ignition as ignition FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 '.$critaire_select.''.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.'  GROUP BY VEHICULE_ID ';

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

			$order_by = ' ORDER BY id DESC';

			$search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

			$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. '';

			$query_filter=$query_principal.''.$critaire.''.$search;

			//print_r($query_filter);die();

			$fetch_data=$this->Model->datatable($query_secondaire);

			$data=array();
			foreach ($fetch_data as $row)
			{

				$sub_array=array();
				$sub_array[]=$row->CODE;
				$sub_array[]=$row->DESC_MARQUE;
				$sub_array[]=$row->DESC_MODELE;
				$sub_array[]=$row->PLAQUE;
				$sub_array[]=$row->COULEUR;
				$sub_array[]=(isset($row->KILOMETRAGE)?$row->KILOMETRAGE.' litres / KM' : 'N/A');

				// $sub_array[]= "<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='120px' width='120px' ></a>";

				$sub_array[]=' <table><tr><td style = "width:5000px;"><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->VEHICULE_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->desc_proprio . '</td></tr></table></a>';

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'>&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				if($row->IS_ACTIVE==1){
					$sub_array[]='<td><label class="text-primary">Activé</label></td>';
				}else{
					$sub_array[]='<td><label class="text-danger">Désactivé</label></td>';
				}

				$option = ' ';

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
				<td class='btn-sm'>propriétaire</td>
				<th class='btn-sm'><strong>".$row->desc_proprio."</strong></th>
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
				<img src = '".base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT)."' height='100%'  width='100%'  style= 'border-radius:50%;'>
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
				<th class='btn-sm'>".$row->ADRESSE."</th>
				</tr>

				<tr class='btn-sm'>
				<td>Email</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->EMAIL."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Téléphone</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->TELEPHONE."</th>
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

			// chauffeur_vehicule LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID LEFT JOIN vehicule ON vehicule.CODE = chauffeur_vehicule.CODE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID

			$critaire = ' ';

			// if(!empty($VEHICULE_ID))
			// {
			// 	$critaire = ' AND vehicule.VEHICULE_ID ='.$VEHICULE_ID;
			// }

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
				<img src = "'.base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT).'"" height="50%"  width="50%" >
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

				$option = '
				';

				// $option .= "<li><a class='btn-md' href='" . base_url('chauffeur/Chauffeur/getOne/'. $row->CHAUFFEUR_ID) . "'><span class='bi bi-pencil h5'></span>&nbsp;Modifier</a></li>";

				// $option.= "<li><a class='btn-md' href='#' data-toggle='modal' data-target='#info_chauf" . $row->CHAUFFEUR_ID. "'><i class='bi bi-info-square h5' ></i>&nbsp;Détails</a></li>";


				// if($row->STATUT_VEHICULE==1 && $row->IS_ACTIVE==1)
				// {
				// 	$option.='<li><a class="btn-md" onClick="attribue_voiture('.$row->CHAUFFEUR_ID.',\''.$row->NOM.'\',\''.$row->PRENOM.'\')"><i class="bi bi-plus h5" ></i>&nbsp;Affecter le chauffeur</a></li>';
					
				// }
				// if ($row->STATUT_VEHICULE==2 && $row->IS_ACTIVE==1)
				// {
				// 	$option .= "<li><a class='btn-md' data-toggle='modal' data-target='#modal_retirer" . $row->CHAUFFEUR_ID . "'><span class='bi bi-plus h5' ></span>&nbsp;Retirer&nbsp;voiture</a></li>";

				// 	$option.='<li><a class="btn-md" onClick="modif_affectation(\''.$row->CHAUFFEUR_ID.'\')"><span class="bi bi-pencil h5"></span>&nbsp;&nbsp;Modifier affectation</a></li>';

				// }
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