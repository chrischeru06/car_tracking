<?php

/**Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * 68071895
 * Le 8/2/2024
 * CRUD Proprietaire
 */
class Proprietaire extends CI_Controller
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


	//Fonction pour l'affichage de la page d'enregistrement de proprietaires
	function index(){
		$PROPRIETAIRE_ID=$this->uri->segment(4);
		$data['btn']="Enregistrer";
		$data['title']="NOUVEAU PROPRIETAIRE";
		$proprietaire = array('PROPRIETAIRE_ID'=>NULL,'TYPE_PROPRIETAIRE_ID'=>NULL,'TYPE_SOCIETE_ID'=>NULL,'NOM_PROPRIETAIRE'=>NULL,'PRENOM_PROPRIETAIRE'=>NULL,'PERSONNE_REFERENCE'=>NULL,'EMAIL'=>NULL,'TELEPHONE'=>NULL,'CNI_OU_NIF'=>NULL,'RC'=>NULL,'PROVINCE_ID'=>NULL,'COMMUNE_ID'=>NULL,'ZONE_ID'=>NULL,'COLLINE_ID'=>NULL,'ADRESSE'=>NULL,'PHOTO_PASSPORT'=>NULL,'LOGO'=>NULL,'FILE_CNI_PASSPORT'=>NULL,'FILE_RC'=>NULL,'FILE_NIF'=>NULL,'CATEGORIE_ID'=>NULL,'COUNTRY_ID'=>NULL);

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$my_select_provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1', '`PROVINCE_NAME` ASC');
		$provinces = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);
		$data['provinces']=$provinces;

		$pay = $this->getBindParms('CommonName,COUNTRY_ID', 'countries', '1', 'CommonName ASC');
		$categorieun = $this->getBindParms('DESC_CATEGORIE,CATEGORIE_ID', 'categories', '1', 'DESC_CATEGORIE ASC');
		$data['catego'] =$this->ModelPs->getRequete($proce_requete, $categorieun);

		
		$data['pays'] =$this->ModelPs->getRequete($proce_requete, $pay);

		// $countries1=$this->Model->getRequete('SELECT COUNTRY_ID,CommonName,`ITU-T_Telephone_Code` FROM `countries` WHERE `ITU-T_Telephone_Code` <> "" ORDER BY `CommonName` ASC');

		$countries1 = $this->getBindParms('COUNTRY_ID,CommonName,`ITU-T_Telephone_Code`', 'countries', '`ITU-T_Telephone_Code` <> "" ', 'CommonName ASC');
		$countries1=str_replace('\"', '"', $countries1);
		$countries1=str_replace('\n', '', $countries1);
		$countries1=str_replace('\"', '', $countries1);
		$data['countries1'] =$this->ModelPs->getRequete($proce_requete, $countries1);

		$communes=array();
		$zones=array();
		$collines=array();
		$label_document="";
		$div_personne_moral=' style="display:none;"';
		$div_personne_physique=' style="display:none;"';
		$div_photo=' style="display:none;"';

		if(!empty($PROPRIETAIRE_ID))
		{
			$data['btn']="Modifier";
			$data['title']="MODIFICATION D'UN proprietaire";

			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_proprio = $this->getBindParms('PROPRIETAIRE_ID,proprietaire.TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,PERSONNE_REFERENCE,EMAIL,TELEPHONE,CNI_OU_NIF,RC,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,ADRESSE,PHOTO_PASSPORT,LOGO,FILE_NIF,FILE_RC,FILE_CNI_PASSPORT,CATEGORIE_ID,COUNTRY_ID', 'proprietaire', '1 AND md5(PROPRIETAIRE_ID)="'.$PROPRIETAIRE_ID.'"', '`PROPRIETAIRE_ID` ASC');
			$my_select_proprio=str_replace('\"', '"', $my_select_proprio);
			$my_select_proprio=str_replace('\n', '', $my_select_proprio);
			$my_select_proprio=str_replace('\"', '', $my_select_proprio);
			$proprietaire = $this->ModelPs->getRequeteOne($proce_requete, $my_select_proprio);

			$my_select_communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1 AND PROVINCE_ID='.$proprietaire['PROVINCE_ID'].'', '`COMMUNE_NAME` ASC');
			$communes = $this->ModelPs->getRequete($proce_requete, $my_select_communes);

			$my_select_zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1 AND COMMUNE_ID='.$proprietaire['COMMUNE_ID'].'', '`ZONE_NAME` ASC');
			$zones = $this->ModelPs->getRequete($proce_requete, $my_select_zones);

			$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID='.$proprietaire['ZONE_ID'].'', '`COLLINE_NAME` ASC');
			$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);
			$categorieun = $this->getBindParms('DESC_CATEGORIE,CATEGORIE_ID', 'categories', '1', 'DESC_CATEGORIE ASC');
			// $cate = $this->ModelPs->getRequete($proce_requete, $categorieun);


			
			if($proprietaire['TYPE_PROPRIETAIRE_ID']==1)
			{
				$label_document="NIF";
				$div_personne_moral='';
				$div_personne_physique=' style="display:none;"';
			}
			else
			{
				$label_document="CNI / Numéro passport";
				$div_personne_moral=' style="display:none;"';
				$div_personne_physique='';
			}
		}
		$data['label_document']=$label_document;
		$data['div_personne_moral']=$div_personne_moral;
		$data['div_personne_physique']=$div_personne_physique;
		$data['div_photo']=$div_photo;

		$data['communes']=$communes;
		$data['zones']=$zones;
		$data['collines']=$collines;
		$data['proprietaire']=$proprietaire;

		$this->load->view('Proprietaire_Add_View',$data);
	}

	  //Fonction pour l'enregistrement de données dans la base de données ainsi que la mise à jour
	function save()
	{
		$PROPRIETAIRE_ID=$this->input->post('PROPRIETAIRE_ID');

		if(empty($PROPRIETAIRE_ID)) 
		{
			$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
			if ($TYPE_PROPRIETAIRE_ID==1) 
			{

				$LOGO_OLD = $this->input->post('LOGO_OLD');
				$FILE_NIF_OLD = $this->input->post('FILE_NIF_OLD');
				$FICHIER_RC_OLD = $this->input->post('FICHIER_RC_OLD');

				if(empty($_FILES['LOGO']['name']) && empty($LOGO_OLD) )
				{
					$this->form_validation->set_rules('LOGO',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}
				if(empty($_FILES['FILE_NIF']['name']) && empty($FILE_NIF_OLD) )
				{
					$this->form_validation->set_rules('FILE_NIF',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}if(empty($_FILES['FILE_RC']['name']) && empty($FILE_RC_OLD) )
				{
					$this->form_validation->set_rules('FILE_RC',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}

				$this->form_validation->set_rules('NOM_PROPRIETAIRE','NOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('CNI_OU_NIF','','trim|is_unique[proprietaire.CNI_OU_NIF]',array('is_unique'=>'<font style="color:red;font-size:15px;">*Le propriétaire existe déjà</font>'));


				$this->form_validation->set_rules('EMAIL','','required|valid_email|is_unique[proprietaire.EMAIL]',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>','valid_email'=>'<font style="color:red;size:2px;">*L\'addresse email doit contenir @</font>','is_unique'=>'<font style="color:red;size:2px;">*L\'addresse existe déjà</font>'));

				$this->form_validation->set_rules('CONFIRMATION_EMAIL', 'Email Confirmation', 'required|matches[EMAIL]');

				$this->form_validation->set_rules('TELEPHONE','TELEPHONE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('COUNTRY_ID','COUNTRY_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$COUNTRY_ID=$this->input->post('COUNTRY_ID');
				if($COUNTRY_ID==28){

					$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
					$this->form_validation->set_rules('CATEGORIE_ID','CATEGORIE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));


				}

				$this->form_validation->set_rules('ADRESSE','ADRESSE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

			}else 
			{
				$FILE_CNI_PASSPORT_OLD = $this->input->post('FILE_CNI_PASSPORT_OLD');


				if(empty($_FILES['FILE_CNI_PASSPORT']['name']) && empty($FILE_CNI_PASSPORT_OLD) )
				{
					$this->form_validation->set_rules('FILE_CNI_PASSPORT',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}

				$photo_passport_old = $this->input->post('photo_passport_old');


				if(empty($_FILES['photo_passport']['name']) && empty($photo_passport_old) )
				{
					$this->form_validation->set_rules('photo_passport',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}

				$this->form_validation->set_rules('NOM_PROPRIETAIRE','NOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PRENOM_PROPRIETAIRE','PRENOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('CNI_OU_NIF','','trim|is_unique[proprietaire.CNI_OU_NIF]',array('is_unique'=>'<font style="color:red;font-size:15px;">*Le propriétaire existe déjà</font>'));

					// $this->form_validation->set_rules("EMAIL","EMAIL", "trim|required|valid_email",array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

				$this->form_validation->set_rules('EMAIL','','required|valid_email|is_unique[proprietaire.EMAIL]',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>','valid_email'=>'<font style="color:red;size:2px;">*L\'addresse email doit contenir @</font>','is_unique'=>'<font style="color:red;size:2px;">*L\'addresse existe déjà</font>'));


				$this->form_validation->set_rules('CONFIRMATION_EMAIL', 'Email Confirmation', 'required|matches[EMAIL]');

				$this->form_validation->set_rules('TELEPHONE','TELEPHONE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('COUNTRY_ID','COUNTRY_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$COUNTRY_ID=$this->input->post('COUNTRY_ID');
				if($COUNTRY_ID==28){

					$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				}
				$this->form_validation->set_rules('ADRESSE','ADRESSE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
			}

			if($this->form_validation->run() == FALSE)
			{
				$this->index();
			}
			else
			{
				$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
				if ($TYPE_PROPRIETAIRE_ID==1) 
				{
					$table='proprietaire';

					$email=$this->input->post('EMAIL');
					// $password=$this->notifications->generate_password(8);
					$password=12345;


					$LOGO_OLD = $this->input->post('LOGO_OLD');
					$FILE_NIF_OLD = $this->input->post('FILE_NIF_OLD');
					$FILE_RC_OLD = $this->input->post('FILE_RC_OLD');


					$NOM_PROPRIETAIRE=$this->input->post('NOM_PROPRIETAIRE');

					if(empty($_FILES['LOGO']['name']) && !empty($LOGO_OLD))
					{
						$file_logo = $LOGO_OLD;
					}elseif (!empty($_FILES['LOGO']['name']) && empty($LOGO_OLD)) {
						$file_logo = $this->upload_document($_FILES['LOGO']['tmp_name'],$_FILES['LOGO']['name'],$NOM_PROPRIETAIRE);

					}
					if(empty($_FILES['FILE_NIF']['name']) && !empty($FILE_NIF_OLD))
					{
						$file_nif = $FILE_NIF_OLD;
					}elseif (!empty($_FILES['FILE_NIF']['name']) && empty($FILE_NIF_OLD)) {
						$file_nif = $this->upload_document($_FILES['FILE_NIF']['tmp_name'],$_FILES['FILE_NIF']['name'],$NOM_PROPRIETAIRE);

					}
					if(empty($_FILES['FILE_RC']['name']) && !empty($FILE_RC_OLD))
					{
						$file_rc = $FILE_RC_OLD;
					}elseif (!empty($_FILES['FILE_RC']['name']) && empty($FILE_RC_OLD)) {
						$file_rc = $this->upload_document($_FILES['FILE_RC']['tmp_name'],$_FILES['FILE_RC']['name'],$NOM_PROPRIETAIRE);

					}


					$data = array(
						'TYPE_PROPRIETAIRE_ID'=>$this->input->post('TYPE_PROPRIETAIRE_ID'),
							// 'TYPE_SOCIETE_ID'=>$this->input->post('TYPE_SOCIETE_ID'),
						'NOM_PROPRIETAIRE'=>$this->input->post('NOM_PROPRIETAIRE'),
						'CNI_OU_NIF'=>$this->input->post('CNI_OU_NIF'),
						'RC'=>$this->input->post('RC'),
						'PERSONNE_REFERENCE'=>$this->input->post('PERSONNE_REFERENCE'),
						'EMAIL'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'COUNTRY_ID'=>$this->input->post('COUNTRY_ID'),
						'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
						'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
						'ZONE_ID'=>$this->input->post('ZONE_ID'),
						'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
						'ADRESSE'=>$this->input->post('ADRESSE'),
						'CATEGORIE_ID'=>$this->input->post('CATEGORIE_ID'),

						'LOGO'=>$file_logo,
						'FILE_NIF'=>$file_nif,
						'FILE_RC'=>$file_rc

					);
					// print_r($data);exit();

					$PROPRIETAIRE_ID=$this->Model->insert_last_id($table,$data);
					$NOM_PROPRIETAIRE=$this->input->post('NOM_PROPRIETAIRE');
					$data_insert_users = array(

						'IDENTIFICATION'=>$this->input->post('NOM_PROPRIETAIRE'),
						'USER_NAME'=>$this->input->post('EMAIL'),
						'PASSWORD'=>md5($password),
						'PROFIL_ID'=>2,
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID,


					); 
					$table_users = 'users';
					$done=$this->Model->create($table_users,$data_insert_users);

					// if($done>0)
					// {
					// 	$mess="Cher(e) <b>".$NOM_PROPRIETAIRE."</b>,<br><br>
					// 	Bienvenue sur la plateforme CAR TRACKING.<br>
					// 	Pour vous connecter, prière de bien vouloir utiliser vos identifiants de connexion ci-dessous:<br>
					// 	- Nom d'utilisateur : <b>$email</b><br>
					// 	- Mot de passe : <b>$password</b>";
					// 	$subjet="Notification d'enregistrement";
					// 	$this->notifications->send_mail(array($email),$subjet,array(),$mess,array());
					// }

				}else
				{
					$table='proprietaire';
					$NOM_PROPRIETAIRE=$this->input->post('NOM_PROPRIETAIRE');

					$photo_passport_old = $this->input->post('photo_passport_old');



					if(empty($_FILES['photo_passport']['name']) && !empty($photo_passport_old))
					{
						$file3 = $photo_passport_old;
					}elseif (!empty($_FILES['photo_passport']['name']) && empty($photo_passport_old)) {
						$file3 = $this->upload_document_nomdocument($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name'],$NOM_PROPRIETAIRE);

					}


					$FILE_CNI_PASSPORT_OLD = $this->input->post('FILE_CNI_PASSPORT_OLD');

					if(empty($_FILES['FILE_CNI_PASSPORT']['name']) && !empty($FILE_CNI_PASSPORT_OLD))
					{
						$file_fil = $FILE_CNI_PASSPORT_OLD;
					}elseif (!empty($_FILES['FILE_CNI_PASSPORT']['name']) && empty($FILE_CNI_PASSPORT_OLD)) {
						$file_fil = $this->upload_cni($_FILES['FILE_CNI_PASSPORT']['tmp_name'],$_FILES['FILE_CNI_PASSPORT']['name'],$NOM_PROPRIETAIRE);

					}
					


					$data = array(
						'TYPE_PROPRIETAIRE_ID'=>$this->input->post('TYPE_PROPRIETAIRE_ID'),
						'NOM_PROPRIETAIRE'=>$this->input->post('NOM_PROPRIETAIRE'),
						'PRENOM_PROPRIETAIRE'=>$this->input->post('PRENOM_PROPRIETAIRE'),
						'CNI_OU_NIF'=>$this->input->post('CNI_OU_NIF'),
						'EMAIL'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'COUNTRY_ID'=>$this->input->post('COUNTRY_ID'),
						'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
						'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
						'ZONE_ID'=>$this->input->post('ZONE_ID'),
						'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
						'ADRESSE'=>$this->input->post('ADRESSE'),
						'PHOTO_PASSPORT'=>$file3,
						'FILE_CNI_PASSPORT'=>$file_fil
						
					);

					$PROPRIETAIRE_ID=$this->Model->insert_last_id($table,$data);
					// $password=$this->notifications->generate_password(8);
					$password=12345;

					$NOM_PROPRIETAIRE=$this->input->post('NOM_PROPRIETAIRE');
					$email=$this->input->post('EMAIL');
					$data_insert_users = array(

						'IDENTIFICATION'=>$this->input->post('NOM_PROPRIETAIRE')." ".$this->input->post('PRENOM_PROPRIETAIRE'),
						'USER_NAME'=>$this->input->post('EMAIL'),
						'PASSWORD'=>md5($password),
						'PROFIL_ID'=>2,
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID,


					); 
					$table_users = 'users';
					$done=$this->Model->create($table_users,$data_insert_users);

					// if($done>0)
					// {
					// 	$mess="Cher(e) <b>".$NOM_PROPRIETAIRE."</b>,<br><br>
					// 	Bienvenue sur la plateforme CAR TRACKING.<br>
					// 	Pour vous connecter, prière de bien vouloir utiliser vos identifiants de connexion ci-dessous:<br>
					// 	- Nom d'utilisateur : <b>$email</b><br>
					// 	- Mot de passe : <b>$password</b>";
					// 	$subjet="Notification d'enregistrement";
					// 	$this->notifications->send_mail(array($email),$subjet,array(),$mess,array());
					// }
				}

				$this->session->set_flashdata($data);
				redirect(base_url('proprietaire/Proprietaire/liste'));
			}

		}else
		{
			$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
			if ($TYPE_PROPRIETAIRE_ID==1) 
			{

				$LOGO_OLD = $this->input->post('LOGO_OLD');
				$FILE_NIF_OLD = $this->input->post('FILE_NIF_OLD');
				$FILE_RC_OLD = $this->input->post('FILE_RC_OLD');




				if(empty($_FILES['LOGO']['name']) && empty($LOGO_OLD))
				{
					$this->form_validation->set_rules('LOGO',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}
				if(empty($_FILES['FILE_NIF']['name']) && empty($FILE_NIF_OLD))
				{
					$this->form_validation->set_rules('FILE_NIF',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}if(empty($_FILES['FILE_RC']['name']) && empty($FILE_RC_OLD))
				{
					$this->form_validation->set_rules('FILE_RC',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}
				$this->form_validation->set_rules('CATEGORIE_ID','CATEGORIE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('NOM_PROPRIETAIRE','NOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('TELEPHONE','TELEPHONE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$COUNTRY_ID=$this->input->post('COUNTRY_ID');
				if($COUNTRY_ID==28){

					$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));



				}

				$this->form_validation->set_rules('ADRESSE','ADRESSE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

			}else 
			{
				$this->form_validation->set_rules('NOM_PROPRIETAIRE','NOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PRENOM_PROPRIETAIRE','PRENOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$FILE_CNI_PASSPORT_OLD = $this->input->post('FILE_CNI_PASSPORT_OLD');


				if(empty($_FILES['FILE_CNI_PASSPORT']['name']) && empty($FILE_CNI_PASSPORT_OLD) )
				{
					$this->form_validation->set_rules('FILE_CNI_PASSPORT',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}

				$photo_passport_old = $this->input->post('photo_passport_old');


				if(empty($_FILES['photo_passport']['name']) && empty($photo_passport_old) )
				{
					$this->form_validation->set_rules('photo_passport',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
				}


				$this->form_validation->set_rules('TELEPHONE','TELEPHONE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$COUNTRY_ID=$this->input->post('COUNTRY_ID');
				if($COUNTRY_ID==28){

					$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

					$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));


				}

				$this->form_validation->set_rules('ADRESSE','ADRESSE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
			}

			$id=$this->input->post('PROPRIETAIRE_ID');

			if ($this->form_validation->run() == FALSE)
			{
				$this->ajouter($id);
			}
			else
			{
				$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
				if ($TYPE_PROPRIETAIRE_ID==1) 
				{
					$table='proprietaire';

					$LOGO_OLD = $this->input->post('LOGO_OLD');
					$FILE_NIF_OLD = $this->input->post('FILE_NIF_OLD');
					$FILE_RC_OLD = $this->input->post('FILE_RC_OLD');



					$NOM_PROPRIETAIRE=$this->input->post('NOM_PROPRIETAIRE');

					// if(empty($_FILES['LOGO']['name']) && !empty($LOGO_OLD))
					// {
					// 	$file_logo = $LOGO_OLD;
					// }elseif (!empty($_FILES['LOGO']['name']) && empty($LOGO_OLD)) {
					// 	$file_logo = $this->upload_document($_FILES['LOGO']['tmp_name'],$_FILES['LOGO']['name'],$NOM_PROPRIETAIRE);

					// }
					$FILE_LOGO = $this->input->post('LOGO_OLD');
					if(empty($_FILES['LOGO']['name']))
					{
						$file_logo = $this->input->post('LOGO_OLD');	
					}
					else
					{
						$file_logo = $this->upload_document($_FILES['LOGO']['tmp_name'],$_FILES['LOGO']['name']);
					}
					$FILE_NIF = $this->input->post('FILE_NIF_OLD');
					if(empty($_FILES['FILE_NIF']['name']))
					{
						$file_nif = $this->input->post('FILE_NIF_OLD');	
					}
					else
					{
						$file_nif = $this->upload_document($_FILES['FILE_NIF']['tmp_name'],$_FILES['FILE_NIF']['name']);
					}

					$FILE_RC = $this->input->post('FILE_RC_OLD');
					if(empty($_FILES['FILE_RC']['name']))
					{
						$file_rc = $this->input->post('FILE_RC_OLD');	
					}
					else
					{
						$file_rc = $this->upload_document($_FILES['FILE_RC']['tmp_name'],$_FILES['FILE_RC']['name']);
					}

					$data_updaate=array(
						'TYPE_PROPRIETAIRE_ID'=>$this->input->post('TYPE_PROPRIETAIRE_ID'),
						'NOM_PROPRIETAIRE'=>$this->input->post('NOM_PROPRIETAIRE'),
						'CNI_OU_NIF'=>$this->input->post('CNI_OU_NIF'),
						'RC'=>$this->input->post('RC'),
						'PERSONNE_REFERENCE'=>$this->input->post('PERSONNE_REFERENCE'),
						'EMAIL'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'COUNTRY_ID'=>$this->input->post('COUNTRY_ID'),
						'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
						'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
						'ZONE_ID'=>$this->input->post('ZONE_ID'),
						'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
						'ADRESSE'=>$this->input->post('ADRESSE'),
						'CATEGORIE_ID'=>$this->input->post('CATEGORIE_ID'),
						'LOGO'=>$file_logo,
						'FILE_RC'=>$file_rc,
						'FILE_NIF'=>$file_nif

					);

					$update=$this->Model->update($table,array('PROPRIETAIRE_ID'=>$id),$data_updaate);

				}else
				{
					$table='proprietaire';

					$photo_passport_old = $this->input->post('photo_passport_old');

					if(empty($_FILES['photo_passport']['name']) && !empty($photo_passport_old))
					{
						$file4 = $photo_passport_old;
					}elseif (!empty($_FILES['photo_passport']['name']) && empty($photo_passport_old)) {
						$file4 = $this->upload_document($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name'],$this->input->post('NOM_PROPRIETAIRE'));

					}elseif(!empty($_FILES['photo_passport']['name']) && !empty($photo_passport_old)){

						$file4 = $this->upload_document($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name'],$this->input->post('NOM_PROPRIETAIRE'));

					}

					$FILE_CNI_PASSPORT_OLD = $this->input->post('FILE_CNI_PASSPORT_OLD');

					if(empty($_FILES['FILE_CNI_PASSPORT']['name']) && !empty($FILE_CNI_PASSPORT_OLD))
					{
						$file_fil = $FILE_CNI_PASSPORT_OLD;
					}elseif (!empty($_FILES['FILE_CNI_PASSPORT']['name']) && empty($FILE_CNI_PASSPORT_OLD)) {
						$file_fil = $this->upload_cni($_FILES['FILE_CNI_PASSPORT']['tmp_name'],$_FILES['FILE_CNI_PASSPORT']['name'],$NOM_PROPRIETAIRE);

					}

					$data_updaate=array(
						'TYPE_PROPRIETAIRE_ID'=>$this->input->post('TYPE_PROPRIETAIRE_ID'),
						'NOM_PROPRIETAIRE'=>$this->input->post('NOM_PROPRIETAIRE'),
						'PRENOM_PROPRIETAIRE'=>$this->input->post('PRENOM_PROPRIETAIRE'),
						'CNI_OU_NIF'=>$this->input->post('CNI_OU_NIF'),
						'EMAIL'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'COUNTRY_ID'=>$this->input->post('COUNTRY_ID'),
						'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
						'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
						'ZONE_ID'=>$this->input->post('ZONE_ID'),
						'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
						'ADRESSE'=>$this->input->post('ADRESSE'),
						'PHOTO_PASSPORT'=>$file4,
						'FILE_CNI_PASSPORT'=>$file_fil

					);


					$update=$this->Model->update($table,array('PROPRIETAIRE_ID'=>$id),$data_updaate);
				}

				if ($data_update)
				{
					$message['message']='<div class="alert alert-success text-center" id="message">La modification de cet utilisateur a été faite avec succès</div>';
					$this->session->set_flashdata($message);
					redirect(base_url('proprietaire/Proprietaire/liste'));

				}else
				{
					$message['message']='<div class="alert alert-success text-center" id="message">La modification de cet utilisateur a échouée </div>';
					$this->session->set_flashdata($message);

				}
				redirect(base_url('proprietaire/Proprietaire/liste'));

			}
		}

	}

	//fonction pour l'affichage de la liste
	function liste()
	{
		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$pay = $this->getBindParms('CommonName,COUNTRY_ID', 'countries', '1', 'CommonName ASC');
		$data['pays'] = $this->ModelPs->getRequete($proce_requete, $pay);

		$provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1', 'PROVINCE_NAME ASC');
		$data['provinces'] = $this->ModelPs->getRequete($proce_requete, $provinces);
		$motif_desactivation = $this->getBindParms('ID_MOTIF,DESC_MOTIF', 'motif', '1 AND ID_TYPE=1', 'DESC_MOTIF ASC');
		$data['motif_des'] = $this->ModelPs->getRequete($proce_requete, $motif_desactivation);

		$motif_ativation = $this->getBindParms('ID_MOTIF,DESC_MOTIF', 'motif', '1 AND ID_TYPE=2', 'DESC_MOTIF ASC');
		$data['motif_ativ'] = $this->ModelPs->getRequete($proce_requete, $motif_ativation);


		
		$this->load->view('Proprietaire_List_View',$data);
	}


	//Fonction pour la liste
	function listing(){

		$COMMUNE_ID=$this->input->post('COMMUNE_ID');
		$PROVINCE_ID=$this->input->post('PROVINCE_ID');
		$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
		$IS_ACTIVE=$this->input->post('IS_ACTIVE');
		$COUNTRY_ID=$this->input->post('COUNTRY_ID');


		$critaire = '';

		if($TYPE_PROPRIETAIRE_ID != '' && $TYPE_PROPRIETAIRE_ID != 0 && $IS_ACTIVE != '' && $IS_ACTIVE != 0)
		{
			$critaire=" AND proprietaire.TYPE_PROPRIETAIRE_ID=".$TYPE_PROPRIETAIRE_ID;
			$critaire.=" AND proprietaire.IS_ACTIVE = ".$IS_ACTIVE;
		}
		else if($TYPE_PROPRIETAIRE_ID != '' && $TYPE_PROPRIETAIRE_ID != 0 && $IS_ACTIVE == 0)
		{
			$critaire=" AND proprietaire.TYPE_PROPRIETAIRE_ID=".$TYPE_PROPRIETAIRE_ID;
		}
		else if($TYPE_PROPRIETAIRE_ID == 0 && $IS_ACTIVE != '' && $IS_ACTIVE != 0)
		{
			$critaire=" AND proprietaire.IS_ACTIVE=".$IS_ACTIVE;
		}



		if($PROVINCE_ID>0) $critaire.=" AND proprietaire.PROVINCE_ID=".$PROVINCE_ID;
		if($COMMUNE_ID>0) $critaire.=" AND proprietaire.COMMUNE_ID=".$COMMUNE_ID;
		if($COUNTRY_ID>0) $critaire.=" AND proprietaire.COUNTRY_ID=".$COUNTRY_ID;

		


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

		$query_principal='SELECT PROPRIETAIRE_ID,proprietaire.TYPE_PROPRIETAIRE_ID,if(proprietaire.`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS info_personne,CNI_OU_NIF,PERSONNE_REFERENCE,EMAIL,TELEPHONE,DATE_INSERTION,IS_ACTIVE,PHOTO_PASSPORT,COUNTRY_ID,LOGO,type_proprietaire.DESC_TYPE_PROPRIETAIRE FROM proprietaire JOIN   type_proprietaire ON proprietaire.TYPE_PROPRIETAIRE_ID=type_proprietaire.TYPE_PROPRIETAIRE_ID WHERE 1';

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

			// if($row->TYPE_PROPRIETAIRE_ID == 1 && empty($row->LOGO)){
			// 	$sub_array[] ='<tbody><tr><td><a href="javascript:;" onclick="get_detail_pers_moral(' . $row->PROPRIETAIRE_ID . ')" style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->info_personne . '</td></tr></tbody></a>
			// 	';

			// }elseif(!empty($row->LOGO)){

			// 	$sub_array[] = ' <tbody><tr><td><a href="javascript:;" onclick="get_detail(' . $row->PROPRIETAIRE_ID . ')"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></a></td><td> '.'     '.' ' . $row->info_personne . '</td></tr></tbody></a>

			// 	<div class="modal fade" id="mypicture' .$row->PROPRIETAIRE_ID.'">
			// 	<div class="modal-dialog">
			// 	<div class="modal-content">
			// 	<div class="modal-body">
			// 	<img src = "'.base_url('upload/proprietaire/photopassport/'.$row->LOGO).'" height="100%"  width="100%" >
			// 	</div>
			// 	<div class="modal-footer">
			// 	<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">Fermer</button>
			// 	</div>
			// 	</div>
			// 	</div>
			// 	</div>';

			// }else{

			// 	$sub_array[] = ' <tbody><tr><td><a href="javascript:;" onclick="get_detail(' . $row->PROPRIETAIRE_ID . ')"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->info_personne . '</td></tr></tbody></a>

			// 	<div class="modal fade" id="mypicture' .$row->PROPRIETAIRE_ID.'">
			// 	<div class="modal-dialog">
			// 	<div class="modal-content">
			// 	<div class="modal-body">
			// 	<img src = "'.base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT).'" height="100%"  width="100%" >
			// 	</div>
			// 	<div class="modal-footer">
			// 	<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">Fermer</button>
			// 	</div>
			// 	</div>
			// 	</div>
			// 	</div>';
			// }
			
			if($row->TYPE_PROPRIETAIRE_ID == 1 && empty($row->LOGO)){
				$sub_array[] ='<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><i class="bi bi-info-square h5" ></i>
				style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->info_personne . '</td></tr></tbody></a>
				';

			}elseif(!empty($row->LOGO)){

				$sub_array[] = ' <tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></td><td> '.'     '.' ' . $row->info_personne . '</td></tr></tbody></a>

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

				$sub_array[] = ' <tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></td><td> '.'     '.' ' . $row->info_personne . '</td></tr></tbody></a>

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

			$sub_array[]=$row->DESC_TYPE_PROPRIETAIRE;

			
			if (!empty($row->EMAIL)) 
			{
				$sub_array[]=$row->EMAIL;	
			}else{
				$sub_array[]='N/A';	
			}						
			$sub_array[]=$row->TELEPHONE;


			if($row->IS_ACTIVE==1){
				$sub_array[]='<form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

				<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">
				<table>
				<td><label class="text-primary">Activé</label></td>
				<td><label class="switch"> 
				<input type="checkbox" id="myCheck" onclick="myFunction_desactive(' . $row->PROPRIETAIRE_ID . ')" checked>
				<span class="slider round"></span>
				</label></td>
				</table>
				</form>

				';
			}else{
				$sub_array[]='<form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

				<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

				<table>
				<td><label class="text-danger">Désactivé</label></td>
				<td><label class="switch"> 
				<input type="checkbox" id="myCheck" onclick="myFunction(' . $row->PROPRIETAIRE_ID . ')">
				<span class="slider round"></span>
				</label></td>
				</table>
				</form>

				';
			}

			// $sub_array[]='<label class="text-center btn btn-outline-primary rounded-pill">'.$NOMBRE['nombre'].'</label>';

			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select = $this->getBindParms('COUNT(VEHICULE_ID) as nombre', 'proprietaire LEFT JOIN vehicule ON vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID', '1 AND proprietaire.PROPRIETAIRE_ID ='.$row->PROPRIETAIRE_ID.'', 'proprietaire.PROPRIETAIRE_ID ASC');
			$NOMBRE = $this->ModelPs->getRequeteOne($proce_requete, $my_select);

			if(!empty($NOMBRE))
			{
				$sub_array[]="<a class='btn btn-outline-primary rounded-pill' href='" . base_url('proprietaire/Proprietaire/Detail_vehicule/'.md5($row->PROPRIETAIRE_ID)). "' style='font-size:10px;'><label>".$NOMBRE['nombre']."</label></a>";
			}
			$action = '<div class="dropdown text-center" style="color:#fff;">
			<a class=" btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown"><i class="bi bi-three-dots h5" style="color:blue;"></i>  <span class="caret"></span>
			</a>
			<ul class="dropdown-menu dropdown-menu-right">';

			$action .="<li>

			<a class='btn-md' href='" . base_url('proprietaire/Proprietaire/index/'.md5($row->PROPRIETAIRE_ID)) . "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;Modifier</a>
			</li>"; 

			$action .="<li>

			<a class='btn-md' href='" . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). "'><i class='bi bi-info-square h5' ></i>&nbsp;&nbsp;Détails</a>
			</a>
			</li>";

			$sub_array[]=$action;

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


	//Fonction pour recuperer les donnes d'un proprietaire
	function get_detail($PROPRIETAIRE_ID)
	{


		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_proprietaire = $this->getBindParms('proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.EMAIL,proprietaire.TELEPHONE,type_proprietaire.DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,CNI_OU_NIF,proprietaire.PHOTO_PASSPORT,proprietaire.PERSONNE_REFERENCE,proprietaire.ADRESSE,LOGO,FILE_CNI_PASSPORT,RC', 'proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_PROPRIETAIRE_ID=proprietaire.TYPE_PROPRIETAIRE_ID', '1 AND proprietaire.PROPRIETAIRE_ID='.$PROPRIETAIRE_ID.'', '`PROPRIETAIRE_ID` ASC');
		$proprietaire = $this->ModelPs->getRequeteOne($proce_requete, $my_select_proprietaire);

		if(empty($proprietaire['PERSONNE_REFERENCE'])){

			$PERSONNE_REFERENCE='N/A';
		}else{
			$PERSONNE_REFERENCE=$proprietaire['PERSONNE_REFERENCE'];
		}

		if($proprietaire['TYPE_PROPRIETAIRE_ID']==1){

			$fichier_cni = base_url().'upload/proprietaire/photopassport/'.$proprietaire['LOGO'].'';

		}else{

			$fichier_cni = base_url().'upload/proprietaire/piece_identite/'.$proprietaire['FILE_CNI_PASSPORT'].'';

		}

		if($proprietaire['TYPE_PROPRIETAIRE_ID']==1){

			$fichier = base_url().'upload/proprietaire/photopassport/'.$proprietaire['LOGO'].'';

		}else{

			$fichier = base_url().'upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'].'';

		}

		if($proprietaire['TYPE_PROPRIETAIRE_ID']==1){

			$label_doc='Logo';
			$cni_physique='';
		}else{

			$label_doc='CNI/Passport';
			$cni_physique='<table class="table table-borderless">
			<tr>
			<td><span class="fa fa-newspaper-o"></span> &nbsp;&nbsp; CNI / Passport</td>
			<td>'.$proprietaire['CNI_OU_NIF'].'</td>
			</tr>
			</table>';

		}

		
		$div_info = '<img src="'.$fichier.'" height="100%"  width="100%"  style= "border-radius:50%;" />';
		$div_info_cni = '<img src="'.$fichier_cni.'" height="100%"  width="100%"  style= "border-radius:50%;" />';

		$output = array(
			"CNI" => $proprietaire['CNI_OU_NIF'],
			"TELEPHONE" => $proprietaire['TELEPHONE'],
			"EMAIL" => $proprietaire['EMAIL'],
			"DESC_TYPE_PROPRIETAIRE" => $proprietaire['DESC_TYPE_PROPRIETAIRE'],
			"PERSONNE_REFERENCE" => $PERSONNE_REFERENCE,
			"div_info"=>$div_info,
			"ADRESSE"=>$proprietaire['ADRESSE'],
			"label_doc"=>$label_doc,
			"cni_physique"=>$cni_physique,
			"fichier_cni"=>$div_info_cni,




		);
		echo json_encode($output);
	}

	//Fonction pour recuperer les donnes d'un chauffeur
	function get_detail_chauffeur($CODE){


		$proce_requete = "CALL `getRequete`(?,?,?,?);";	
		$my_select_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CODE ="'.$CODE.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
		$my_select_chauffeur=str_replace('\"', '"', $my_select_chauffeur);
		$my_select_chauffeur=str_replace('\n', '', $my_select_chauffeur);
		$my_select_chauffeur=str_replace('\"', '', $my_select_chauffeur);
		$chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_chauffeur);

		$fichier = base_url().'upload/chauffeur/'.$chauffeur['PHOTO_PASSPORT'].'';

		
		$div_info = '<img src="'.$fichier.'" height="100%"  width="100%"  style= "border-radius:50%;" />';
		$output = array(
			"NOM" => $chauffeur['NOM'].' '.$chauffeur['PRENOM'],
			"DATE_NAISSANCE" => $chauffeur['DATE_NAISSANCE'],
			"NUMERO_TELEPHONE" => $chauffeur['NUMERO_TELEPHONE'],
			"ADRESSE_MAIL" => $chauffeur['ADRESSE_MAIL'],
			"NUMERO_CARTE_IDENTITE" => $chauffeur['NUMERO_CARTE_IDENTITE'],
			"ADRESSE_PHYSIQUE" => $chauffeur['ADRESSE_PHYSIQUE'],
			"div_info"=>$div_info,
			

		);
		echo json_encode($output);
	}

	//Fonction pour modifier le statut
	function changer_statut($id)
	{
		$USER_ID = $this->session->userdata('USER_ID');
		$get_status=$this->Model->getOne('proprietaire',array('PROPRIETAIRE_ID'=>$id));
		$today = date('Y-m-d H:s');
		$desc_status='';
		if($get_status['IS_ACTIVE']==1)
		{
			$desc_status='La Désactivation';
			$this->Model->update('proprietaire', array('PROPRIETAIRE_ID'=>$id),array('IS_ACTIVE'=>2));
		}
		else
		{
			$desc_status='L\'Activation';

			$this->Model->update('proprietaire', array('PROPRIETAIRE_ID'=>$id),array('IS_ACTIVE'=>1));
		}

		$data['message']='<div class="alert alert-success text-center" id="message">'."$desc_status".' '." effectuée avec succès".'</div>';
		$this->session->set_flashdata($data);
		redirect(base_url('proprietaire/Proprietaire/liste/'));
	}

	//function pour l'affichage de la page de detail
	function Detail(){

		$PROPRIETAIRE_ID=$this->uri->segment(4);
		$proprietaire=$this->Model->getRequeteOne("SELECT proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.proprietaire_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.EMAIL,proprietaire.TELEPHONE,DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,CNI_OU_NIF,proprietaire.PHOTO_PASSPORT,PROVINCE_NAME,COMMUNE_NAME,ZONE_NAME,COLLINE_NAME,proprietaire.ADRESSE,proprietaire.LOGO ,proprietaire.FILE_NIF,proprietaire.FILE_RC,proprietaire.RC,categories.DESC_CATEGORIE FROM proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_proprietaire_ID=proprietaire.TYPE_proprietaire_ID LEFT JOIN provinces ON provinces.PROVINCE_ID=proprietaire.PROVINCE_ID LEFT JOIN communes ON communes.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN zones ON zones.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON collines.ZONE_ID=zones.ZONE_ID left join  categories on proprietaire.CATEGORIE_ID=categories.CATEGORIE_ID WHERE 1 AND md5(proprietaire.proprietaire_ID)='".$PROPRIETAIRE_ID."'");


		$desactive=$this->Model->getRequeteOne("SELECT proprietaire.proprietaire_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,proprietaire.IS_ACTIVE FROM proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_proprietaire_ID=proprietaire.TYPE_proprietaire_ID WHERE proprietaire.IS_ACTIVE=2 AND md5(proprietaire.proprietaire_ID)='".$PROPRIETAIRE_ID."'");
		if ($proprietaire['TYPE_PROPRIETAIRE_ID']==1) 
		{
			
			$label_cni='NIF';
		}elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==2) {
			$label_cni='CNI / Passport';
			
		}

		$data['proprietaire']=$proprietaire;
		$data['label_cni']=$label_cni;

		$data['desactive']=$desactive;
		$data['PROPRIETAIRE_ID'] = $data['proprietaire']['proprietaire_ID'];
		// print_r($data['proprietaire_ID']);die();
		$data['dte'] =date("d-m-Y H:i:s", strtotime($proprietaire['DATE_INSERTION']));

		$VEHICULE_PRO = " ";
		$data['VEHICULE_PRO'] = $VEHICULE_PRO;

		$this->load->view('Detail_proprietaire_view',$data);

	}


	//function pour l'affichage de la page de detail avec les vehicules
	function Detail_vehicule($VEHICULE_PRO = " "){
		
		//$PROPRIETAIRE_ID=$this->uri->segment(4);
		$proprietaire=$this->Model->getRequeteOne("SELECT proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.proprietaire_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.EMAIL,proprietaire.TELEPHONE,DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,CNI_OU_NIF,proprietaire.PHOTO_PASSPORT,PROVINCE_NAME,COMMUNE_NAME,ZONE_NAME,COLLINE_NAME,proprietaire.ADRESSE,proprietaire.LOGO ,proprietaire.FILE_NIF,proprietaire.FILE_RC,proprietaire.RC,categories.DESC_CATEGORIE FROM proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_proprietaire_ID=proprietaire.TYPE_proprietaire_ID LEFT JOIN provinces ON provinces.PROVINCE_ID=proprietaire.PROVINCE_ID LEFT JOIN communes ON communes.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN zones ON zones.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON collines.ZONE_ID=zones.ZONE_ID left join  categories on proprietaire.CATEGORIE_ID=categories.CATEGORIE_ID WHERE 1 AND md5(proprietaire.proprietaire_ID)='".$VEHICULE_PRO."'");


		$desactive=$this->Model->getRequeteOne("SELECT proprietaire.proprietaire_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,proprietaire.IS_ACTIVE FROM proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_proprietaire_ID=proprietaire.TYPE_proprietaire_ID WHERE proprietaire.IS_ACTIVE=2 AND md5(proprietaire.proprietaire_ID)='".$VEHICULE_PRO."'");
		if ($proprietaire['TYPE_PROPRIETAIRE_ID']==1) 
		{
			
			$label_cni='NIF';
		}elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==2) {
			$label_cni='CNI / Numéro passport';
			
		}

		$data['proprietaire']=$proprietaire;
		$data['label_cni']=$label_cni;

		$data['desactive']=$desactive;
		$data['PROPRIETAIRE_ID'] = $data['proprietaire']['proprietaire_ID'];
		// print_r($data['proprietaire_ID']);die();
		$data['dte'] =date("d-m-Y H:i:s", strtotime($proprietaire['DATE_INSERTION']));

		$data['VEHICULE_PRO'] = $VEHICULE_PRO;

		$this->load->view('Detail_proprietaire_view',$data);

	}

	//Fonction pour le detail de tous les vehicules d'un client
	function detail_vehicule_client(){

		$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');

		$critaire = '' ;

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";

		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}
		$order_by='';
		$order_column=array('NOM_PROPRIETAIRE','CNI_OU_NIF','PERSONNE_REFERENCE','EMAIL','TELEPHONE','DATE_INSERTION');

		$order_column=array('1','DESC_MARQUE','DESC_MODELE','COULEUR','PLAQUE');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' DESC_MARQUE ASC';
		}



		$search = !empty($_POST['search']['value']) ? (' AND (`DESC_MARQUE` LIKE "%' . $var_search . '%" OR DESC_MODELE LIKE "%' . $var_search . '%"
			OR PLAQUE LIKE "%' . $var_search . '%" )') : '';


		$query_principal = 'SELECT vehicule.VEHICULE_ID,vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.COULEUR,vehicule.PHOTO,vehicule.CODE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE WHERE vehicule.PROPRIETAIRE_ID='.$PROPRIETAIRE_ID;

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
			$proce_requete = "CALL `getRequete`(?,?,?,?);";	
			$my_select_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CODE ="'.$row->CODE.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
			$my_select_chauffeur=str_replace('\"', '"', $my_select_chauffeur);
			$my_select_chauffeur=str_replace('\n', '', $my_select_chauffeur);
			$my_select_chauffeur=str_replace('\"', '', $my_select_chauffeur);
			$chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_chauffeur);

			$sub_array=array();
			$sub_array[]=$i;
			$sub_array[]=$row->DESC_MARQUE;
			$sub_array[]=$row->DESC_MODELE;
			$sub_array[]=$row->COULEUR;
			$sub_array[]=$row->PLAQUE;
			$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->VEHICULE_ID. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/photo_vehicule/').$row->PHOTO.'"></a></td><td></td></tr></tbody></a>
			<div class="dropdown">
			</div>
			<div class="modal fade" id="mypicture' .$row->VEHICULE_ID.'">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-body">
			<img src = "'.base_url('upload/photo_vehicule/'.$row->PHOTO).'" height="100%"  width="100%" >
			</div>
			<div class="modal-footer">
			<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">Fermer</button>
			</div>
			</div>
			</div>
			</div>';
			// $proce_requete = "CALL `getRequete`(?,?,?,?);";
			// $my_select_chauffeur = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM,chauffeur.PHOTO_PASSPORT,chauffeur_vehicule.CODE', 'chauffeur_vehicule join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID', 'STATUT_AFFECT=1 AND chauffeur_vehicule.CODE='.$row->CODE.'', '`CHAUFFEUR_ID` ASC');
			// $chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_chauffeur);

			if(!empty($chauffeur)){

				// $sub_array[] = '<a  href="' . base_url("tracking/Dashboard/position_voiture/".$row->CODE) . '" ><center><span class="bi bi-eye"></span></center></a>';

				$sub_array[] = ' <tbody><tr><td><a href="javascript:;" onclick="get_detail_chauffeur(\'' .$row->CODE . '\')"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/chauffeur/').$chauffeur['PHOTO_PASSPORT'].'"></a></td>&nbsp;<td> '.'  '.' ' . $chauffeur['NOM']. ' '.$chauffeur['PRENOM'].'</td></tr></tbody></a>
				';
			}else{

				$sub_array[] = '<font style="color:red;"> Pas&nbsp;de&nbsp;chauffeur&nbsp;affecté&nbsp;à&nbsp;ce&nbsp;véhicule ! </font>';
			}
			if(!empty($chauffeur)){

				$sub_array[] = '<a  href="' . base_url("tracking/Dashboard/position_voiture/".md5($row->CODE)) . '" ><center><span class="bi bi-eye"></span></center></a>';

			}else{

				$sub_array[] = '<font style="color:red;"> Pas&nbsp;de&nbsp;chauffeur&nbsp;affecté&nbsp;à&nbsp;ce&nbsp;véhicule ! </font>';
			}
			

			$data[]=$sub_array;
		}
		$recordsTotal = $this->ModelPs->datatable("CALL `getTable`('" . $query_principal . "')");
		$recordsFiltered = $this->ModelPs->datatable(" CALL `getTable`('" . $requetedebasefilter . "')");

		$nbr_vehicule = $i;
		$output = array(
			"draw" => intval($_POST['draw']),
			"recordsTotal" => count($recordsTotal),
			"recordsFiltered" => count($recordsFiltered),
			"data" => $data,
		);
		echo json_encode($output);

	}

	//Fonction pour activer/desactiver un proprietaire et enregistrer l'historique
	function active_desactive($status,$PROPRIETAIRE_ID)
	{
		$USER_ID=$this->session->userdata('USER_ID');

		if($status==2)
		{
           //pour desactivation
			$this->Model->update('proprietaire', array('PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID),array('IS_ACTIVE'=>2));

			$ID_MOTIF_des = $this->input->post('ID_MOTIF_des');

			$data = array('ID_MOTIF'=>$ID_MOTIF_des,'USER_ID'=>$USER_ID,'IS_ACTIVE'=>2);

			$result = $this->Model->create('historique_proprietaire',$data);

		}else if($status==1)
		{
		//pour activation
			$this->Model->update('proprietaire', array('PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID),array('IS_ACTIVE'=>1));

			$ID_MOTIF = $this->input->post('ID_MOTIF');
			$data = array('ID_MOTIF'=>$ID_MOTIF,'USER_ID'=>$USER_ID,'IS_ACTIVE'=>1);

			$result = $this->Model->create('historique_proprietaire',$data);

		}

		echo json_encode(array('status'=>$status));


	}

	function get_nbr_proprio($TYPE_PROPRIETAIRE_ID,$IS_ACTIVE,$COUNTRY_ID,$PROVINCE_ID,$COMMUNE_ID)
	{
		$critaire_proprio = '' ;			

			// if($TYPE_PROPRIETAIRE_ID == 1) // Assurance valide
			// {
			// 	$critaire_proprio = ' AND TYPE_PROPRIETAIRE_ID = "'.$TYPE_PROPRIETAIRE_ID.'"';
			// }
			// else if($TYPE_PROPRIETAIRE_ID == 2) // Assurance invalide
			// {
			// 	$critaire_proprio = ' AND TYPE_PROPRIETAIRE_ID ="'.$TYPE_PROPRIETAIRE_ID.'"';
			// }

		if($TYPE_PROPRIETAIRE_ID != '' && $TYPE_PROPRIETAIRE_ID != 0 && $IS_ACTIVE != '' && $IS_ACTIVE != 0)
		{
			$critaire_proprio=" AND proprietaire.TYPE_PROPRIETAIRE_ID=".$TYPE_PROPRIETAIRE_ID;
			$critaire_proprio.=" AND proprietaire.IS_ACTIVE = ".$IS_ACTIVE;
		}
		else if($TYPE_PROPRIETAIRE_ID != '' && $TYPE_PROPRIETAIRE_ID != 0 && $IS_ACTIVE == 0)
		{
			$critaire_proprio=" AND proprietaire.TYPE_PROPRIETAIRE_ID=".$TYPE_PROPRIETAIRE_ID;
		}
		else if($TYPE_PROPRIETAIRE_ID == 0 && $IS_ACTIVE != '' && $IS_ACTIVE != 0)
		{
			$critaire_proprio=" AND proprietaire.IS_ACTIVE=".$IS_ACTIVE;
		}

		if($PROVINCE_ID>0){
			$critaire_proprio.=" AND proprietaire.PROVINCE_ID=".$PROVINCE_ID;
		} 
		if($COMMUNE_ID>0){
			$critaire_proprio.=" AND proprietaire.COMMUNE_ID=".$COMMUNE_ID;

		}

		if($COUNTRY_ID>0) {
			$critaire_proprio.=" AND proprietaire.COUNTRY_ID=".$COUNTRY_ID;
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$proprio_req = $this->getBindParms('COUNT(PROPRIETAIRE_ID) AS nombre_p', 'proprietaire', ' 1 '.$critaire_proprio.'', '`PROPRIETAIRE_ID` ASC');

		$proprio_req = str_replace('\"', '"', $proprio_req);
		$proprio_req = str_replace('\n', '', $proprio_req);
		$proprio_req = str_replace('\"', '', $proprio_req);

		$proprio = $this->ModelPs->getRequeteOne($proce_requete, $proprio_req);

		echo $proprio['nombre_p'];
	}


	// Recuperation des photos passeports
	public function upload_document_nomdocument($nom_file,$nom_champ,$nomdocument)
	{
		$rep_doc =FCPATH.'upload/proprietaire/photopassport/';
		$file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
		$file_extension = strtolower($file_extension);
		$valid_ext = array('pdf');
		$code=uniqid();
		if(!is_dir($rep_doc)) //crée un dossier s'il n'existe pas déja   
		{
			mkdir($rep_doc,0777,TRUE);
		}
		// unlink(base_url()."upload/proprietaire/photopassport/".$code.$nomdocument.".".$file_extension);
		move_uploaded_file($nom_file, $rep_doc.$code.$nomdocument.".".$file_extension);
		$pathfile=$code.$nomdocument.".".$file_extension;
		return $pathfile;
	}

	// Recuperation des cni ou passport
	public function upload_cni($nom_file,$nom_champ,$nomdocument)
	{
		$rep_doc =FCPATH.'upload/proprietaire/piece_identite/';
		$file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
		$file_extension = strtolower($file_extension);
		$valid_ext = array('pdf');
		$code=uniqid();

		if(!is_dir($rep_doc)) //crée un dossier s'il n'existe pas déja   
		{
			mkdir($rep_doc,0777,TRUE);
		}
		// unlink(base_url()."upload/proprietaire/piece_identite/".$code.$nomdocument.".".$file_extension);
		move_uploaded_file($nom_file, $rep_doc.$code.$nomdocument.".".$file_extension);
		$pathfile=$code.$nomdocument.".".$file_extension;
		return $pathfile;
	}
		// Recuperation des fichiers(pdf)
	public function upload_document($nom_file,$nom_champ)
	{
		$rep_doc =FCPATH.'upload/proprietaire/photopassport/';
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

	 //Fonction pour la selection des communes
	function get_communes($PROVINCE_ID)
	{
		$html="<option value='0'>Séléctionner</option>";
		
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
		$html='<option value="">Séléctionner</option>';
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
		$html='<option value="">Séléctionner</option>';
		
		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID='.$ZONE_ID.'', '`COLLINE_NAME` ASC');
		$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);
		foreach ($collines as $colline)
		{
			$html.='<option value="'.$colline['COLLINE_ID'].'">'.$colline['COLLINE_NAME'].'</option>';
		}
		echo json_encode($html);
	}

	//fonction pour recuperer le nombre des vehicules d'un proprietaire

	function get_nbr_vehicule($PROPRIETAIRE_ID)
	{

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$my_query = $this->getBindParms('COUNT(VEHICULE_ID) AS nombre_v', 'vehicule', '1 AND PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID.'', '`VEHICULE_ID` ASC');
		$vehicule = $this->ModelPs->getRequeteOne($proce_requete, $my_query);

		echo $vehicule['nombre_v'];
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