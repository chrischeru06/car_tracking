<?php
/**
 * Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * 68071895
 * Le 21/2/2024
 * Gestion des utilisateurs
 */
class Users extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->out_application();

	}

	//Fonction pour retourner à la page d'authentification lorsqu'on perd la session
	function out_application()
	{
		if(empty($this->session->userdata('USER_ID')))
		{
			redirect(base_url('Login/logout'));

		}
	}


	//Fonction pour l'affichage de La liste des utilisateurs
	function index(){

		$this->load->view('Users_List_View');
	}


	//Fonction pour la liste des utilisateurs 
	function listing(){


		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";
		
		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}
		$order_by='';
		$order_column=array('id','DESCRIPTION_PROFIL','CODE_PROFIL');
		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' PROFIL_ID DESC';
		}
		
		$search = !empty($_POST['search']['value']) ? (' AND (users.IDENTIFICATION LIKE "%' . $var_search . '%" 
			OR users.TELEPHONE LIKE "%' . $var_search . '%" OR USER_NAME LIKE "%' . $var_search . '%" OR DESCRIPTION_PROFIL LIKE "%' . $var_search . '%")') : '';

		$query_principal='SELECT users.USER_ID,users.IDENTIFICATION,users.TELEPHONE,users.USER_NAME,profil.DESCRIPTION_PROFIL as profile,STATUT,proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.PHOTO_PASSPORT,proprietaire.PROPRIETAIRE_ID FROM `users` join profil on users.PROFIL_ID=profil.PROFIL_ID LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID=users.PROPRIETAIRE_ID WHERE 1';
		$critaire = '' ;
		
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
			if($row->TYPE_PROPRIETAIRE_ID == 1){
				$sub_array[] ='<tbody><tr><td><a href="javascript:;" onclick="get_detail_pers_moral(' . $row->PROPRIETAIRE_ID . ')" style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->IDENTIFICATION . '</td></tr></tbody></a>
				';

			}elseif($row->USER_ID == 1){

				$sub_array[] ='&nbsp;&nbsp;&nbsp;<i class="bi bi-person"></i>&nbsp;&nbsp;&nbsp;&nbsp;'.$row->IDENTIFICATION;


			}else{

				$sub_array[] = ' <tbody><tr><td><a href="javascript:;" onclick="get_detail(' . $row->PROPRIETAIRE_ID . ')"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->IDENTIFICATION . '</td></tr></tbody></a>
				
				<div class="modal fade" id="mypicture' .$row->PROPRIETAIRE_ID.'">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-body">
				<img src = "'.base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT).'" height="100%"  width="100%" >
				</div>
				<div class="modal-footer">
				<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">'.lang('btn_fermer').'</button>
				</div>
				</div>
				</div>
				</div>';
			}

			// $sub_array[]=$row->IDENTIFICATION;
			$sub_array[]=$row->USER_NAME;
			$sub_array[]=$row->TELEPHONE;
			$sub_array[]=$row->profile;
			
			if($row->STATUT==1){
				$sub_array[]='<form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

				<input type = "hidden" value="'.$row->STATUT.'" id="status">

				<table>
				<td><label class="text-primary">'.lang('label_active_acc').'</label></td>
				<td><label class="switch"> 
				<input type="checkbox" id="myCheck" onclick="myFunction_desactive(' . $row->USER_ID . ')" checked>
				<span class="slider round"></span>
				</label></td>
				</table>



				</form>

				';
			}else{
				$sub_array[]='<form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

				<input type = "hidden" value="'.$row->STATUT.'" id="status">

				<table>
				<td><label class="text-danger">'.lang('label_desactive_acc').'</label></td>
				<td><label class="switch"> 
				<input type="checkbox" id="myCheck" onclick="myFunction(' . $row->USER_ID . ')">
				<span class="slider round"></span>
				</label></td>
				</table>
				</form>

				';
			}
			$option = '<div class="dropdown "style="color:white;float:right;">
			<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
			<i class="bi bi-three-dots h5" style="color:blue;"></i>
			<span class="caret"></span></a>
			<ul class="dropdown-menu dropdown-menu-left">
			';
			if ($row->IDENTIFICATION!='ADMIN' || $row->IDENTIFICATION!='admin') {
				$option .= "<li><a class='btn-md' href='" . base_url('administration/Users/getOne/'.md5($row->USER_ID)). "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;".lang('btn_modifier')."</a></li>";
				$option .= "<li><a class='btn-md' href='#' data-toggle='modal'
				data-target='#mydelete" . $row->USER_ID . "'><span class='bi bi-trash h5'></span>&nbsp;&nbsp;".lang('btn_supprimer')."</a></li>";
				$option .="<li>

				<a class='btn-md' href='" . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). "'><i class='bi bi-info-square h5' ></i>&nbsp;&nbsp;".lang('btn_detail')."</a>
				</a>
				</li>";
			}else{

				$option .= "<li><a class='btn-md' href='" . base_url('administration/Users/getOneAdmin/'.$row->USER_ID). "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;".lang('btn_modifier')."</a></li>";


			}
			
			
			$option .= " </ul>
			</div>
			<div class='modal fade' id='mydelete" . $row->USER_ID . "' tabindex='-1'>
			<div class='modal-dialog modal-dialog-centered modal-lg'>
			<div class='modal-content'>
			<div class='modal-header'>
			<h5 class='modal-title'></h5>
			<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
			</div>
			<div class='modal-body'>
			<center><h5>".lang('msg_suppression_util')." <b style='color:cadetblue;'>' " .$row->IDENTIFICATION." '</b> ?</h5></center>
			</div>

			<div class='modal-footer'>
			<a class='btn btn-danger btn-md' href='" . base_url('administration/Users/delete/'.$row->USER_ID) . "'>".lang('btn_supprimer')."</a>
			<button class='btn btn-secondary btn-md' data-dismiss='modal'>".lang('modal_btn_quitter')."</button>
			</div>

			</div>
			</div>
			</div>";
			
			$sub_array[]=$option;

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

	//Affichage de la page de modification
	public function getOne($id){

		$PROPRIETAIRE_ID=$this->uri->segment(4);
		// $geted= $this->Model->getOne('users', array(md5('USER_ID')=>$USER_ID));

		$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_geted = $this->getBindParms('`USER_ID`,`IDENTIFICATION`,`USER_NAME`,`PASSWORD`,`TELEPHONE`,`PROFIL_ID`,`PROPRIETAIRE_ID`,`STATUT`', 'users', '1 AND md5(USER_ID)="'.$id.'"', '`USER_ID` ASC');
			$my_select_geted=str_replace('\"', '"', $my_select_geted);
			$my_select_geted=str_replace('\n', '', $my_select_geted);
			$my_select_geted=str_replace('\"', '', $my_select_geted);
			$geted = $this->ModelPs->getRequeteOne($proce_requete, $my_select_geted);

			 // print_r($geted);die();
		// $data['profiles']=$this->Model->getRequete('SELECT `PROFIL_ID`,`DESCRIPTION_PROFIL` FROM `profil` WHERE 1 ORDER BY DESCRIPTION_PROFIL ASC');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$my_select_provinces = $this->getBindParms('`PROFIL_ID`,`DESCRIPTION_PROFIL`', 'profil', '1', '`DESCRIPTION_PROFIL` ASC');
		$profiles = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);
		$data['profiles']=$profiles;

		$data['geted']=$geted;
		$proprietaire = array('PROPRIETAIRE_ID'=>$id,'TYPE_PROPRIETAIRE_ID'=>NULL,'TYPE_SOCIETE_ID'=>NULL,'NOM_PROPRIETAIRE'=>NULL,'PRENOM_PROPRIETAIRE'=>NULL,'PERSONNE_REFERENCE'=>NULL,'EMAIL'=>NULL,'TELEPHONE'=>NULL,'CNI_OU_NIF'=>NULL,'RC'=>NULL,'PROVINCE_ID'=>NULL,'COMMUNE_ID'=>NULL,'ZONE_ID'=>NULL,'COLLINE_ID'=>NULL,'ADRESSE'=>NULL,'PHOTO_PASSPORT'=>NULL,'LOGO'=>NULL,'FILE_CNI_PASSPORT'=>NULL,'FILE_RC'=>NULL,'FILE_NIF'=>NULL,'CATEGORIE_ID'=>NULL,'COUNTRY_ID'=>NULL);
		$div_photo=' style="display:none;"';
		if(!empty($id))
		{
			$data['btn']="".lang('btn_modifier')."";
			// $data['title']="MODIFICATION D'UN proprietaire";

			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_proprio = $this->getBindParms('PROPRIETAIRE_ID,proprietaire.TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,PERSONNE_REFERENCE,EMAIL,TELEPHONE,CNI_OU_NIF,RC,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,ADRESSE,PHOTO_PASSPORT,LOGO,FILE_NIF,FILE_RC,FILE_CNI_PASSPORT,CATEGORIE_ID,COUNTRY_ID', 'proprietaire', '1 AND PROPRIETAIRE_ID="'.$geted['PROPRIETAIRE_ID'].'"', '`PROPRIETAIRE_ID` ASC');
			$my_select_proprio=str_replace('\"', '"', $my_select_proprio);
			$my_select_proprio=str_replace('\n', '', $my_select_proprio);
			$my_select_proprio=str_replace('\"', '', $my_select_proprio);
			$proprietaire = $this->ModelPs->getRequeteOne($proce_requete, $my_select_proprio);
			// print_r($proprietaire);die();
			if(!empty($proprietaire['PROVINCE_ID'])){

				$my_select_provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1 AND PROVINCE_ID='.$proprietaire['PROVINCE_ID'].'', '`PROVINCE_NAME` ASC');
			$provinces = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);
			$data['provinces']=$provinces;

			$my_select_communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1 AND PROVINCE_ID='.$proprietaire['PROVINCE_ID'].'', '`COMMUNE_NAME` ASC');
			$communes = $this->ModelPs->getRequete($proce_requete, $my_select_communes);

			$my_select_zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1 AND COMMUNE_ID='.$proprietaire['COMMUNE_ID'].'', '`ZONE_NAME` ASC');
			$zones = $this->ModelPs->getRequete($proce_requete, $my_select_zones);

			$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID='.$proprietaire['ZONE_ID'].'', '`COLLINE_NAME` ASC');
			$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);

			}else{

				$my_select_provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1', '`PROVINCE_NAME` ASC');
			$provinces = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);
			$data['provinces']=$provinces;

			$my_select_communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1', '`COMMUNE_NAME` ASC');
			$communes = $this->ModelPs->getRequete($proce_requete, $my_select_communes);

			$my_select_zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1', '`ZONE_NAME` ASC');
			$zones = $this->ModelPs->getRequete($proce_requete, $my_select_zones);

			$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1', '`COLLINE_NAME` ASC');
			$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);

			}

			
			$categorieun = $this->getBindParms('DESC_CATEGORIE,CATEGORIE_ID', 'categories', '1', 'DESC_CATEGORIE ASC');
			// $cate = $this->ModelPs->getRequete($proce_requete, $categorieun);
			$pay = $this->getBindParms('CommonName,COUNTRY_ID', 'countries', '1', 'CommonName ASC');
			$data['pays'] =$this->ModelPs->getRequete($proce_requete, $pay);

			$countries1 = $this->getBindParms('COUNTRY_ID,CommonName,`ITU-T_Telephone_Code`', 'countries', '`ITU-T_Telephone_Code` <> "" ', 'CommonName ASC');
			$countries1=str_replace('\"', '"', $countries1);
			$countries1=str_replace('\n', '', $countries1);
			$countries1=str_replace('\"', '', $countries1);
			$data['countries1'] =$this->ModelPs->getRequete($proce_requete, $countries1);

			
			if($proprietaire['TYPE_PROPRIETAIRE_ID']==1)
			{
				$label_document="".lang('input_nif')."";
				$div_personne_moral='';
				$div_personne_physique=' style="display:none;"';
			}
			else
			{
				$label_document="".lang('input_cni_passeport')."";
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

		$this->load->view('Users_Proprio_Update_View',$data);

	}

	public function getOneAdmin($id){
		$geted= $this->Model->getOne('users', array('USER_ID'=>$id));
			// print_r($geted);die();
		// $data['profiles']=$this->Model->getRequete('SELECT `PROFIL_ID`,`DESCRIPTION_PROFIL` FROM `profil` WHERE 1 ORDER BY DESCRIPTION_PROFIL ASC');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$my_select_provinces = $this->getBindParms('`PROFIL_ID`,`DESCRIPTION_PROFIL`', 'profil', '1', '`DESCRIPTION_PROFIL` ASC');
		$profiles = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);
		$data['profiles']=$profiles;

		$data['geted']=$geted;

		$this->load->view('Users_Update_View',$data);

	}

	

	//Fonction pour modifier dans la BD un utilisateur
	public function modifier(){
		$id_aff=$this->input->post('id');
		$identity= $this->input->post('IDENTIFICATION');
		$email= $this->input->post('E-MAIL');
		$profil= $this->input->post('PROFIL');
		$tel= $this->input->post('numero_telephone');
		$Passworde= $this->input->post('Passworde');


		$this->form_validation->set_rules('IDENTIFICATION', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$this->form_validation->set_rules('E-MAIL', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$this->form_validation->set_rules('PROFIL', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('numero_telephone', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));


		if($this->form_validation->run()==FALSE){
			$this->getOneAdmin($id_aff);

		}

		else{
			if(!empty($Passworde)){
				$array= array('IDENTIFICATION'=>$identity,'USER_NAME'=>$email,'PROFIL_ID'=>$profil,'TELEPHONE'=>$tel,'PASSWORD'=>md5($Passworde));	
			}else{
				$array= array('IDENTIFICATION'=>$identity,'USER_NAME'=>$email,'PROFIL_ID'=>$profil,'TELEPHONE'=>$tel);
			}
			
			$this->Model->update('users',array('USER_ID'=>$id_aff),$array);

			$data['message'] = '<div class="alert alert-primary text-center" id="message">' . lang('msg_success_modif') . '</div>';
			$this->session->set_flashdata($data);
			redirect(base_url('administration/Users'));

		}
	}

	function save_modif(){

		$TYPE_PROPRIETAIRE_ID=$this->input->post('TYPE_PROPRIETAIRE_ID');
		if ($TYPE_PROPRIETAIRE_ID==1) 
		{

			$LOGO_OLD = $this->input->post('LOGO_OLD');
			$FILE_NIF_OLD = $this->input->post('FILE_NIF_OLD');
			$FILE_RC_OLD = $this->input->post('FILE_RC_OLD');




			if(empty($_FILES['LOGO']['name']) && empty($LOGO_OLD))
			{
				$this->form_validation->set_rules('LOGO',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
			}
			if(empty($_FILES['FILE_NIF']['name']) && empty($FILE_NIF_OLD))
			{
				$this->form_validation->set_rules('FILE_NIF',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
			}if(empty($_FILES['FILE_RC']['name']) && empty($FILE_RC_OLD))
			{
				$this->form_validation->set_rules('FILE_RC',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
			}
			$this->form_validation->set_rules('CATEGORIE_ID','CATEGORIE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

			$this->form_validation->set_rules('NOM_PROPRIETAIRE','NOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

			$this->form_validation->set_rules('TELEPHONE','TELEPHONE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

			$COUNTRY_ID=$this->input->post('COUNTRY_ID');
			if($COUNTRY_ID==28){

				$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

				$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

				$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

				$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));



			}

			$this->form_validation->set_rules('ADRESSE','ADRESSE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

		}else 
		{
			$this->form_validation->set_rules('NOM_PROPRIETAIRE','NOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

			$this->form_validation->set_rules('PRENOM_PROPRIETAIRE','PRENOM_PROPRIETAIRE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));
			$FILE_CNI_PASSPORT_OLD = $this->input->post('FILE_CNI_PASSPORT_OLD');


			if(empty($_FILES['FILE_CNI_PASSPORT']['name']) && empty($FILE_CNI_PASSPORT_OLD) )
			{
				$this->form_validation->set_rules('FILE_CNI_PASSPORT',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
			}

			$photo_passport_old = $this->input->post('photo_passport_old');


			if(empty($_FILES['photo_passport']['name']) && empty($photo_passport_old) )
			{
				$this->form_validation->set_rules('photo_passport',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
			}


			$this->form_validation->set_rules('TELEPHONE','TELEPHONE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

			$COUNTRY_ID=$this->input->post('COUNTRY_ID');
			if($COUNTRY_ID==28){

				$this->form_validation->set_rules('PROVINCE_ID','PROVINCE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

				$this->form_validation->set_rules('COMMUNE_ID','COMMUNE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

				$this->form_validation->set_rules('ZONE_ID','ZONE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));

				$this->form_validation->set_rules('COLLINE_ID','COLLINE_ID','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));


			}

			$this->form_validation->set_rules('ADRESSE','ADRESSE','required',array('required'=>'<font style="color:red;">'.lang('msg_validation').'</font>'));
		}

		$id=$this->input->post('PROPRIETAIRE_ID');
		$USER_ID=$this->input->post('id');

		if ($this->form_validation->run() == FALSE)
		{
			$this->getOne($id);
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
				$Password_old=$this->input->post('Password_old');
				$Passworde=$this->input->post('Passworde');
				$Passworde1=$this->input->post('Passworde1');
				if(!empty($Password_old) && !empty($Passworde) && !empty($Passworde1)){

					$data_updaate1=array(
						'IDENTIFICATION'=>$this->input->post('NOM_PROPRIETAIRE'),				
						'USER_NAME'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'PASSWORD'=>md5($Passworde1)

					);
					$updatee=$this->Model->update('users',array('USER_ID'=>$USER_ID),$data_updaate1);
				}else{

					$data_updaate1=array(
						'IDENTIFICATION'=>$this->input->post('NOM_PROPRIETAIRE'),				
						'USER_NAME'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE')


					);
					$updatee=$this->Model->update('users',array('USER_ID'=>$USER_ID),$data_updaate1);

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


				$Password_old=$this->input->post('Password_old');
				$Passworde=$this->input->post('Passworde');
				$Passworde1=$this->input->post('Passworde1');
				if(!empty($Password_old) && !empty($Passworde) && !empty($Passworde1)){

					$data_updaate2=array(
						'IDENTIFICATION'=>$this->input->post('NOM_PROPRIETAIRE'),				
						'USER_NAME'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE'),
						'PASSWORD'=>md5($Passworde1)

					);
					$updatee=$this->Model->update('users',array('USER_ID'=>$USER_ID),$data_updaate2);
				}else{

					$data_updaate2=array(
						'IDENTIFICATION'=>$this->input->post('NOM_PROPRIETAIRE'),				
						'USER_NAME'=>$this->input->post('EMAIL'),
						'TELEPHONE'=>$this->input->post('TELEPHONE')


					);
					$updatee=$this->Model->update('users',array('USER_ID'=>$USER_ID),$data_updaate2);

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
				$message['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
				$this->session->set_flashdata($message);
				redirect(base_url('administration/Users'));

			}else
			{
				$message['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_error_modif').' </div>';
				$this->session->set_flashdata($message);

			}
			redirect(base_url('administration/Users'));

		}
	}
	function check_pwd($PASSWORD,$id){
		$USER_ID=$id;


		$verif_pwd=$this->Model->getOne('users',array('USER_ID'=>$USER_ID));

		if ($verif_pwd['PASSWORD']!=md5($PASSWORD)) {

			$html=''.lang('incorrect_pssword').' !';

			
		}else{

			$html='';
		}

		echo json_encode($html);


	}
	//Fonction pour activer/desactiver un proprietaire
	function active_desactive($status,$USER_ID){

		if($status==1){
			$this->Model->update('users', array('USER_ID'=>$USER_ID),array('STATUT'=>2));

			$data['message'] = '<div class="alert alert-danger text-center" id="message">' . lang('swal_desactive_proprio') . '</div>';
			$this->session->set_flashdata($data);

		}else if($status==2){
			$this->Model->update('users', array('USER_ID'=>$USER_ID),array('STATUT'=>1));
			$data['message'] = '<div class="alert alert-primary text-center" id="message">' . lang('swal_active_proprio') . '</div>';
			$this->session->set_flashdata($data);
		}

		echo json_encode(array('status'=>$status));


	}

	//Fonction pour supprimer l'utilisateur
	function delete()
	{
		$table="users";
		$criteres['USER_ID']=$this->uri->segment(4);
		$data['rows']= $this->Model->getOne($table,$criteres);
		$this->Model->delete($table,$criteres);
		$data['message']='<div class="alert alert-success text-center" id="message">'.lang('suppression_success').'</div>';
		$this->session->set_flashdata($data);
		redirect(base_url('administration/Users/index'));
	}


	//Fonction pour ne pas pouvoir desactiver un admin
	function non_desactive_user()
	{
		$statut=2;

		echo json_encode($statut);
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