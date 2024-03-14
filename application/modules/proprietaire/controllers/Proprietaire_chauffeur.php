<?php


/**Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * 68071895
 * Le 28/2/2024
 * Gestion des Chauffeurs d'un proprietaire
 */
class Proprietaire_chauffeur extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->out_application();
		$this->load->helper('email');
	}

	//Fonction pour rediriger sur la page de connexion si une fois la session est perdue
	function out_application()
	{
		if(empty($this->session->userdata('USER_ID')))
		{
			redirect(base_url('Login/logout'));

		}
	}

	//Fonction pour l'affichage de la liste
	function index(){

		$this->load->view('Proprietaire_chauffeur_List_View');
	}

	//Fonction pour la liste
	function listing()
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

		$query_principal='SELECT  DISTINCT chauffeur.CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,proprietaire.PROPRIETAIRE_ID,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE FROM `chauffeur_vehicule` join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID join vehicule on vehicule.CODE=chauffeur_vehicule.CODE join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID WHERE `STATUT_AFFECT`=1';

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
			if($row->STATUT_VEHICULE==1 && $row->IS_ACTIVE==1)
				{
					$option.='<li><a class="btn-md" onClick="attribue_voiture('.$row->CHAUFFEUR_ID.',\''.$row->NOM.'\',\''.$row->PRENOM.'\')"><i class="bi bi-plus h5" ></i>&nbsp;Affecter le chauffeur</a></li>';
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

	//Fonction pour ajouter les provinces,communes,zones et collines
	function ajouter()
	{
		$data['provinces'] = $this->Model->getRequete("SELECT `PROVINCE_ID`, `PROVINCE_NAME` FROM `provinces` WHERE 1 ORDER BY PROVINCE_NAME ASC");
		$data['communes'] = $this->Model->getRequete('SELECT COMMUNE_ID, COMMUNE_NAME FROM communes WHERE 1 ORDER BY COMMUNE_NAME ASC');
		$data['zones'] = $this->Model->getRequete('SELECT ZONE_ID ,ZONE_NAME,COMMUNE_ID FROM zones WHERE 1 ORDER BY ZONE_NAME ASC');
		$data['collines'] = $this->Model->getRequete('SELECT COLLINE_ID, COLLINE_NAME FROM collines WHERE 1 ORDER BY COLLINE_NAME ASC');
		$data['title'] = 'Nouveau chauffeur';
		$data['type_genre'] = $this->Model->getRequete('SELECT GENRE_ID, DESCR_GENRE FROM syst_genre WHERE 1 ORDER BY DESCR_GENRE ASC');
		// $data['ethnie'] = $this->Model->getRequete('SELECT ETHNIE_ID, DESCR_ETHNIE FROM syst_ethnie WHERE 1 ORDER BY DESCR_ETHNIE ASC');
		$this->load->view('Proprietaire_Chauffeur_Add_View',$data);
	}

	//Fonction pour inserer dans la BD
	function add()
	{
		$table ='chauffeur';
		$this->form_validation->set_rules('nom','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('prenom','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('adresse_physique','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('numero_telephone','','trim|required|is_unique[chauffeur.numero_telephone]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le téléphone doit être unique</font>'));
    	// Gestion numero carte d'identite doit etre unique
		$this->form_validation->set_rules('NUMERO_CARTE_IDENTITE','','trim|required|is_unique[chauffeur.NUMERO_CARTE_IDENTITE]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le numéro doit être unique</font>'));
			// Gestion nmail qui doit etre unique
		$this->form_validation->set_rules('adresse_email','','trim|required|is_unique[chauffeur.ADRESSE_MAIL]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le mail doit être unique</font>'));
		$this->form_validation->set_rules('CONFIRMATION_EMAIL','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('personne_contact_telephone','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('NUMERO_PERMIS','','trim|required|is_unique[chauffeur.NUMERO_PERMIS]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le permis doit être unique</font>'));
		$this->form_validation->set_rules('PROVINCE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('COMMUNE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('ZONE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('COLLINE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));


		$this->form_validation->set_rules('date_naissance','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('date_expiration','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('GENRE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		if(!isset($_FILES['fichier_carte_identite']) || empty($_FILES['fichier_carte_identite']['name']))
		{
			$this->form_validation->set_rules('fichier_carte_identite',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		}

		if(!isset($_FILES['photo_passport']) || empty($_FILES['photo_passport']['name']))
		{
			$this->form_validation->set_rules('photo_passport',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		}
		if(!isset($_FILES['file_permis']) || empty($_FILES['file_permis']['name']))
		{
			$this->form_validation->set_rules('file_permis',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->ajouter();
		}
		else
		{
			$adresse_email = $this->input->post('adresse_email');
			$data_insert = array(
				'NOM' => $this->input->post('nom'),
				'PRENOM' => $this->input->post('prenom'),
				'ADRESSE_PHYSIQUE' => $this->input->post('adresse_physique'),
				'ADRESSE_MAIL' => $adresse_email,
				'NUMERO_TELEPHONE' => $this->input->post('numero_telephone'),
				'NUMERO_CARTE_IDENTITE' => $this->input->post('NUMERO_CARTE_IDENTITE'),
				'PERSONNE_CONTACT_TELEPHONE' => $this->input->post('personne_contact_telephone'),
				'NUMERO_PERMIS' => $this->input->post('NUMERO_PERMIS'),
				'FILE_CARTE_IDENTITE' => $this->upload_document($_FILES['fichier_carte_identite']['tmp_name'],$_FILES['fichier_carte_identite']['name']),

				'FILE_PERMIS' => $this->upload_document($_FILES['file_permis']['tmp_name'],$_FILES['file_permis']['name']),
				'PHOTO_PASSPORT' => $this->upload_document($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name']),

				'PROVINCE_ID' => $this->input->post('PROVINCE_ID'),
				'COMMUNE_ID' => $this->input->post('COMMUNE_ID'),
				'ZONE_ID' => $this->input->post('ZONE_ID'),
				'COLLINE_ID' => $this->input->post('COLLINE_ID'),
				'DATE_NAISSANCE' => $this->input->post('date_naissance'),
				 'DATE_EXPIRATION_PERMIS' => $this->input->post('date_expiration'),
				'GENRE_ID' => $this->input->post('GENRE_ID')
			);
			
			$inser = $this->Model->create($table,$data_insert);
			// if($CHAUFFEUR_ID>0)
			// {
				if($inser)
				{
					
					
					$data['message']='<div class="alert alert-success text-center" id="message">Ajout effectuer avec succès</div>';
					$this->session->set_flashdata($data);
					redirect(base_url('chauffeur/Chauffeur/index'));
				}
				else
				{
					$this->load->view('Chauffeur_Add_View',$data);
				}
			//}
			// else
			// {
			// 	$data['message']='<div class="alert alert-success text-center" id="message">Ajout n\'est pas faite avec succès</div>';
			// 	$this->session->set_flashdata($data);
			// 	redirect(base_url('agent/Agent/index'));
			// }
		}
	}
	//Fonction pour filter les communes
	function get_communes($ID_PROVINCE=0)
	{
		$communes = $this->Model->getRequete('SELECT `COMMUNE_ID`, `COMMUNE_NAME` FROM `communes` WHERE PROVINCE_ID='.$ID_PROVINCE.' ORDER BY COMMUNE_NAME ASC');
		$html='<option value="">---sélectionner---</option>';
		foreach ($communes as $key)
		{
			$html.='<option value="'.$key['COMMUNE_ID'].'">'.$key['COMMUNE_NAME'].'</option>';
		}
		echo json_encode($html);
	}

  	//Fonction pour filtrer les zones
	function get_zones($ID_COMMUNE=0)
	{
		$zones = $this->Model->getRequete('SELECT `ZONE_ID`, `ZONE_NAME` FROM `zones` WHERE COMMUNE_ID='.$ID_COMMUNE.' ORDER BY ZONE_NAME ASC');
		$html='<option value="">---sélectionner---</option>';
		foreach ($zones as $key)
		{
			$html.='<option value="'.$key['ZONE_ID'].'">'.$key['ZONE_NAME'].'</option>';
		}
		echo json_encode($html);
	}

  	//Fonction pour filtrer les collines
	function get_collines($ID_ZONE=0)
	{
		$collines = $this->Model->getRequete('SELECT `COLLINE_ID`, `COLLINE_NAME` FROM `collines` WHERE ZONE_ID='.$ID_ZONE.' ORDER BY COLLINE_NAME ASC');
		$html='<option value="">---sélectionner---</option>';
		foreach ($collines as $key)
		{
			$html.='<option value="'.$key['COLLINE_ID'].'">'.$key['COLLINE_NAME'].'</option>';
		}
		echo json_encode($html);
	}
	function verif_date()
	{
		$DATE_NAISSANCE=$this->input->post('DATE_NAISSANCE');
		
		$aujourdhui = date("Y-m-d");

		$diff = date_diff(date_create($DATE_NAISSANCE), date_create($aujourdhui));
		$data = $diff->format('%y');

		echo json_encode($data);

	}
	// Recuperation des fichiers(pdf)
	public function upload_document($nom_file,$nom_champ)
	{
		$rep_doc =FCPATH.'upload/chauffeur/';
		$fichier=basename("piece".uniqid());
		$file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
		$file_extension = strtolower($file_extension);
		$valid_ext = array('pdf');
		if(!is_dir($rep_doc)) //crée un dossier s'il n'existe pas déja   
		{
			mkdir($rep_doc,0777,TRUE);
		}  
		move_uploaded_file($nom_file, $rep_doc.$fichier.".".$file_extension);
		$pathfile=$fichier.".".$file_extension;
		return $pathfile;
	}

	//fonction pour la selection des collonnes de la base de données en utilisant les procedures stockées
	public function getBindParms($columnselect, $table, $where, $orderby)
	{
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