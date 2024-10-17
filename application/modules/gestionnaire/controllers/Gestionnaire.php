<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 15/10/2024
	Desc      : CRUD du Gestionnaire
*/
	class Gestionnaire extends CI_Controller
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
			$data['title'] = 'Liste';

			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$motif_activation = $this->getBindParms('ID_MOTIF,DESC_MOTIF', 'motif', '1 AND ID_TYPE=1', 'DESC_MOTIF ASC');
			$data['motif_ativ'] = $this->ModelPs->getRequete($proce_requete, $motif_activation);

			$motif_desactivation = $this->getBindParms('ID_MOTIF,DESC_MOTIF', 'motif', '1 AND ID_TYPE=2', 'DESC_MOTIF ASC');
			$data['motif_des'] = $this->ModelPs->getRequete($proce_requete, $motif_desactivation);
			
			$this->load->view('Gestionnaire_liste_view',$data);
		}

		//Fonction pour l'affichage
		function listing()
		{
			$critaire = '' ;
			
			$query_principal='SELECT ID_GESTIONNAIRE_VEHICULE,gestionnaire_vehicule.NOM,gestionnaire_vehicule.PRENOM,gestionnaire_vehicule.ADRESSE_PHYSIQUE,gestionnaire_vehicule.NUMERO_TELEPHONE,DESCR_GENRE,gestionnaire_vehicule.ADRESSE_MAIL,PROVINCE_NAME,COMMUNE_NAME,ZONE_NAME,COLLINE_NAME,gestionnaire_vehicule.PHOTO_PROFIL,gestionnaire_vehicule.PROPRIETAIRE_ID,if(TYPE_PROPRIETAIRE_ID=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,TYPE_PROPRIETAIRE_ID,LOGO,proprietaire.PHOTO_PASSPORT,gestionnaire_vehicule.IS_ACTIVE,gestionnaire_vehicule.DATE_INSERTION FROM gestionnaire_vehicule JOIN syst_genre ON syst_genre.GENRE_ID = gestionnaire_vehicule.GENRE_ID JOIN provinces ON provinces.PROVINCE_ID = gestionnaire_vehicule.PROVINCE_ID JOIN communes ON communes.COMMUNE_ID = gestionnaire_vehicule.COMMUNE_ID JOIN zones ON zones.ZONE_ID = gestionnaire_vehicule.ZONE_ID JOIN collines ON collines.COLLINE_ID = gestionnaire_vehicule.COLLINE_ID JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = gestionnaire_vehicule.PROPRIETAIRE_ID  WHERE 1 ';


			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$limit = ' LIMIT 0,10';
			if($_POST['length'] != -1)
			{
				$limit = ' LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}

			$order_by='';
			$order_column=array('gestionnaire_vehicule.NOM','gestionnaire_vehicule.PRENOM','gestionnaire_vehicule.ADRESSE_PHYSIQUE','gestionnaire_vehicule.NUMERO_TELEPHONE','DESCR_GENRE','gestionnaire_vehicule.ADRESSE_MAIL','PROVINCE_NAME','COMMUNE_NAME','ZONE_NAME','COLLINE_NAME','NOM_PROPRIETAIRE','PRENOM_PROPRIETAIRE','gestionnaire_vehicule.DATE_INSERTION');

			$order_by=isset($_POST['order']) ? ' ORDER BY '.$order_column[$_POST['order']['0']['column']].' ' .$_POST['order']['0']['dir'] : ' ORDER BY ID_GESTIONNAIRE_VEHICULE DESC';

			$order_by = ' ORDER BY ID_GESTIONNAIRE_VEHICULE DESC';

			$search=!empty($_POST['search']['value']) ? (" AND (gestionnaire_vehicule.NOM LIKE '%$var_search%' OR gestionnaire_vehicule.PRENOM LIKE '%$var_search%' OR gestionnaire_vehicule.ADRESSE_PHYSIQUE LIKE '%$var_search%' OR gestionnaire_vehicule.NUMERO_TELEPHONE LIKE '%$var_search%' OR DESCR_GENRE LIKE '%$var_search%' OR gestionnaire_vehicule.ADRESSE_MAIL LIKE '%$var_search%' OR PROVINCE_NAME LIKE '%$var_search%' OR COMMUNE_NAME LIKE '%$var_search%' OR ZONE_NAME LIKE '%$var_search%' OR COLLINE_NAME LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR PRENOM_PROPRIETAIRE LIKE '%$var_search%' OR gestionnaire_vehicule.DATE_INSERTION LIKE '%$var_search%' )"):'';

			$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. ''. $limit;

			$query_filter=$query_principal.''.$critaire.''.$search;

			//print_r($query_filter);die();

			$fetch_data=$this->Model->datatable($query_secondaire);

			$data=array();
			$u=1;
			foreach ($fetch_data as $row)
			{
				$sub_array=array();
				$sub_array[]=$u++;

				$source = !empty($row->PHOTO_PROFIL) ? base_url('upload/gestionnaire/'.$row->PHOTO_PROFIL) : base_url('upload/gestionnaire/user.png');

				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px;" src="' . $source . '"></a></td><td>'.$row->NOM.' '.$row->PRENOM.'</td></tr></tbody></table></a>';
				$sub_array[]=$row->NUMERO_TELEPHONE;
				$sub_array[]=$row->ADRESSE_MAIL;

				if($row->TYPE_PROPRIETAIRE_ID == 1 && empty($row->LOGO)){
					$sub_array[] ='<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><i class="bi bi-info-square h5" ></i>
					style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->proprio_desc . '</td></tr></tbody></a>
					';
				}
				else if($row->TYPE_PROPRIETAIRE_ID == 1 && !empty($row->LOGO)){

					$sub_array[] = '<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></td><td> '.'     '.' ' . $row->proprio_desc . '</td></tr></tbody></a>';
				}
				else if(!empty($row->PHOTO_PASSPORT))
				{
					$sub_array[] = ' <tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></td><td> '.'     '.' ' . $row->proprio_desc . '</td></tr></tbody></a>';
				}
				else
				{
					$sub_array[] ='<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><i class="bi bi-info-square h5" ></i>
					style="border-radius:50%;width:30px;height:30px" class="bi bi-user round text-dark"> '.'  &nbsp;   '.' ' . $row->proprio_desc . '</td></tr></tbody></a>
					';
				}



				if($row->IS_ACTIVE == 1){

					$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<center title='.lang('checkbox_desactiver').'><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_desactive('.$row->ID_GESTIONNAIRE_VEHICULE.')" checked >
					<span class="slider round"></span>
					</label></center>
					</form>

					';
				}
				else if($row->IS_ACTIVE == 2){
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">
					
					
					<center title='.lang('btn_active').'><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_active('.$row->ID_GESTIONNAIRE_VEHICULE.')">
					<span class="slider round"></span>
					</label></center>
					
					</form>

					';
				}

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_INSERTION));


				$option = '<div class="dropdown text-center">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>	
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				$option .= "<a class='btn-md' href='" . base_url('gestionnaire/Gestionnaire/ajouter/'.md5($row->ID_GESTIONNAIRE_VEHICULE)) . "'><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_modifier')."</li></a>";

				$option .= "<a class='btn-md' href='" . base_url('gestionnaire/Gestionnaire/Detail/'.md5($row->ID_GESTIONNAIRE_VEHICULE)) . "'><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-info-square'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_detail')."</li></a>";

				
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



       // Appel du formulaire d'enregistrement
		function ajouter()
		{			
			$ID_GESTIONNAIRE_VEHICULE = $this->uri->segment(4);

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			$data['btn'] = "Enregistrer";
			$data['title']="Enregistrement du Gestionnaire";

			$gestionnaire = array('ID_GESTIONNAIRE_VEHICULE'=>NULL,'NOM'=>NULL,'PRENOM'=>NULL,'ADRESSE_PHYSIQUE'=>NULL,'NUMERO_TELEPHONE'=>NULL,'GENRE_ID'=>NULL,'ADRESSE_MAIL'=>NULL,'PROVINCE_ID'=>NULL,'COMMUNE_ID'=>NULL,'ZONE_ID'=>NULL,'COLLINE_ID'=>NULL,'PHOTO_PROFIL'=>NULL,'PROPRIETAIRE_ID'=>NULL,'CARTE_ID'=>NULL);
			
			$genre = $this->getBindParms('GENRE_ID,DESCR_GENRE','syst_genre',' 1 ','DESCR_GENRE ASC');
			$genre = $this->ModelPs->getRequete($psgetrequete, $genre);

			$province = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME','provinces',' 1 ','PROVINCE_NAME ASC');
			$province = $this->ModelPs->getRequete($psgetrequete, $province);

			$commune = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME','communes',' 1 ','COMMUNE_NAME ASC');
			$commune = $this->ModelPs->getRequete($psgetrequete, $commune);

			$zone = $this->getBindParms('ZONE_ID,ZONE_NAME','zones',' 1 ','ZONE_NAME ASC');
			$zone = $this->ModelPs->getRequete($psgetrequete, $zone);

			$colline = $this->getBindParms('COLLINE_ID,COLLINE_NAME','collines',' 1 ','COLLINE_NAME ASC');
			$colline = $this->ModelPs->getRequete($psgetrequete, $colline);

			$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');

			$proprio=str_replace('\"', '"', $proprio);
			$proprio=str_replace('\n', '', $proprio);
			$proprio=str_replace('\"', '', $proprio);

			$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);

			if(!empty($ID_GESTIONNAIRE_VEHICULE))
			{
				$data['btn'] = "Modifier";
				$data['title'] = "Modification du Gestionnaire";

				$gestionnaire = $this->Model->getRequeteOne("SELECT ID_GESTIONNAIRE_VEHICULE,NOM,PRENOM,ADRESSE_PHYSIQUE,NUMERO_TELEPHONE,GENRE_ID,ADRESSE_MAIL,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,PHOTO_PROFIL,PROPRIETAIRE_ID,CARTE_ID FROM gestionnaire_vehicule  WHERE md5(ID_GESTIONNAIRE_VEHICULE)='".$ID_GESTIONNAIRE_VEHICULE."'");

				$genre = $this->getBindParms('GENRE_ID,DESCR_GENRE','syst_genre',' 1 ','DESCR_GENRE ASC');
				$genre = $this->ModelPs->getRequete($psgetrequete, $genre);

				$province = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME','provinces',' 1 ','PROVINCE_NAME ASC');
				$province = $this->ModelPs->getRequete($psgetrequete, $province);

				$commune = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME','communes',' 1 ','COMMUNE_NAME ASC');
				$commune = $this->ModelPs->getRequete($psgetrequete, $commune);

				$zone = $this->getBindParms('ZONE_ID,ZONE_NAME','zones',' 1 ','ZONE_NAME ASC');
				$zone = $this->ModelPs->getRequete($psgetrequete, $zone);

				$colline = $this->getBindParms('COLLINE_ID,COLLINE_NAME','collines',' 1 ','COLLINE_NAME ASC');
				$colline = $this->ModelPs->getRequete($psgetrequete, $colline);

				$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');

				$proprio=str_replace('\"', '"', $proprio);
				$proprio=str_replace('\n', '', $proprio);
				$proprio=str_replace('\"', '', $proprio);

				$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);
			}

			$data['gestionnaire']=$gestionnaire;
			$data['genre']=$genre;
			$data['province']=$province;
			$data['commune']=$commune;
			$data['zone']=$zone;
			$data['colline']=$colline;
			$data['proprio']=$proprio;

			$this->load->view('Gestionnaire_add_View',$data);
		}



		 //Fonction pour la selection des communes
		function get_communes($PROVINCE_ID)
		{
			$html="<option value='0'>".lang('selectionner')."</option>";

			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$my_select_communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1 AND PROVINCE_ID='.$PROVINCE_ID.'', '`COMMUNE_NAME` ASC');
			$communes = $this->ModelPs->getRequete($proce_requete, $my_select_communes);
			foreach ($communes as $commune)
			{
				$html.="<option value='".$commune['COMMUNE_ID']."'>".$commune['COMMUNE_NAME']."</option>";
			}
			echo json_encode($html);
		}

		   //Fonction pour la selection des zones
		function get_zones($COMMUNE_ID)
		{
			$html='<option value="">'.lang('selectionner').'</option>';
			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1 AND COMMUNE_ID='.$COMMUNE_ID.'', '`ZONE_NAME` ASC');
			$zones = $this->ModelPs->getRequete($proce_requete, $my_select_zones);



			foreach ($zones as $zone)
			{
				$html.='<option value="'.$zone['ZONE_ID'].'">'.$zone['ZONE_NAME'].'</option>';
			}
			echo json_encode($html);
		}

	//Fonction pour la selection des collines
		function get_collines($ZONE_ID)
		{
			$html='<option value="">'.lang('selectionner').'</option>';

			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID='.$ZONE_ID.'', '`COLLINE_NAME` ASC');
			$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);
			foreach ($collines as $colline)
			{
				$html.='<option value="'.$colline['COLLINE_ID'].'">'.$colline['COLLINE_NAME'].'</option>';
			}
			echo json_encode($html);
		}


		   //PERMET L'UPLOAD 
		public function upload_file($input_name)
		{
			$nom_file = $_FILES[$input_name]['tmp_name'];
			$nom_champ = $_FILES[$input_name]['name'];
			$ext=pathinfo($nom_champ, PATHINFO_EXTENSION);
			$repertoire_fichier = FCPATH . 'upload/gestionnaire/';
			$code=uniqid();
			$name=$code. 'GESTIONNAIRE' .$ext;
			$file_link = $repertoire_fichier . $name;

			if (!is_dir($repertoire_fichier)) {
				mkdir($repertoire_fichier, 0777, TRUE);
			}
			move_uploaded_file($nom_file, $file_link);
			return $name;
		}

		
		//Fonction pour l'enregistrement & la mise à jour
		function save()
		{
			$ID_GESTIONNAIRE_VEHICULE = $this->input->post('ID_GESTIONNAIRE_VEHICULE');

			if(empty($ID_GESTIONNAIRE_VEHICULE))   //Controle d'enregistrement
			{

				$this->form_validation->set_rules('NOM','NOM','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PRENOM','PRENOM','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ADRESSE_PHYSIQUE','ADRESSE_PHYSIQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules("NUMERO_TELEPHONE"," ","trim|required|is_unique[gestionnaire_vehicule.NUMERO_TELEPHONE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le numéro existe déjà !</font>'));

				$this->form_validation->set_rules('GENRE_ID','GENRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules("ADRESSE_MAIL"," ","trim|required|is_unique[gestionnaire_vehicule.ADRESSE_MAIL]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Email existe déjà !</font>'));

				$this->form_validation->set_rules("ADRESSE_MAIL"," ","trim|required|is_unique[users.USER_NAME]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Email existe déjà !</font>'));

				$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				if (empty($_FILES['PHOTO_PROFIL_NEW']['name']))
				{
					$this->form_validation->set_rules('PHOTO_PROFIL_NEW','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}

				if (empty($_FILES['CARTE_ID_NEW']['name']))
				{
					$this->form_validation->set_rules('CARTE_ID_NEW','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}	

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				

				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					//Check mail in table users

					// $psgetrequete = "CALL `getRequete`(?,?,?,?);";

					// $mail = $this->input->post('ADRESSE_MAIL');

					// $check_existe_mail_user = $this->getBindParms('USER_NAME','users','  USER_NAME = "'.$mail.'"','USER_ID ASC');

					// $check_existe_mail_user = str_replace('\"', '"', $check_existe_mail_user);
					// $check_existe_mail_user = str_replace('\n', '', $check_existe_mail_user);
					// $check_existe_mail_user = str_replace('\"', '', $check_existe_mail_user);

					// $check_existe_mail_user = $this->ModelPs->getRequete($psgetrequete, $check_existe_mail_user);

					// if(!empty($check_existe_mail_user))
					// {
					// 	$message['message']='<div class="alert alert-danger text-center" id="message">Adresse mail existe déjà !</div>';
					// 	$this->session->set_flashdata($message);
					// 	redirect(base_url('gestionnaire/Gestionnaire/ajouter'));
					// }
					// else
					// {

					$PHOTO_PROFIL = $this->upload_file('PHOTO_PROFIL_NEW');
					$CARTE_ID = $this->upload_file('CARTE_ID_NEW');

					$data = array(
						'NOM'=>$this->input->post('NOM'),
						'PRENOM'=>$this->input->post('PRENOM'),
						'ADRESSE_PHYSIQUE'=>$this->input->post('ADRESSE_PHYSIQUE'),
						'NUMERO_TELEPHONE'=>$this->input->post('NUMERO_TELEPHONE'),
						'GENRE_ID'=>$this->input->post('GENRE_ID'),
						'ADRESSE_MAIL'=>$this->input->post('ADRESSE_MAIL'),
						'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
						'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
						'ZONE_ID'=>$this->input->post('ZONE_ID'),
						'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
						'PHOTO_PROFIL'=>$PHOTO_PROFIL,
						'CARTE_ID'=>$CARTE_ID,
						'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
					);


					$table = "gestionnaire_vehicule";

					$ID_GESTIONNAIRE_VEHICULE = $this->Model->insert_last_id($table,$data);

						// Enregistrement dans la table users

					$NOM = $this->input->post('NOM');
					$PRENOM = $this->input->post('PRENOM');
					$password=12345;

					$data_user = array(
						'IDENTIFICATION' => $NOM.' '.$PRENOM,
						'USER_NAME'=> $this->input->post('ADRESSE_MAIL'),
						'PASSWORD'=>md5($password),
						'TELEPHONE'=> $this->input->post('NUMERO_TELEPHONE'),
						'PROFIL_ID'=> 4,
						'ID_GESTIONNAIRE_VEHICULE'=> $ID_GESTIONNAIRE_VEHICULE,
					);

					$create = $this->Model->create('users',$data_user);


					if ($create)
					{
						$message['message']='<div class="alert alert-success text-center" id="message">Enregistrement avec succès</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('gestionnaire/Gestionnaire'));

					}else
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">Echec d\'enregistrement </div>';
						$this->session->set_flashdata($message);
						redirect(base_url('gestionnaire/Gestionnaire'));

					}

					// }

				}


			}else // Controle de mise à jour
			{

				$this->form_validation->set_rules('NOM','NOM','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PRENOM','PRENOM','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ADRESSE_PHYSIQUE','ADRESSE_PHYSIQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('NUMERO_TELEPHONE','NUMERO_TELEPHONE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('GENRE_ID','GENRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ADRESSE_MAIL','ADRESSE_MAIL','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));	

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$ID_GESTIONNAIRE_VEHICULE = $this->input->post('ID_GESTIONNAIRE_VEHICULE');

					$psgetrequete = "CALL `getRequete`(?,?,?,?);";

					//Check phone numbers

					$phone_numbers = $this->input->post('NUMERO_TELEPHONE');

					$check_existe_phone = $this->getBindParms('NUMERO_TELEPHONE','gestionnaire_vehicule',' ID_GESTIONNAIRE_VEHICULE !='.$ID_GESTIONNAIRE_VEHICULE.' and NUMERO_TELEPHONE="'.$phone_numbers.'"','ID_GESTIONNAIRE_VEHICULE ASC');

					$check_existe_phone = str_replace('\"', '"', $check_existe_phone);
					$check_existe_phone = str_replace('\n', '', $check_existe_phone);
					$check_existe_phone = str_replace('\"', '', $check_existe_phone);

					$check_existe_phone = $this->ModelPs->getRequete($psgetrequete, $check_existe_phone);

					//Check mail

					$mail = $this->input->post('ADRESSE_MAIL');
					
					$check_existe_mail = $this->getBindParms('ADRESSE_MAIL','gestionnaire_vehicule',' ID_GESTIONNAIRE_VEHICULE !='.$ID_GESTIONNAIRE_VEHICULE.' and ADRESSE_MAIL="'.$mail.'"','ID_GESTIONNAIRE_VEHICULE ASC');

					$check_existe_mail = str_replace('\"', '"', $check_existe_mail);
					$check_existe_mail = str_replace('\n', '', $check_existe_mail);
					$check_existe_mail = str_replace('\"', '', $check_existe_mail);

					$check_existe_mail = $this->ModelPs->getRequete($psgetrequete, $check_existe_mail);


					$getUser = $this->Model->getOne('users',array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE));


					$check_existe_mail_user = $this->getBindParms('USER_NAME','users',' USER_ID !='.$getUser['USER_ID'].' and USER_NAME="'.$mail.'"','USER_ID ASC');

					$check_existe_mail_user = str_replace('\"', '"', $check_existe_mail_user);
					$check_existe_mail_user = str_replace('\n', '', $check_existe_mail_user);
					$check_existe_mail_user = str_replace('\"', '', $check_existe_mail_user);

					$check_existe_mail_user = $this->ModelPs->getRequete($psgetrequete, $check_existe_mail_user);

					// $check_existe_mail_user = $this->Model->getRequete('SELECT USER_NAME FROM users WHERE USER_ID !='.$getUser['USER_ID'].' AND USER_NAME="'.$mail.'"');

					if(!empty($check_existe_phone))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le numéro de téléphone existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('gestionnaire/Gestionnaire/ajouter'));
					}
					else if(!empty($check_existe_mail) || !empty($check_existe_mail_user))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">Adresse mail existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('gestionnaire/Gestionnaire/ajouter'));
					}
					else
					{
						//Photo de profil
						if (!empty($_FILES["PHOTO_PROFIL_NEW"]["tmp_name"])) {
							$PHOTO_PROFIL = $this->upload_file('PHOTO_PROFIL_NEW');
						}else{
							$PHOTO_PROFIL = $this->input->post('PHOTO_PROFIL');
						}

						//DOC carte identité

						if (!empty($_FILES["CARTE_ID_NEW"]["tmp_name"])) {
							$CARTE_ID = $this->upload_file('CARTE_ID_NEW');
						}else{
							$CARTE_ID = $this->input->post('CARTE_ID');
						}
						
						$data=array(
							'NOM'=>$this->input->post('NOM'),
							'PRENOM'=>$this->input->post('PRENOM'),
							'ADRESSE_PHYSIQUE'=>$this->input->post('ADRESSE_PHYSIQUE'),
							'NUMERO_TELEPHONE'=>$this->input->post('NUMERO_TELEPHONE'),
							'GENRE_ID'=>$this->input->post('GENRE_ID'),
							'ADRESSE_MAIL'=>$this->input->post('ADRESSE_MAIL'),
							'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
							'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
							'ZONE_ID'=>$this->input->post('ZONE_ID'),
							'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
							'PHOTO_PROFIL'=>$PHOTO_PROFIL,
							'CARTE_ID'=>$CARTE_ID,
							'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),

						);

						$table = "gestionnaire_vehicule";

						$update = $this->Model->update($table,array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE),$data);

						// Mise à jour dans la table users

						$NOM = $this->input->post('NOM');
						$PRENOM = $this->input->post('PRENOM');

						$data_user = array(
							'IDENTIFICATION' => $NOM.' '.$PRENOM,
							'USER_NAME'=> $this->input->post('ADRESSE_MAIL'),
							'TELEPHONE'=> $this->input->post('NUMERO_TELEPHONE'),
						);

						$update_user = $this->Model->update('users',array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE),$data_user);

						if ($update && $update_user)
						{
							$message['message']='<div class="alert alert-success text-center" id="message">Mise à jour avec succès <i class="fa fa-check"></i></div>';
							$this->session->set_flashdata($message);
							redirect(base_url('gestionnaire/Gestionnaire'));

						}else
						{
							$message['message']='<div class="alert alert-danger text-center" id="message">Echec de mise à jour</div>';
							$this->session->set_flashdata($message);
							redirect(base_url('gestionnaire/Gestionnaire'));

						}
						
					}

				}
			}

			
		}


		//Fonction pour activer/desactiver un gestionnaire
		function active_desactive($status)
		{
			$USER_ID = $this->session->userdata('USER_ID');

			if($status == 1){

				$ID_GESTIONNAIRE_VEHICULE = $this->input->post('ID_GESTIONNAIRE_VEHICULE');
				$ID_MOTIF = $this->input->post('ID_MOTIF_des');

				$this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE),array('IS_ACTIVE'=>2));
				
				$data = array('USER_ID'=>$USER_ID,'IS_ACTIVE'=>2,'ID_MOTIF'=>$ID_MOTIF,'ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE);

				$result = $this->Model->create('historique_active_gestionnaire',$data);

				$status = 2;

			}else if($status == 2){
				
				$ID_GESTIONNAIRE_VEHICULE = $this->input->post('ID_GESTIONNAIRE_VEHICULE_I');
				$ID_MOTIF = $this->input->post('ID_MOTIF');

				$this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE),array('IS_ACTIVE'=>1));

				$data = array('USER_ID'=>$USER_ID,'IS_ACTIVE'=>1,'ID_MOTIF'=>$ID_MOTIF,'ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE);
				$result = $this->Model->create('historique_active_gestionnaire',$data);

				$status = 1;
			}

			echo json_encode(array('status'=>$status));

		}

		//function pour l'affichage de detail

		function Detail($ID_GESTIONNAIRE_VEHICULE)
		{
			$gestionnaire = $this->Model->getRequeteOne('SELECT ID_GESTIONNAIRE_VEHICULE,gestionnaire_vehicule.NOM,gestionnaire_vehicule.PRENOM,gestionnaire_vehicule.ADRESSE_PHYSIQUE,gestionnaire_vehicule.NUMERO_TELEPHONE,DESCR_GENRE,gestionnaire_vehicule.ADRESSE_MAIL,PROVINCE_NAME,COMMUNE_NAME,ZONE_NAME,COLLINE_NAME,provinces.PROVINCE_ID,communes.COMMUNE_ID,zones.ZONE_ID,collines.COLLINE_ID,gestionnaire_vehicule.PHOTO_PROFIL,CARTE_ID,gestionnaire_vehicule.PROPRIETAIRE_ID,if(TYPE_PROPRIETAIRE_ID=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,proprietaire.PHOTO_PASSPORT,gestionnaire_vehicule.IS_ACTIVE,gestionnaire_vehicule.DATE_INSERTION FROM gestionnaire_vehicule JOIN syst_genre ON syst_genre.GENRE_ID = gestionnaire_vehicule.GENRE_ID JOIN provinces ON provinces.PROVINCE_ID = gestionnaire_vehicule.PROVINCE_ID JOIN communes ON communes.COMMUNE_ID = gestionnaire_vehicule.COMMUNE_ID JOIN zones ON zones.ZONE_ID = gestionnaire_vehicule.ZONE_ID JOIN collines ON collines.COLLINE_ID = gestionnaire_vehicule.COLLINE_ID JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = gestionnaire_vehicule.PROPRIETAIRE_ID  WHERE 1 AND md5(ID_GESTIONNAIRE_VEHICULE)="'.$ID_GESTIONNAIRE_VEHICULE.'"');

			$data['gestionnaire']=$gestionnaire;

			$this->load->view('Gestionnaire_detail_view',$data);

		}


		//liste d'Historique activation & desactivation gestionnaire
		function get_histo_activate()
		{
			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');
			$ID_GESTIONNAIRE_VEHICULE = $this->input->post('ID_GESTIONNAIRE_VEHICULE');

			$query_principal = 'SELECT ID_HISTO_ACTIVE_GESTIONNAIRE ,historique_active_gestionnaire.IS_ACTIVE,DESC_MOTIF,IDENTIFICATION,historique_active_gestionnaire.DATE_SAVE FROM historique_active_gestionnaire join gestionnaire_vehicule ON gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE =historique_active_gestionnaire.ID_GESTIONNAIRE_VEHICULE JOIN motif ON motif.ID_MOTIF = historique_active_gestionnaire.ID_MOTIF join users ON users.USER_ID = historique_active_gestionnaire.USER_ID WHERE 1 ';

			$order_column = array('','ID_HISTO_ACTIVE_GESTIONNAIRE','historique_active_gestionnaire.IS_ACTIVE','DESC_MOTIF','IDENTIFICATION','historique_active_gestionnaire.DATE_SAVE');


			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);

			$search = !empty($_POST['search']['value']) ? (' AND (DESC_MOTIF LIKE "%' . $var_search . '%" 
				OR IDENTIFICATION LIKE "%' . $var_search . '%"
				OR historique_active_gestionnaire.DATE_SAVE LIKE "%' . $var_search . '%" 
			)') : '';


			$group = "";

			$critaire = " ";

			if ($PROFIL_ID==1) {
				$critaire = " and gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE=".$ID_GESTIONNAIRE_VEHICULE;
			}else{
				$critaire = " and gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE=".$ID_GESTIONNAIRE_VEHICULE." and users.USER_ID=".$USER_ID;
			}

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}

			$order_by = '';

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'ID_HISTO_ACTIVE_GESTIONNAIRE ASC';
			}
			else
			{
				$order_by = ' ORDER BY ID_HISTO_ACTIVE_GESTIONNAIRE ASC';
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
				if($row->IS_ACTIVE == 1)
				{
					$sub_array[] = '<font><i class="text-success fa fa-check" title="Activé"></i></font>';
				}
				if($row->IS_ACTIVE == 2)
				{
					$sub_array[] = '<font><i class="text-danger fa fa-close" title="Désactivé"></i></font>';
				}
				
				$sub_array[] = $row->DESC_MOTIF;
				$sub_array[] = $row->IDENTIFICATION;
				$sub_array[] =  date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));
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

		// Fonction pour recuperer la localité

		function get_localite(){

			$PROVINCE_ID = $this->input->post('PROVINCE_ID');
			$COMMUNE_ID = $this->input->post('COMMUNE_ID');
			$ZONE_ID = $this->input->post('ZONE_ID');
			$COLLINE_ID = $this->input->post('COLLINE_ID');

			$html_prov ="<option value='0'>".lang('selectionner')."</option>";
			$html_com ="<option value='0'>".lang('selectionner')."</option>";
			$html_zon ="<option value='0'>".lang('selectionner')."</option>";
			$html_coll ="<option value='0'>".lang('selectionner')."</option>";

			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1 ', '`PROVINCE_NAME` ASC');
			$provinces = $this->ModelPs->getRequete($proce_requete, $provinces);
			foreach ($provinces as $province)
			{
				if($province['PROVINCE_ID'] == $PROVINCE_ID)
				{
					$html_prov.="<option value='".$province['PROVINCE_ID']."' selected>".$province['PROVINCE_NAME']."</option>";
				}
				else{
					$html_prov.="<option value='".$province['PROVINCE_ID']."'>".$province['PROVINCE_NAME']."</option>";
				}
			}


			$communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1 AND PROVINCE_ID = '.$PROVINCE_ID.'', '`COMMUNE_NAME` ASC');

			$communes = $this->ModelPs->getRequete($proce_requete, $communes);
			foreach ($communes as $commune)
			{
				if($commune['COMMUNE_ID'] == $COMMUNE_ID)
				{
					$html_com.="<option value='".$commune['COMMUNE_ID']."' selected>".$commune['COMMUNE_NAME']."</option>";
				}
				else{
					$html_com.="<option value='".$commune['COMMUNE_ID']."'>".$commune['COMMUNE_NAME']."</option>";
				}
			}

			$zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1 AND COMMUNE_ID = '.$COMMUNE_ID.'', '`ZONE_NAME` ASC');

			$zones = $this->ModelPs->getRequete($proce_requete, $zones);
			foreach ($zones as $zone)
			{
				if($zone['ZONE_ID'] == $ZONE_ID)
				{
					$html_zon.="<option value='".$zone['ZONE_ID']."' selected>".$zone['ZONE_NAME']."</option>";
				}
				else{
					$html_zon.="<option value='".$zone['ZONE_ID']."'>".$zone['ZONE_NAME']."</option>";
				}
			}

			$collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID = '.$ZONE_ID.'', '`COLLINE_NAME` ASC');

			$collines = $this->ModelPs->getRequete($proce_requete, $collines);
			foreach ($collines as $colline)
			{
				if($colline['COLLINE_ID'] == $COLLINE_ID)
				{
					$html_coll.="<option value='".$colline['COLLINE_ID']."' selected>".$colline['COLLINE_NAME']."</option>";
				}
				else{
					$html_coll.="<option value='".$colline['COLLINE_ID']."'>".$colline['COLLINE_NAME']."</option>";
				}
			}

			$output = array('html_prov'=>$html_prov,'html_com'=>$html_com,'html_zon'=>$html_zon,'html_coll'=>$html_coll);

			echo json_encode($output);
		}


		// Fonction pour la modification du gestionnaire par detail des champs
		function modif_detail()
		{
			$ID_GESTIONNAIRE_VEHICULE_modif = $this->input->post('ID_GESTIONNAIRE_VEHICULE_modif');
			$champ = $this->input->post('champ');
			$status = 0;

			$NOM_modif = $this->input->post('NOM_modif');
			$PRENOM_modif = $this->input->post('PRENOM_modif');
			$EMAIL_modif = $this->input->post('EMAIL_modif');
			$TELEPHONE_modif = $this->input->post('TELEPHONE_modif');
			$DATE_NAISSANCE_modif = $this->input->post('DATE_NAISSANCE_modif');
			$CNI_modif = $this->input->post('CNI_modif');
			$ADRESSE_modif = $this->input->post('ADRESSE_modif');
			$PROVINCE_ID_modif = $this->input->post('PROVINCE_ID_modif');
			$COMMUNE_ID_modif = $this->input->post('COMMUNE_ID_modif');
			$ZONE_ID_modif = $this->input->post('ZONE_ID_modif');
			$COLLINE_ID_modif = $this->input->post('COLLINE_ID_modif');

			if($champ == 'NOM')
			{
				$result = $this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE_modif),array('NOM'=>$NOM_modif,'PRENOM'=>$PRENOM_modif));
			}
			else if($champ == 'EMAIL')
			{
				$result = $this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE_modif),array('ADRESSE_MAIL'=>$EMAIL_modif));
			}
			else if($champ == 'TELEPHONE')
			{
				$result = $this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE_modif),array('NUMERO_TELEPHONE'=>$TELEPHONE_modif));
			}
			// else if($champ == 'DATE_NAISSANCE')
			// {
			// 	$result = $this->Model->update('chauffeur', array('CHAUFFEUR_ID'=>$ID_GESTIONNAIRE_VEHICULE_modifID_GESTIONNAIRE_VEHICULE_modif),array('DATE_NAISSANCE'=>$DATE_NAISSANCE_modif));
			// }
			// else if($champ == 'CNI')
			// {
			// 	$result = $this->Model->update('chauffeur', array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID_modif),array('NUMERO_CARTE_IDENTITE'=>$CNI_modif));
			// }
			else if($champ == 'ADRESSE')
			{
				$result = $this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE_modif),array('ADRESSE_PHYSIQUE'=>$ADRESSE_modif));
			}
			else if($champ == 'LOCALITE')
			{
				$result = $this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE_modif),array('PROVINCE_ID'=>$PROVINCE_ID_modif,'COMMUNE_ID'=>$COMMUNE_ID_modif,'ZONE_ID'=>$ZONE_ID_modif,'COLLINE_ID'=>$COLLINE_ID_modif));
			}
			else if($champ == 'PHOTO')
			{
				$PHOTO_PROFIL = $this->upload_file('PHOTO_modif');

				// $file_photo = $this->upload_document($_FILES['PHOTO_modif']['tmp_name'],$_FILES['PHOTO_modif']['name']);

				$result = $this->Model->update('gestionnaire_vehicule', array('ID_GESTIONNAIRE_VEHICULE'=>$ID_GESTIONNAIRE_VEHICULE_modif),array('PHOTO_PROFIL'=>$PHOTO_PROFIL));
			}

			if($result){
				$status = 1;
			}
			echo json_encode(array('status'=>$status));

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