<?php
/*
	Auteur    : NIYOMWUNGERE Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 03-05/09/2024
	crud de l'etat du vehicule 
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
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/image_url/'.$row->PHOTO_KILOMETAGE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/image_url/'.$row->PHOTO_CARBURANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
		
				// $sub_array[] = $row->IMAGE_AVANT;
			    $source = !empty($row->IMAGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_ARRIERE;

				$source = !empty($row->IMAGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

			
			// $sub_array[] = $row->IMAGE_LATERALE_GAUCHE;
			$source = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				

				


             // $sub_array[] = $row->IMAGE_LATERALE_DROITE;
			$source = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				
				// $sub_array[] = $row->IMAGE_TABLEAU_DE_BORD;
			$source = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/image_url/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

                // $sub_array[] = $row->IMAGE_SIEGE_AVANT;
			$source = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_SIEGE_ARRIERE;
				$source = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
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
				<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
				</li>";
				$option .= "<li class='btn-md'><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_supp" . $row->ID_SORTIE . "'><span class='fa fa-minus h5' ></span>&nbsp;&nbsp;&nbsp;Supprimer</a></li>";
					$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous variment supprimer <b>" . $row->NOM .' '.$row->PRENOM. " ? </b></h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('etat_vehicule/Sortie_Vehicule/delete/'.$row->ID_SORTIE)."' >Supprimer</a>
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
		$this->form_validation->set_rules('DATE_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('COMMENTAIRE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));


		if(!isset($_FILES['photo_kilometrage']) || empty($_FILES['photo_kilometrage']['name']))
		{
			$this->form_validation->set_rules('photo_kilometrage',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['carburant']) || empty($_FILES['carburant']['name']))
		{
			$this->form_validation->set_rules('carburant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['photo_avant']) || empty($_FILES['photo_avant']['name']))
		{
			$this->form_validation->set_rules('photo_avant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photo_arriere']) || empty($_FILES['photo_arriere']['name']))
		{
			$this->form_validation->set_rules('photo_arriere',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photolateral_gauche']) || empty($_FILES['photolateral_gauche']['name']))
		{
			$this->form_validation->set_rules('photolateral_gauche',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['photo_lat_droite']) || empty($_FILES['photo_lat_droite']['name']))
		{
			$this->form_validation->set_rules('photo_lat_droite',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['photo_tableau']) || empty($_FILES['photo_tableau']['name']))
		{
			$this->form_validation->set_rules('photo_tableau',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['siege_avant']) || empty($_FILES['siege_avant']['name']))
		{
			$this->form_validation->set_rules('siege_avant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		if(!isset($_FILES['siege_arriere']) || empty($_FILES['siege_arriere']['name']))
		{
			$this->form_validation->set_rules('siege_arriere',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		
		

		if($this->form_validation->run() == FALSE)
		{
			$this->ajouter();
		}
		else
		{
			
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
				'IMAGE_LATERALE_DROITE' => $this->upload_document($_FILES['photo_lat_droite']['tmp_name'],$_FILES['photo_lat_droite']['name']),
				'IMAGE_TABLEAU_DE_BORD' => $this->upload_document($_FILES['photo_tableau']['tmp_name'],$_FILES['photo_tableau']['name']),
				'IMAGE_SIEGE_AVANT' => $this->upload_document($_FILES['siege_avant']['tmp_name'],$_FILES['siege_avant']['name']),
				'IMAGE_SIEGE_ARRIERE' => $this->upload_document($_FILES['siege_arriere']['tmp_name'],$_FILES['siege_arriere']['name']),
				
				
				
				
			);
			
			// print_r($data_insert);die();

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
		$table='sortie_vehicule';
		$criteres['ID_SORTIE']=$id;
		$detete=$this->Model->delete($table,$criteres);
		echo json_encode($detete);
		if($detete)
			{


				$data['message']='<div class="alert alert-success text-center" id="message">La suppression effectuée avec succès</div>';
				$this->session->set_flashdata($data);
				redirect(base_url('etat_vehicule/Sortie_Vehicule/index'));
			}
	}

		//Fonction pour recuperer une ligne 
	function getOne($id)
	{
		$membre = $this->Model->getRequeteOne('SELECT `ID_SORTIE`,`VEHICULE_ID`,`CHAUFFEUR_ID`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,`ID_MOTIF_DEP`,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`DATE_SAVE`,`COMMENTAIRE` FROM `sortie_vehicule` WHERE  ID_SORTIE='.$id);
		$data['membre'] = $membre;

		$data['chauffeuri'] = $this->Model->getRequete('SELECT CHAUFFEUR_ID, CONCAT(`NOM`," ",`PRENOM`)  AS chauffeur_desc FROM chauffeur WHERE 1 ORDER BY NOM ASC');
		$data['vehiculee'] = $this->Model->getRequete('SELECT `VEHICULE_ID`,`CODE` FROM `vehicule` WHERE 1 ORDER BY CODE ASC');
		$data['motif'] = $this->Model->getRequete('SELECT `ID_MOTIF_DEP`,`DESC_MOTIF` FROM `motif_deplacement` WHERE 1 ORDER BY DESC_MOTIF ASC');
		$data['title'] = "Modification d'une sortie du vehicule";
		$this->load->view('Sortie_Vehicule_Update_View',$data);
	}

	function update()
	{
		$id = $this->input->post('ID_SORTIE');
		//print_r($id);exit();

		$this->form_validation->set_rules('VEHICULE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('CHAUFFEUR_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DESTINATION','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_DEPART','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_ESTIMATIVE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		
		$this->form_validation->set_rules('ID_MOTIF_DEP','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DATE_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('COMMENTAIRE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>')); 
		
           
             

		$PHOTO_CARBURANT = $this->input->post('PHOTO_CARBURANT_OLD');
		if(empty($_FILES['PHOTO_CARBURANT']['name']))
		{
			$file = $this->input->post('PHOTO_CARBURANT_OLD');	
		}
		else
		{
			$file = $this->upload_document($_FILES['PHOTO_CARBURANT']['tmp_name'],$_FILES['PHOTO_CARBURANT']['name']);
		}
		$PHOTO_KILOMETAGE = $this->input->post('PHOTO_KILOMETAGE_OLD');
		if(empty($_FILES['PHOTO_KILOMETAGE']['name']))
		{
			$file2 = $this->input->post('PHOTO_KILOMETAGE_OLD');
		}
		else
		{
			$file2 = $this->upload_document($_FILES['PHOTO_KILOMETAGE']['tmp_name'],$_FILES['PHOTO_KILOMETAGE']['name']);
		}
		

		$IMAGE_SIEGE_ARRIERE = $this->input->post('IMAGE_SIEGE_ARRIERE_OLD');
		if(empty($_FILES['IMAGE_SIEGE_ARRIERE']['name']))
		{
			$file3 = $this->input->post('IMAGE_SIEGE_ARRIERE_OLD');
		}
		else
		{
			$file3 = $this->upload_document($_FILES['IMAGE_SIEGE_ARRIERE']['tmp_name'],$_FILES['IMAGE_SIEGE_ARRIERE']['name']);
		} 
           

		$IMAGE_SIEGE_AVANT = $this->input->post('IMAGE_SIEGE_AVANT_OLD');
		if(empty($_FILES['IMAGE_SIEGE_AVANT']['name']))
		{
			$file4 = $this->input->post('IMAGE_SIEGE_AVANT_OLD');
		}
		else
		{
			$file4 = $this->upload_document($_FILES['IMAGE_SIEGE_AVANT']['tmp_name'],$_FILES['IMAGE_SIEGE_AVANT']['name']);
		}
             

		$IMAGE_TABLEAU_DE_BORD = $this->input->post('IMAGE_TABLEAU_DE_BORD_OLD');
		if(empty($_FILES['IMAGE_TABLEAU_DE_BORD']['name']))
		{
			$file5 = $this->input->post('IMAGE_TABLEAU_DE_BORD_OLD');
		}
		else
		{
			$file5 = $this->upload_document($_FILES['IMAGE_TABLEAU_DE_BORD']['tmp_name'],$_FILES['IMAGE_TABLEAU_DE_BORD']['name']);
		}
              

		$IMAGE_LATERALE_DROITE = $this->input->post('IMAGE_LATERALE_DROITE_OLD');
		if(empty($_FILES['IMAGE_LATERALE_DROITE']['name']))
		{
			$file6 = $this->input->post('IMAGE_LATERALE_DROITE_OLD');
		}
		else
		{
			$file6 = $this->upload_document($_FILES['IMAGE_LATERALE_DROITE']['tmp_name'],$_FILES['IMAGE_LATERALE_DROITE']['name']);
		}
          

		$IMAGE_LATERALE_GAUCHE = $this->input->post('IMAGE_LATERALE_GAUCHE_OLD');
		if(empty($_FILES['IMAGE_LATERALE_GAUCHE']['name']))
		{
			$file7 = $this->input->post('IMAGE_LATERALE_GAUCHE_OLD');
		}
		else
		{
			$file7 = $this->upload_document($_FILES['IMAGE_LATERALE_GAUCHE']['tmp_name'],$_FILES['IMAGE_LATERALE_GAUCHE']['name']);
		}
              

		$IMAGE_ARRIERE = $this->input->post('IMAGE_ARRIERE_OLD');
		if(empty($_FILES['IMAGE_ARRIERE']['name']))
		{
			$file8 = $this->input->post('IMAGE_ARRIERE_OLD');
		}
		else
		{
			$file8 = $this->upload_document($_FILES['IMAGE_ARRIERE']['tmp_name'],$_FILES['IMAGE_ARRIERE']['name']);
		}
             

		$IMAGE_AVANT = $this->input->post('IMAGE_AVANT_OLD');
		if(empty($_FILES['IMAGE_AVANT']['name']))
		{
			$file9 = $this->input->post('IMAGE_AVANT_OLD');
		}
		else
		{
			$file9 = $this->upload_document($_FILES['IMAGE_AVANT']['tmp_name'],$_FILES['IMAGE_AVANT']['name']);
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
				'DESTINATION' => $this->input->post('DESTINATION'),
				'HEURE_DEPART' => $this->input->post('HEURE_DEPART'),
				'HEURE_ESTIMATIVE_RETOUR'=> $this->input->post('HEURE_ESTIMATIVE_RETOUR'),
				'ID_MOTIF_DEP' => $this->input->post('ID_MOTIF_DEP'),
				'DATE_COURSE' => $this->input->post('DATE_COURSE'),
				'COMMENTAIRE' => $this->input->post('COMMENTAIRE'),
                 'PHOTO_CARBURANT' => $file,
                 'PHOTO_KILOMETAGE' => $file2,
                 'IMAGE_SIEGE_ARRIERE' => $file3,
                 'IMAGE_SIEGE_AVANT' => $file4,
                 'IMAGE_TABLEAU_DE_BORD' => $file5,
                 'IMAGE_LATERALE_DROITE' => $file6,
                 'IMAGE_LATERALE_GAUCHE' => $file7,

                'IMAGE_ARRIERE' => $file8,
				'IMAGE_AVANT' => $file9,
				
				
			);
			$this->Model->update('sortie_vehicule', array('ID_SORTIE' => $id), $Array);
			$datas['message'] = '<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
			$this->session->set_flashdata($datas);
			redirect(base_url('etat_vehicule/Sortie_Vehicule/index'));
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