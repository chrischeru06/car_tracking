<?php
/*
	Auteur    : NIYOMWUNGERE Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 06-09/02/2024
	crud des chauffeurs
*/
	class Chauffeur_New extends CI_Controller
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
			$this->load->view('Chauffeur_New_List_View');
		}


		//Fonction pour l'affichage
		function listing()
		{

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

			$query_principal='SELECT CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,chauffeur.FILE_PERMIS FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID  WHERE 1';

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
				$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->CHAUFFEUR_ID. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->NOM . ' ' . $row->PRENOM . '</td></tr></tbody></a>

				</div>
				<div class="modal fade" id="mypicture' .$row->CHAUFFEUR_ID. '">
				<div class="modal-dialog modal-dialog-centered ">
				<div class="modal-content">
				<div class="modal-header" style="background:cadetblue;color:white;">
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<center><img src = "'.base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT).'"" height="50%"  width="50%" ></center>
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

				$option .= "<li><a class='btn-md' href='" . base_url('chauffeur/Chauffeur_New/getOne/'. $row->CHAUFFEUR_ID) . "'><span class='bi bi-pencil h5'></span>&nbsp;Modifier</a></li>";

				// $option.= "<li><a class='btn-md' href='#' data-toggle='modal' data-target='#info_chauf" . $row->CHAUFFEUR_ID. "'><i class='bi bi-info-square h5' ></i>&nbsp;Détails</a></li>"
				$option.= "<li><a class='btn-md' href='" . base_url('chauffeur/Chauffeur_New/Detail/'.md5($row->CHAUFFEUR_ID)). "'><i class='bi bi-info-square h5' ></i>&nbsp;Détails</a></li>";




				if($row->STATUT_VEHICULE==1 && $row->IS_ACTIVE==1)
				{
					$option.='<li><a class="btn-md" onClick="attribue_voiture('.$row->CHAUFFEUR_ID.',\''.$row->NOM.'\',\''.$row->PRENOM.'\')"><i class="bi bi-plus h5" ></i>&nbsp;Affecter le chauffeur</a></li>';
					
				}
				if ($row->STATUT_VEHICULE==2 && $row->IS_ACTIVE==1)
				{
					$option .= "<li><a class='btn-md' data-toggle='modal' data-target='#modal_retirer" . $row->CHAUFFEUR_ID . "'><span class='fa fa-minus h5' ></span>&nbsp;Retirer&nbsp;voiture</a></li>";

					$option.='<li><a class="btn-md" onClick="modif_affectation(\''.$row->CHAUFFEUR_ID.'\')"><span class="bi bi-pencil h5"></span>&nbsp;&nbsp;Modifier affectation</a></li>';

				}
				if($row->IS_ACTIVE==1){
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-primary">Activé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="myFunction_desactive(' . $row->CHAUFFEUR_ID . ','.$row->STATUT_VEHICULE.')" checked>
					<span class="slider round"></span>
					</label></td>
					</table>
					
					</form>

					';
				}else{
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-danger">Désactivé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="myFunction(' . $row->CHAUFFEUR_ID . ')">
					<span class="slider round"></span>
					</label></td>
					</table>
					</form>

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
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('chauffeur/Chauffeur_New/retirer_voit/'.$row->CHAUFFEUR_ID)."' >Retirer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";

					//fin modal retire voiture
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
		//function pour l'affichage de la page de detail
		function Detail($CHAUFFEUR_ID){
		// $CHAUFFEUR_ID=$this->uri->segment(4);
			$chauff=$this->Model->getRequeteOne("SELECT CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,chauffeur.FILE_PERMIS FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID  WHERE 1 AND md5(chauffeur.CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'");

			$info_vehicul=$this->ModelPs->getRequeteOne('SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,concat(proprietaire.NOM_PROPRIETAIRE," ",proprietaire.PRENOM_PROPRIETAIRE) as name,proprietaire.PHOTO_PASSPORT FROM chauffeur_vehicule  join vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE join proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID  WHERE chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CHAUFFEUR_ID='.$chauff['CHAUFFEUR_ID'].'');


		// $desactive=$this->Model->getRequeteOne("SELECT proprietaire.proprietaire_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,proprietaire.IS_ACTIVE FROM proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_proprietaire_ID=proprietaire.TYPE_proprietaire_ID WHERE proprietaire.IS_ACTIVE=2 AND md5(proprietaire.proprietaire_ID)='".$PROPRIETAIRE_ID."'");
		// if ($proprietaire['TYPE_PROPRIETAIRE_ID']==1) 
		// {
			
		// 	$label_cni='NIF';
		// }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==2) {
		// 	$label_cni='CNI / Numéro passport';
			
		// }

			$data['chauff']=$chauff;
			$data['info_vehicul']=$info_vehicul;

		// $data['desactive']=$desactive;
		// $data['PROPRIETAIRE_ID'] = $data['proprietaire']['proprietaire_ID'];
		// print_r($data['proprietaire_ID']);die();
		// $data['dte'] =date("d-m-Y H:i:s", strtotime($chauff['DATE_INSERTION']));

			$this->load->view('Chauffeur_New_Detail_View',$data);

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
			$this->load->view('Chauffeur_New_Add_View',$data);
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

	//Fonction pour le controle de la date de naissance

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

	function get_all_voiture()
	{
		$all_voiture = $this->Model->getRequete("SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.CODE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule.ID_MODELE=vehicule_modele.ID_MODELE WHERE 1 AND vehicule.STATUT=1");
		$html='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_voiture))
		{
			foreach($all_voiture as $key)
			{
				$html.='<option value="'.$key['CODE'].'">'.$key['DESC_MARQUE'].'-'.$key['DESC_MODELE'].'  /'.$key['PLAQUE'].' </option>';
			}
		}

		$all_zone_affectation = $this->Model->getRequete("SELECT `CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION` FROM `chauffeur_zone_affectation` WHERE 1");

		$html1='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_zone_affectation))
		{
			foreach($all_zone_affectation as $key1)
			{
				$html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'">'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
			}
		}
		$ouput= array(
			'html'=>$html,
			'html1'=>$html1,
		);
		echo json_encode($ouput);
	}

	function get_zone_affect($CHAUFFEUR_ID)
	{
		$zone_affect=$this->ModelPs->getRequeteOne('SELECT chauffeur_zone_affectation.`CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION`,chauffeur.CHAUFFEUR_ID,chauffeur_vehicule.DATE_DEBUT_AFFECTATION,chauffeur_vehicule.DATE_FIN_AFFECTATION FROM `chauffeur_zone_affectation` join chauffeur_vehicule on chauffeur_zone_affectation.CHAUFF_ZONE_AFFECTATION_ID=chauffeur_vehicule.CHAUFF_ZONE_AFFECTATION_ID JOIN chauffeur on chauffeur_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID WHERE chauffeur.CHAUFFEUR_ID='.$CHAUFFEUR_ID);

	   // print_r($zone_affect);exit();
		$all_zone_affectation = $this->Model->getRequete("SELECT `CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION` FROM `chauffeur_zone_affectation` WHERE 1");

		$html1='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_zone_affectation))
		{
			foreach($all_zone_affectation as $key1)
			{
				if ($key1['CHAUFF_ZONE_AFFECTATION_ID']==$zone_affect['CHAUFF_ZONE_AFFECTATION_ID']) 
				{
					$html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'" selected>'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
				}else
				{
					$html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'">'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
				}
				
			}
		}
		$ouput=array(
			'htmldbut'=>$zone_affect['DATE_DEBUT_AFFECTATION'],
			'htmlfin'=>$zone_affect['DATE_FIN_AFFECTATION'],
			'html1'=>$html1,

		);

		
		echo json_encode($ouput);
	}
	
	function save_modif_chauff()
	{
		// $statut=1 attribution avec succes;
		// $statut=2:possedent une autre voiture qu'on l'a deja attribuée;
		// $statut=3: attribution echoue
		$statut=3;
		$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID_MOD');
		$CHAUFF_ZONE_AFFECTATION_ID = $this->input->post('CHAUFF_ZONE_AFFECTATION_ID_MOD');
		$DATE_DEBUT_AFFECTATION = $this->input->post('DATE_DEBUT_AFFECTATION_MOD');
		$DATE_FIN_AFFECTATION = $this->input->post('DATE_FIN_AFFECTATION_MOD');
		$today = date('Y-m-d H:i:s');

		$result = $this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('CHAUFF_ZONE_AFFECTATION_ID'=>$CHAUFF_ZONE_AFFECTATION_ID,'DATE_DEBUT_AFFECTATION'=>$DATE_DEBUT_AFFECTATION,'DATE_FIN_AFFECTATION'=>$DATE_FIN_AFFECTATION,'DATE_INSERTION'=>$today));

		if($result==true )
		{
			$statut=1;
		}else
		{
			$statut=2;
		}
		echo json_encode($statut);
	}
	

	function save_voiture()
	{
		// $statut=1 attribution avec succes;
		// $statut=2:possedent une autre voiture qu'on l'a deja attribuée;
		// $statut=3: attribution echoue
		$statut=3;
		// $CODE= $this->input->post('code_vehicule');
		$CODE = $this->input->post('VEHICULE_ID');
		$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');
		$CHAUFF_ZONE_AFFECTATION_ID = $this->input->post('CHAUFF_ZONE_AFFECTATION_ID');
		$DATE_DEBUT_AFFECTATION = $this->input->post('DATE_DEBUT_AFFECTATION');
		$DATE_FIN_AFFECTATION = $this->input->post('DATE_FIN_AFFECTATION');



		$data = array('CODE'=>$CODE,'CHAUFFEUR_ID'=>$CHAUFFEUR_ID,'CHAUFF_ZONE_AFFECTATION_ID'=>$CHAUFF_ZONE_AFFECTATION_ID,'DATE_DEBUT_AFFECTATION'=>$DATE_DEBUT_AFFECTATION,'DATE_FIN_AFFECTATION'=>$DATE_FIN_AFFECTATION,'STATUT_AFFECT'=>1);

		$CHAUFFEUR_VEH = $this->Model->create('chauffeur_vehicule',$data);
		$result = $this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('STATUT_VEHICULE'=>2));
		$result = $this->Model->update('vehicule',array('CODE'=>$CODE),array('STATUT'=>2));
		
		if($result==true )
		{
			$statut=1;
		}else
		{
			$statut=2;
		}
		echo json_encode($statut);
	}

	function retirer_voiture()
	{
		$statut=2;

		echo json_encode($statut);
	}


		//fonction pour retirer la voiture
	public function retirer_voit($CHAUFFEUR_ID)
	{

		$chauf_v = $this->Model->getOne('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID));
		//print($chauf['CHAUFFEUR_ID']);exit();
		
		$this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$chauf_v['CHAUFFEUR_ID']),array('STATUT_VEHICULE'=>1));

		$this->Model->update('vehicule',array('CODE'=>$chauf_v['CODE']),array('STATUT'=>1));
		// $today = date('Y-m-d H:s');
		$this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$chauf_v['CHAUFFEUR_ID']),array('STATUT_AFFECT'=>2));

		
		$data['message'] = '<div class="alert alert-success text-center" id="message">' . " Vous avez bien retiré la voiture" . '</div>';
		$this->session->set_flashdata($data);
		redirect(base_url('chauffeur/Chauffeur_New'));

		
	}
	//Fonction pour activer/desactiver un proprietaire
	function active_desactive($status,$CHAUFFEUR_ID)
	{
		if($status==1){
			$this->Model->update('chauffeur', array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('IS_ACTIVE'=>2));

		}else if($status==2){
			$this->Model->update('chauffeur', array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('IS_ACTIVE'=>1));
		}

		echo json_encode(array('status'=>$status));
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
				redirect(base_url('chauffeur/Chauffeur_New/index'));
			}
			else
			{
				$this->load->view('Chauffeur_New_Add_View',$data);
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

		//Fonction pour recuperer une ligne 
	function getOne($id)
	{
		$membre = $this->Model->getRequeteOne('SELECT CHAUFFEUR_ID, NOM, PRENOM, ADRESSE_PHYSIQUE, NUMERO_TELEPHONE, ADRESSE_MAIL, NUMERO_CARTE_IDENTITE, FILE_CARTE_IDENTITE, NUMERO_PERMIS,file_permis,GENRE_ID, PERSONNE_CONTACT_TELEPHONE, PROVINCE_ID, COMMUNE_ID, ZONE_ID, COLLINE_ID,PHOTO_PASSPORT FROM chauffeur WHERE CHAUFFEUR_ID='.$id);
		$data['membre'] = $membre;
		$data['provinces'] = $this->Model->getRequete('SELECT PROVINCE_ID, PROVINCE_NAME FROM provinces WHERE 1 ORDER BY PROVINCE_NAME ASC');
		$data['communes'] = $this->Model->getRequete('SELECT COMMUNE_ID, COMMUNE_NAME, PROVINCE_ID FROM communes WHERE  PROVINCE_ID='.$membre['PROVINCE_ID'].' ORDER BY COMMUNE_NAME ASC');
		$data['zones'] = $this->Model->getRequete('SELECT ZONE_ID ,ZONE_NAME,COMMUNE_ID FROM zones WHERE COMMUNE_ID='.$membre['COMMUNE_ID'].' ORDER BY ZONE_NAME ASC');
		$data['collines'] = $this->Model->getRequete('SELECT COLLINE_ID, COLLINE_NAME FROM collines WHERE ZONE_ID='.$membre['ZONE_ID'].' ORDER BY COLLINE_NAME ASC');
		$data['title'] = "Modification d'un chauffeur";
		$this->load->view('Chauffeur_New_Update_View',$data);
	}

	function update()
	{
		$id = $this->input->post('CHAUFFEUR_ID');
		//print_r($id);exit();

		$this->form_validation->set_rules('NOM','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('PRENOM','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('ADRESSE_PHYSIQUE','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('NUMERO_TELEPHONE','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('NUMERO_CARTE_IDENTITE','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('PROVINCE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('COMMUNE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('ZONE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('COLLINE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$FILE_CARTE_IDENTITE = $this->input->post('FILE_CARTE_IDENTITE_OLD');
		if(empty($_FILES['FILE_CARTE_IDENTITE']['name']))
		{
			$file = $this->input->post('FILE_CARTE_IDENTITE_OLD');	
		}
		else
		{
			$file = $this->upload_document($_FILES['FILE_CARTE_IDENTITE']['tmp_name'],$_FILES['FILE_CARTE_IDENTITE']['name']);
		}

		$file_permis = $this->input->post('file_permis_OLD');
		if(empty($_FILES['file_permis']['name']))
		{
			$file2 = $this->input->post('file_permis_OLD');
		}
		else
		{
			$file2 = $this->upload_document($_FILES['file_permis']['tmp_name'],$_FILES['file_permis']['name']);
		}

		$PHOTO_PASSPORT = $this->input->post('PHOTO_PASSPORT_OLD');
		if(empty($_FILES['PHOTO_PASSPORT']['name']))
		{
			$file3 = $this->input->post('PHOTO_PASSPORT_OLD');
		}
		else
		{
			$file3 = $this->upload_document($_FILES['PHOTO_PASSPORT']['tmp_name'],$_FILES['PHOTO_PASSPORT']['name']);
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->getOne($id);
		}
		else
		{
			$Array = array(
				'NOM' => $this->input->post('NOM'),
				'PRENOM' => $this->input->post('PRENOM'),
				'ADRESSE_PHYSIQUE' => $this->input->post('ADRESSE_PHYSIQUE'),
				'NUMERO_TELEPHONE' => $this->input->post('NUMERO_TELEPHONE'),
				'NUMERO_CARTE_IDENTITE' => $this->input->post('NUMERO_CARTE_IDENTITE'),
				'PERSONNE_CONTACT_TELEPHONE' => $this->input->post('PERSONNE_CONTACT_TELEPHONE'),
				'ADRESSE_MAIL' => $this->input->post('ADRESSE_MAIL'),
				'FILE_CARTE_IDENTITE' => $file,
				'PHOTO_PASSPORT' => $file2,
				'PROVINCE_ID' => $this->input->post('PROVINCE_ID'),
				'COMMUNE_ID' => $this->input->post('COMMUNE_ID'),
				'ZONE_ID' => $this->input->post('ZONE_ID'),
				'COLLINE_ID' => $this->input->post('COLLINE_ID'),
				'PHOTO_PASSPORT' => $file3
			);
			$this->Model->update('chauffeur', array('CHAUFFEUR_ID' => $id), $Array);
			$datas['message'] = '<div class="alert alert-success text-center" id="message">La modification s\'est faite avec succès</div>';
			$this->session->set_flashdata($datas);
			redirect(base_url('chauffeur/Chauffeur_New/index'));
		}
	}
	//Fonction pour le detail du chauffeur
	function detail_chauffeur($CHAUFFEUR_ID)
	{
		$query_principal=$this->Model->getRequeteOne('SELECT vehicule.VEHICULE_ID,vehicule_marque.DESC_MARQUE,vehicule_nom.DESC_MODELE,vehicule.PLAQUE,vehicule.COULEUR,vehicule.PHOTO FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_nom ON vehicule_nom.ID_NOM=vehicule.ID_NOM JOIN agent_card ON vehicule.CODE=agent_card.CARD_UID JOIN chauffeur ON agent_card.CODE_AGENT=chauffeur.CODE_AGENT WHERE chauffeur.CHAUFFEUR_ID ='.$CHAUFFEUR_ID);
     // print_r($query_principal)

		$fichier = base_url().'upload/photo_vehicule/'.$query_principal['PHOTO'].'';

		$div_info = '<img src="'.$fichier.'" height="100%"  width="100%"  style= "border-radius:50%;" />';
		$output = array(

			"DESC_MODELE" =>$query_principal['DESC_MODELE'] ,
			"PLAQUE" =>$query_principal['PLAQUE'] ,
			"COULEUR" =>$query_principal['COULEUR'] ,
			"PHOTO" =>$div_info ,

		);
		echo json_encode($output);


	}


	//liste de l'Historique des chauffeurs
	function hist_chauff()
	{

		$USER_ID=$this->session->userdata('USER_ID');
		$PROFIL_ID=$this->session->userdata('PROFIL_ID');
		
		$CHAUFFEUR_ID=$this->input->post('CHAUFFEUR_ID');
		 // print_r($CHAUFFEUR_ID);die();

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";
		$critaire = " ";
		if ($PROFIL_ID==1) {
			$critere_veh = " and chauffeur_vehicule.CHAUFFEUR_ID=".$CHAUFFEUR_ID;
		}else{
			$critere_veh = " and chauffeur_vehicule.CHAUFFEUR_ID=".$CHAUFFEUR_ID." and users.USER_ID=".$USER_ID;

		}

		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}
		$order_by = '';

		$order_column = array('','NOM_PROPRIETAIRE','PLAQUE','DESC_MARQUE','DATE_DEBUT_AFFECTATION','DATE_FIN_AFFECTATION');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
		}
		$search = !empty($_POST['search']['value']) ? (' AND (NOM_PROPRIETAIRE LIKE "%' . $var_search . '%" 
			OR PRENOM_PROPRIETAIRE LIKE "%' . $var_search . '%"
			OR chauffeur_vehicule.CODE LIKE "%' . $var_search . '%" 
			OR PLAQUE LIKE "%' . $var_search . '%" 
			OR DESC_MARQUE LIKE "%' . $var_search . '%"
			OR DESC_MODELE  LIKE "%' . $var_search . '%"
			OR concat(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE) LIKE "%' . $var_search . '%"
			OR concat(PRENOM_PROPRIETAIRE," ",NOM_PROPRIETAIRE) LIKE "%' . $var_search . '%")') : '';

		if ($PROFIL_ID==1) {
			$query_principal='SELECT chauffeur_vehicule.CODE,CHAUFFEUR_VEHICULE_ID,DATE_FORMAT(chauffeur_vehicule.`DATE_FIN_AFFECTATION`,"%d-%m-%Y") as date_fin_format,DATE_FORMAT(chauffeur_vehicule.`DATE_DEBUT_AFFECTATION`,"%d-%m-%Y") as date_deb_format,vehicule.PLAQUE,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE FROM chauffeur_vehicule join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID JOIN vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID  WHERE 1 '.$critere_veh.'';
		}else{

			$query_principal='SELECT chauffeur_vehicule.`CODE`,CHAUFFEUR_VEHICULE_ID,DATE_FORMAT(chauffeur_vehicule.`DATE_FIN_AFFECTATION`,"%d-%m-%Y") as date_fin_format,DATE_FORMAT(chauffeur_vehicule.`DATE_DEBUT_AFFECTATION`,"%d-%m-%Y") as date_deb_format,vehicule.PLAQUE,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE FROM `chauffeur_vehicule` join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID JOIN vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID join users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE 1 '.$critere_veh.'';

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
		$u=1;
		// print_r($fetch_data);die();
		foreach ($fetch_data as $row) 
		{
			

			$sub_array=array();
			$sub_array[]=$u++;
			$sub_array[] = $row->PLAQUE;
			$sub_array[] = $row->DESC_MARQUE." / ".$row->DESC_MODELE;
			$sub_array[] = $row->NOM_PROPRIETAIRE." ".$row->PRENOM_PROPRIETAIRE;
			$sub_array[] = $row->date_deb_format;
			$sub_array[] = $row->date_fin_format;
			$sub_array[]="&nbsp;<a href='".base_url('chauffeur/Chauffeur_New/tracking_chauffeur/').md5($row->CODE).'/'.md5($row->CHAUFFEUR_VEHICULE_ID)."'>&nbsp;&nbsp;&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";
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


	function tracking_chauffeur($CODE,$CHAUFFEUR_VEHICULE_ID)
	{
		$fontinfo = $this->input->post('rtoggle');

		
		$info = '';

		if($fontinfo == ''){

			$info = 'streets';

		}else{

			$info = $fontinfo;
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_date = $this->getBindParms('`DATE_DEBUT_AFFECTATION`,`DATE_FIN_AFFECTATION`', 'chauffeur_vehicule', '1 and md5(CHAUFFEUR_VEHICULE_ID)="'.$CHAUFFEUR_VEHICULE_ID.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
		$my_select_date=str_replace('\"', '"', $my_select_date);
		$my_select_date=str_replace('\n', '', $my_select_date);
		$my_select_date=str_replace('\"', '', $my_select_date);
		$date_affectation = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date);

		$my_select_heure_trajet = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', '1', '`HEURE_ID` ASC');
		$heure_trajet = $this->ModelPs->getRequete($proce_requete, $my_select_heure_trajet);

		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND md5(chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID) ="'.$CHAUFFEUR_VEHICULE_ID.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
		$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);


		$my_selectvehicule = $this->getBindParms('VEHICULE_ID,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,vehicule.CODE,vehicule.KILOMETRAGE', 'vehicule join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND md5(vehicule.CODE) ="'.$CODE.'"', '`VEHICULE_ID` ASC');
		$my_selectvehicule=str_replace('\"', '"', $my_selectvehicule);
		$my_selectvehicule=str_replace('\n', '', $my_selectvehicule);
		$my_selectvehicule=str_replace('\"', '', $my_selectvehicule);
		$get_vehicule = $this->ModelPs->getRequeteOne($proce_requete, $my_selectvehicule);

		$data['info'] = $info;
		$data['heure_trajet']=$heure_trajet;
		$data['get_chauffeur']=$get_chauffeur;
		$data['get_vehicule']=$get_vehicule;
		$data['date_affectation']=$date_affectation;
		$data['CODE_VEH']=$CODE;
		$data['CHAUFFEUR_VEHICULE_ID']=$CHAUFFEUR_VEHICULE_ID;



		$this->load->view('Tracking_View',$data);
		
	}

	//Fonction pour les filtres
	function tracking_chauffeur_filtres(){

		$fontinfo = $this->input->post('rtoggle');
		$DATE_SELECT = $this->input->post('DATE_DAT');
		$DATE_DAT_FIN = $this->input->post('DATE_DAT_FIN');	
		$CODE = $this->input->post('CODE');
		$HEURE1 = $this->input->post('HEURE1');
		$HEURE2 = $this->input->post('HEURE2');
		$CODE_COURSE = $this->input->post('CODE_COURSE');
		$CHAUFFEUR_VEHICULE_ID = $this->input->post('CHAUFFEUR_VEHICULE_ID');

		// print_r($CHAUFFEUR_VEHICULE_ID);die();

		$distance_finale=0;
		$distance_arrondie=0;
		$score_finale=0;
		$critere='';
		$critere1='';


		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_heure1 = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', 'HEURE_ID="'.$HEURE1.'"', '`HEURE_ID` ASC');
		$my_select_heure1=str_replace('\"', '"', $my_select_heure1);
		$my_select_heure1=str_replace('\n', '', $my_select_heure1);
		$my_select_heure1=str_replace('\"', '', $my_select_heure1);
		$heure_select1 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_heure1);


		$my_select_heure2 = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', 'HEURE_ID="'.$HEURE2.'"', '`HEURE_ID` ASC');
		$my_select_heure2=str_replace('\"', '"', $my_select_heure2);
		$my_select_heure2=str_replace('\n', '', $my_select_heure2);
		$my_select_heure2=str_replace('\"', '', $my_select_heure2);
		$my_select_heure2 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_heure2);

		if(!empty($DATE_SELECT) && !empty($DATE_DAT_FIN)){

			$critere.=' AND date_format(tracking_data.date,"%Y-%m-%d")between "'.$DATE_SELECT.'" AND "'.$DATE_DAT_FIN.'" ';


		}

		if (!empty($HEURE1) && !empty($HEURE2)) 
		{
			$critere.=' AND date_format(tracking_data.`date`,"%H:%i:%s") between "'.$heure_select1['HEURE'].'" AND "'.$my_select_heure2['HEURE'].'" ';
			

		}

		if (!empty($CODE_COURSE)) 
		{
			$critere1.=' AND md5(tracking_data.CODE_COURSE) ="'.$CODE_COURSE.'"';
			

		}
		

		$info = '';

		if($fontinfo == ''){

			$info = 'streets';

		}else{

			$info = $fontinfo;
		}

		$data['info'] = $info;

		// requete pour recuperer le chauffeur
		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,KILOMETRAGE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND md5(chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID) ="'.$CHAUFFEUR_VEHICULE_ID.'"', 'vehicule.`PROPRIETAIRE_ID` ASC');
		$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);


		//requete pour recuperer tout le trajet parcouru
		$my_selectget_data = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date', 'tracking_data', ' md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\n', '', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '', $my_selectget_data);
		$get_data = $this->ModelPs->getRequete($proce_requete, $my_selectget_data);

		//Requete pour recuperer partout ou il ya eu exces de vitesse
		$my_selectget_exces_vitesse = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H %i") as hour', 'tracking_data', ' vitesse >= 50 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_exces_vitesse=str_replace('\"', '"', $my_selectget_exces_vitesse);
		$my_selectget_exces_vitesse=str_replace('\"', '"', $my_selectget_exces_vitesse);
		$my_selectget_exces_vitesse=str_replace('\n', '', $my_selectget_exces_vitesse);
		$my_selectget_exces_vitesse=str_replace('\"', '', $my_selectget_exces_vitesse);
		$get_data_exces_vitesse = $this->ModelPs->getRequete($proce_requete, $my_selectget_exces_vitesse);

		////Requete pour recuperer partout ou il ya eu accident
		$my_selectget_accident= $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H %i") as hour', 'tracking_data', ' accident=1 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_accident=str_replace('\"', '"', $my_selectget_accident);
		$my_selectget_accident=str_replace('\"', '"', $my_selectget_accident);
		$my_selectget_accident=str_replace('\n', '', $my_selectget_accident);
		$my_selectget_accident=str_replace('\"', '', $my_selectget_accident);
		$get_data_accident = $this->ModelPs->getRequete($proce_requete, $my_selectget_accident);
		
		//requete pour recuperer les arrets
		$my_selectget_arret = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H:%i") as heure', 'tracking_data', ' ignition=0 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_arret=str_replace('\"', '"', $my_selectget_arret);
		$my_selectget_arret=str_replace('\"', '"', $my_selectget_arret);
		$my_selectget_arret=str_replace('\n', '', $my_selectget_arret);
		$my_selectget_arret=str_replace('\"', '', $my_selectget_arret);
		$get_arret = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret);


		$distance=0;

		//Calcul de la distance parcourue
		if(!empty($get_data)){



			$i=0;
			foreach ($get_data as $value_get_arret) {

				

				if ($value_get_arret['ignition']==1) {

					if ($i==0) {
						$pointdepart=[$value_get_arret['latitude'],$value_get_arret['longitude']];

					}
					else{
						$pointactuel=[$value_get_arret['latitude'],$value_get_arret['longitude']];


						$distance+=$this->Model->getDistance($pointdepart['0'],$pointdepart['1'],$pointactuel['0'],$pointactuel['1']);

					}

				}else{
					$pointdepart=[$value_get_arret['latitude'],$value_get_arret['longitude']];



				}
				$i++;

			}

			$distance_finale=$distance;
			$distance_arrondie=round($distance_finale);


		}


		//calcul de la distance	par filtre	
		$my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.'  AND ignition=0' , '`id` ASC');
		$my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);
		$get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);
		$nvldistance=0;

		$my_selectmin_arret = $this->getBindParms('MIN(id) as minimum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.' ' , '`id` ASC');
		$my_selectmin_arret=str_replace('\"', '"', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\n', '', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\"', '', $my_selectmin_arret);
		$min_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmin_arret);

		$my_selectmax_arret = $this->getBindParms('MAX(id) as maximum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.'' , '`id` ASC');
		$my_selectmax_arret=str_replace('\"', '"', $my_selectmax_arret);
		$my_selectmax_arret=str_replace('\n', '', $my_selectmax_arret);
		$my_selectmax_arret=str_replace('\"', '', $my_selectmax_arret);
		$max_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmax_arret);


		$my_selectmin_arret_plus = $this->getBindParms('id as arret_min_deux', 'tracking_data', '1 AND id = (SELECT MIN(id) FROM tracking_data WHERE id NOT IN (SELECT MIN(id) FROM tracking_data)) AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.'' , '`id` ASC');
		$my_selectmin_arret_plus=str_replace('\"', '"', $my_selectmin_arret_plus);
		$my_selectmin_arret_plus=str_replace('\n', '', $my_selectmin_arret_plus);
		$my_selectmin_arret_plus=str_replace('\"', '', $my_selectmin_arret_plus);
		$min_arret_plus = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmin_arret_plus);
		// $min_arret_plus=$min_arret['minimum']+1;

		// print_r($max_arret);die();
		$nvldistance_arrondie=0;
		if(!empty($min_arret) && !empty($min_arret_plus) && !empty($max_arret)){

			for ($i=$min_arret['minimum'],$j=$min_arret_plus['arret_min_deux']; $i <$max_arret['maximum'],$j <$max_arret['maximum'] ; $i++,$j++) {

				$my_selectarret1= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$i.'"' , '`id` ASC');
				$my_selectarret1=str_replace('\"', '"', $my_selectarret1);
				$my_selectarret1=str_replace('\n', '', $my_selectarret1);
				$my_selectarret1=str_replace('\"', '', $my_selectarret1);

				$point_distance = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret1);

				$my_selectarret2= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$j.'"' , '`id` ASC');
				$my_selectarret2=str_replace('\"', '"', $my_selectarret2);
				$my_selectarret2=str_replace('\n', '', $my_selectarret2);
				$my_selectarret2=str_replace('\"', '', $my_selectarret2);

				$point_distance2 = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret2);
				if(!empty($point_distance) && !empty($point_distance2)){

					$nvldistance+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
				}else{

					$nvldistance+=0;
				} 


			}
			$nvldistance_arrondie=round($nvldistance);
		}
		

		//Calcul du score
		$point_final=20;
		$point_point=20;
		
		if(!empty($get_arret_date)){

			$data_arret=array();

			foreach ($get_arret_date as $keyget_arret) {
				$data_arret[]=$keyget_arret['id'];

			}

			$nbre=count($data_arret);
			$valeur_valeur=array();
			$point_distance_fin=array();
			for($i=0,$j=1;$i<$nbre,$j<$nbre;$i++,$j++){

				$my_selectrequete = $this->getBindParms('count(id) as idsup', 'tracking_data', '1 AND vitesse >= 50 AND tracking_data.id between "'.$data_arret[$i].'" AND "'.$data_arret[$j].'"' , '`id` ASC');
				$my_selectrequete=str_replace('\"', '"', $my_selectrequete);
				$my_selectrequete=str_replace('\n', '', $my_selectrequete);
				$my_selectrequete=str_replace('\"', '', $my_selectrequete);
				$requete = $this->ModelPs->getRequete($proce_requete, $my_selectrequete);
				$valeur_valeur[]=$requete;


			}

			$add=0;

			foreach ($valeur_valeur as $keyvaleur_valeur) {
				foreach ($keyvaleur_valeur as $keykeyvaleur_valeur) {

					foreach ($keykeyvaleur_valeur as $final_final) {

						if($final_final>0){

							$add=$add+1;
						}
					}

				}
				
			}

			$point_final=$point_point-$add;

		}
		if(!empty($CODE_COURSE)){
			$my_selectrequete_excsee = $this->getBindParms('id', 'tracking_data', '1 AND vitesse >= 50 AND md5(tracking_data.CODE_COURSE)= "'.$CODE_COURSE.'"' , '`id` ASC');
			$my_selectrequete_excsee=str_replace('\"', '"', $my_selectrequete_excsee);
			$my_selectrequete_excsee=str_replace('\n', '', $my_selectrequete_excsee);
			$my_selectrequete_excsee=str_replace('\"', '', $my_selectrequete_excsee);
			$requete_exces = $this->ModelPs->getRequete($proce_requete, $my_selectrequete_excsee);
			 // print_r($requete_exces);die();
			if(!empty($requete_exces)){

				$point_final=$point_point-1;
				
			}
		}

		//le Resume des courses se trouvant sur la carte
		$card_card='';		
		$get_data_arret_prime = $this->Model->getRequete('SELECT CODE_COURSE FROM tracking_data WHERE md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' GROUP BY CODE_COURSE');
		$tabl=' ';
		$mark_v='';
		$mark_vprim='';
		if (!empty($CODE_COURSE) && !empty($get_data_arret_prime)) {
			$tabl_prime=array();

			foreach ($get_data_arret_prime as $value_get_arret_prim) {
				$my_selectone_element_prim = $this->getBindParms('id,date as date_actu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,ignition,latitude,longitude', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_prim['CODE_COURSE'].'" ' , '`id` ASC');
				$my_selectone_element_prim=str_replace('\"', '"', $my_selectone_element_prim);
				$my_selectone_element_prim=str_replace('\n', '', $my_selectone_element_prim);
				$my_selectone_element_prim=str_replace('\"', '', $my_selectone_element_prim);

				$one_element_prim = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element_prim);

				
				$my_select_date_compare2_prim = $this->getBindParms('date as date_actu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,latitude,longitude,date_format(tracking_data.date,"%d %m") as day_month', 'tracking_data', ' CODE_COURSE="'.$value_get_arret_prim['CODE_COURSE'].'" ', 'id DESC');
				$my_select_date_compare2_prim=str_replace('\"', '"', $my_select_date_compare2_prim);
				$my_select_date_compare2_prim=str_replace('\n', '', $my_select_date_compare2_prim);
				$my_select_date_compare2_prim=str_replace('\"', '', $my_select_date_compare2_prim);
				$date_compare2_prim = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date_compare2_prim);
				$tabl_prime[]=[$this->notifications->ago($one_element_prim['date_actu'],$date_compare2_prim['date_actu']),$one_element_prim['CODE_COURSE'],$one_element_prim['date_actu'],$date_compare2_prim['date_actu'],$one_element_prim['hour'],$one_element_prim['sec'],$date_compare2_prim['hour'],$date_compare2_prim['sec'],$one_element_prim['latitude'],$one_element_prim['longitude'],$date_compare2_prim['latitude'],$date_compare2_prim['longitude'],$one_element_prim['ignition'],$one_element_prim['day_month'],$date_compare2_prim['day_month']];
				


			}

			if (!empty($tabl_prime)) {
				foreach ($tabl_prime as $keytablprim) {

					$mark_vprim=$mark_vprim.$keytablprim[9].'<>'.$keytablprim[8].'<>'.$keytablprim[11].'<>'.$keytablprim[10].'<>'.$keytablprim[12].'<>'.$keytablprim[4].'<>'.$keytablprim[6].'<>@';

				}
			}
		}
		$distdislegend=0;
		$get_data_arret = $this->Model->getRequete('SELECT CODE_COURSE FROM tracking_data WHERE CODE_COURSE IS NOT NULL and  md5(device_uid) ="'.$CODE.'" '.$critere.'  GROUP BY CODE_COURSE');
		$dataplace = '';
		$dataplace1 = '';
		$card_card1='';
		//calcul du temps d'arret
		if(!empty($get_data_arret)){
			$tabl=array();
			$dist_geofenc=array();
			$depasse_zone=0;
			foreach ($get_data_arret as $value_get_arret_code) {
				$my_selectone_element = $this->getBindParms('id,tracking_data.date as date_vu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,md5(CODE_COURSE) as code_course_crypt,ignition,latitude,longitude,CEINTURE,CLIM', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_code['CODE_COURSE'].'" ' , '`id` ASC');
				$my_selectone_element=str_replace('\"', '"', $my_selectone_element);
				$my_selectone_element=str_replace('\n', '', $my_selectone_element);
				$my_selectone_element=str_replace('\"', '', $my_selectone_element);
				$one_element = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element);

				$my_select_date_compare2 = $this->getBindParms('id,tracking_data.date as date_vu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,latitude,longitude,date_format(tracking_data.date,"%d %m") as day_month', 'tracking_data', ' CODE_COURSE="'.$value_get_arret_code['CODE_COURSE'].'" ', 'id DESC');
				$my_select_date_compare2=str_replace('\"', '"', $my_select_date_compare2);
				$my_select_date_compare2=str_replace('\n', '', $my_select_date_compare2);
				$my_select_date_compare2=str_replace('\"', '', $my_select_date_compare2);
				$date_compare2 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date_compare2);

				$my_selectone_element_moins = $this->getBindParms('id', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_code['CODE_COURSE'].'" AND id > "'.$one_element['id'].'" ' , '`id` ASC');
				$my_selectone_element_moins=str_replace('\"', '"', $my_selectone_element_moins);
				$my_selectone_element_moins=str_replace('\n', '', $my_selectone_element_moins);
				$my_selectone_element_moins=str_replace('\"', '', $my_selectone_element_moins);

				$min_arret_plus_plus = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element_moins);

				
				// print_r($date_compare2);die();
				if(!empty($one_element) && !empty($min_arret_plus_plus) && !empty($date_compare2)){

					for ($i=$one_element['id'],$j=$min_arret_plus_plus['id']; $i <$date_compare2['id'],$j <$date_compare2['id'] ; $i++,$j++) {


						$my_selectarret1= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$i.'"' , '`id` ASC');
						$my_selectarret1=str_replace('\"', '"', $my_selectarret1);
						$my_selectarret1=str_replace('\n', '', $my_selectarret1);
						$my_selectarret1=str_replace('\"', '', $my_selectarret1);

						$point_distance = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret1);

						$my_selectarret2= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$j.'"' , '`id` ASC');
						$my_selectarret2=str_replace('\"', '"', $my_selectarret2);
						$my_selectarret2=str_replace('\n', '', $my_selectarret2);
						$my_selectarret2=str_replace('\"', '', $my_selectarret2);

						$point_distance2 = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret2);
						if(!empty($point_distance) && !empty($point_distance2)){

							$distdislegend+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
						}else{

							$distdislegend+=0;

						}


					}

				}
				

				//geofence

				$my_select_geo_el = $this->getBindParms('id,tracking_data.date as date_vu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,md5(CODE_COURSE) as code_course_crypt,ignition,latitude,longitude,CEINTURE,CLIM', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_code['CODE_COURSE'].'" ' , '`id` ASC');
				$my_select_geo_el=str_replace('\"', '"', $my_select_geo_el);
				$my_select_geo_el=str_replace('\n', '', $my_select_geo_el);
				$my_select_geo_el=str_replace('\"', '', $my_select_geo_el);

				$elt_geofence_course = $this->ModelPs->getRequete($proce_requete, $my_select_geo_el);

				$my_selectdelim = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID,COORD', 'chauffeur_vehicule join chauffeur_zone_affectation on chauffeur_zone_affectation.CHAUFFEUR_VEHICULE_ID =chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ', '1 AND md5(chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID)="'.$CHAUFFEUR_VEHICULE_ID.'" ' , 'chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ASC');
				$my_selectdelim=str_replace('\"', '"', $my_selectdelim);
				$my_selectdelim=str_replace('\n', '', $my_selectdelim);
				$my_selectdelim=str_replace('\"', '', $my_selectdelim);
				$zones_delim = $this->ModelPs->getRequeteOne($proce_requete, $my_selectdelim);

				$COORD_POLYGONE = $zones_delim['COORD'];
				$COORD_POLYGONE = str_replace('[[', '', $COORD_POLYGONE);
				$COORD_POLYGONE = str_replace(']]', '', $COORD_POLYGONE);

				$COORD_POLYGONE_P = $COORD_POLYGONE;

				$COORD_POLY = $COORD_POLYGONE_P;

				$COORD_POLY = explode('],[', $COORD_POLY);
				$COORD_POLY = str_replace('[', '', $COORD_POLY);
				$COORD_POLY = str_replace(']', '', $COORD_POLY);

				$COORD_POLY_SEND=array();

				for ($i=0; $i < count($COORD_POLY); $i++) { 


					$COORD_POLY2 = $COORD_POLY[$i];

					$COORD_POLY2 = str_replace(']', '', $COORD_POLY2);
					$COORD_POLY2 = str_replace('[', '', $COORD_POLY2);

					$COORD_POLY2 = explode(',', $COORD_POLY2);

					$COORD_POLY_SEND[].= $COORD_POLY2[0].','.$COORD_POLY2[1];

				}
				
				$polygon_die = array();
				foreach ($COORD_POLY_SEND as $coord) {
					list($x, $y) = explode(",", $coord);
					$polygon_die[] = array((string)$x, (string)$y);
				}
				$COORD_POLY_repl = str_replace('[', '', $polygon_die);
				
				foreach ($elt_geofence_course as $key_geo) {

					
					$point_check=array($key_geo['longitude'],$key_geo['latitude']);
					$response[]=$this->Model->isPointInsidePolygon($point_check, $COORD_POLY_repl);

				}

				foreach ($response as $keyresponse) {
					if($keyresponse==1){
						$depasse_zone=1;
					}else{
						$depasse_zone=2;
					}
				}

				$tabl[]=[$this->notifications->ago($one_element['date_vu'],$date_compare2['date_vu']),$one_element['code_course_crypt'],$one_element['date_vu'],$date_compare2['date_vu'],$one_element['hour'],$one_element['sec'],$date_compare2['hour'],$date_compare2['sec'],$one_element['latitude'],$one_element['longitude'],$date_compare2['latitude'],$date_compare2['longitude'],$one_element['ignition'],$one_element['day_month'],$date_compare2['day_month'],round($distdislegend),$one_element['CEINTURE'],$one_element['CLIM'],$depasse_zone];


			}

			$data['tabl'] = $tabl;


			$v=1;
			if (!empty($tabl)) 
			{

				$getplacesname = 0;
				$getplacesname1 = 0;

				foreach ($tabl as $keytabl) 
				{

					$getplacesname++;
					$getplacesname1++;


					$mark_v=$mark_v.$keytabl[9].'<>'.$keytabl[8].'<>'.$keytabl[11].'<>'.$keytabl[10].'<>'.$keytabl[12].'<>@';


					if($keytabl[16]==1){
						// $valeur_ceinture='<div class="fa fa-check" style="color:green"></div>';
						$valeur_ceinture='<label style="color:green">ON</label>';

					}else{
						// $valeur_ceinture='<div class="fa fa-close" style="color:red"></div>';
						$valeur_ceinture='<label style="color:#dc3545">OFF</label>';

					}
					if($keytabl[17]==1){
						// $valeur_clim='<div class="fa fa-check" style="color:green"></div>';
						$valeur_clim='<label style="color:green">ON</label>';

					}else{
						// $valeur_clim='<div class="fa fa-close" style="color:red"></div>';
						$valeur_clim='<label style="color:#dc3545">OFF</label>';

					}
					$lat = $keytabl[8];
					$lng = $keytabl[9];

					if($keytabl[18]==1){
						$ch_color='border: solid 1px rgba(128, 128, 128, 0.3);';
						$alert='';

					}else{
						$ch_color='border: solid 1px rgba(220, 53, 69, 1);';
						$card_card1='<div class="card">
						<center><h5 class="card-title" style="font-size: .8rem; color:#dc3545;"><span class="fa fa-warning text-danger"></span> Il a dépassé la zone<span style="font-size: .8rem;"></span></h5></center>

						</div>';
						$alert='<div style="top: 10px;position: absolute;right: 10px;font-size: .8rem; color:#dc3545;"><span class="fa fa-warning text-danger"></span></div>';
					}

					$dataplace.= '<script>

					$(document).ready(function() {


						var url ="https://api.mapbox.com/geocoding/v5/mapbox.places/'.$lng.','.$lat.'.json?access_token=pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A";
						var url1 ="https://api.mapbox.com/geocoding/v5/mapbox.places/'.$keytabl[11].','.$keytabl[10].'.json?access_token=pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A";

						$.get(url, function(data){

							var feature = data.features[0];
							var name = feature.text;
							var type = feature.type;


							$("#getplacesname'.$getplacesname.'").html(name)

							console.log("Les donnees ",data)

							});

							$.get(url1, function(data){

								var feature = data.features[0];
								var name = feature.text;
								var type = feature.type;

								$("#getplacesname1'.$getplacesname1.'").html(name)

								console.log("Les donnees ",data)

								});

								});

								</script>';





								if ($keytabl[12]==1) {

									$card_card.='<div class="card" onclick="change_carte(\''.$keytabl[1].'\')">
									<div class="jss408" style="'.$ch_color.'">
									<div class="jss491" style="cursor: pointer;">
									<div class="jss490">
									<div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation0 css-wmagas" style="margin: 4px 7px 7px auto; color: grey; font-size: 11px;">
									<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="pen-to-square" class="svg-inline--fa fa-pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="font-size: 18px;"><path fill="currentColor" d="M386.7 22.63C411.7-2.365 452.3-2.365 477.3 22.63L489.4 34.74C514.4 59.74 514.4 100.3 489.4 125.3L269 345.6C260.6 354.1 249.9 359.1 238.2 362.7L147.6 383.6C142.2 384.8 136.6 383.2 132.7 379.3C128.8 375.4 127.2 369.8 128.4 364.4L149.3 273.8C152 262.1 157.9 251.4 166.4 242.1L386.7 22.63zM454.6 45.26C442.1 32.76 421.9 32.76 409.4 45.26L382.6 72L440 129.4L466.7 102.6C479.2 90.13 479.2 69.87 466.7 57.37L454.6 45.26zM180.5 281L165.3 346.7L230.1 331.5C236.8 330.2 242.2 327.2 246.4 322.1L417.4 152L360 94.63L189 265.6C184.8 269.8 181.8 275.2 180.5 281V281zM208 64C216.8 64 224 71.16 224 80C224 88.84 216.8 96 208 96H80C53.49 96 32 117.5 32 144V432C32 458.5 53.49 480 80 480H368C394.5 480 416 458.5 416 432V304C416 295.2 423.2 288 432 288C440.8 288 448 295.2 448 304V432C448 476.2 412.2 512 368 512H80C35.82 512 0 476.2 0 432V144C0 99.82 35.82 64 80 64H208z"></path>
									</svg>
									</div>
									</div>
									<div class="jss503">
									<div class="jss509">'.$alert.'
									<div class="jss511"><sup class="jss507">'.$keytabl[13].'</sup><span class="jss510 jss512">'.$keytabl[4].'<span class="jss494">:'.$keytabl[5].'&nbsp;</span></span><span class="jss517"><label id="getplacesname'.$getplacesname.'"></label></span></div><div class="jss513">'.$keytabl[0].'<span style="float: right;">'.$keytabl[15].' km</span></div><div class="jss511"><sup class="jss507">'.$keytabl[14].'</sup><span class="jss510 jss514">'.$keytabl[6].'<span class="jss494">:'.$keytabl[7].'</span></span><span class="jss518"><label id="getplacesname1'.$getplacesname1.'"></label></span>
									</div>
									</div>
									<span class="jss510" style="color:#7D7E7F;">Ceinture<span class="jss494">&nbsp;&nbsp;'.$valeur_ceinture.'</span></span><span class="jss518" style="color:#7D7E7F;">Climatiseur&nbsp;&nbsp;'.$valeur_clim.'</span>
									</div>							
									</div>
									</div>
									</div>';
								}
								elseif ($keytabl[12]==0) 
								{
									$card_card.='<div class="card"  onclick="change_carte(\''.$keytabl[1].'\')">
									<div class="jss110" style="cursor: pointer;'.$ch_color.'" >'.$alert.'
									<div class="jss111">
									<div class="jss112" style="width: 78px; font-size: 11px; font-weight: 500;"><p><sup class="jss500 jss501"> '.$keytabl[13].'</sup>'.$keytabl[4].'<span class="jss119">:'.$keytabl[5].'</span></p><span style="display: block; height: 2px;"></span><p style="position: relative;"><sup class="jss500 jss501">'.$keytabl[14].'</sup>'.$keytabl[6].'<span class="jss119">:'.$keytabl[7].'&nbsp;</span></p>
									</div>
									<div class="jss112 jss113" style="width: 61%;"><span class="jss114" style="padding: 0px;"> <label id="getplacesname'.$getplacesname.'"></label></span><p class="jss515">'.$keytabl[0].' d\'arrêt </p>
									</div>
									</div>
									</div>
									</div>
									';


								}



								$v++;
							}

						}



					}



					//calcul du carburant consommé
					if(!empty($get_chauffeur['KILOMETRAGE'])){

						$carburant_before=$get_chauffeur['KILOMETRAGE'] * $nvldistance_arrondie;

						$carburant = round($carburant_before);


					}else{

						$carburant='N/A  ';
					}



					//delimitation : geofencing
					$my_selectprovinces = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID,COORD', 'chauffeur_vehicule join chauffeur_zone_affectation on chauffeur_zone_affectation.CHAUFFEUR_VEHICULE_ID =chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ', '1 AND md5(chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID) ="'.$CHAUFFEUR_VEHICULE_ID.'" ' , 'chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ASC');
					$my_selectprovinces=str_replace('\"', '"', $my_selectprovinces);
					$my_selectprovinces=str_replace('\n', '', $my_selectprovinces);
					$my_selectprovinces=str_replace('\"', '', $my_selectprovinces);
					$provinces_delim = $this->ModelPs->getRequete($proce_requete, $my_selectprovinces);
					$limites='';
					if(!empty($provinces_delim)){

						foreach ($provinces_delim as $keyprovinces_delim) {
							// $limites.=$keyprovinces_delim['COORD'];

							$limites.="{
								'type': 'Feature',
								'properties': {
									'osm_id': '1694988',
									'osm_way_id': null,
									'boundary': 'administrative',
									'admin_leve': '4',
									'name': 'Bujumbura Mairie',
									'ref_COG': null
									},
									'geometry': {
										'type': 'MultiPolygon',
										'coordinates': [".$keyprovinces_delim['COORD']."]
									}
									},
									";

								}

							}
							$i=1;

					//carte
							$my_selectvitesse_max= $this->getBindParms(' MAX(vitesse) AS max_vitesse', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
							$my_selectvitesse_max=str_replace('\"', '"', $my_selectvitesse_max);
							$my_selectvitesse_max=str_replace('\n', '', $my_selectvitesse_max);
							$my_selectvitesse_max=str_replace('\"', '', $my_selectvitesse_max);

							$vitesse_max = $this->ModelPs->getRequeteOne($proce_requete, $my_selectvitesse_max);

							if(empty($vitesse_max['max_vitesse']))

							{
								$vitesse_max['max_vitesse']=0;
							}




							$track = '';
							$vitesse_exces = '';
							$geojsonexces='';

							if ($CODE_COURSE!='') {
								if(!empty($get_data)){


									foreach ($get_data as $key) {

										$track.='['.$key['longitude'].','.$key['latitude'].'],';
									}



								}else{
									$number='1';

									$track.='['.$number.','.$number.'],';


								}

								$track.='@';

								$track = str_replace(',@', "", $track);


								if (!empty($get_data_exces_vitesse)) {

									foreach ($get_data_exces_vitesse as $value_data_exces_vitesse) {
										$vitesse_exces.='['.$value_data_exces_vitesse['longitude'].','.$value_data_exces_vitesse['latitude'].'],';
										$geojsonexces.="{
											'type': 'Feature',
											'properties': {
												'description':
												'<span class=\"fa fa-warning\">&nbsp;&nbsp;Excès de vitesse</span>&nbsp;&nbsp;".$value_data_exces_vitesse['vitesse']." Km/h<br><i class=\"fa fa-clock-o \">&nbsp;&nbsp;".$value_data_exces_vitesse['hour']."</p>'
												},
												'geometry': {
													'type': 'Point',
													'coordinates': [".$value_data_exces_vitesse['longitude'].", ".$value_data_exces_vitesse['latitude']."]
												}
												},
												" ;



											}
											$vitesse_exces.='@';

											$vitesse_exces = str_replace(',@', "", $vitesse_exces);
										}else{

											$vitesse_exces.='[1,1],';

										}
							// $vitesse_exces.='@';
							// $vitesse_exces = str_replace(',@', "", $vitesse_exces);
									}else{

										$track.= '';
										$vitesse_exces.= '[1,1]';


									}
									$accident='';
									$geojsonaccident='';
									if(!empty($track)){
										if(!empty($get_data_accident)){
											foreach ($get_data_accident as $keyget_data_accident) {
												$accident.='['.$keyget_data_accident['longitude'].','.$keyget_data_accident['latitude'].'],';
												$geojsonaccident.="{
													'type': 'Feature',
													'properties': {
														'description':
														'<font  style=\"color:red;\">Accident !</font><br><i class=\"fa fa-clock-o\" style=\"color:red;\">&nbsp;&nbsp;".$keyget_data_accident['hour']."</i>'
														},
														'geometry': {
															'type': 'Point',
															'coordinates': [".$keyget_data_accident['longitude'].", ".$keyget_data_accident['latitude']."]
														}
														},
														" ;

														$accident.='@';

														$accident = str_replace(',@', "", $accident);
													}
												}else{

													$accident.='[1,1]';
												}
											}




											$data['geojsonaccident'] = $geojsonaccident;
											$data['track'] = $track;
											$data['get_chauffeur'] = $get_chauffeur;
											$data['get_arret'] = $get_arret;
											$data['distance_finale'] = $nvldistance_arrondie;
											$data['carburant'] = $carburant;
											$data['CODE'] = $CODE;
											$data['DATE'] = $DATE_SELECT;
											$data['score'] = $score_finale;
											$data['limites']=$limites;
											$data['card_card']=$card_card;
											$data['tabl']=$tabl;
											$data['mark_vprim']=$mark_vprim;
											$data['dataplace']=$dataplace;
											$data['vitesse_exces'] = $vitesse_exces;
											$data['geojsonexces'] = $geojsonexces;
											$data['card_card1'] = $card_card1;

											$map_filtre = $this->load->view('Maptracking_view',$data,TRUE);

											$output = array(
												"distance_finale" => $nvldistance_arrondie,
												"carburant" => $carburant,
												"DATE"=>$DATE_SELECT,
												"CODE"=>$CODE,
												"map_filtre"=>$map_filtre,
												"score_finale"=>$point_final,
												"vitesse_max"=>$vitesse_max['max_vitesse'],
										// "ligne_arret"=>$ligne_arret


											);

											echo json_encode($output);

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