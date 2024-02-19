<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 30/01/2024
*/
	class Chauffeur extends CI_Controller
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
			$this->load->view('Chauffeur_List_View');
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

			 $query_principal='SELECT CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID  WHERE 1';


        // $query_principal='SELECT DISTINCT(chauffeur.CHAUFFEUR_ID),chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,vehicule.VEHICULE_ID,vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.COULEUR,vehicule.PHOTO,chauffeur.DATE_NAISSANCE,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE  FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID   join chauffeur_vehicule on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join  vehicule on chauffeur_vehicule.CODE=vehicule.CODE join vehicule_marque on vehicule.ID_MARQUE=vehicule_marque.ID_MARQUE join vehicule_modele on vehicule.ID_MODELE=vehicule_modele.ID_MODELE WHERE 1';


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
			foreach ($fetch_data as $row) {
				$sub_array=array();
				$sub_array[]=$u++;
				
			$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->CHAUFFEUR_ID. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/agent/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->NOM . ' ' . $row->PRENOM . '</td></tr></tbody></a>';
				// $sub_array[] = $row->ADRESSE_PHYSIQUE;
				$sub_array[] = $row->PROVINCE_NAME;
				$sub_array[] = $row->COMMUNE_NAME;
				$sub_array[] = $row->ZONE_NAME;
				$sub_array[] = $row->COLLINE_NAME;
				$sub_array[] = $row->NUMERO_TELEPHONE;
				$sub_array[] = $row->ADRESSE_MAIL;
				// $sub_array[] = $row->NUMERO_CARTE_IDENTITE;
				// $sub_array[] = $row->PERSONNE_CONTACT_TELEPHONE;
				// $sub_array[] = $row->DATE_INSERTION;

				// $sub_array[]=' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->CHAUFFEUR_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/client/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> </td></tr></tbody></a>';

				// $sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE))."&nbsp;<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><label class='text-center bi bi-eye'></a>";

				$option = '<div class="dropdown ">
				<a class=" text-black btn-sm" data-toggle="dropdown">
				<i class="bx bx-dots-vertical-rounded"></i>
				
				
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-left">
				';

				$option .= "<li><a class='btn-md' href='" . base_url('chauffeur/Chauffeur/getOne/'. $row->CHAUFFEUR_ID) . "'><font color='green'><label class='text-info text-dark'>&nbsp;&nbsp;Modifier</label></font></a></li>";

				// $option.='<li><a class="btn-md" onClick="attribue_voiture(\''.$row->CHAUFFEUR_ID.'\')"><label class="text-dark"style="font-weight:bold">&nbsp;&nbsp;Attribuer la voiture</label></a></li>';

	        if($row->STATUT_VEHICULE==1 && $row->IS_ACTIVE==1)
			{
			  $option.='<li><a class="btn-md" onClick="attribue_voiture(\''.$row->CHAUFFEUR_ID.'\')"><label class="text-dark"style="font-weight:bold">&nbsp;&nbsp;Attribuer la voiture</label></a></li>';
			}

			if ($row->STATUT_VEHICULE==2 && $row->IS_ACTIVE==1)
			{
		      $option .= "<li><a data-toggle='modal' data-target='#modal_retirer" . $row->CHAUFFEUR_ID . "'><label class='text-dark'style='font-weight:bold'>&nbsp;&nbsp;Retirer la voiture</label></a></li>";

			}
			if($row->IS_ACTIVE==1){
				$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">
				<label class="switch"> 
				<input type="checkbox" id="myCheck" onclick="myFunction_desactive(' . $row->CHAUFFEUR_ID . ')" checked>
				<span class="slider round"></span>
				</label>
				</form>

				';
			}else{
				$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">
				<label class="switch"> 
				<input type="checkbox" id="myCheck" onclick="myFunction(' . $row->CHAUFFEUR_ID . ')">
				<span class="slider round"></span>
				</label>
				</form>';
			}

			$option.= "<a hre='#' data-toggle='modal' data-target='#info_chauf" . $row->CHAUFFEUR_ID. "' style = 'margin-left:50px;text-decoration:none;color:black;' ><b class='text-dark' >Détail</b></a>";
        		//modal pour retirer la voiture
			$option .= " </ul>
			</div>
			<div class='modal fade' id='modal_retirer" .$row->CHAUFFEUR_ID. "'>
			<div class='modal-dialog'>
			<div class='modal-content'>
			
			<div class='modal-body'>
			<center><h5><strong style='color:black'>Voulez-vous retirer la voiture de</strong> <br><b style='background-color:prink;color:green;'><i>" . $row->NOM .' '.$row->PRENOM. "</i> ? </b></h5></center>
			<div class='modal-footer'>
			<a class='btn btn-danger btn-md' href='".base_url('chauffeur/Chauffeur/retirer_voit/'.$row->CHAUFFEUR_ID)."' >Retirer</a>
			
			<button class='btn btn-primary btn-md' data-dismiss='modal'>Quitter</button>
			</div>

			</div>
			</div>
			</div>";
			//fin modal retire voiture



     //debut Detail cahuffeur
    $option .="
    </div>
    <div class='modal fade' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
    <div class='modal-dialog'>
    <div class='modal-content'>
    <div class='modal-body'>

     <center><h3>Détail du chauffeur</h3></center>

    <table class= 'table table-striped'>
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
    <td>Information du vehicule</td>
    <th><a hre='#' data-toggle='modal' data-target='#info_voitu" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a></th>
    </tr>


    </table>
    </div>
    <div class='modal-footer'>
    <button class='btn btn-primary btn-md' class='close' data-dismiss='modal'>Fermer</button>
    </div>
    </div>
    </div>
    </div>";
    //fin debut Detail cahuffeur

    $info_vehicul=$this->ModelPs->getRequeteOne('SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR FROM chauffeur_vehicule  join vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE WHERE chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CHAUFFEUR_ID='.$row->CHAUFFEUR_ID.'');

  if(!empty($info_vehicul))
  {

   //debut modal de info voiture(id=info_voitu)
    $option .="
    </div>
    <div class='modal fade' id='info_voitu" .$row->CHAUFFEUR_ID. "'>
    <div class='modal-dialog'>
    <div class='modal-content'>
    <div class='modal-body'>
    <center><h3>Détail du vehicule </h3></center>
    <div class='row'>
    <div class='col-md-6' >
    <img src = '".base_url('upload/photo_vehicule/').$info_vehicul['PHOTO']."' height='100%' width='100%' >
    </div>
    <div class='col-md-6'>
    <table class='table table-hover text-dark'>
    
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
    <div class='modal-footer'>
    <button class='btn btn-primary btn-md' class='close' data-dismiss='modal'>Fermer</button>
    </div>
    </div>
    </div>
    </div>";
     //fin modal de info voiture(id=info_voitu)

 }else
 {
      $option .="";
 }
    


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
		$this->load->view('Chauffeur_Add_View',$data);
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

		$rep_doc =FCPATH.'upload/agent/';

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

		$all_voiture = $this->Model->getRequete("SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_NOM,vehicule.PLAQUE,vehicule.CODE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule.ID_MODELE=vehicule_modele.ID_MODELE WHERE 1");

		$html='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_voiture))
		{
			foreach($all_voiture as $key)
			{

				$html.='<option value="'.$key['CODE'].'">'.$key['DESC_MARQUE'].'-'.$key['DESC_MODELE'].'  /'.$key['PLAQUE'].' </option>';

			}
		}
		echo json_encode($html);
	}


	function save_voiture()
	{
		// $statut=1 attribution avec succes;
		// $statut=2:possedent une autre voiture qu'on l'a deja attribuée;
		// $statut=3: attribution echoue
		$statut=3;
		// $CODE= $this->input->post('code_vehicule');
		$VEHICULE_ID = $this->input->post('VEHICULE_ID');
		$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');

		$data = array('CODE'=>$VEHICULE_ID,'CHAUFFEUR_ID'=>$CHAUFFEUR_ID,'STATUT_AFFECT'=>1);

		$CHAUFFEUR_VEH = $this->Model->create('chauffeur_vehicule',$data);
		$result = $this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('STATUT_VEHICULE'=>2));
		$result = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID),array('STATUT'=>2));
		
		if($result==true )
		{
		 $statut=1;
		}else
		{
		  $statut=2;
	   }
		echo json_encode($statut);
	}

		//fonction pour retirer la carte à un agent
	public function retirer_voit($CHAUFFEUR_ID)
	{

		$chauf = $this->Model->getOne('chauffeur',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID));
		 //print($chauf['CHAUFFEUR_ID']);exit();
		
		$this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$chauf['CHAUFFEUR_ID']),array('STATUT_VEHICULE'=>1));

		$this->Model->update('vehicule',array('VEHICULE_ID'=>$chauf['CODE']),array('STATUT'=>1));
		// $today = date('Y-m-d H:s');
		$this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$chauf['CHAUFFEUR_ID']),array('STATUT_AFFECT'=>2));

		
		$data['message'] = '<div class="alert alert-success text-center" id="message">' . " Vous avez bien retiré la voiture" . '</div>';
		$this->session->set_flashdata($data);
		redirect(base_url('chauffeur/Chauffeur'));

		
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




	// // Recuperation des fichiers et le nommer au nom que tu vais
	// public function upload_document_nomdocument($nom_file,$nom_champ,$nomdocument)
	// {
	// 	$rep_doc =FCPATH.'upload/agent/photopassport/';
	// 	$file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
	// 	$file_extension = strtolower($file_extension);
	// 	$valid_ext = array('pdf');
	// 	if(!is_dir($rep_doc)) //crée un dossier s'il n'existe pas déja   
	// 	{
	// 		mkdir($rep_doc,0777,TRUE);
	// 	}
	// 	unlink(base_url()."upload/agent/photopassport/".$nomdocument.".".$file_extension);
	// 	move_uploaded_file($nom_file, $rep_doc.$nomdocument.".".$file_extension);
	// 	$pathfile=$nomdocument.".".$file_extension;
	// 	return $pathfile;
	// }





      
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
		$this->form_validation->set_rules('NUMERO_PERMIS','','trim|required|is_unique[chauffeur.NUMERO_PERMIS]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le permis doit être unique</font>'));
		$this->form_validation->set_rules('PROVINCE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('COMMUNE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('ZONE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('COLLINE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));


		$this->form_validation->set_rules('date_naissance','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
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
				// 'ETHNIE_ID' => $this->input->post('ETHNIE_ID'),
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
		$this->load->view('Chauffeur_Update_View',$data);
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
			redirect(base_url('chauffeur/Chauffeur/index'));
		}
	}


	//Fonction pour le detail de tous les vehicules d'un client
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












		
		// //Fonction pour l'enregistrement de données dans la base de données ainsi que la mise à jour
		// function save()
		// {
		// 	$VEHICULE_ID=$this->input->post('VEHICULE_ID');

		// 	if(empty($VEHICULE_ID))   //Controle d'enregistrement
		// 	{

		// 		$this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

		// 		$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		$this->form_validation->set_rules('ID_NOM','ID_NOM','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		$this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

		// 		$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		$this->form_validation->set_rules('CLIENT_ID','CLIENT_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		if (empty($_FILES['PHOTO_OUT']['name']))
		// 		{
		// 			$this->form_validation->set_rules('PHOTO_OUT','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
		// 		}

		// 		if($this->form_validation->run() == FALSE)
		// 		{
		// 			$this->ajouter();
		// 		}
		// 		else
		// 		{
		// 			$PHOTO = $this->upload_file('PHOTO_OUT');
		// 			$data = array
		// 			(
		// 				'CODE'=>$this->input->post('CODE'),
		// 				'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
		// 				'ID_NOM'=>$this->input->post('ID_NOM'),
		// 				'PLAQUE'=>$this->input->post('PLAQUE'),
		// 				'COULEUR'=>$this->input->post('COULEUR'),
		// 				'PHOTO'=>$PHOTO,
		// 				'CLIENT_ID'=>$this->input->post('CLIENT_ID'),
		// 			);

		// 			$table = "vehicule";

		// 			$creation=$this->ModelPs->create($table,$data);

		// 			if ($creation)
		// 			{
		// 				$message['message']='<div class="alert alert-success text-center" id="message">Enregistrement du vehicule avec succès</div>';
		// 				$this->session->set_flashdata($message);
		// 				redirect(base_url('vehicule/Vehicule'));

		// 			}else
		// 			{
		// 				$message['message']='<div class="alert alert-danger text-center" id="message">Echec d\'enregistrement </div>';
		// 				$this->session->set_flashdata($message);
		// 				redirect(base_url('vehicule/Vehicule'));

		// 			}

		// 		}


		// 	}else // Controle de mise à jour
		// 	{
		// 		// $this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

		// 		$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		$this->form_validation->set_rules('ID_NOM','ID_NOM','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		// $this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

		// 		$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		$this->form_validation->set_rules('CLIENT_ID','CLIENT_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

		// 		if($this->form_validation->run() == FALSE)
		// 		{
		// 			$this->ajouter();
		// 		}
		// 		else
		// 		{
		// 			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

		// 			$check_existe = $this->ModelPs->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_NOM,CODE,PLAQUE,CLIENT_ID FROM vehicule WHERE VEHICULE_ID != '".$VEHICULE_ID."'");

		// 			if(!empty($check_existe) && $check_existe['CODE'] == $this->input->post('CODE'))
		// 			{
		// 				$message['message']='<div class="alert alert-danger text-center" id="message">le code existe déjà !</div>';
		// 				$this->session->set_flashdata($message);
		// 				redirect(base_url('vehicule/Vehicule/ajouter'));
		// 			}
		// 			else if(!empty($check_existe) && $check_existe['PLAQUE'] == $this->input->post('PLAQUE'))
		// 			{
		// 				$message['message']='<div class="alert alert-danger text-center" id="message">le plaque existe déjà !</div>';
		// 				$this->session->set_flashdata($message);
		// 				redirect(base_url('vehicule/Vehicule/ajouter'));
		// 			}
		// 			else
		// 			{
		// 				if (!empty($_FILES["PHOTO_OUT"]["tmp_name"])) {
		// 					$PHOTO=$this->upload_file('PHOTO_OUT');
		// 				}else{
		// 					$PHOTO=$this->input->post('PHOTO');
		// 				}

		// 				$data=array
		// 				(
		// 					'CODE'=>$this->input->post('CODE'),
		// 					'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
		// 					'ID_NOM'=>$this->input->post('ID_NOM'),
		// 					'PLAQUE'=>$this->input->post('PLAQUE'),
		// 					'COULEUR'=>$this->input->post('COULEUR'),
		// 					'PHOTO'=>$PHOTO,
		// 					'CLIENT_ID'=>$this->input->post('CLIENT_ID'),
		// 				);

		// 				$table = "vehicule";

		// 				$update=$this->ModelPs->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

		// 				if ($update)
		// 				{
		// 					$message['message']='<div class="alert alert-success text-center" id="message">Modification du vehicule avec succès</div>';
		// 					$this->session->set_flashdata($message);
		// 					redirect(base_url('vehicule/Vehicule'));

		// 				}else
		// 				{
		// 					$message['message']='<div class="alert alert-danger text-center" id="message">Echec de modification </div>';
		// 					$this->session->set_flashdata($message);
		// 					redirect(base_url('vehicule/Vehicule'));

		// 				}
						
		// 			}

					

		// 		}
		// 	}

			
		// }



	}
?>