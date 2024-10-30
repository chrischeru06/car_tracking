<?php
/*
	Auteur    : Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 30/10/2024
	Desc      : Ihm pour les anomalies des vehicules 
*/
	class Anomalie extends CI_Controller
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
			
			$this->load->view('Anomalie_List_View');
		}


		//Liste des retour vehicules
		function listing()
		{

			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL = $this->session->userdata('PROFIL_ID');
			$ID_TYPE_ANOMALIE = $this->input->post('ID_TYPE_ANOMALIE');
			$critaire = "";

			$critaire_anomalie = "";

			if($ID_TYPE_ANOMALIE > 0)
			{
				$critaire_anomalie .= " AND anomalie_vehicule.ID_TYPE_ANOMALIE =".$ID_TYPE_ANOMALIE;
			}

			if($PROFIL == 3) //Si c'est le chauffeur
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}
			else if($PROFIL == 4) //Si c'est le Gestionnaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE = '.$get_user['ID_GESTIONNAIRE_VEHICULE'];
				}
			}
			else if($PROFIL == 2) //Si c'est le propriétaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$get_user['PROPRIETAIRE_ID'];
				}
			}

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','chauffeur.NOM','chauffeur.PRENOM','COMMENTAIRE_PANNE ','CIRCONSTANNCES_ACCIDENT');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'anomalie_vehicule.ID_ANOMALIE DESC';
			}
			else
			{
				$order_by = ' ORDER BY anomalie_vehicule.ID_ANOMALIE DESC';
			}

			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR COMMENTAIRE_PANNE LIKE "%' . $var_search . '%"
				OR CIRCONSTANNCES_ACCIDENT LIKE "%' . $var_search . '%"
			)') : '';

			$query_principal='SELECT `ID_ANOMALIE`,vehicule.VEHICULE_ID,vehicule.PHOTO,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule,chauffeur.CHAUFFEUR_ID,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) as desc_chauf,chauffeur.PHOTO_PASSPORT,type_anomalie.DESCRIPTION,COMMENTAIRE_PANNE,DATE_ANOMALIE,LIEU_ANOMALIE,CIRCONSTANNCES_ACCIDENT,anomalie_vehicule.IMAGE_AVANT,anomalie_vehicule.IMAGE_ARRIERE,anomalie_vehicule.IMAGE_LATERALE_GAUCHE,anomalie_vehicule.IMAGE_LATERALE_DROITE,anomalie_vehicule.IMAGE_TABLEAU_DE_BORD,anomalie_vehicule.IMAGE_SIEGE_AVANT,anomalie_vehicule.IMAGE_SIEGE_ARRIERE,anomalie_vehicule.DATE_SAVE FROM `anomalie_vehicule` JOIN type_anomalie ON anomalie_vehicule.ID_TYPE_ANOMALIE = type_anomalie.ID_TYPE_ANOMALIE JOIN chauffeur ON anomalie_vehicule.CHAUFFEUR_ID = chauffeur.CHAUFFEUR_ID JOIN vehicule ON anomalie_vehicule.VEHICULE_ID = vehicule.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID JOIN gestionnaire_vehicule ON gestionnaire_vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID JOIN users ON chauffeur.CHAUFFEUR_ID = users.CHAUFFEUR_ID WHERE 1 '.$critaire_anomalie.'';


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

				
				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('chauffeur/Chauffeur_New/Detail/'.md5($row->CHAUFFEUR_ID)).'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_chauf . '</td></tr></tbody></a>';

				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID.'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/photo_vehicule/').$row->PHOTO.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_vehicule . '</td></tr></tbody></a>';

				$sub_array[] = $row->DESCRIPTION;
				

				if(empty($row->COMMENTAIRE_PANNE))
				{
					$sub_array[] = '<center>N/A</center>';
				}
				else
				{
					$sub_array[] = "<center><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_panne" . $row->ID_ANOMALIE . "'><font class='fa fa-eye' id='eye'></font></a></center>";
				}

				if(empty($row->CIRCONSTANNCES_ACCIDENT))
				{
					$sub_array[] = '<center>N/A</center>';
				}
				else
				{
					$sub_array[] = "<center><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_circonstance" . $row->ID_ANOMALIE . "'><font class='fa fa-eye' id='eye'></font></a></center>";
				}

				$sub_array[] = $row->DATE_ANOMALIE;
				$sub_array[] = $row->LIEU_ANOMALIE;
				$sub_array[] = date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if($PROFIL == 3) // Si c'est le chauffeur
				{
					$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Retour_Vehicule/getOne/'. $row->ID_ANOMALIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";

						$option .= "<li class='btn-md'><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_supp" . $row->ID_ANOMALIE . "'><span class='fa fa-minus h5' ></span>&nbsp;&nbsp;&nbsp;Supprimer</a></li>";
				}
				
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_panne" .$row->ID_ANOMALIE . "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5>Commentaire sur l'anomalie</h5>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<center>
				<p>".$row->COMMENTAIRE_PANNE."</p>
				</center>

				<div class='modal-footer'>

				<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
				</div>
				</div>
				</div>
				</div>
				</div>";


				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_circonstance" .$row->ID_ANOMALIE . "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5>Commentaire sur l'anomalie</h5>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<center>
				<p>".$row->CIRCONSTANNCES_ACCIDENT."</p>
				</center>

				<div class='modal-footer'>

				<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
				</div>
				</div>
				</div>
				</div>
				</div>";

				
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_supp" .$row->ID_ANOMALIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous variment supprimer cette demande ?</h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('etat_vehicule/Anomalie/delete/'.$row->ID_ANOMALIE)."' >Supprimer</a>
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


	//Fonction pour le formulaire d'Enrégistrement
		function ajouter()
		{
			$data['title'] = 'Enrégistrement d\'anomalie';
			
			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');

			$critaire = '';

			$get_chauffeur = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			if(!empty($get_chauffeur))
			{
				$get_user = $this->Model->getRequeteOne('SELECT users.USER_ID FROM users JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID JOIN chauffeur ON chauffeur.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID WHERE chauffeur.CHAUFFEUR_ID = '.$get_chauffeur['CHAUFFEUR_ID'].'');

				if(!empty($get_user))
				{
					$critaire.= ' AND users.USER_ID = '.$get_user['USER_ID'];
				}

				$vehicule = $this->Model->getRequete('SELECT vehicule.VEHICULE_ID,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire.' ORDER BY desc_vehicule ASC');
			}

			$data['vehiculee'] = $vehicule;

			$data['type_anomalie'] = $this->Model->getRequete('SELECT `ID_TYPE_ANOMALIE`,`DESCRIPTION` FROM `type_anomalie` WHERE 1 AND ID_TYPE_ANOMALIE = 1 OR ID_TYPE_ANOMALIE = 2 ORDER BY DESCRIPTION ASC');

			$this->load->view('Anomalie_Add_View',$data);
		}




	// Recuperation des fichiers(pdf)
		public function upload_document($nom_file,$nom_champ)
		{
			$rep_doc =FCPATH.'upload/photo_vehicule/';
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

		$this->form_validation->set_rules('HEURE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		// $this->form_validation->set_rules('COMMENTAIRE_ANOMALIE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));


		if(!isset($_FILES['PHOTO_KILOMETRAGE_RETOUR']) || empty($_FILES['PHOTO_KILOMETRAGE_RETOUR']['name']))
		{
			$this->form_validation->set_rules('PHOTO_KILOMETRAGE_RETOUR',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['PHOTO_CARBURANT_RETOUR']) || empty($_FILES['PHOTO_CARBURANT_RETOUR']['name']))
		{
			$this->form_validation->set_rules('PHOTO_CARBURANT_RETOUR',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}
		

		if($this->form_validation->run() == FALSE)
		{
			$this->ajouter();
		}
		else
		{
			$USER_ID = $this->session->userdata('USER_ID');
			$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			$CHAUFFEUR_ID = $get_user['CHAUFFEUR_ID'];

			$data_insert = array(
				'VEHICULE_ID' => $this->input->post('VEHICULE_ID'),
				'CHAUFFEUR_ID' =>$CHAUFFEUR_ID,
				'HEURE_RETOUR' => $this->input->post('HEURE_RETOUR'),

				'PHOTO_KILOMETRAGE_RETOUR' => $this->upload_document($_FILES['PHOTO_KILOMETRAGE_RETOUR']['tmp_name'],$_FILES['PHOTO_KILOMETRAGE_RETOUR']['name']),

				'PHOTO_CARBURANT_RETOUR' => $this->upload_document($_FILES['PHOTO_CARBURANT_RETOUR']['tmp_name'],$_FILES['PHOTO_CARBURANT_RETOUR']['name']),

				'COMMENTAIRE_ANOMALIE' => $this->input->post('COMMENTAIRE_ANOMALIE'),
				
			);

			$table ='retour_vehicule';
			$insert=$this->Model->create($table,$data_insert);
			
			if($insert)
			{

				$data['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_enreg_ft_success').'</div>';
				$this->session->set_flashdata($data);
				redirect(base_url('etat_vehicule/Retour_Vehicule'));
			}
			else
			{
				$data['message']='<div class="alert alert-danger text-center" id="message">Error !</div>';
				$this->session->set_flashdata($data);
				redirect(base_url('etat_vehicule/Retour_Vehicule/ajouter'));
			}
			
		}
	}

	function delete($id)
	{
		$table='anomalie_vehicule';
		$criteres['ID_ANOMALIE']=$id;
		$detete=$this->Model->delete($table,$criteres);
		echo json_encode($detete);
		if($detete)
		{
			$data['message']='<div class="alert alert-success text-center" id="message">La suppression effectuée avec succès</div>';
			$this->session->set_flashdata($data);
			redirect(base_url('etat_vehicule/Anomalie'));
		}
	}

		//Fonction pour recuperer une ligne 
	function getOne($id)
	{
		$membre = $this->Model->getRequeteOne('SELECT `ID_RETOUR`,`VEHICULE_ID`,`CHAUFFEUR_ID`,`HEURE_RETOUR`,`PHOTO_KILOMETRAGE_RETOUR`,`PHOTO_CARBURANT_RETOUR`,`COMMENTAIRE_ANOMALIE`,`COMMENTAIRE_VALIDATION` FROM `retour_vehicule` WHERE ID_RETOUR='.$id);
		$data['membre'] = $membre;

		$data['title'] = 'Modification retour véhicule';

		$USER_ID = $this->session->userdata('USER_ID');
		$PROFIL_ID = $this->session->userdata('PROFIL_ID');

		$critaire = '';

		$get_chauffeur = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

		if(!empty($get_chauffeur))
		{
			$get_user = $this->Model->getRequeteOne('SELECT users.USER_ID FROM users JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID JOIN chauffeur ON chauffeur.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID WHERE chauffeur.CHAUFFEUR_ID = '.$get_chauffeur['CHAUFFEUR_ID'].'');

			if(!empty($get_user))
			{
				$critaire.= ' AND users.USER_ID = '.$get_user['USER_ID'];
			}

			$vehicule = $this->Model->getRequete('SELECT vehicule.VEHICULE_ID,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire.' ORDER BY desc_vehicule ASC');
		}

		$data['vehiculee'] = $vehicule;

		$this->load->view('Retour_Vehicule_Update_View',$data);
	}

	function update()
	{
		$id = $this->input->post('ID_RETOUR');

		$this->form_validation->set_rules('VEHICULE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$this->form_validation->set_rules('HEURE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$PHOTO_KILOMETRAGE_RETOUR = $this->input->post('PHOTO_KILOMETRAGE_RETOUR_OLD');
		if(empty($_FILES['PHOTO_KILOMETRAGE_RETOUR']['name']))
		{
			$PHOTO_KILOMETRAGE_RETOUR = $this->input->post('PHOTO_KILOMETRAGE_RETOUR_OLD');	
		}
		else
		{
			$PHOTO_KILOMETRAGE_RETOUR = $this->upload_document($_FILES['PHOTO_KILOMETRAGE_RETOUR']['tmp_name'],$_FILES['PHOTO_KILOMETRAGE_RETOUR']['name']);
		}

		$PHOTO_CARBURANT_RETOUR = $this->input->post('PHOTO_CARBURANT_RETOUR_OLD');
		if(empty($_FILES['PHOTO_CARBURANT_RETOUR']['name']))
		{
			$PHOTO_CARBURANT_RETOUR = $this->input->post('PHOTO_CARBURANT_RETOUR_OLD');
		}
		else
		{
			$PHOTO_CARBURANT_RETOUR = $this->upload_document($_FILES['PHOTO_CARBURANT_RETOUR']['tmp_name'],$_FILES['PHOTO_CARBURANT_RETOUR']['name']);
		}


		$IS_EXCHANGE = $this->input->post('IS_EXCHANGE');

		if($IS_EXCHANGE == 2) //S'il y a d'anolmalie
		{
			$COMMENTAIRE_ANOMALIE = $this->input->post('COMMENTAIRE_ANOMALIE');
		}
		else
		{
			$COMMENTAIRE_ANOMALIE = null;
		}


		if($this->form_validation->run() == FALSE)
		{
			$this->getOne($id);
		}
		else
		{
			$USER_ID = $this->session->userdata('USER_ID');
			$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			$CHAUFFEUR_ID = $get_user['CHAUFFEUR_ID'];

			$stat = $this->Model->getRequeteOne('SELECT IS_VALIDATED FROM retour_vehicule WHERE  ID_RETOUR = '.$id.' AND CHAUFFEUR_ID = '.$CHAUFFEUR_ID.'');

			if ($stat['IS_VALIDATED'] != 1 )  // Si la demande n'est pas validée
			{

			$Array = array(
				'VEHICULE_ID' => $this->input->post('VEHICULE_ID'),
				'CHAUFFEUR_ID' => $CHAUFFEUR_ID,
				'HEURE_RETOUR' => $this->input->post('HEURE_RETOUR'),
				'COMMENTAIRE_ANOMALIE' => $COMMENTAIRE_ANOMALIE,
				'PHOTO_KILOMETRAGE_RETOUR' => $PHOTO_KILOMETRAGE_RETOUR,
				'PHOTO_CARBURANT_RETOUR' => $PHOTO_CARBURANT_RETOUR,
				'IS_VALIDATED' => 0,
				
			);
			$this->Model->update('retour_vehicule', array('ID_RETOUR' => $id), $Array);

				$datas['message'] = '<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
				$this->session->set_flashdata($datas);
				redirect(base_url('etat_vehicule/Retour_Vehicule'));
			}
			else
			{
				$datas['message'] = '<div class="alert alert-danger text-center" id="message">Impossible de modifier car la demande est déjà validée !</div>';
				$this->session->set_flashdata($datas);
				redirect(base_url('etat_vehicule/Retour_Vehicule/getOne/'.$id));
			}
		}
	}


	// Fonction pour recuperer les status (approuver ou refuser)
	function get_all_statut()
	{
		$all_statut = $this->Model->getRequete("SELECT `TRAITEMENT_DEMANDE_ID`,`DESC_TRATDEMANDE` FROM `traitement_demande` WHERE 1");
		$html='<option value="">--- '.lang('selectionner').' ----</option>';
		if(!empty($all_statut))
		{
			foreach($all_statut as $key)
			{
				$html.='<option value="'.$key['TRAITEMENT_DEMANDE_ID'].'">'.$key['DESC_TRATDEMANDE'].' </option>';
			}
		}

		echo json_encode($html);
	}

	// Enregistrement de la reponse pour la demande
	function save_stat_demande()
	{
		$statut = 0;

		$demande = false;

		$ID_RETOUR = $this->input->post('ID_RETOUR');
		$IS_VALIDATED = $this->input->post('IS_VALIDATED');
		$COMMENTAIRE = $this->input->post('COMMENTAIRE');
		$USER_ID = $this->input->post('USER_ID');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$demande = $this->Model->update('retour_vehicule',array('ID_RETOUR'=>$ID_RETOUR,),array('IS_VALIDATED'=>$IS_VALIDATED,'COMMENTAIRE_VALIDATION'=>$COMMENTAIRE));

			// Enregistrement dans la table d'historique demande retour vehicule

		$data_histo = array(
			'ID_RETOUR' => $ID_RETOUR,
			'IS_VALIDATED'=> $this->input->post('IS_VALIDATED'),
			'COMMENTAIRE'=> $this->input->post('COMMENTAIRE'),
			'USER_ID' => $this->input->post('USER_ID'),
		);

		$demande = $this->Model->create('histo_retour_vehicule',$data_histo);


		// if ($IS_VALIDATED == 1)  // Demande approuvée
		// {
		// 	$get_sortie = $this->Model->getOne('sortie_vehicule',array('ID_SORTIE'=>$ID_SORTIE));

		// 	// Mise à jour d'etat ds la table vehicule

		// 	$data_update = array(
		// 		'IMAGE_AVANT' => $get_sortie['IMAGE_AVANT'],
		// 		'IMAGE_ARRIERE'=> $get_sortie['IMAGE_ARRIERE'],
		// 		'IMAGE_LATERALE_GAUCHE'=> $get_sortie['IMAGE_LATERALE_GAUCHE'],
		// 		'IMAGE_LATERALE_DROITE' => $get_sortie['IMAGE_LATERALE_DROITE'],
		// 		'IMAGE_TABLEAU_DE_BORD' => $get_sortie['IMAGE_TABLEAU_DE_BORD'],
		// 		'IMAGE_SIEGE_AVANT' => $get_sortie['IMAGE_SIEGE_AVANT'],
		// 		'IMAGE_SIEGE_ARRIERE' => $get_sortie['IMAGE_SIEGE_ARRIERE'],
		// 	);

		// 	$demande = $this->Model->update('vehicule', array('VEHICULE_ID' => $get_sortie['VEHICULE_ID']), $data_update);

		// 	// Enregistrement ds la table historique d'etat

		// 	$data_save = array(
		// 		'VEHICULE_ID' => $get_sortie['VEHICULE_ID'],
		// 		'IMAGE_AVANT' => $get_sortie['IMAGE_AVANT'],
		// 		'IMAGE_ARRIERE'=> $get_sortie['IMAGE_ARRIERE'],
		// 		'IMAGE_LATERALE_GAUCHE'=> $get_sortie['IMAGE_LATERALE_GAUCHE'],
		// 		'IMAGE_LATERALE_DROITE' => $get_sortie['IMAGE_LATERALE_DROITE'],
		// 		'IMAGE_TABLEAU_DE_BORD' => $get_sortie['IMAGE_TABLEAU_DE_BORD'],
		// 		'IMAGE_SIEGE_AVANT' => $get_sortie['IMAGE_SIEGE_AVANT'],
		// 		'IMAGE_SIEGE_ARRIERE' => $get_sortie['IMAGE_SIEGE_ARRIERE'],
		// 		'USER_ID' => $this->input->post('USER_ID'),
		// 	);

		// 	$demande = $this->Model->create('historique_etat_vehicule',$data_save);
		// }

		if($demande == true)
		{
			$statut=1;
		}else
		{
			$statut=2;
		}


		echo json_encode($statut);
	}


	// Historique demande retour vehicule
	function getHistorique($id)
	{

		$query_principal='SELECT `HISTO_RETOUR_ID`,histo_retour_vehicule.IS_VALIDATED,histo_retour_vehicule.COMMENTAIRE,IDENTIFICATION,histo_retour_vehicule.DATE_SAVE FROM histo_retour_vehicule JOIN retour_vehicule ON retour_vehicule.ID_RETOUR = histo_retour_vehicule.ID_RETOUR JOIN users ON users.USER_ID = histo_retour_vehicule.USER_ID  WHERE 1';

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";


		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}

		$search = !empty($_POST['search']['value']) ? (' AND (HISTO_RETOUR_ID LIKE "%' . $var_search . '%" 
			OR histo_retour_vehicule.IS_VALIDATED LIKE "%' . $var_search . '%" 
			OR histo_retour_vehicule.COMMENTAIRE LIKE "%' . $var_search . '%" 
			OR IDENTIFICATION LIKE "%' . $var_search . '%"
			OR histo_retour_vehicule.DATE_SAVE  LIKE "%' . $var_search . '%")') : '';

		$order_by = '';

		$order_column = array('','HISTO_RETOUR_ID','histo_retour_vehicule.IS_VALIDATED','histo_retour_vehicule.COMMENTAIRE','IDENTIFICATION ',' histo_retour_vehicule.DATE_SAVE');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'HISTO_RETOUR_ID DESC DESC';
		}
		else
		{
			$order_by = ' ORDER BY HISTO_RETOUR_ID  DESC';
		}

		$critaire = "";
		$critaire.= ' AND retour_vehicule.ID_RETOUR = '.$id;


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

			if($row->IS_VALIDATED == 0)
			{
				$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';
			}
			else if($row->IS_VALIDATED == 1)
			{
				$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
			}
			else if($row->IS_VALIDATED == 2)
			{
				$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

			}

			$sub_array[] = $row->COMMENTAIRE;
			$sub_array[] = $row->IDENTIFICATION;
			$sub_array[] = date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

			// $sub_array[]=$option;
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