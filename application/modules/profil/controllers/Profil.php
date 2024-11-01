<?php

/**Fait par Nzosaba Santa Milka
 * Santa.milka@mediabox.bi
 * 68071895
 * Gestion de profil
 * Le 26/02/2024
 * 
 * 
 */
class Profil extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->out_application();
		$this->load->helper('email');
	}

	//Fonction pour rediriger sur login en cas de perte de session
	function out_application()
	{
		if(empty($this->session->userdata('USER_ID')))
		{
			redirect(base_url('Login/logout'));

		}
	}

	//Fonction pour l'affichage d'une page par defaut
	function index()
	{

		$USER_ID=$this->session->userdata('USER_ID');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_utilisateur = $this->getBindParms('users.USER_ID,users.IDENTIFICATION,users.TELEPHONE,users.USER_NAME,profil.DESCRIPTION_PROFIL as profile,STATUT,proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.PHOTO_PASSPORT,proprietaire.PROPRIETAIRE_ID,proprietaire.COUNTRY_ID,countries.CommonName,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,zones.ZONE_NAME,collines.COLLINE_NAME,proprietaire.PERSONNE_REFERENCE,proprietaire.CNI_OU_NIF,PASSWORD,LOGO', ' `users` join profil on users.PROFIL_ID=profil.PROFIL_ID LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID=users.PROPRIETAIRE_ID JOIN countries ON countries.COUNTRY_ID=proprietaire.COUNTRY_ID JOIN provinces ON provinces.PROVINCE_ID=proprietaire.PROVINCE_ID JOIN communes ON communes.COMMUNE_ID=proprietaire.COMMUNE_ID JOIN zones ON zones.ZONE_ID=proprietaire.ZONE_ID JOIN collines ON collines.COLLINE_ID=proprietaire.COLLINE_ID', ' users.USER_ID='.$USER_ID.'', '`USER_ID` ASC');
		$utilisateur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_utilisateur);
		// print_r($utilisateur);exit();

			// $my_select_proprio= $this->getBindParms('proprietaire.PROPRIETAIRE_ID,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,PERSONNE_REFERENCE,EMAIL,TELEPHONE,CNI_OU_NIF,RC,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,ADRESSE,PHOTO_PASSPORT','proprietaire JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID','users.USER_ID='.$USER_ID.'','PROPRIETAIRE_ID ASC');

		$my_select_proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,PERSONNE_REFERENCE,EMAIL,proprietaire.TELEPHONE,CNI_OU_NIF,RC,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,ADRESSE,PHOTO_PASSPORT,countries.CommonName,proprietaire.COUNTRY_ID', 'proprietaire JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID JOIN countries ON countries.COUNTRY_ID=proprietaire.COUNTRY_ID', ' users.USER_ID='.$USER_ID.'', '`PROPRIETAIRE_ID` ASC');

		$proprietaire=$this->ModelPs->getRequeteOne($proce_requete, $my_select_proprio);

		$pay = $this->getBindParms('CommonName,COUNTRY_ID', 'countries', '1', 'CommonName ASC');
		$pays =$this->ModelPs->getRequete($proce_requete, $pay);

		$html1='<option value="">--- '.lang('selectionner').' ----</option>';
		if(!empty($pays) && !empty($proprietaire['COUNTRY_ID']))
		{
			foreach($pays as $key1)
			{
				if ($key1['COUNTRY_ID']==$proprietaire['COUNTRY_ID']) 
				{
					$html1.='<option value="'.$key1['COUNTRY_ID'].'" selected>'.$key1['CommonName'].'</option>';
				}else
				{
					$html1.='<option value="'.$key1['COUNTRY_ID'].'">'.$key1['CommonName'].'</option>';
				}
				
			}
		}

		$my_select_provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1', '`PROVINCE_NAME` ASC');
		$provinces = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);

		$html2='<option value="">--- '.lang('selectionner').' ----</option>';
		if(!empty($provinces) && !empty($proprietaire['PROVINCE_ID']))
		{
			foreach($provinces as $key2)
			{
				if ($key2['PROVINCE_ID']==$proprietaire['PROVINCE_ID']) 
				{
					$html2.='<option value="'.$key2['PROVINCE_ID'].'" selected>'.$key2['PROVINCE_NAME'].'</option>';
				}else
				{
					$html2.='<option value="'.$key2['PROVINCE_ID'].'">'.$key2['PROVINCE_NAME'].'</option>';
				}
				
			}
		}
        $html3='';
		if(!empty($proprietaire['PROVINCE_ID'])){

			$my_select_communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1 AND PROVINCE_ID='.$proprietaire['PROVINCE_ID'].'', '`COMMUNE_NAME` ASC');
			$communes = $this->ModelPs->getRequete($proce_requete, $my_select_communes);

			$html3='<option value="">--- '.lang('selectionner').' ----</option>';


			foreach($communes as $key3)
			{
				if ($key3['COMMUNE_ID']==$proprietaire['COMMUNE_ID']) 
				{
					$html3.='<option value="'.$key3['COMMUNE_ID'].'" selected>'.$key3['COMMUNE_NAME'].'</option>';
				}else
				{
					$html3.='<option value="'.$key3['COMMUNE_ID'].'">'.$key3['COMMUNE_NAME'].'</option>';
				}
				
			}


		}

		$html4='';

		if(!empty($proprietaire['COMMUNE_ID'])){

			$my_select_zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1 AND COMMUNE_ID='.$proprietaire['COMMUNE_ID'].'', '`ZONE_NAME` ASC');
			$zones = $this->ModelPs->getRequete($proce_requete, $my_select_zones);

			$html4='<option value="">--- '.lang('selectionner').' ----</option>';


			foreach($zones as $key4)
			{
				if ($key4['ZONE_ID']==$proprietaire['ZONE_ID']) 
				{
					$html4.='<option value="'.$key4['ZONE_ID'].'" selected>'.$key4['ZONE_NAME'].'</option>';
				}else
				{
					$html4.='<option value="'.$key4['ZONE_ID'].'">'.$key4['ZONE_NAME'].'</option>';
				}
				
			}


		}
		$html5='';

		if(!empty($proprietaire['ZONE_ID'])){

			$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID='.$proprietaire['ZONE_ID'].'', '`COLLINE_NAME` ASC');
			$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);

			$html5='<option value="">--- '.lang('selectionner').' ----</option>';


			foreach($collines as $key5)
			{
				if ($key5['COLLINE_ID']==$proprietaire['COLLINE_ID']) 
				{
					$html5.='<option value="'.$key5['COLLINE_ID'].'" selected>'.$key5['COLLINE_NAME'].'</option>';
				}else
				{
					$html5.='<option value="'.$key5['COLLINE_ID'].'">'.$key5['COLLINE_NAME'].'</option>';
				}
				
			}


		}

		$data['html1']=$html1;
		$data['html2']=$html2;
		$data['html3']=$html3;
		$data['html4']=$html4;
		$data['html5']=$html5;
		$data['utilisateur']=$utilisateur;
		$data['proprietaire']=$proprietaire;

		$this->load->view('Profil_View',$data);
	}


	//Fonction pour modifier le mot de passe
	function edit_pwd(){

		$USER_ID=$this->session->userdata('USER_ID');
		
		$PASSWORD=$this->input->post('NEW_PASSWORD');

// print_r($PASSWORD);die();

		// $verif_pwd=$this->Model->getOne('users',array('USER_ID'=>$USER_ID));

		$table='users';

		$data_updaate= array(

			'PASSWORD'=>md5($PASSWORD),
		);

		$update=$this->Model->update($table,array('USER_ID'=>$USER_ID),$data_updaate);
		$message['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
		$this->session->set_flashdata($message);


		redirect(base_url('profil/Profil'));


	}

	function check_pwd($PASSWORD){
		$USER_ID=$this->session->userdata('USER_ID');


		$verif_pwd=$this->Model->getOne('users',array('USER_ID'=>$USER_ID));

		if ($verif_pwd['PASSWORD']!=md5($PASSWORD)) {

			$html=''.lang('incorrect_pssword').'!';

			
		}else{

			$html='';
		}

		echo json_encode($html);


	}

	//Fonction pour modifier dans la BD les données d'un proprietaire 
	function edit_info(){

		$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
		$PROPRIETAIRE_ID=$this->input->post('PROPRIETAIRE_ID');

		if ($TYPE_PROPRIETAIRE_ID==1) 
		{
			$table='proprietaire';

					// $LOGO_OLD = $this->input->post('LOGO_OLD');

			$NOM_PROPRIETAIRE=$this->input->post('NOM_PROPRIETAIRE');

					// if(empty($_FILES['LOGO']['name']) && !empty($LOGO_OLD))
					// {
					// 	$file_logo = $LOGO_OLD;
					// }elseif (!empty($_FILES['LOGO']['name']) && empty($LOGO_OLD)) {
					// 	$file_logo = $this->upload_document_nomdocument($_FILES['LOGO']['tmp_name'],$_FILES['LOGO']['name'],$NOM_PROPRIETAIRE);

					// }

			$data_updaate=array(
				'TYPE_PROPRIETAIRE_ID'=>$TYPE_PROPRIETAIRE_ID,
				'NOM_PROPRIETAIRE'=>$this->input->post('NOM_PROPRIETAIRE'),
				'CNI_OU_NIF'=>$this->input->post('CNI_OU_NIF'),
						// 'RC'=>$this->input->post('RC'),
				'PERSONNE_REFERENCE'=>$this->input->post('PERSONNE_REFERENCE'),
				'EMAIL'=>$this->input->post('EMAIL'),
				'TELEPHONE'=>$this->input->post('TELEPHONE'),
				'COUNTRY_ID'=>$this->input->post('COUNTRY_ID'),
				'PROVINCE_ID'=>$this->input->post('PROVINCE_ID'),
				'COMMUNE_ID'=>$this->input->post('COMMUNE_ID'),
				'ZONE_ID'=>$this->input->post('ZONE_ID'),
				'COLLINE_ID'=>$this->input->post('COLLINE_ID'),
				'ADRESSE'=>$this->input->post('ADRESSE')
						// 'LOGO'=>$file_logo
			);

			$update=$this->Model->update($table,array('PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID),$data_updaate);

		}else
		{
			$table='proprietaire';

					// $photo_passport_old = $this->input->post('photo_passport_old');

					// if(empty($_FILES['photo_passport']['name']) && !empty($photo_passport_old))
					// {
					// 	$file4 = $photo_passport_old;
					// }elseif (!empty($_FILES['photo_passport']['name']) && empty($photo_passport_old)) {
					// 	$file4 = $this->upload_document_nomdocument($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name'],$this->input->post('NOM_PROPRIETAIRE'));

					// }elseif(!empty($_FILES['photo_passport']['name']) && !empty($photo_passport_old)){

					// 	$file4 = $this->upload_document_nomdocument($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name'],$this->input->post('NOM_PROPRIETAIRE'));

					// }

					// $FILE_CNI_PASSPORT_OLD = $this->input->post('FILE_CNI_PASSPORT_OLD');

					// if(empty($_FILES['FILE_CNI_PASSPORT']['name']) && !empty($FILE_CNI_PASSPORT_OLD))
					// {
					// 	$file_fil = $FILE_CNI_PASSPORT_OLD;
					// }elseif (!empty($_FILES['FILE_CNI_PASSPORT']['name']) && empty($FILE_CNI_PASSPORT_OLD)) {
					// 	$file_fil = $this->upload_cni($_FILES['FILE_CNI_PASSPORT']['tmp_name'],$_FILES['FILE_CNI_PASSPORT']['name'],$NOM_PROPRIETAIRE);

					// }

			$data_updaate=array(
				'TYPE_PROPRIETAIRE_ID'=>$TYPE_PROPRIETAIRE_ID,
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
				'ADRESSE'=>$this->input->post('ADRESSE')
						// 'PHOTO_PASSPORT'=>$file4,
						// 'FILE_CNI_PASSPORT'=>$file_fil

			);



			$update=$this->Model->update($table,array('PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID),$data_updaate);
		}

		$message['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
		$this->session->set_flashdata($message);


		redirect(base_url('profil/Profil'));


	}

	//Fonction pour l'affichage du modal des documents uploader
	function get_modal_documents($PROPRIETAIRE_ID){
		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_proprietaire = $this->getBindParms('proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.EMAIL,proprietaire.TELEPHONE,type_proprietaire.DESC_TYPE_PROPRIETAIRE,proprietaire.DATE_INSERTION,CNI_OU_NIF,proprietaire.PHOTO_PASSPORT,proprietaire.PERSONNE_REFERENCE,proprietaire.ADRESSE,LOGO,FILE_CNI_PASSPORT', 'proprietaire left JOIN type_proprietaire ON type_proprietaire.TYPE_PROPRIETAIRE_ID=proprietaire.TYPE_PROPRIETAIRE_ID', '1 AND proprietaire.PROPRIETAIRE_ID='.$PROPRIETAIRE_ID.'', '`PROPRIETAIRE_ID` ASC');
		$proprietaire = $this->ModelPs->getRequeteOne($proce_requete, $my_select_proprietaire);

		

		if($proprietaire['TYPE_PROPRIETAIRE_ID']==1){

			$fichier = base_url().'upload/proprietaire/photopassport/'.$proprietaire['LOGO'].'';

		}else{

			$fichier = base_url().'upload/proprietaire/piece_identite/'.$proprietaire['FILE_CNI_PASSPORT'].'';

		}

		
		$div_info = '<img src="'.$fichier.'" height="100%"  width="100%"/>';

		$output = array(
			
			"div_info"=>$div_info,
			

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