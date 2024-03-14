<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 21/02/2024
*/
	class Centre_situation2 extends CI_Controller
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

			$this->load->view('Centre_situation_View2',$data);
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

			$coordinates = '-3.3944616,29.3726466';
			$zoom = 8;

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
				$zoom = 11; 
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

					$track_data = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.IS_ACTIVE,proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,COULEUR,KILOMETRAGE,PHOTO,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,tracking_data.accident FROM tracking_data JOIN vehicule ON vehicule.CODE = tracking_data.device_uid JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 '.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.' AND device_uid = "'.$key['CODE'].'" ORDER BY id DESC LIMIT 1');

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
						else if($track_data['mouvement'] == 0)
						{
							$nbrVehiculeStationnement += 1;
						}

						// Nbr véhicules allumés et éteints

						if($track_data['ignition'] == 1)
						{
							$nbrVehiculeAllume += 1;
						}
						else if($track_data['ignition'] == 0)
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


						$donnees_vehicule = $donnees_vehicule.$VEHICULE_ID.'<>'.$latitude.'<>'.$longitude.'<>'.$CODE.'<>'.$DESC_MARQUE.'<>'.$DESC_MODELE.'<>'.$PLAQUE.'<>'.$COULEUR.'<>'.$KILOMETRAGE.'<>'.$proprio_desc.'<>'.$PHOTO.'<>'.md5($CODE).'<>'.$chauffeur_desc.'<>'.$IS_ACTIVE.'<>@';
					}
				}
			}

			$data_marker = '{"name":"iss","id":25544,"latitude":-3.3861416,"longitude":29.3619433,"altitude":422.14118359729,"velocity":27564.765811989,"visibility":"daylight","footprint":4518.3408389917,"timestamp":1710315700,"daynum":2460382.8206019,"solar_lat":-2.6891941350284,"solar_lon":66.928429639143,"units":"kilometers"}';

			//echo $data_marker;
			
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
			$data['data_marker'] = $data_marker;

			// $data['data_marker'] = $data_marker;

			 $map = $this->load->view('Getcarte_Tracking_View2',$data,TRUE);

			$output = array('carte_view'=>$map,'proprio'=>$proprio,'donnees_vehicule'=>$donnees_vehicule,'nbrVehicule'=>$nbrVehicule,'nbrProprietaire'=>$nbrProprietaire,'nbrChauffeur'=>$nbrChauffeur,'vehiculeActif'=>$nbrVehiculeActif,'vehiculeInactif'=>$nbrVehiculeInactif,'vehiculeAllume'=>$nbrVehiculeAllume,'vehiculeEteint'=>$nbrVehiculeEteint,'vehiculeStationnement'=>$nbrVehiculeStationnement,'vehiculeMouvement'=>$nbrVehiculeMouvement,'vehiculeAvecAccident'=>$nbrVehiculeAvecAccident,'vehiculeSansAccident'=>$nbrVehiculeSansAccident,'coordinates'=>$coordinates,'zoom'=>$zoom,'data_marker'=>$data_marker);
			echo json_encode($output);

		}

		function getmapSymbol()
		{
			$data = '{{"name":"iss","id":25544,"latitude":-3.43143,"longitude":29.9079,"altitude":422.14118359729,"velocity":27564.765811989,"visibility":"daylight","footprint":4518.3408389917,"timestamp":1710315700,"daynum":2460382.8206019,"solar_lat":-2.6891941350284,"solar_lon":66.928429639143,"units":"kilometers"}}';
			echo $data;
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