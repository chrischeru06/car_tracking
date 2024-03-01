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
			$html="<option value=''> Séléctionner </option>";
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
			$zoom = 9;

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

			$get_vihicule = $this->Model->getRequete('SELECT CODE FROM vehicule LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID WHERE 1'.$critere_proprietaire.''.$critere_vehicule.''.$critere_user.'');

			$donnees_vehicule = ' ';

			$nbrProprietaire = count($proprio);
			$nbrVehicule = 0;
			$nbrVehiculeMouvement = 0;
			$nbrVehiculeStationnement = 0;
			$nbrVehiculeAllume = 0;
			$nbrVehiculeEteint = 0;
			$nbrVehiculeActif = 0;

			if(!empty($get_vihicule))
			{
				foreach ($get_vihicule as $key) {

					$track_data = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.IS_ACTIVE,proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,COULEUR,KILOMETRAGE,PHOTO,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc FROM tracking_data JOIN vehicule ON vehicule.CODE = tracking_data.device_uid JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 '.$critere_proprietaire.' '.$critere_vehicule.''.$critere_user.' AND device_uid = "'.$key['CODE'].'" ORDER BY id DESC LIMIT 1');

					if(!empty($track_data))
					{
						$VEHICULE_ID = $track_data['VEHICULE_ID'];

						$nbrVehicule += 1;

						// Nbr véhicules actifs

						if($track_data['IS_ACTIVE'] == 1)
						{
							$nbrVehiculeActif += 1;
						}
						// else if($track_data['ignition'] == 0)
						// {
						// 	$nbrVehiculeEteint += 1;
						// }

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
					else
					{
						$donnees_vehicule = ' ';
						$nbrVehicule = 0;
					}
					
				}
			}
			
			$data['proprio'] = $proprio;
			$data['donnees_vehicule'] = $donnees_vehicule;
			$data['nbrVehicule'] = $nbrVehicule; 
			$data['nbrProprietaire'] = $nbrProprietaire;
			$data['nbrChauffeur'] = $nbrChauffeur;
			$data['vehiculeActif'] = $nbrVehiculeActif;
			$data['vehiculeMouvement'] = $nbrVehiculeMouvement;
			$data['vehiculeStationnement'] = $nbrVehiculeStationnement;
			$data['vehiculeAllume'] = $nbrVehiculeAllume;
			$data['vehiculeEteint'] = $nbrVehiculeEteint;
			$data['coordinates'] = $coordinates;
			$data['zoom'] = $zoom;

			$map = $this->load->view('Getcarte_Tracking_View',$data,TRUE);

			$output = array('carte_view'=>$map,'proprio'=>$proprio,'donnees_vehicule'=>$donnees_vehicule,'nbrVehicule'=>$nbrVehicule,'nbrProprietaire'=>$nbrProprietaire,'nbrChauffeur'=>$nbrChauffeur,'vehiculeActif'=>$nbrVehiculeActif,'vehiculeAllume'=>$nbrVehiculeAllume,'vehiculeEteint'=>$nbrVehiculeEteint,'vehiculeStationnement'=>$nbrVehiculeStationnement,'vehiculeMouvement'=>$nbrVehiculeMouvement,'coordinates'=>$coordinates,'zoom'=>$zoom);
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