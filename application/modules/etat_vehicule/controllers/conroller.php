<?php
/*
	Auteur    : NIYOMWUNGERE Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 0/09/2024
	crud du sortie du vehicule
*/
	class Sortie_Vehicule extends CI_Controller
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

		//la fonction index visualise la liste
		function index()
		{
			
			$this->load->view('Sortie_Vehicule_List_View');
		}


		//Fonction pour l'affichage
		function listing()
		{
			$USER_ID=$this->session->userdata('USER_ID');

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$critaire = "";

			// if($this->session->PROFIL_ID == 2) // Si c'est le proprietaire
			// {
			// 	$critaire = " AND chauffeur_vehicule.STATUT_AFFECT=1 AND users.USER_ID=".$USER_ID;
			// }
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
				)') : '';

			$query_principal='SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE` FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP WHERE 1';

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
				
				$sub_array[] = $row->NOM." ".$row->PRENOM;
				 $sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->DESC_MOTIF;
				// $sub_array[] = $row->DATE_COURSE;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->PHOTO_KILOMETAGE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Kilometrage de départ</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->PHOTO_KILOMETAGE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				// $sub_array[] = $row->PHOTO_CARBURANT_RETOUR;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture2' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->PHOTO_CARBURANT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture2' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Carburant de départ</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->PHOTO_CARBURANT).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
		
				// $sub_array[] = $row->IMAGE_AVANT;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture3' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_AVANT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture3' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Image avant</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_AVANT).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
			
				// $sub_array[] = $row->IMAGE_ARRIERE;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture4' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_ARRIERE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture4' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Image arrière</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_ARRIERE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';

			
			// $sub_array[] = $row->IMAGE_LATERALE_GAUCHE;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture5' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_LATERALE_GAUCHE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture5' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Latérale gauche</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_LATERALE_GAUCHE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';

				


             // $sub_array[] = $row->IMAGE_LATERALE_DROITE;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture6' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_LATERALE_DROITE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture6' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Latérale droite</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_LATERALE_DROITE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				
				// $sub_array[] = $row->IMAGE_TABLEAU_DE_BORD;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture7' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_TABLEAU_DE_BORD.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture7' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Tableau de bord</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_TABLEAU_DE_BORD).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
                // $sub_array[] = $row->IMAGE_SIEGE_AVANT;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture8' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_SIEGE_AVANT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture8' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Siège avant</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_SIEGE_AVANT).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';

				// $sub_array[] = $row->IMAGE_SIEGE_ARRIERE;
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture9' . $row->ID_SORTIE. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_SIEGE_ARRIERE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture9' .$row->ID_SORTIE.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">Siege arriere</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_SIEGE_ARRIERE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				if($row->IS_VALIDATED==0)
				{
					$sub_array[] ='Pas validé';
				}elseif ($row->IS_VALIDATED==1) {
					$sub_array[] ='Validé';
				}else{
					$sub_array[] ='Rejeté';
				}
				$sub_array[] = $row->COMMENTAIRE;


				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				$option .= "<li class='btn-md'>
				<a class='btn-md' href='" . base_url('etat_vehicule/Retour_Vehicule/getOne/'. $row->ID_RETOUR) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
				</li>";
				$option .= "<li class='btn-md'><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_supp" . $row->ID_RETOUR . "'><span class='fa fa-minus h5' ></span>&nbsp;&nbsp;&nbsp;Supprimer</a></li>";
					$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_supp" .$row->ID_RETOUR. "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous variment supprimer <b>" . $row->NOM .' '.$row->PRENOM. " ? </b></h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('etat_vehicule/Retour_Vehicule/delete/'.$row->ID_RETOUR)."' >Supprimer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";

			
		
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
	

	//Fonction pour ajouter les chauffeur,vehicule et motif deplacement
		function ajouter()
		{
			$data['title'] = 'Remise du véhicule';
			$data['chauffeuri'] = $this->Model->getRequete('SELECT CHAUFFEUR_ID, CONCAT(`NOM`," ",`PRENOM`)  AS chauffeur_desc FROM chauffeur WHERE 1 ORDER BY NOM ASC');
			$data['vehiculee'] = $this->Model->getRequete('SELECT `VEHICULE_ID`,`CODE` FROM `vehicule` WHERE 1 ORDER BY CODE ASC');
			$data['motif'] = $this->Model->getRequete('SELECT `ID_MOTIF_DEP`,`DESC_MOTIF` FROM `motif_deplacement` WHERE 1 ORDER BY DESC_MOTIF ASC');

			$this->load->view('Sortie_Vehicule_Add_View',$data);
		}


	

	// Recuperation des fichiers(pdf)
    public function upload_document($nom_file,$nom_champ)
	{
			$rep_doc =FCPATH.'upload/image_url/';
			$fichier=uniqid();
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


  	//Fonction pour inserer dans la BD
	function add()
	{

		$this->form_validation->set_rules('VEHICULE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('CHAUFFEUR_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DESTINATION','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_DEPART','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_ESTIMATIVE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		
		$this->form_validation->set_rules('ID_MOTIF_DEP','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DATE_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));$this->form_validation->set_rules('COMMENTAIRE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));



		if(!isset($_FILES['photo_siege_arriere']) || empty($_FILES['photo_siege_arriere']['name']))
		{
			$this->form_validation->set_rules('photo_siege_arriere',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['photo_siegeavant']) || empty($_FILES['photo_siegeavant']['name']))
		{
			$this->form_validation->set_rules('photo_siegeavant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['photo_dashbord']) || empty($_FILES['photo_dashbord']['name']))
		{
			$this->form_validation->set_rules('photo_dashbord',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['photolateral_droite']) || empty($_FILES['photolateral_droite']['name']))
		{
			$this->form_validation->set_rules('photolateral_droite',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photolateral_gauche']) || empty($_FILES['photolateral_gauche']['name']))
		{
			$this->form_validation->set_rules('photolateral_gauche',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photo_arriere']) || empty($_FILES['photo_arriere']['name']))
		{
			$this->form_validation->set_rules('photo_arriere',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photo_avant']) || empty($_FILES['photo_avant']['name']))
		{
			$this->form_validation->set_rules('photo_avant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photo_kilometrage']) || empty($_FILES['photo_kilometrage']['name']))
		{
			$this->form_validation->set_rules('photo_kilometrage',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['carburant']) || empty($_FILES['carburant']['name']))
		{
			$this->form_validation->set_rules('carburant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->ajouter();
		}
		else
		{
			//print_r($this->input->post('VEHICULE_ID'));die();
			
			$data_insert = array(
				'VEHICULE_ID' => $this->input->post('VEHICULE_ID'),
				'CHAUFFEUR_ID' => $this->input->post('CHAUFFEUR_ID'),
				'DESTINATION' => $this->input->post('DESTINATION'),
				'HEURE_DEPART' => $this->input->post('HEURE_DEPART'),
				'HEURE_ESTIMATIVE_RETOUR'=> $this->input->post('HEURE_ESTIMATIVE_RETOUR'),
				'ID_MOTIF_DEP' => $this->input->post('ID_MOTIF_DEP'),
				'DATE_COURSE' => $this->input->post('DATE_COURSE'),
				'COMMENTAIRE' => $this->input->post('COMMENTAIRE'),
			
				'PHOTO_KILOMETAGE' => $this->upload_document($_FILES['photo_kilometrage']['tmp_name'],$_FILES['photo_kilometrage']['name']),

				'PHOTO_CARBURANT' => $this->upload_document($_FILES['carburant']['tmp_name'],$_FILES['carburant']['name']),

				'IMAGE_AVANT' => $this->upload_document($_FILES['photo_avant']['tmp_name'],$_FILES['photo_avant']['name']),
				'IMAGE_ARRIERE' => $this->upload_document($_FILES['photo_arriere']['tmp_name'],$_FILES['photo_arriere']['name']),
				'IMAGE_LATERALE_GAUCHE' => $this->upload_document($_FILES['photolateral_gauche']['tmp_name'],$_FILES['photolateral_gauche']['name']),
				
				'IMAGE_LATERALE_DROITE' => $this->upload_document($_FILES['photolateral_droite']['tmp_name'],$_FILES['photolateral_droite']['name']),
				'IMAGE_TABLEAU_DE_BORD' => $this->upload_document($_FILES['photo_dashbord']['tmp_name'],$_FILES['photo_dashbord']['name']),

				
				'IMAGE_SIEGE_AVANT' => $this->upload_document($_FILES['photo_siegeavant']['tmp_name'],$_FILES['photo_siegeavant']['name']),
				'IMAGE_SIEGE_ARRIERE' => $this->upload_document($_FILES['photo_siege_arriere']['tmp_name'],$_FILES['photo_siege_arriere']['name']),
			);

			$table ='sortie_vehicule';
			$insert=$this->Model->create($table,$data_insert);
			
			
			if($insert)
			{


				$data['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_enreg_ft_success').'</div>';
				$this->session->set_flashdata($data);
				redirect(base_url('etat_vehicule/Sortie_Vehicule/index'));
			}
			else
			{
				$this->load->view('Retour_Vehicule_Add_View',$data);
			}
			
		}
	}

    function delete($id)
	{
		$table='retour_vehicule';
		$criteres['ID_RETOUR']=$id;
		$detete=$this->Model->delete($table,$criteres);
		echo json_encode($detete);
		if($detete)
			{


				$data['message']='<div class="alert alert-success text-center" id="message">La suppression effectuée avec succès</div>';
				$this->session->set_flashdata($data);
				redirect(base_url('etat_vehicule/Retour_Vehicule/index'));
			}
	}

		//Fonction pour recuperer une ligne 
	function getOne($id)
	{
		$membre = $this->Model->getRequeteOne('SELECT `ID_RETOUR`,`VEHICULE_ID`,`CHAUFFEUR_ID`,`HEURE_RETOUR`,`PHOTO_KILOMETRAGE_RETOUR`,`PHOTO_CARBURANT_RETOUR`,`COMMENTAIRE_ANOMALIE`,`COMMENTAIRE_VALIDATION` FROM `retour_vehicule` WHERE ID_RETOUR='.$id);
		$data['membre'] = $membre;

		// $data['provinces'] = $this->Model->getRequete('SELECT PROVINCE_ID, PROVINCE_NAME FROM provinces WHERE 1 ORDER BY PROVINCE_NAME ASC');

		$data['chauffeuri'] = $this->Model->getRequete('SELECT CHAUFFEUR_ID, CONCAT(`NOM`," ",`PRENOM`)  AS chauffeur_desc FROM chauffeur WHERE 1 ORDER BY NOM ASC');
			$data['vehiculee'] = $this->Model->getRequete('SELECT `VEHICULE_ID`,`CODE` FROM `vehicule` WHERE 1 ORDER BY CODE ASC');
		$data['title'] = "Modification d'un chauffeur";
		$this->load->view('Retour_Vehicule_Update_View',$data);
	}

	function update()
	{
		$id = $this->input->post('ID_RETOUR');
		//print_r($id);exit();

		$this->form_validation->set_rules('VEHICULE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('CHAUFFEUR_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('COMMENTAIRE_ANOMALIE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('COMMENTAIRE_VALIDATION','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$PHOTO_KILOMETRAGE_RETOUR = $this->input->post('PHOTO_KILOMETRAGE_RETOUR_OLD');
		if(empty($_FILES['PHOTO_KILOMETRAGE_RETOUR']['name']))
		{
			$file = $this->input->post('PHOTO_KILOMETRAGE_RETOUR_OLD');	
		}
		else
		{
			$file = $this->upload_document($_FILES['PHOTO_KILOMETRAGE_RETOUR']['tmp_name'],$_FILES['PHOTO_KILOMETRAGE_RETOUR']['name']);
		}

		$PHOTO_CARBURANT_RETOUR = $this->input->post('PHOTO_CARBURANT_RETOUR_OLD');
		if(empty($_FILES['PHOTO_CARBURANT_RETOUR']['name']))
		{
			$file2 = $this->input->post('PHOTO_CARBURANT_RETOUR_OLD');
		}
		else
		{
			$file2 = $this->upload_document($_FILES['PHOTO_CARBURANT_RETOUR']['tmp_name'],$_FILES['PHOTO_CARBURANT_RETOUR']['name']);
		}

	
		if($this->form_validation->run() == FALSE)
		{
			$this->getOne($id);
		}
		else
		{
			$Array = array(
				'VEHICULE_ID' => $this->input->post('VEHICULE_ID'),
				'CHAUFFEUR_ID' => $this->input->post('CHAUFFEUR_ID'),
				'HEURE_RETOUR' => $this->input->post('HEURE_RETOUR'),
				'COMMENTAIRE_ANOMALIE' => $this->input->post('COMMENTAIRE_ANOMALIE'),
				'COMMENTAIRE_VALIDATION' => $this->input->post('COMMENTAIRE_VALIDATION'),
				'PHOTO_KILOMETRAGE_RETOUR' => $file,
				'PHOTO_CARBURANT_RETOUR' => $file2,
				
			);
			$this->Model->update('retour_vehicule', array('ID_RETOUR' => $id), $Array);
			$datas['message'] = '<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
			$this->session->set_flashdata($datas);
			redirect(base_url('etat_vehicule/Retour_Vehicule/index'));
		}
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