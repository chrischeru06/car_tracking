<?php
/*
	Auteur    : Niyomwungere Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 15/10/2024
	espace perso du gestionnaire
*/
	class Espace_Perso_Gestionnaire extends CI_Controller
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
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');

			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$motif_activation = $this->getBindParms('ID_MOTIF,DESC_MOTIF', 'motif', '1 AND ID_TYPE=1', 'DESC_MOTIF ASC');
			$data['motif_ativ'] = $this->ModelPs->getRequete($proce_requete, $motif_activation);

			$motif_desactivation = $this->getBindParms('ID_MOTIF,DESC_MOTIF', 'motif', '1 AND ID_TYPE=2', 'DESC_MOTIF ASC');
			$data['motif_des'] = $this->ModelPs->getRequete($proce_requete, $motif_desactivation);
			if ($PROFIL_ID==1) {
				$this->Model->update('vehicule',array('STAT_NOTIFICATION'=>1),array('STAT_NOTIFICATION'=>2));
			}
			
			$this->load->view('Vehicule_liste_View',$data);
		}

		//Fonction pour l'affichage
		function listing()
		{
			$USER_ID = $this->session->userdata('USER_ID');

			$CHECK_VALIDE = $this->input->post('CHECK_VALIDE');
			$PROFIL_ID=$this->session->userdata('PROFIL_ID');

			// print_r($PROFIL_ID);die();

			$critaire = '' ;
			$critaire_doc_valide = '' ;

			$date_now = date('Y-m-d');

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}

			if($CHECK_VALIDE == 1) // Assurance valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 2) // Assurance invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE < "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 3) // Controle technique valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 4) // Controle technique invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK < "'.$date_now.'"';
			}

			$query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,STATUT_VEH_AJOUT,`LOGO`,vehicule.FILE_ASSURANCE,vehicule.FILE_CONTRO_TECHNIQUE,vehicule.STATUT FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire_doc_valide.'';


			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$limit = ' LIMIT 0,10';
			if($_POST['length'] != -1)
			{
				$limit = ' LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}

			$order_by='';
			$order_column=array('CODE','DESC_MARQUE','DESC_MODELE','PLAQUE','COULEUR','PHOTO','DATE_SAVE');

			$order_by=isset($_POST['order']) ? ' ORDER BY '.$order_column[$_POST['order']['0']['column']].' ' .$_POST['order']['0']['dir'] : ' ORDER BY VEHICULE_ID DESC';

			$order_by = ' ORDER BY VEHICULE_ID DESC';

			$search=!empty($_POST['search']['value']) ? (" AND (vehicule.CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

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

				if($this->session->userdata('PROFIL_ID') == 1)
				{
					if (!empty($row->CODE)) {
						$sub_array[]=$row->CODE;
					}else{
						$sub_array[]="".lang('lste_n_a')."";

					}
					
				}

				$sub_array[]=$row->PLAQUE;
				$sub_array[]=$row->DESC_MARQUE;
				// $sub_array[]=$row->DESC_MODELE;
				$sub_array[]=$row->COULEUR;


				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a href='".base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID."'>&nbsp;&nbsp;&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				if($row->STATUT_VEH_AJOUT == 2 && $this->session->userdata('PROFIL_ID') == 1){

					$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->STATUT_VEH_AJOUT.'" id="status">

					<center title='.lang('checkbox_desactiver').'><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_desactive(' . $row->VEHICULE_ID . ')" checked >
					<span class="slider round"></span>
					</label></center>
					</form>

					';
				}
				elseif($row->STATUT_VEH_AJOUT == 2 && $this->session->userdata('PROFIL_ID') != 1){

					$sub_array[]='<center><i class="fa fa-check text-success  small" title='.lang('title_demande_approuve').'></i></i><font style="font-size:14px;" class="text-success" title='.lang('title_demande_approuve').'> </font></center>';
				}
				elseif ($row->STATUT_VEH_AJOUT==1) 
				{
					$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title='.lang('title_veh_attente').'></i></center>';

				}elseif ($row->STATUT_VEH_AJOUT==3) 
				{
					$sub_array[]='<center><i class="fa fa-ban text-danger  small" title='.lang('title_veh_refuse').'></i></i><font style="font-size:14px;" class="text-danger" title='.lang('title_veh_refuse').'> </font></center>';
				}elseif($row->STATUT_VEH_AJOUT == 4 && $this->session->userdata('PROFIL_ID') == 1){
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->STATUT_VEH_AJOUT.'" id="status">
					
					
					<center title='.lang('btn_active').'><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_active(' . $row->VEHICULE_ID . ')">
					<span class="slider round"></span>
					</label></center>
					
					</form>

					';
				}
				elseif($row->STATUT_VEH_AJOUT == 4 && $this->session->userdata('PROFIL_ID') != 1){
					$sub_array[]='<center><i class="fa fa-close text-danger  small" title='.lang('title_veh_desactive').'></i></i><font style="font-size:14px;" class="text-danger" title='.lang('title_veh_desactive').'> </font></center>';
				}

				$option = '<div class="dropdown text-center">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>	
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';


				if(!empty($row->DATE_FIN_ASSURANCE))
				{
					if(date('Y-m-d',strtotime($row->DATE_FIN_ASSURANCE)) >= date('Y-m-d'))
					{
						$sub_array[] = '<center><i class="fa fa-check text-success small" title='.lang('title_valide').'></i><font class="text-success small" title='.lang('title_valide').'> </font></center>';
					}
					else 
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger small" title='.lang('title_expire').'></i><font class="text-danger small" title='.lang('title_expire').'> </font></center>';

						$option.='<a class="btn-md" style="cursor:pointer;" onclick="assure_controle(\''.$row->VEHICULE_ID .'\',1)"> <li class="btn-md" style=""><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td>Assurance</td></tr></table></li></a>';
						
					}
				}
				else
				{
					$sub_array[] = '<center><font class="small" title="">'.lang('lste_n_a').'</font></center>';
				}
				
				if(!empty($row->DATE_FIN_CONTROTECHNIK))
				{
					if($row->DATE_FIN_CONTROTECHNIK >= date('Y-m-d'))
					{
						$sub_array[] = '<center><i class="fa fa-check text-success small" title='.lang('title_valide').'></i><font class="text-success small" title='.lang('title_valide').'> </font></center>';
					}
					else
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger small" title='.lang('title_expire').'></i><font class="text-danger small" title='.lang('title_expire').'> </font></center>';

						$option.='<a class="btn-md" style="cursor:pointer;" onclick="assure_controle('.$row->VEHICULE_ID.',2)"><li class="btn-md" style=""><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td>'.lang('td_ctrl_technique').'</td></tr></table></li></a>';
					}
				}
				else
				{
					$sub_array[] = '<center><font class="small" title="">'.lang('lste_n_a').'</font></center>';
				}

				
				if ($row->STATUT_VEH_AJOUT == 1 || $row->STATUT_VEH_AJOUT ==2 )
				{
					$option .= "<a class='btn-md' href='" . base_url('vehicule/Vehicule/ajouter/'.md5($row->VEHICULE_ID)) . "'><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_modifier')."</li></a>";
					
				}

				if($PROFIL_ID == 1)
				{
					if($row->STATUT_VEH_AJOUT == 1 || $row->STATUT_VEH_AJOUT == 3){
						$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->VEHICULE_ID . ",".$row->STATUT_VEH_AJOUT.")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_traiter')."</li></a>";

					}
					if($row->STATUT == 1 && $row->STATUT_VEH_AJOUT == 2)
					{

						$option.='<a class="btn-md" href="'.base_url('vehicule/Vehicule/affecter_vehicule/'.$row->VEHICULE_ID).'"><li class="btn-md" style=""><table><tr><td><i class="fa fa-plus h5" ></i></td><td>'.lang('btn_affecter_chauf').'</td></tr></table></li></a>';
					}
					else if($row->STATUT == 2)
					{

						$option .= "<a class='btn-md' href='#' data-toggle='modal' data-target='#modal_retirer" . $row->VEHICULE_ID . "'><li class='btn-md'><table><tr><td><i class='fa fa-close h5' ></i></td><td>".lang('btn_annuler_affectation')."</td></tr></table></li></a>";
					}
				}

				
				

				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_retirer" .$row->VEHICULE_ID. "'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5></h5>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>".lang('modal_annuler_affectation')." <b>" . $row->DESC_MARQUE.' - '.$row->DESC_MODELE." ? </b></h5></center>
				<div class='modal-footer'>

				<a class='btn btn-outline-primary rounded-pill' href='".base_url('vehicule/Vehicule/annuler_affectation/'.md5($row->CODE))."' ><font class='fa fa-check'></font> ".lang('modal_btn_valider')."</a>

				<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
				</div>
				</div>
				</div>
				</div>
				</div>";

				
				if ($row->TYPE_PROPRIETAIRE_ID==1)
				{
					$option .="
					</div>
					<div class='modal fade' id='proprio" .$row->VEHICULE_ID."' style='border-radius:100px;'>
					<div class='modal-dialog modal-lg'>
					<div class='modal-content'>

					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h5 class='modal-title'>".lang('title_info_proprio')."</h5>
					<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>

					<h4 class=''></h4>

					<div class='row'>

					<div class='col-md-6'>
					<img src = '".base_url('upload/proprietaire/photopassport/'.$row->LOGO)."' height='100%'  width='100%'  style= 'border-radius:50%;'>
					</div>

					<div class='col-md-6'>

					<h4></h4>

					<table class='table table-borderless'>

					<tr>
					<td class='btn-sm'>".lang('input_nom')."</td>

					<th class='btn-sm'>".$row->NOM_PROPRIETAIRE."</th>
					</tr>

					<tr>
					<td class='btn-sm'>".lang('input_adresse')."</td>

					<th class='btn-sm'>".$row->ADRESSE."</th>
					</tr>

					<tr class='btn-sm'>
					<td>".lang('input_email')."</td>

					<th class='btn-sm'>".$row->EMAIL."</th>
					</tr>

					<tr>
					<td class='btn-sm'>".lang('input_tlphone')."</td>

					<td class='btn-sm'>".$row->TELEPHONE."</td>
					</tr>

					</table>

					</div>
					</div>

					</div>
					</div>
					</div>";

				}else
				{
					$option .="
					</div>
					<div class='modal fade' id='proprio" .$row->VEHICULE_ID."' style='border-radius:100px;'>
					<div class='modal-dialog modal-lg'>
					<div class='modal-content'>

					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h5 class='modal-title'>".lang('title_info_proprio')."</h5>
					<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
					</div>
					<div class='modal-body'>

					<h4 class=''></h4>

					<div class='row'>

					<div class='col-md-6'>
					<img src = '".base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT)."' height='100%'  width='100%'  style= 'border-radius:50%;'>
					</div>

					<div class='col-md-6'>

					<h4></h4>

					<table class='table table-borderless'>

					<tr>
					<td class='btn-sm'>".lang('input_nom')."</td>
					<th class='btn-sm'>".$row->NOM_PROPRIETAIRE."</th>
					</tr>

					<tr>
					<td class='btn-sm'>".lang('input_adresse')."</td>

					<th class='btn-sm'>".$row->ADRESSE."</th>
					</tr>

					<tr class='btn-sm'>
					<td>Email</td>

					<th class='btn-sm'>".$row->EMAIL."</th>
					</tr>

					<tr>
					<td class='btn-sm'>".lang('input_tlphone')."</td>

					<td class='btn-sm'>".$row->TELEPHONE."</td>
					</tr>

					</table>

					</div>
					</div>

					</div>
					</div>
					</div>";
				}

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


		  // // $ouput= array(
         // //        'html'=>$html,
         // //        'html1'=>$html1,
		 // );
			echo json_encode($html);
		}

		function save_stat_vehicul()
		{
		// $statut=1 traitement avec succes;
		// $statut=2:traitement echoue
		// $statut=3: traitement 
			$statut=3;


			$STATUT_VEH_AJOUT = $this->input->post('STATUT_VEH_AJOUT');
			$VEHICULE_ID = $this->input->post('VEHICULE_TRAITE');
			$TRAITEMENT_DEMANDE_ID = $this->input->post('TRAITEMENT_DEMANDE_ID');
			$COMMENTAIRE = $this->input->post('COMMENTAIRE');
			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			if ($TRAITEMENT_DEMANDE_ID == 1) {
				$CODE = $this->input->post('CODE');
				$my_select_code_veh = $this->getBindParms('CODE', 'vehicule', '1 AND CODE="'.$CODE.'"', '`VEHICULE_ID` ASC');
				$my_select_code_veh=str_replace('\"', '"', $my_select_code_veh);
				$my_select_code_veh=str_replace('\n', '', $my_select_code_veh);
				$my_select_code_veh=str_replace('\"', '', $my_select_code_veh);
				$code_vehicule = $this->ModelPs->getRequete($proce_requete, $my_select_code_veh);

				if (!empty($code_vehicule)) {
					$statut=4;
				}else{
					$statut=3;
				}
			}
			
			$vehcul = false;

			if($statut==3){

				if ($STATUT_VEH_AJOUT == 1 && $TRAITEMENT_DEMANDE_ID==1) 
				{
					$vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>2,'CODE'=>$CODE));

				}
				else if ($STATUT_VEH_AJOUT == 3 && $TRAITEMENT_DEMANDE_ID==1) 
				{
					$vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>2,'CODE'=>$CODE));

				}
				else if ($STATUT_VEH_AJOUT==1 && $TRAITEMENT_DEMANDE_ID==2) {
					$vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>3)); 
				}

				if($vehcul==true)
				{
					$statut=1;
				}else
				{
					$statut=2;
				}
			}
			
			echo json_encode($statut);
		}

		//Fonction pour activer/desactiver un vehicule
		function active_desactive($status){
			$USER_ID = $this->session->userdata('USER_ID');

			if($status == 2){
				$ID_MOTIF_des = $this->input->post('ID_MOTIF_des');
				$VEHICULE_ID = $this->input->post('VEHICULE_ID_ID');

				$this->Model->update('vehicule', array('VEHICULE_ID'=>$VEHICULE_ID),array('STATUT_VEH_AJOUT'=>4));
				
				$data = array('USER_ID'=>$USER_ID,'STATUT'=>4,'ID_MOTIF'=>$ID_MOTIF_des,'VEHICULE_ID'=>$VEHICULE_ID);

				$result = $this->Model->create('historique_activ_des_vehicule',$data);

				$status = 2;

			}else if($status == 4){
				$ID_MOTIF = $this->input->post('ID_MOTIF');
				$VEHICULE_ID = $this->input->post('VEHICULE_ID_I');

				$data = array('USER_ID'=>$USER_ID,'STATUT'=>2,'ID_MOTIF'=>$ID_MOTIF,'VEHICULE_ID'=>$VEHICULE_ID);

				$result = $this->Model->create('historique_activ_des_vehicule',$data);
				$this->Model->update('vehicule', array('VEHICULE_ID'=>$VEHICULE_ID),array('STATUT_VEH_AJOUT'=>2));
				$status = 1;
			}

			echo json_encode(array('status'=>$status));

		}

       // Appel du formulaire d'enregistrement
		function ajouter()
		{			
			$VEHICULE_ID = $this->uri->segment(4);
			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');
			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			if ($PROFIL_ID!=1) {



				$user_req = $this->getBindParms('PROPRIETAIRE_ID','users','1 and USER_ID='.$USER_ID,'USER_ID ASC');
				$user = $this->ModelPs->getRequeteOne($psgetrequete, $user_req);

				$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 and PROPRIETAIRE_ID='.$user['PROPRIETAIRE_ID'],'proprio_desc ASC');

				$proprio=str_replace('\"', '"', $proprio);
				$proprio=str_replace('\n', '', $proprio);
				$proprio=str_replace('\"', '', $proprio);

				$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);
				// print_r($proprio);die();

				if (!empty($VEHICULE_ID)) {

					$user_req = $this->getBindParms('PROPRIETAIRE_ID','users','1 and USER_ID='.$USER_ID,'USER_ID ASC');
					$user = $this->ModelPs->getRequeteOne($psgetrequete, $user_req);

					$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 and PROPRIETAIRE_ID='.$user['PROPRIETAIRE_ID'],'proprio_desc ASC');

					$proprio=str_replace('\"', '"', $proprio);
					$proprio=str_replace('\n', '', $proprio);
					$proprio=str_replace('\"', '', $proprio);

					$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);
				}
			}else{
				$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');

				$proprio=str_replace('\"', '"', $proprio);
				$proprio=str_replace('\n', '', $proprio);
				$proprio=str_replace('\"', '', $proprio);

				$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);
			}

			$data['btn'] = "Enregistrer";
			$data['title']="Enregistrement du véhicule";

			$vehicule = array('VEHICULE_ID'=>NULL,'ID_MARQUE'=>NULL,'ID_MODELE'=>NULL,'CODE'=>NULL,'PLAQUE'=>NULL,'COULEUR'=>NULL,'KILOMETRAGE'=>NULL,'PHOTO'=>NULL,'PROPRIETAIRE_ID'=>NULL,'ANNEE_FABRICATION'=>NULL,'NUMERO_CHASSIS'=>NULL,'USAGE_ID'=>NULL,'DATE_FIN_CONTROTECHNIK'=>NULL,'DATE_FIN_ASSURANCE'=>NULL,'DATE_DEBUT_CONTROTECHNIK'=>NULL,'DATE_DEBUT_ASSURANCE'=>NULL,'FILE_CONTRO_TECHNIQUE'=>NULL,'FILE_ASSURANCE'=>NULL,'ID_ASSUREUR'=>NULL,'IMAGE_AVANT'=>NULL,'IMAGE_ARRIERE'=>NULL,'IMAGE_LATERALE_GAUCHE'=>NULL,'IMAGE_LATERALE_DROITE'=>NULL,'IMAGE_TABLEAU_DE_BORD'=>NULL,'IMAGE_SIEGE_AVANT'=>NULL,'IMAGE_SIEGE_ARRIERE'=>NULL,'SHIFT_ID'=>NULL);
			

			$marque = $this->getBindParms('ID_MARQUE,DESC_MARQUE','vehicule_marque',' 1 ','DESC_MARQUE ASC');
			$marque = $this->ModelPs->getRequete($psgetrequete, $marque);
			$usage = $this->getBindParms('USAGE_ID,USAGE_DESC','veh_usage',' 1 ','USAGE_DESC ASC');
			$usage = $this->ModelPs->getRequete($psgetrequete, $usage);

			$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' 1 ','DESC_MODELE ASC');
			$modele = $this->ModelPs->getRequete($psgetrequete, $modele);
			

			$assureur = $this->getBindParms('`ID_ASSUREUR`, `ASSURANCE`', 'assureur', '1', '`ASSURANCE` ASC');
			$assureur = $this->ModelPs->getRequete($psgetrequete, $assureur);

			$shift = $this->getBindParms('`SHIFT_ID`, HEURE_DEBUT,HEURE_FIN', 'shift', '1', '`SHIFT_ID` ASC');
			$shift = $this->ModelPs->getRequete($psgetrequete, $shift);


			if(!empty($VEHICULE_ID))
			{
				$data['btn'] = "Modifier";
				$data['title'] = "Modification du véhicule";

				$vehicule = $this->Model->getRequeteOne("SELECT vehicule.VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,PROPRIETAIRE_ID,NUMERO_CHASSIS,USAGE_ID,ANNEE_FABRICATION,vehicule.DATE_FIN_CONTROTECHNIK,vehicule.DATE_FIN_ASSURANCE,vehicule.DATE_DEBUT_ASSURANCE,vehicule.DATE_DEBUT_CONTROTECHNIK,vehicule.FILE_CONTRO_TECHNIQUE,vehicule.FILE_ASSURANCE,vehicule.ID_ASSUREUR,vehicule.IMAGE_AVANT,vehicule.IMAGE_ARRIERE,vehicule.IMAGE_LATERALE_GAUCHE,vehicule.IMAGE_LATERALE_DROITE,vehicule.IMAGE_TABLEAU_DE_BORD,vehicule.IMAGE_SIEGE_AVANT,vehicule.IMAGE_SIEGE_ARRIERE,SHIFT_ID FROM vehicule LEFT JOIN historique_assurance ON historique_assurance.VEHICULE_ID = vehicule.VEHICULE_ID WHERE md5(vehicule.VEHICULE_ID)='".$VEHICULE_ID."'");

				// if(empty($vehicule))
				// {
				// 	redirect(base_url('Login/logout'));
				// }

				// print_r($vehicule);die();

				$marque = $this->getBindParms('ID_MARQUE,DESC_MARQUE','vehicule_marque',' 1 ','DESC_MARQUE ASC');
				$marque = $this->ModelPs->getRequete($psgetrequete, $marque);

				$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' 1 ','DESC_MODELE ASC');
				$modele = $this->ModelPs->getRequete($psgetrequete, $modele);
				$usage = $this->getBindParms('USAGE_ID,USAGE_DESC','veh_usage',' 1 ','USAGE_DESC ASC');
				$usage = $this->ModelPs->getRequete($psgetrequete, $usage);

				if ($PROFIL_ID==1) {
					$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');

					$proprio=str_replace('\"', '"', $proprio);
					$proprio=str_replace('\n', '', $proprio);
					$proprio=str_replace('\"', '', $proprio);

					$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);
				}
				

				

			}

			$data['vehicule_data']=$vehicule;
			$data['marque']=$marque;
			$data['modele']=$modele;
			$data['usage']=$usage;
			$data['proprio']=$proprio;
			$data['assureur'] = $assureur;
			$data['shift'] = $shift;

			// print_r($vehicule);die();
			$this->load->view('Vehicule_add_View',$data);
		}

       //Selection des noms des vehicules
		function get_modele($ID_MARQUE)
		{
			$html="<option value=''>-- ".lang('selectionner')." --</option>";

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' ID_MARQUE='.$ID_MARQUE.'','DESC_MODELE ASC');
			$modele = $this->ModelPs->getRequete($psgetrequete, $modele);

			// $modele = $this->Model->getRequete("SELECT ID_MODELE ,DESC_MODELE FROM vehicule_modele WHERE ID_MARQUE=".$ID_MARQUE." ORDER BY DESC_MODELE ASC");

			foreach ($modele as $modele)
			{
				$html.="<option value='".$modele['ID_MODELE']."'>".$modele['DESC_MODELE']."</option>";
			}
			echo json_encode($html);
		}

		   //PERMET L'UPLOAD DE L'IMAGE
		public function upload_file($input_name)
		{
			$nom_file = $_FILES[$input_name]['tmp_name'];
			$nom_champ = $_FILES[$input_name]['name'];
			$ext=pathinfo($nom_champ, PATHINFO_EXTENSION);
			$repertoire_fichier = FCPATH . 'upload/photo_vehicule/';
			$code=uniqid();
			$name=$code. 'VEHICULE.' .$ext;
			$file_link = $repertoire_fichier . $name;

			if (!is_dir($repertoire_fichier)) {
				mkdir($repertoire_fichier, 0777, TRUE);
			}
			move_uploaded_file($nom_file, $file_link);
			return $name;
		}

        //Verification des donnees uniques pour les vehicules

		// function check_existe($VEHICULE_ID=0,$CODE="",$PLAQUE="")
		// {
		// 	print_r($PLAQUE);die();
		// }


		
		//Fonction pour l'enregistrement de données dans la base de données ainsi que la mise à jour
		function save()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			if(empty($VEHICULE_ID))   //Controle d'enregistrement
			{

				// $this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ID_ASSUREUR','ID_ASSUREUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('ID_MODELE','ID_MODELE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

				$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('KILOMETRAGE','KILOMETRAGE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('USAGE_ID','USAGE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('NUMERO_CHASSIS','NUMERO_CHASSIS','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('ANNEE_FABRICATION','ANNEE_FABRICATION','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('DATE_DEBUT_CONTROTECHNIK','DATE_DEBUT_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_CONTROTECHNIK','DATE_FIN_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_DEBUT_ASSURANCE','DATE_DEBUT_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_ASSURANCE','DATE_FIN_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				if (empty($_FILES['PHOTO_OUT']['name']))
				{
					$this->form_validation->set_rules('PHOTO_OUT','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['FILE_CONTRO_TECHNIQUE']['name']))
				{
					$this->form_validation->set_rules('FILE_CONTRO_TECHNIQUE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['FILE_ASSURANCE']['name']))
				{
					$this->form_validation->set_rules('FILE_ASSURANCE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_AVANT']['name']))
				{
					$this->form_validation->set_rules('IMAGE_AVANT','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_ARRIERE']['name']))
				{
					$this->form_validation->set_rules('IMAGE_ARRIERE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_LATERALE_GAUCHE']['name']))
				{
					$this->form_validation->set_rules('IMAGE_LATERALE_GAUCHE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_LATERALE_DROITE']['name']))
				{
					$this->form_validation->set_rules('IMAGE_LATERALE_DROITE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_TABLEAU_DE_BORD']['name']))
				{
					$this->form_validation->set_rules('IMAGE_TABLEAU_DE_BORD','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_SIEGE_AVANT']['name']))
				{
					$this->form_validation->set_rules('IMAGE_SIEGE_AVANT','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['IMAGE_SIEGE_ARRIERE']['name']))
				{
					$this->form_validation->set_rules('IMAGE_SIEGE_ARRIERE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				

				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$PHOTO = $this->upload_file('PHOTO_OUT');
					$file_controtechnik = $this->upload_file('FILE_CONTRO_TECHNIQUE');
					$file_assurance = $this->upload_file('FILE_ASSURANCE');
					$IMAGE_AVANT = $this->upload_file('IMAGE_AVANT');
					$IMAGE_ARRIERE = $this->upload_file('IMAGE_ARRIERE');
					$IMAGE_LATERALE_GAUCHE = $this->upload_file('IMAGE_LATERALE_GAUCHE');
					$IMAGE_LATERALE_DROITE = $this->upload_file('IMAGE_LATERALE_DROITE');
					$IMAGE_TABLEAU_DE_BORD = $this->upload_file('IMAGE_TABLEAU_DE_BORD');
					$IMAGE_SIEGE_AVANT = $this->upload_file('IMAGE_SIEGE_AVANT');
					$IMAGE_SIEGE_ARRIERE = $this->upload_file('IMAGE_SIEGE_ARRIERE');

					$data = array(
						'ID_ASSUREUR'=>$this->input->post('ID_ASSUREUR'),
						'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
						'ID_MODELE'=>$this->input->post('ID_MODELE'),
						'PLAQUE'=>$this->input->post('PLAQUE'),
						'COULEUR'=>$this->input->post('COULEUR'),
						'KILOMETRAGE'=>$this->input->post('KILOMETRAGE'),
						'PHOTO'=>$PHOTO,
						'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
						'USAGE_ID'=>$this->input->post('USAGE_ID'),
						'NUMERO_CHASSIS'=>$this->input->post('NUMERO_CHASSIS'),
						'ANNEE_FABRICATION'=>$this->input->post('ANNEE_FABRICATION'),
						'DATE_DEBUT_ASSURANCE'=>$this->input->post('DATE_DEBUT_ASSURANCE'),
						'DATE_FIN_ASSURANCE'=>$this->input->post('DATE_FIN_ASSURANCE'),
						'DATE_DEBUT_CONTROTECHNIK'=>$this->input->post('DATE_DEBUT_CONTROTECHNIK'),
						'DATE_FIN_CONTROTECHNIK'=>$this->input->post('DATE_FIN_CONTROTECHNIK'),
						'FILE_ASSURANCE'=>$file_assurance,
						'FILE_CONTRO_TECHNIQUE'=>$file_controtechnik,
						'IMAGE_AVANT'=>$IMAGE_AVANT,
						'IMAGE_ARRIERE'=>$IMAGE_ARRIERE,
						'IMAGE_LATERALE_GAUCHE'=>$IMAGE_LATERALE_GAUCHE,
						'IMAGE_LATERALE_DROITE'=>$IMAGE_LATERALE_DROITE,
						'IMAGE_TABLEAU_DE_BORD'=>$IMAGE_TABLEAU_DE_BORD,
						'IMAGE_SIEGE_AVANT'=>$IMAGE_SIEGE_AVANT,
						'IMAGE_SIEGE_ARRIERE'=>$IMAGE_SIEGE_ARRIERE,
						'SHIFT_ID'=>$this->input->post('SHIFT_ID'),

						
					);

					$table = "vehicule";

					$VEHICULE_ID = $this->Model->insert_last_id($table,$data);

					// Enregistrement dans la table d'historique d'assaurance

					$data_histo_assure = array(
						'VEHICULE_ID' => $VEHICULE_ID,
						'ID_ASSUREUR'=> $this->input->post('ID_ASSUREUR'),
						'USER_ID' => $this->input->post('USER_ID'),
						'DATE_DEBUT_ASSURANCE'=>$this->input->post('DATE_DEBUT_ASSURANCE'),
						'DATE_FIN_ASSURANCE'=>$this->input->post('DATE_FIN_ASSURANCE'),
						'FILE_ASSURANCE' => $file_assurance,
					);

					$create_assure = $this->Model->create('historique_assurance',$data_histo_assure);

				// Enregistrement dans la table d'historique du controle technique

					$data_histo_controle = array(
						'VEHICULE_ID' => $VEHICULE_ID,
						'USER_ID' => $this->input->post('USER_ID'),
						'DATE_DEBUT_CONTROTECHNIK'=>$this->input->post('DATE_DEBUT_CONTROTECHNIK'),
						'DATE_FIN_CONTROTECHNIK'=>$this->input->post('DATE_FIN_CONTROTECHNIK'),
						'FILE_CONTRO_TECHNIQUE' => $file_controtechnik,
					);

					$create_controle = $this->Model->create('historique_controle_technique',$data_histo_controle);

					// Enregistrement dans la table d'historique d'etat du vehicule
					$data_histo_etat_vehicule = array(
						'VEHICULE_ID' => $VEHICULE_ID,
						'USER_ID' => $this->input->post('USER_ID'),
						'IMAGE_AVANT'=>$IMAGE_AVANT,
						'IMAGE_ARRIERE'=>$IMAGE_ARRIERE,
						'IMAGE_LATERALE_GAUCHE'=>$IMAGE_LATERALE_GAUCHE,
						'IMAGE_LATERALE_DROITE'=>$IMAGE_LATERALE_DROITE,
						'IMAGE_TABLEAU_DE_BORD'=>$IMAGE_TABLEAU_DE_BORD,
						'IMAGE_SIEGE_AVANT'=>$IMAGE_SIEGE_AVANT,
						'IMAGE_SIEGE_ARRIERE'=>$IMAGE_SIEGE_ARRIERE,
					);

					$create_etat = $this->Model->create('historique_etat_vehicule',$data_histo_etat_vehicule);

					if ($create_assure && $create_controle && $create_etat)
					{
						$message['message']='<div class="alert alert-success text-center" id="message">Enregistrement du vehicule avec succès</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule'));

					}else
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">Echec d\'enregistrement </div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule'));

					}

				}


			}else // Controle de mise à jour
			{

				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ID_MODELE','ID_MODELE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				// $this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

				$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('KILOMETRAGE','KILOMETRAGE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('USAGE_ID','USAGE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('NUMERO_CHASSIS','NUMERO_CHASSIS','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('ANNEE_FABRICATION','ANNEE_FABRICATION','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('DATE_DEBUT_CONTROTECHNIK','DATE_DEBUT_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_CONTROTECHNIK','DATE_FIN_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_DEBUT_ASSURANCE','DATE_DEBUT_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_ASSURANCE','DATE_FIN_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));


				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$VEHICULE_ID = $this->input->post('VEHICULE_ID');

					// $check_existe = $this->Model->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,PROPRIETAIRE_ID FROM vehicule WHERE VEHICULE_ID != '".$VEHICULE_ID."'");

					$psgetrequete = "CALL `getRequete`(?,?,?,?);";

					// $check_existe = $this->getBindParms('VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,"PLAQUE",PROPRIETAIRE_ID','vehicule',' VEHICULE_ID !='.$VEHICULE_ID.' and CODE='.$this->input->post('CODE').'','VEHICULE_ID ASC');
					// $check_existe=str_replace('\"', '"', $check_existe);
					// $check_existe=str_replace('\n', '', $check_existe);
					// $check_existe=str_replace('\"', '', $check_existe);

					// $check_existe1 = $this->ModelPs->getRequete($psgetrequete, $check_existe);
					$val_plaque=$this->input->post('PLAQUE');
					$check_existe_plak = $this->getBindParms('VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,"PLAQUE",PROPRIETAIRE_ID','vehicule',' VEHICULE_ID !='.$VEHICULE_ID.' and PLAQUE="'.$val_plaque.'"','VEHICULE_ID ASC');
					$check_existe_plak=str_replace('\"', '"', $check_existe_plak);
					$check_existe_plak=str_replace('\n', '', $check_existe_plak);
					$check_existe_plak=str_replace('\"', '', $check_existe_plak);

					$check_existe_plak1 = $this->ModelPs->getRequete($psgetrequete, $check_existe_plak);
					 // print_r($check_existe1);die();
					// if(!empty($check_existe1) )
					// {
					// 	$message['message']='<div class="alert alert-danger text-center" id="message">le code existe déjà !</div>';
					// 	$this->session->set_flashdata($message);
					// 	redirect(base_url('vehicule/Vehicule/ajouter'));
					// }
					// else 
					if(!empty($check_existe_plak1))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le plaque existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule/ajouter'));
					}
					else
					{
						//Photo du vehicule
						if (!empty($_FILES["PHOTO_OUT"]["tmp_name"])) {
							$PHOTO = $this->upload_file('PHOTO_OUT');
						}else{
							$PHOTO = $this->input->post('PHOTO');
						}

						//Photo assurance

						if (!empty($_FILES["FILE_ASSURANCE"]["tmp_name"])) {
							$file_assurance = $this->upload_file('FILE_ASSURANCE');
						}else{
							$file_assurance = $this->input->post('FILE_ASSURANCE_OLD');
						}


						//Photo controle technique

						if (!empty($_FILES["FILE_CONTRO_TECHNIQUE"]["tmp_name"])) {
							$file_contro = $this->upload_file('FILE_CONTRO_TECHNIQUE');
						}else{
							$file_contro = $this->input->post('FILE_CONTRO_TECHNIQUE_OLD');
						}


						if (!empty($_FILES["IMAGE_AVANT"]["tmp_name"])) {
							$IMAGE_AVANT = $this->upload_file('IMAGE_AVANT');
						}else{
							$IMAGE_AVANT = $this->input->post('IMAGE_AVANT_OLD');
						}

						if (!empty($_FILES["IMAGE_ARRIERE"]["tmp_name"])) {
							$IMAGE_ARRIERE = $this->upload_file('IMAGE_ARRIERE');
						}else{
							$IMAGE_ARRIERE = $this->input->post('IMAGE_ARRIERE_OLD');
						}

						if (!empty($_FILES["IMAGE_LATERALE_GAUCHE"]["tmp_name"])) {
							$IMAGE_LATERALE_GAUCHE = $this->upload_file('IMAGE_LATERALE_GAUCHE');
						}else{
							$IMAGE_LATERALE_GAUCHE = $this->input->post('IMAGE_LATERALE_GAUCHE_OLD');
						}

						if (!empty($_FILES["IMAGE_LATERALE_DROITE"]["tmp_name"])) {
							$IMAGE_LATERALE_DROITE = $this->upload_file('IMAGE_LATERALE_DROITE');
						}else{
							$IMAGE_LATERALE_DROITE = $this->input->post('IMAGE_LATERALE_DROITE_OLD');
						}

						if (!empty($_FILES["IMAGE_TABLEAU_DE_BORD"]["tmp_name"])) {
							$IMAGE_TABLEAU_DE_BORD = $this->upload_file('IMAGE_TABLEAU_DE_BORD');
						}else{
							$IMAGE_TABLEAU_DE_BORD = $this->input->post('IMAGE_TABLEAU_DE_BORD_OLD');
						}

						if (!empty($_FILES["IMAGE_SIEGE_AVANT"]["tmp_name"])) {
							$IMAGE_SIEGE_AVANT = $this->upload_file('IMAGE_SIEGE_AVANT');
						}else{
							$IMAGE_SIEGE_AVANT = $this->input->post('IMAGE_SIEGE_AVANT_OLD');
						}

						if (!empty($_FILES["IMAGE_SIEGE_ARRIERE"]["tmp_name"])) {
							$IMAGE_SIEGE_ARRIERE = $this->upload_file('IMAGE_SIEGE_ARRIERE');
						}else{
							$IMAGE_SIEGE_ARRIERE = $this->input->post('IMAGE_SIEGE_ARRIERE_OLD');
						}


						$data=array(
							//'CODE'=>$this->input->post('CODE'),
							'ID_ASSUREUR'=>$this->input->post('ID_ASSUREUR'),
							'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
							'ID_MODELE'=>$this->input->post('ID_MODELE'),
							'PLAQUE'=>$this->input->post('PLAQUE'),
							'COULEUR'=>$this->input->post('COULEUR'),
							'KILOMETRAGE'=>$this->input->post('KILOMETRAGE'),
							// 'PHOTO'=>$PHOTO_OUT,
							'PHOTO'=>$PHOTO,
							'FILE_ASSURANCE'=>$file_assurance,
							'FILE_CONTRO_TECHNIQUE'=>$file_contro,

							'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
							'USAGE_ID'=>$this->input->post('USAGE_ID'),
							'NUMERO_CHASSIS'=>$this->input->post('NUMERO_CHASSIS'),
							'ANNEE_FABRICATION'=>$this->input->post('ANNEE_FABRICATION'),
							'DATE_DEBUT_ASSURANCE'=>$this->input->post('DATE_DEBUT_ASSURANCE'),
							'DATE_FIN_ASSURANCE'=>$this->input->post('DATE_FIN_ASSURANCE'),
							'DATE_DEBUT_CONTROTECHNIK'=>$this->input->post('DATE_DEBUT_CONTROTECHNIK'),
							'DATE_FIN_CONTROTECHNIK'=>$this->input->post('DATE_FIN_CONTROTECHNIK'),
							'IMAGE_AVANT'=>$IMAGE_AVANT,
							'IMAGE_ARRIERE'=>$IMAGE_ARRIERE,
							'IMAGE_LATERALE_GAUCHE'=>$IMAGE_LATERALE_GAUCHE,
							'IMAGE_LATERALE_DROITE'=>$IMAGE_LATERALE_DROITE,
							'IMAGE_TABLEAU_DE_BORD'=>$IMAGE_TABLEAU_DE_BORD,
							'IMAGE_SIEGE_AVANT'=>$IMAGE_SIEGE_AVANT,
							'IMAGE_SIEGE_ARRIERE'=>$IMAGE_SIEGE_ARRIERE,
							'SHIFT_ID'=>$this->input->post('SHIFT_ID'),

						);

						$table = "vehicule";

						$update=$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

						// Enregistrement dans la table d'historique d'assaurance

						$data_histo_assure = array(
							'VEHICULE_ID' => $VEHICULE_ID,
							'ID_ASSUREUR'=> $this->input->post('ID_ASSUREUR'),
							'USER_ID' => $this->input->post('USER_ID'),
							'DATE_DEBUT_ASSURANCE'=>$this->input->post('DATE_DEBUT_ASSURANCE'),
							'DATE_FIN_ASSURANCE'=>$this->input->post('DATE_FIN_ASSURANCE'),
							'FILE_ASSURANCE' => $file_assurance,
						);

						$create_assure = $this->Model->create('historique_assurance',$data_histo_assure);

				// Enregistrement dans la table d'historique du controle technique

						$data_histo_controle = array(
							'VEHICULE_ID' => $VEHICULE_ID,
							'USER_ID' => $this->input->post('USER_ID'),
							'DATE_DEBUT_CONTROTECHNIK'=>$this->input->post('DATE_DEBUT_CONTROTECHNIK'),
							'DATE_FIN_CONTROTECHNIK'=>$this->input->post('DATE_FIN_CONTROTECHNIK'),
							'FILE_CONTRO_TECHNIQUE' => $file_contro,
						);

						$create_controle = $this->Model->create('historique_controle_technique',$data_histo_controle);

						// Enregistrement dans la table d'historique d'etat du vehicule
						$data_histo_etat_vehicule = array(
							'VEHICULE_ID' => $VEHICULE_ID,
							'USER_ID' => $this->input->post('USER_ID'),
							'IMAGE_AVANT'=>$IMAGE_AVANT,
							'IMAGE_ARRIERE'=>$IMAGE_ARRIERE,
							'IMAGE_LATERALE_GAUCHE'=>$IMAGE_LATERALE_GAUCHE,
							'IMAGE_LATERALE_DROITE'=>$IMAGE_LATERALE_DROITE,
							'IMAGE_TABLEAU_DE_BORD'=>$IMAGE_TABLEAU_DE_BORD,
							'IMAGE_SIEGE_AVANT'=>$IMAGE_SIEGE_AVANT,
							'IMAGE_SIEGE_ARRIERE'=>$IMAGE_SIEGE_ARRIERE,
						);

						$create_etat = $this->Model->create('historique_etat_vehicule',$data_histo_etat_vehicule);


						if ($update && $create_assure && $create_controle && $create_etat)
						{
							$message['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif_veh').' <i class="fa fa-check"></i></div>';
							$this->session->set_flashdata($message);
							redirect(base_url('vehicule/Vehicule'));

						}else
						{
							$message['message']='<div class="alert alert-danger text-center" id="message">'.lang('msg_echec_modif_veh').'</div>';
							$this->session->set_flashdata($message);
							redirect(base_url('vehicule/Vehicule'));

						}
						
					}

				}
			}

			
		}

		// Fonction pour le detail du vehicule

		function get_detail_vehicule($VEHICULE_ID = '')
		{
			$infos_vehicule = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.STATUT_VEH_AJOUT,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,FILE_ASSURANCE,vehicule.DATE_SAVE,FILE_CONTRO_TECHNIQUE,proprietaire.PROPRIETAIRE_ID,STATUT_VEH_AJOUT,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.LOGO,proprietaire.PHOTO_PASSPORT AS photo_pro,COULEUR,KILOMETRAGE,PHOTO,chauffeur.CHAUFFEUR_ID,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,chauffeur.PHOTO_PASSPORT AS photo_chauf,tracking_data.accident,chauffeur_vehicule.STATUT_AFFECT FROM vehicule LEFT JOIN tracking_data ON vehicule.CODE = tracking_data.device_uid LEFT JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE LEFT JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 AND VEHICULE_ID = "'.$VEHICULE_ID.'" ORDER BY chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID DESC LIMIT 1');
			
			$data['infos_vehicule'] = $infos_vehicule;

			//determination de nombre de jours qu'un vehicule a était enregistré
			//$date = date('Y-m-d',strtotime($infos_vehicule['DATE_SAVE']));

			$aujourdhui = date("Y-m-d");

			// $nbr_jours = $this->NbJours($date, $aujourdhui);

			$nbr_jours = $this->notifications->ago($infos_vehicule['DATE_SAVE'],$aujourdhui);

			$explode = explode(" ",$nbr_jours);

			if($explode[1] == 'Moiss')
			{
				$nbr_jours = substr($nbr_jours, 0, -1);
			}

			$data['nbr_jours'] = $nbr_jours;

			$this->load->view('Vehicule_detail_View',$data);
		}

		//fonction pour determier le nombre de jour ecoulé entre deux dates

		function NbJours($debut, $fin) {

			$tDeb = explode("-", $debut);
			$tFin = explode("-", $fin);

			$diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - 
			mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

			$result = ($diff / 86400)+1;

			return(round($result));

		}

		function check_val_code()
		{
			$CODE = $this->input->post('CODE');
			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_code_veh = $this->getBindParms('CODE', 'vehicule', '1 AND CODE="'.$CODE.'"', '`VEHICULE_ID` ASC');
			$my_select_code_veh=str_replace('\"', '"', $my_select_code_veh);
			$my_select_code_veh=str_replace('\n', '', $my_select_code_veh);
			$my_select_code_veh=str_replace('\"', '', $my_select_code_veh);
			$code_vehicule = $this->ModelPs->getRequete($proce_requete, $my_select_code_veh);
			if(!empty($code_vehicule)){

				$verify=2;
			}else{

				$verify=1;
			}

			echo json_encode($verify);
		}

        // Fonction pour afficher les assureurs
		function select_assureur($VEHICULE_ID_ASSURE)
		{
			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select = $this->getBindParms('`ID_ASSUREUR`, `ASSURANCE`', 'assureur', '1', '`ASSURANCE` ASC');
			$assureur = $this->ModelPs->getRequete($proce_requete, $my_select);

			$my_select_vehicul = $this->getBindParms('`ID_ASSUREUR`', 'vehicule', '1 AND VEHICULE_ID='.$VEHICULE_ID_ASSURE, '`ID_ASSUREUR` ASC');
			$assureur_vehi = $this->ModelPs->getRequeteOne($proce_requete, $my_select_vehicul);
			$html_assureur='<option value="">'.lang('selectionner').'</option>';
			foreach ($assureur as $key)
			{
				
				$html_assureur.='<option value="'.$key['ID_ASSUREUR'].'">'.$key['ASSURANCE'].' </option>';
				
				
			}

			$array = array('html_assureur'=>$html_assureur);
			echo json_encode($array);
		}

		//Fonction pour l'enregistrement d'assurance et controle technique

		function save_assure_controle()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID_ASSURE_CONTROLE');
			$USER_ID = $this->input->post('USER_ID');

			$ACTION = $this->input->post('ACTION');

			$ID_ASSUREUR = $this->input->post('ID_ASSUREUR');
			$DATE_DEBUT_ASSURANCE = $this->input->post('DATE_DEBUT_ASSURANCE');
			$DATE_FIN_ASSURANCE = $this->input->post('DATE_FIN_ASSURANCE');
			$FILE_ASSURANCE = $this->upload_file('FILE_ASSURANCE');

			$DATE_DEBUT_CONTROTECHNIK = $this->input->post('DATE_DEBUT_CONTROTECHNIK');
			$DATE_FIN_CONTROTECHNIK = $this->input->post('DATE_FIN_CONTROTECHNIK');
			$FILE_CONTRO_TECHNIQUE = $this->upload_file('FILE_CONTRO_TECHNIQUE');

			if($ACTION == 1) //Enregistrement d'assurance
			{
				//Mise à jour ds la table vehicule
				$data = array(
					'DATE_DEBUT_ASSURANCE' => $DATE_DEBUT_ASSURANCE,
					'DATE_FIN_ASSURANCE' => $DATE_FIN_ASSURANCE,
					'FILE_ASSURANCE' => $FILE_ASSURANCE,
					'ID_ASSUREUR' => $ID_ASSUREUR,
				);

				$table='vehicule';
				$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

				// Enregistrement dans la table d'historique d'assurance

				$data_histo = array(
					'VEHICULE_ID' => $VEHICULE_ID,
					'ID_ASSUREUR'=> $ID_ASSUREUR,
					'USER_ID' => $USER_ID,
					'DATE_DEBUT_ASSURANCE' => $DATE_DEBUT_ASSURANCE,
					'DATE_FIN_ASSURANCE' => $DATE_FIN_ASSURANCE,
					'FILE_ASSURANCE' => $FILE_ASSURANCE,
				);

				$table2 = 'historique_assurance';
				$create = $this->Model->create($table2,$data_histo);

				echo json_encode(array('status' => TRUE));
			}
			else if($ACTION == 2) //Enregistrement du controle technique
			{
				//Mise à jour ds la table vehicule
				$data = array(
					'DATE_DEBUT_CONTROTECHNIK' => $DATE_DEBUT_CONTROTECHNIK,
					'DATE_FIN_CONTROTECHNIK' => $DATE_FIN_CONTROTECHNIK,
					'FILE_CONTRO_TECHNIQUE' => $FILE_CONTRO_TECHNIQUE,
				);

				$table='vehicule';
				$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

				// Enregistrement dans la table d'historique du controle technique

				$data_histo = array(
					'VEHICULE_ID' => $VEHICULE_ID,
					'USER_ID' => $USER_ID,
					'DATE_DEBUT_CONTROTECHNIK' => $DATE_DEBUT_CONTROTECHNIK,
					'DATE_FIN_CONTROTECHNIK' => $DATE_FIN_CONTROTECHNIK,
					'FILE_CONTRO_TECHNIQUE' => $FILE_CONTRO_TECHNIQUE,
				);


				$table2 = 'historique_controle_technique';
				$create = $this->Model->create($table2,$data_histo);

				echo json_encode(array('status' => TRUE));
			}
		}


		//Fonction pour la liste d'historique d'etat du vehicule
		function liste_etat_vehicule()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;

			$query_principal='SELECT ID_ETAT_VEHICULE,IDENTIFICATION,historique_etat_vehicule.IMAGE_AVANT,historique_etat_vehicule.IMAGE_ARRIERE,historique_etat_vehicule.IMAGE_LATERALE_GAUCHE,historique_etat_vehicule.IMAGE_LATERALE_DROITE,historique_etat_vehicule.IMAGE_LATERALE_DROITE,historique_etat_vehicule.IMAGE_TABLEAU_DE_BORD,historique_etat_vehicule.IMAGE_SIEGE_AVANT,historique_etat_vehicule.IMAGE_SIEGE_ARRIERE,historique_etat_vehicule.DATE_SAVE FROM historique_etat_vehicule JOIN vehicule ON vehicule.VEHICULE_ID = historique_etat_vehicule.VEHICULE_ID JOIN users ON users.USER_ID = historique_etat_vehicule.USER_ID WHERE 1';

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('ID_ETAT_VEHICULE','IMAGE_AVANT','IMAGE_ARRIERE','historique_etat_vehicule.IMAGE_LATERALE_GAUCHE','historique_etat_vehicule.IMAGE_LATERALE_DROITE','historique_etat_vehicule.IMAGE_TABLEAU_DE_BORD');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_ETAT_VEHICULE ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`ID_ETAT_VEHICULE` LIKE "%' . $var_search . '%" 
				OR IDENTIFICATION LIKE "%' . $var_search . '%")') : '';


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

				$source = !empty($row->IMAGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';
				

			

				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#IMAGE_ARRIERE" . $row->ID_ETAT_VEHICULE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";

				$source = !empty($row->IMAGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#IMAGE_LATERALE_GAUCHE" . $row->ID_ETAT_VEHICULE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#IMAGE_LATERALE_DROITE" . $row->ID_ETAT_VEHICULE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#IMAGE_TABLEAU_DE_BORD" . $row->ID_ETAT_VEHICULE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/photo_vehicule/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#IMAGE_SIEGE_AVANT" . $row->ID_ETAT_VEHICULE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#IMAGE_SIEGE_ARRIERE" . $row->ID_ETAT_VEHICULE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				$sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";

			
				


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


		//Fonction pour une liste d'historique assurance
		function liste_assurance()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;

			$query_principal='SELECT ID_HISTORIQUE_ASSURANCE,ASSURANCE,IDENTIFICATION,historique_assurance.DATE_DEBUT_ASSURANCE,historique_assurance.DATE_FIN_ASSURANCE,historique_assurance.FILE_ASSURANCE,historique_assurance.DATE_SAVE FROM historique_assurance JOIN vehicule ON vehicule.VEHICULE_ID = historique_assurance.VEHICULE_ID JOIN assureur ON assureur.ID_ASSUREUR = historique_assurance.ID_ASSUREUR JOIN users ON users.USER_ID = historique_assurance.USER_ID WHERE 1';

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('ID_HISTORIQUE_ASSURANCE','ASSURANCE','IDENTIFICATION','historique_assurance.DATE_DEBUT_ASSURANCE','historique_assurance.DATE_FIN_ASSURANCE','historique_assurance.DATE_SAVE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_HISTORIQUE_ASSURANCE ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`ID_HISTORIQUE_ASSURANCE` LIKE "%' . $var_search . '%" OR ASSURANCE LIKE "%' . $var_search . '%"
				OR IDENTIFICATION LIKE "%' . $var_search . '%" OR historique_assurance.DATE_DEBUT_ASSURANCE LIKE "%' . $var_search . '%" OR historique_assurance.DATE_FIN_ASSURANCE LIKE "%' . $var_search . '%" OR historique_assurance.DATE_SAVE LIKE "%' . $var_search . '%" )') : '';


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
				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->ID_HISTORIQUE_ASSURANCE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->FILE_ASSURANCE) ? base_url('upload/photo_vehicule/'.$row->FILE_ASSURANCE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';


				$sub_array[]= date('d-m-Y',strtotime($row->DATE_DEBUT_ASSURANCE));
				$sub_array[]= date('d-m-Y',strtotime($row->DATE_FIN_ASSURANCE));
				$sub_array[]=$row->ASSURANCE;
				$sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";

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



		//Fonction pour une liste d'historique controle technique
		function liste_controle()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;

			$query_principal='SELECT ID_HISTORIQUE_CONTROLE,IDENTIFICATION,historique_controle_technique.DATE_DEBUT_CONTROTECHNIK,historique_controle_technique.DATE_FIN_CONTROTECHNIK,historique_controle_technique.FILE_CONTRO_TECHNIQUE,historique_controle_technique.DATE_SAVE FROM historique_controle_technique JOIN vehicule ON vehicule.VEHICULE_ID = historique_controle_technique.VEHICULE_ID JOIN users ON users.USER_ID = historique_controle_technique.USER_ID WHERE 1';

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('ID_HISTORIQUE_CONTROLE','IDENTIFICATION','historique_controle_technique.DATE_DEBUT_CONTROTECHNIK','historique_controle_technique.DATE_FIN_CONTROTECHNIK','historique_controle_technique.DATE_SAVE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_HISTORIQUE_CONTROLE ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`ID_HISTORIQUE_CONTROLE` LIKE "%' . $var_search . '%" OR IDENTIFICATION LIKE "%' . $var_search . '%" OR historique_controle_technique.DATE_DEBUT_CONTROTECHNIK LIKE "%' . $var_search . '%" OR historique_controle_technique.DATE_FIN_CONTROTECHNIK LIKE "%' . $var_search . '%" OR historique_controle_technique.DATE_SAVE LIKE "%' . $var_search . '%" )') : '';


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
				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#mypicture2" . $row->ID_HISTORIQUE_CONTROLE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";

				$source = !empty($row->FILE_CONTRO_TECHNIQUE) ? base_url('upload/photo_vehicule/'.$row->ID_HISTORIQUE_CONTROLE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				$sub_array[]= date('d-m-Y',strtotime($row->DATE_DEBUT_CONTROTECHNIK));
				$sub_array[]= date('d-m-Y',strtotime($row->DATE_FIN_CONTROTECHNIK));
				$sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";
				$option .="
				<div class='modal fade' id='mypicture2" .$row->ID_HISTORIQUE_CONTROLE."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>".lang('title_doc_ctrl_technique')."</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<embed src = '".base_url('upload/photo_vehicule/'.$row->FILE_CONTRO_TECHNIQUE)."'  style='border-radius: 5px;height:500px;width: 100%;'>

				</div>
				</div>
				</div>
				</div>

				
				";

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

		//Fonction pour une liste d'historique d'activation et desactivation du vehicule
		function liste_active_desactive()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;

			$query_principal='SELECT historique_activ_des_vehicule.ID_HISTORIQUE,historique_activ_des_vehicule.STATUT,users.IDENTIFICATION,DESC_MOTIF,historique_activ_des_vehicule.DATE_SAVE FROM historique_activ_des_vehicule JOIN vehicule ON vehicule.VEHICULE_ID = historique_activ_des_vehicule.VEHICULE_ID JOIN users ON users.USER_ID = historique_activ_des_vehicule.USER_ID JOIN motif ON motif.ID_MOTIF = historique_activ_des_vehicule.ID_MOTIF WHERE 1 AND historique_activ_des_vehicule.STATUT != 1 AND historique_activ_des_vehicule.STATUT != 3';

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='ORDER BY historique_activ_des_vehicule.ID_HISTORIQUE ASC';

			$order_column=array('historique_activ_des_vehicule.ID_HISTORIQUE','historique_activ_des_vehicule.STATUT','IDENTIFICATION','DESC_MOTIF','historique_activ_des_vehicule.DATE_SAVE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_HISTORIQUE_CONTROLE ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`IDENTIFICATION` LIKE "%' . $var_search . '%" OR DESC_MOTIF LIKE "%' . $var_search . '%" OR historique_activ_des_vehicule.DATE_SAVE LIKE "%' . $var_search . '%" )') : '';


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
				
				if($row->STATUT == 2)
				{
					$sub_array[] = '<label title='.lang('title_veh_active').'><i class="text-success small fa fa-check" title='.lang('title_veh_active').'></i><font class="text-success small "></font></label>';
				}
				else if($row->STATUT == 4)
				{
					$sub_array[] = '<label title='.lang('title_veh_desactive').'><i class="text-danger small fa fa-close"></i><font class="text-danger small "></font></label>';
				}
				else{
					$sub_array[] = '<label><font class="text-dark small ">'.lang('lste_n_a').'</font></label>';
				}

				$sub_array[] = $row->IDENTIFICATION;
				$sub_array[] = $row->DESC_MOTIF;
				
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";

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


		//fonction pour recuperer le nombre des vehicules selon la validation des documments

		function get_nbr_vehicule($CHECK_VALIDE)
		{
			$USER_ID = $this->session->userdata('USER_ID');

			$CHECK_VALIDE = $CHECK_VALIDE;
			$PROFIL_ID=$this->session->userdata('PROFIL_ID');

			// print_r($PROFIL_ID);die();

			$critaire = ' ' ;
			$critaire_doc_valide = '' ;

			$date_now = date('Y-m-d');

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}

			$critaire_doc_valide = '' ;

			if($CHECK_VALIDE == 1) // Assurance valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 2) // Assurance invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE < "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 3) // Controle technique valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 4) // Controle technique invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK < "'.$date_now.'"';
			}

			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$vehicule = $this->getBindParms('COUNT(VEHICULE_ID) AS nombre_v', 'vehicule JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON users.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID', ' 1 '.$critaire.''.$critaire_doc_valide.'', '`VEHICULE_ID` ASC');

			$vehicule = str_replace('\"', '"', $vehicule);
			$vehicule = str_replace('\n', '', $vehicule);
			$vehicule = str_replace('\"', '', $vehicule);

			$vehicule = $this->ModelPs->getRequeteOne($proce_requete, $vehicule);

			echo $vehicule['nombre_v'];
		}

		// Fonction pour affecter le vehicule au chauffeur

		function affecter_vehicule($VEHICULE_ID)
		{
			$centre = '-2.84551,30.3337';
			$zoom = 9;

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			// Recherche des chauffeurs

			$chauffeur = $this->getBindParms('chauffeur.CHAUFFEUR_ID,CONCAT(NOM," ",PRENOM) AS chauffeur_desc','chauffeur',' 1 AND STATUT_VEHICULE = 1 AND IS_ACTIVE = 1','chauffeur_desc ASC');

			$chauffeur = str_replace('\"', '"', $chauffeur);
			$chauffeur = str_replace('\n', '', $chauffeur);
			$chauffeur = str_replace('\"', '', $chauffeur);

			$chauffeur = $this->ModelPs->getRequete($psgetrequete, $chauffeur);

			//Recherche du detail du vehicule

			$vehicule = $this->getBindParms('vehicule.VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE','vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MARQUE = vehicule_marque.ID_MARQUE',' 1 AND vehicule.VEHICULE_ID = '.$VEHICULE_ID.'','vehicule.VEHICULE_ID ASC');

			$vehicule = str_replace('\"', '"', $vehicule);
			$vehicule = str_replace('\n', '', $vehicule);
			$vehicule = str_replace('\"', '', $vehicule);

			$vehicule = $this->ModelPs->getRequeteOne($psgetrequete, $vehicule);
			
			$data['vehicule']=$vehicule;
			$data['centre']=$centre;
			$data['zoom']=$zoom;
			$data['chauffeur']=$chauffeur;

			$this->load->view('Vehicule_Affectation_View',$data);
		}

		//Fonction pour enregistrer l'affectation d'un vehicule au chauffeur
		function save_affect_vehicule()
		{
		// $statut=1 attribution avec succes;
		// $statut=2: attribution echoue

			$statut=2;

			$CODE = $this->input->post('CODE');
			$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');

			$COORD = $this->input->post('COORD');
			$COORD = str_replace("LatLng(", "[", $COORD);
			$COORD = str_replace(")", "]", $COORD);
			$COORD = '['.$COORD.']';

			$COORD_POLY = $COORD;

			$COORD_POLY = explode(',[', $COORD_POLY);
			$COORD_POLY_SEND = '';
			for ($i=0; $i < count($COORD_POLY); $i++) { 

				$COORD_POLY2 = $COORD_POLY[$i];

				$COORD_POLY2 = str_replace(']', '', $COORD_POLY2);
				$COORD_POLY2 = str_replace('[', '', $COORD_POLY2);

				$COORD_POLY2 = explode(',', $COORD_POLY2);
				$COORD_POLY_SEND.= '['.$COORD_POLY2[1].','.$COORD_POLY2[0].'],';

			}

			$COORD_POLY_SEND .= '@';
			$COORD_POLY_SEND = str_replace(',@', '', $COORD_POLY_SEND);

			$COORD_POLY_SEND = '[['.$COORD_POLY_SEND.']]';



			$DATE_DEBUT_AFFECTATION = $this->input->post('DATE_DEBUT_AFFECTATION');
			$DATE_FIN_AFFECTATION = $this->input->post('DATE_FIN_AFFECTATION');

			$result1 = false;
			$result2 = false;

			$data = array('CODE'=>$CODE,'CHAUFFEUR_ID'=>$CHAUFFEUR_ID,'DATE_DEBUT_AFFECTATION'=>$DATE_DEBUT_AFFECTATION,'DATE_FIN_AFFECTATION'=>$DATE_FIN_AFFECTATION,'STATUT_AFFECT'=>1);

			$CHAUFFEUR_VEH = $this->Model->insert_last_id('chauffeur_vehicule',$data);

			$data_2 = array('COORD'=>$COORD_POLY_SEND,'CHAUFFEUR_VEHICULE_ID'=>$CHAUFFEUR_VEH);
			$CHAUFFEUR_AFF = $this->Model->create('chauffeur_zone_affectation',$data_2);

			$result1 = $this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('STATUT_VEHICULE'=>2));

			$result2 = $this->Model->update('vehicule',array('CODE'=>$CODE),array('STATUT'=>2));

			if($result1 == true && $result2 == true)
			{
				$statut = 1;
			}else
			{
				$statut = 2;
			}
			echo json_encode($statut);
		}

			//fonction pour annuler l'affectation du vehicule
		public function annuler_affectation($CODE)
		{
			$chauf_v = $this->Model->getOne('chauffeur_vehicule',array('md5(CODE)'=>$CODE,'STATUT_AFFECT'=>1));

			$this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$chauf_v['CHAUFFEUR_ID']),array('STATUT_VEHICULE'=>1));

			$this->Model->update('vehicule',array('md5(CODE)'=>$CODE),array('STATUT'=>1));

			$this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$chauf_v['CHAUFFEUR_ID'],'STATUT_AFFECT'=>1),array('STATUT_AFFECT'=>2));


			$data['message'] = '<div class="alert alert-success text-center" id="message">' . lang('msg_annule_affectation') . '</div>';

			$this->session->set_flashdata($data);

			redirect(base_url('vehicule/Vehicule'));


		}


		//Annulation de l'affectation du vehicule automatique si la date fin est expirée
		function annule_affect_auto()
		{
			$date_now = date('Y-m-d');

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			// Recherche des chauffeurs

			$all_affect = $this->getBindParms('DATE_FIN_AFFECTATION,chauffeur_vehicule.CODE,chauffeur_vehicule.CHAUFFEUR_ID,STATUT_AFFECT','chauffeur_vehicule',' 1 AND DATE_FIN_AFFECTATION < "'.$date_now.'" AND STATUT_AFFECT = 1 ','DATE_FIN_AFFECTATION ASC');

			$all_affect = str_replace('\"', '"', $all_affect);
			$all_affect = str_replace('\n', '', $all_affect);
			$all_affect = str_replace('\"', '', $all_affect);

			$all_affect = $this->ModelPs->getRequete($psgetrequete, $all_affect);

			//print_r($all_affect);die();

			$result1 = false;
			$result2 = false;
			$result3 = false;

			if(!empty($all_affect))
			{
				foreach($all_affect as $key)
				{
					$result1 = $this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$key['CHAUFFEUR_ID']),array('STATUT_VEHICULE'=>1));

					$result2 = $this->Model->update('vehicule',array('CODE'=>$key['CODE']),array('STATUT'=>1));

					$result3 = $this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$key['CHAUFFEUR_ID'],'STATUT_AFFECT'=>1),array('STATUT_AFFECT'=>2));
				}

				if($result1 == true && $result2 == true && $result3 == true)
				{
					echo 'Annulation automatique d\'affectation faite avec succès';
				}else
				{
					echo 'Echec !';
				}
			}
			else
			{
				echo 'Pas d\'affectation expirée avec un chauffeur affecté au vehicule !';
			}

		}

		//Fonction pour les notifications des anomalies
		function check_anomalies(){
			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');

			if (!empty($USER_ID) && !empty($PROFIL_ID)) {

				$today = date('Y-m-d');
			// $today = '2024-04-02';
				$psgetrequete = "CALL `getRequete`(?,?,?,?);";
			//notifications apres l'ajout d'un vehicule par le proprietaire
				$nbre_vehicule=$this->Model->getRequeteOne('SELECT count(VEHICULE_ID) as nbre FROM vehicule WHERE STAT_NOTIFICATION=1');
				$maintenant=date('Y-m-d H:i:s');

				$nbre_vehicule_req = $this->getBindParms('vehicule.VEHICULE_ID,vehicule.PLAQUE,vehicule.PROPRIETAIRE_ID,users.IDENTIFICATION,DATE_SAVE','vehicule join users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID','1 and STAT_NOTIFICATION=1','VEHICULE_ID ASC');

				$nbre_vehicule_voir = $this->ModelPs->getRequete($psgetrequete, $nbre_vehicule_req);
				$nbre_vehicule=count($nbre_vehicule_voir);

				$html='';
				$html1='';
				foreach ($nbre_vehicule_voir as $keyvehicule) {
					$heure_veh=$this->notifications->ago($keyvehicule['DATE_SAVE'],$maintenant);



					$html.='
					<a href="' . base_url('vehicule/Vehicule'). '" style="color:black;">
					<li class="notification-item">
					<i class="bi bi-exclamation-circle text-warning"></i>
					<div>
					<h4>'.$keyvehicule['IDENTIFICATION'].'</h4>
					<p>'.lang('label_plaque').' : '.$keyvehicule['PLAQUE'].'</p>
					<p>'.lang('franc_date_il_ya').' '.$heure_veh.' '.lang('angl_date_il_ya').'</p>
					</div>
					</li>
					</a>
					<li>
					<hr class="dropdown-divider">
					</li>
					'; 
				}


				if ($PROFIL_ID==1) {
				//Notification lorsqu'il y a exces de vitesse
				// $anomalies_req = $this->getBindParms('device_uid','tracking_data','1 and vitesse>=50 and STATUT_NOTIF=1 and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" GROUP BY device_uid','id ASC');
				// $anomalies_req=str_replace('\"', '"', $anomalies_req);
				// $anomalies_req=str_replace('\n', '', $anomalies_req);
				// $anomalies_req=str_replace('\"', '', $anomalies_req);
				// $anomalies_exces_vitesse = $this->ModelPs->getRequete($psgetrequete, $anomalies_req);
					$anomalies_exces_vitesse =$this->Model->getRequete('SELECT device_uid FROM tracking_data WHERE 1 and vitesse>=50 and STATUT_NOTIF=1 and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" GROUP BY device_uid');
					$nbre_exces_vit=0;
					if (!empty($anomalies_exces_vitesse)) {
						
						foreach ($anomalies_exces_vitesse as $keyexces) {

							$my_selectvitesse_max= $this->getBindParms(' MAX(vitesse) AS max_vitesse', 'tracking_data', '1 AND device_uid ="'.$keyexces['device_uid'].'" AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$today.'"' , '`id` ASC');
							$my_selectvitesse_max=str_replace('\"', '"', $my_selectvitesse_max);
							$my_selectvitesse_max=str_replace('\n', '', $my_selectvitesse_max);
							$my_selectvitesse_max=str_replace('\"', '', $my_selectvitesse_max);

							$vitesse_max = $this->ModelPs->getRequeteOne($psgetrequete, $my_selectvitesse_max);

							$personal_req = $this->getBindParms('device_uid,date as date_depass,vehicule.PLAQUE,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,chauffeur.NOM,chauffeur.PRENOM','tracking_data join vehicule on vehicule.code=tracking_data.device_uid join proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID join chauffeur_vehicule on chauffeur_vehicule.CODE=tracking_data.device_uid join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID','1 and chauffeur_vehicule.STATUT_AFFECT=1 and device_uid="'.$keyexces['device_uid'].'"','id ASC');
							$personal_req=str_replace('\"', '"', $personal_req);
							$personal_req=str_replace('\n', '', $personal_req);
							$personal_req=str_replace('\"', '', $personal_req);
							$personal = $this->ModelPs->getRequeteOne($psgetrequete, $personal_req);
							if (!empty($personal)) {
								$heure_exces=$this->notifications->ago($personal['date_depass'],$maintenant);

								$html.='
								<a href="' . base_url('tracking/Dashboard/tracking_chauffeur/'.md5($keyexces['device_uid'])). '" style="color:black;">
								<li class="notification-item">
								<i class="bi bi-exclamation-circle text-warning"></i>
								<div>
								<h4 class="text-warning">'.lang('h_exces_vitesse').'</h4>
								<p> Max : '.$vitesse_max['max_vitesse'].' Km/h</p>
								<p>'.lang('title_proprio_list').': '.$personal['NOM_PROPRIETAIRE'].' '.$personal['PRENOM_PROPRIETAIRE'].'</p>
								<p>'.lang('p_chauffeur').' : '.$personal['NOM'].' '.$personal['PRENOM'].'</p>
								<p>'.lang('label_plaque').' : '.$personal['PLAQUE'].'</p>
								<p>'.lang('franc_date_il_ya').' '.$heure_exces.' '.lang('angl_date_il_ya').'</p>
								</div>
								</li>
								</a>
								<li>
								<hr class="dropdown-divider">
								</li>
								'; 

							}
							


							
							
						}
						$nbre_exces_vit=count($anomalies_exces_vitesse);
					}

				//Notification lorsqu'il y a accident
				// $acc_req = $this->getBindParms('device_uid','tracking_data','1 and accident=1 and STATUT_NOTIF=1 and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" GROUP BY device_uid','id ASC');
				// $acc_req=str_replace('\"', '"', $acc_req);
				// $acc_req=str_replace('\n', '', $acc_req);
				// $acc_req=str_replace('\"', '', $acc_req);
				// $anomalies_accident = $this->ModelPs->getRequete($psgetrequete, $acc_req);
					$anomalies_accident =$this->Model->getRequete('SELECT device_uid FROM tracking_data WHERE 1 and accident=1 and STATUT_NOTIF=1 and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" GROUP BY device_uid');
					$nbre_accident=0;
					if (!empty($anomalies_accident)) {
						foreach ($anomalies_accident as $keyaccident) {

							$personal_req = $this->getBindParms('device_uid,date_depass,vehicule.PLAQUE,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,chauffeur.NOM,chauffeur.PRENOM','tracking_data join vehicule on vehicule.code=tracking_data.device_uid join proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID join chauffeur_vehicule on chauffeur_vehicule.CODE=tracking_data.device_uid join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID','1 and chauffeur_vehicule.STATUT_AFFECT=1 and device_uid="'.$keyaccident['device_uid'].'"','id ASC');
							$personal_req=str_replace('\"', '"', $personal_req);
							$personal_req=str_replace('\n', '', $personal_req);
							$personal_req=str_replace('\"', '', $personal_req);
							$personal = $this->ModelPs->getRequeteOne($psgetrequete, $personal_req);
							$heure_accident=$this->notifications->ago($personal['date_depass'],$maintenant);



							$html.='
							<a href="' . base_url('tracking/Dashboard/tracking_chauffeur/'.md5($keyaccident['device_uid'])). '" style="color:black;">
							<li class="notification-item">
							<i class="bi bi-exclamation-circle text-danger"></i>
							<div>
							<h4 class="text-danger">'.lang('h_accident').'</h4>
							<p>'.lang('title_proprio_list').' : '.$personal['NOM_PROPRIETAIRE'].' '.$personal['PRENOM_PROPRIETAIRE'].'</p>
							<p>'.lang('p_chauffeur').' : '.$personal['NOM'].' '.$personal['PRENOM'].'</p>
							<p>'.lang('label_plaque').' : '.$personal['PLAQUE'].'</p>
							<p>'.lang('franc_date_il_ya').' '.$heure_accident.' '.lang('angl_date_il_ya').'</p>
							</div>
							</li>
							</a>
							<li>
							<hr class="dropdown-divider">
							</li>
							'; 
						}
						$nbre_accident=count($anomalies_accident);
					}

				//Notification lorsqu'il y a eu depassement de la zone delimitée:geofencing

					$my_select_geo_el = $this->getBindParms('id,tracking_data.date as date_vu,latitude,longitude,CEINTURE,chauffeur.NOM,chauffeur.PRENOM,tracking_data.device_uid,vehicule.CODE', 'tracking_data join chauffeur_vehicule ON chauffeur_vehicule.CODE=tracking_data.device_uid join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule on vehicule.CODE=chauffeur_vehicule.code', '1 and tracking_data.STATUT_NOTIF=1 and chauffeur_vehicule.STATUT_AFFECT=1' , '`id` ASC');

					$my_select_geo_el=str_replace('\"', '"', $my_select_geo_el);
					$my_select_geo_el=str_replace('\n', '', $my_select_geo_el);
					$my_select_geo_el=str_replace('\"', '', $my_select_geo_el);

					$elt_geofence_course = $this->ModelPs->getRequete($psgetrequete, $my_select_geo_el);
					$response=array();
					$a=0;

					if (!empty($elt_geofence_course)) {
						foreach ($elt_geofence_course as $key) {
							$my_selectdelim = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID,COORD', 'chauffeur_vehicule join chauffeur_zone_affectation on chauffeur_zone_affectation.CHAUFFEUR_VEHICULE_ID =chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND CODE ="'.$key['CODE'].'" ' , 'chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ASC');
							$my_selectdelim=str_replace('\"', '"', $my_selectdelim);
							$my_selectdelim=str_replace('\n', '', $my_selectdelim);
							$my_selectdelim=str_replace('\"', '', $my_selectdelim);
							$zones_delim = $this->ModelPs->getRequeteOne($psgetrequete, $my_selectdelim);

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


							$point_check=array($key['longitude'],$key['latitude']);
							$response[]=[$this->Model->isPointInsidePolygon($point_check, $COORD_POLY_repl),$key['NOM'],$key['PRENOM'],$key['CODE'],$this->notifications->ago($key['date_vu'],$maintenant)];



						}
						if (!empty($response)) {
							foreach ($response as $keyresponse) {

								$donnees = $keyresponse[0];
								if($donnees==2) {
									$a=1;

									$html1='
									<a href="' . base_url('tracking/Dashboard/tracking_chauffeur/'.md5($keyresponse[3])). '" style="color:black;">
									<li class="notification-item">
									<i class="bi bi-exclamation-circle text-danger"></i>
									<div>
									<h4 class="text-danger">'.lang('title_geofence').'</h4>

									<p>'.lang('p_chauffeur').' : '.$keyresponse[1].' '.$keyresponse[2].'</p>
									<p>'.lang('franc_date_il_ya').' '.$keyresponse[4].' '.lang('angl_date_il_ya').' </p>
									</div>
									</li>
									</a>
									<li>
									<hr class="dropdown-divider">
									</li>
									';
								}

							}

						}
					}
					//Notif fin d'affectation
					$psgetrequete = "CALL `getRequete`(?,?,?,?);";

					$all_affect_check = $this->getBindParms('t1.CHAUFFEUR_VEHICULE_ID,t1.DATE_INSERTION,t1.DATE_DEBUT_AFFECTATION,t1.DATE_FIN_AFFECTATION,t1.CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM','chauffeur_vehicule t1 INNER JOIN (
						SELECT CHAUFFEUR_ID, MAX(DATE_INSERTION) AS max_date
						FROM chauffeur_vehicule
						GROUP BY CHAUFFEUR_ID
					) t2 ON t1.CHAUFFEUR_ID = t2.CHAUFFEUR_ID AND t1.DATE_INSERTION = t2.max_date join chauffeur on chauffeur.CHAUFFEUR_ID=t1.CHAUFFEUR_ID',' 1 AND DATE_FIN_AFFECTATION <= "'.$today.'" ','CHAUFFEUR_VEHICULE_ID DESC');

					$all_affect_check = str_replace('\"', '"', $all_affect_check);
					$all_affect_check = str_replace('\n', '', $all_affect_check);
					$all_affect_check = str_replace('\"', '', $all_affect_check);
					$all_affect_check = str_replace('\r', '', $all_affect_check);


					$check_all_affect = $this->ModelPs->getRequete($psgetrequete, $all_affect_check);
					// print_r($check_all_affect);die();
					$html2='';
					$nbre_fin_affect=0;
					if (!empty($check_all_affect)) {
						$nbre_fin_affect=count($check_all_affect);
						foreach ($check_all_affect as $keycheck_all_affect) {
							$interval=$this->notifications->ago($keycheck_all_affect['DATE_FIN_AFFECTATION'],$today);
							$html2.='
							<a href="' . base_url('chauffeur/Chauffeur/affecter_chauff/'.$keycheck_all_affect['CHAUFFEUR_ID']). '" style="color:black;">
							<li class="notification-item">
							<i class="bi bi-exclamation-circle text-danger"></i>
							<div>
							<h4 class="text-danger">'.lang('title_fin_affectation').'</h4>

							<p>'.lang('p_chauffeur').' : '.$keycheck_all_affect['NOM'].' '.$keycheck_all_affect['PRENOM'].'</p>
							<p>'.lang('franc_date_il_ya').' '.$interval.' '.lang('angl_date_il_ya').' </p>
							</div>
							</li>
							</a>
							<li>
							<hr class="dropdown-divider">
							</li>
							';
						}
						
						
					}

					//Notification pour l'activation forfait

					$today = date('Y-m-d');
					$nbr_device_proche_exp = 0;
					$nbr_device_exp = 0;
					$jrsRestants = ' ';
					$jrsEcoules_exp = ' ';
					$html_device = '';
					$html_device_exp = '';

					$device = $this->getBindParms('device.DEVICE_ID,device.CODE,device.DATE_EXPIRE_MEGA,NUMERO,CONCAT(DESC_MARQUE," - " ,DESC_MODELE," - ",PLAQUE) AS vehicule,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','device JOIN vehicule ON vehicule.VEHICULE_ID = device.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID',' 1','device.DEVICE_ID ASC');

					$device=str_replace('\"', '"', $device);
					$device=str_replace('\n', '', $device);
					$device=str_replace('\"', '', $device);

					$device = $this->ModelPs->getRequete($psgetrequete, $device);

					if(!empty($device))
					{
						foreach ($device as $key_device) {

							$jrsRestants = $this->notifications->ago($key_device['DATE_EXPIRE_MEGA'],$today);

							$jrsRestants = explode(" ", $jrsRestants)[0];

							//print_r($jrsRestants);die();

							if($jrsRestants <= 4 && $today <= $key_device['DATE_EXPIRE_MEGA']) // <= 4jrs avant l'expiration du forfait
							{
								$nbr_device_proche_exp += 1;

								$html_device.='
								<a href="' . base_url('notification/Notification/'.'').'" style="color:black;">
								<li class="notification-item">
								<i class="bi bi-exclamation-circle text-warning"></i>
								<div>
								<h4 class="text-warning">'.lang('title_forfait_fin').'</h4>
								<p>Véhicule : '.$key_device['vehicule'].'</p>
								<p>propriétaire : '.$key_device['proprio_desc'].'</p>
								<p>'.lang('p_carte_sim').' : '.$key_device['NUMERO'].'</p>
								<p>'.lang('reste').' '.$jrsRestants.' '.lang('jrs').'</p>
								</div>
								</li>
								</a>
								<li>
								<hr class="dropdown-divider">
								</li>
								'; 

								$check_notif = $this->getBindParms('ID_NOTIFICATION,DEVICE_ID,ID_CAT_NOTIF,DATE_ENVOIE','notification_app',' 1 AND DEVICE_ID ='.$key_device['DEVICE_ID'].' AND ID_CAT_NOTIF = 1 AND DATE(DATE_ENVOIE) = CURDATE()','ID_NOTIFICATION ASC');
								$check_notif = $this->ModelPs->getRequeteOne($psgetrequete, $check_notif);

								if(empty($check_notif))
								{
									$data_save = array(
										'DEVICE_ID' => $key_device['DEVICE_ID'],
										'ID_CAT_NOTIF'=> 1,
										'MESSAGE' => 'Le forfait du véhicule <b>'.$key_device['vehicule'].'</b> du propriétaire <b>'.$key_device['proprio_desc'].'</b> avec le numéro<br>carte sim <b>'.$key_device['NUMERO'].'</b> va bientôt expiré il ne reste que <b>'.$jrsRestants.'</b> jour(s) pensez à renouveler le forfait',
									);

									$create = $this->Model->create('notification_app',$data_save);

								// $subjet = "Car tracking Notification";

							    // $email = "mushagalusabypacifique10@gmail.com";

							    // $message = 'Le forfait du véhicule <b>'.$key_device['vehicule'].'</b> du propriétaire <b>'.$key_device['proprio_desc'].'</b> avec le numéro<br>carte sim <b>'.$key_device['NUMERO'].'</b> va bientôt expiré il ne reste que <b>'.$jrsRestants.'</b> jour(s) pensez à renouveler le forfait';

							    // $this->notifications->send_mail(array($email),$subjet,array(),$message,array());

								}
							}
							else if($today > $key_device['DATE_EXPIRE_MEGA']) // forfait déjà expiré
							{
								$nbr_device_exp += 1;

								$html_device_exp.='
								<a href="' . base_url('notification/Notification/'.'').'" style="color:black;">
								<li class="notification-item">
								<i class="bi bi-exclamation-circle text-danger"></i>
								<div>
								<h4 class="text-danger">Forfait expriré</h4>
								<p>Véhicule : '.$key_device['vehicule'].'</p>
								<p>propriétaire : '.$key_device['proprio_desc'].'</p>
								<p>'.lang('p_carte_sim').' : '.$key_device['NUMERO'].'</p>
								<p>'.lang('franc_date_il_ya').' '.$jrsRestants.' '.lang('jrs_jrs').' '.lang('angl_date_il_ya').'</p>
								</div>
								</li>
								</a>
								<li>
								<hr class="dropdown-divider">
								</li>
								';

								$check_notif = $this->getBindParms('ID_NOTIFICATION,DEVICE_ID,ID_CAT_NOTIF,DATE_ENVOIE','notification_app',' 1 AND DEVICE_ID ='.$key_device['DEVICE_ID'].' AND ID_CAT_NOTIF = 2 AND DATE(DATE_ENVOIE) = CURDATE()','ID_NOTIFICATION ASC');
								$check_notif = $this->ModelPs->getRequeteOne($psgetrequete, $check_notif);

								if(empty($check_notif))
								{
									$data_save = array(
										'DEVICE_ID' => $key_device['DEVICE_ID'],
										'ID_CAT_NOTIF'=> 2,
										'MESSAGE' => 'Le forfait du véhicule <b>'.$key_device['vehicule'].'</b> du propriétaire <b>'.$key_device['proprio_desc'].'</b> avec le numéro <br> carte sim <b>'.$key_device['NUMERO'].'</b> est déjà expiré il y a <b>'.$jrsRestants.'</b> jour(s) Veuillez renouveler le forfait',
									);

									$create = $this->Model->create('notification_app',$data_save);
								}
							}
						}
					}


					// $check_all_affect = $this->Model->getRequete("SELECT t1.DATE_INSERTION,t1.DATE_DEBUT_AFFECTATION,t1.DATE_FIN_AFFECTATION,t1.CHAUFFEUR_ID
					// 	FROM chauffeur_vehicule t1
					// 	INNER JOIN (
					// 		SELECT CHAUFFEUR_ID, MAX(DATE_INSERTION) AS max_date
					// 		FROM chauffeur_vehicule
					// 		GROUP BY CHAUFFEUR_ID
					// 	) t2 ON t1.CHAUFFEUR_ID = t2.CHAUFFEUR_ID AND t1.DATE_INSERTION = t2.max_date WHERE DATE_FIN_AFFECTATION <='".$today."'");

					$nbre_anomalies=$nbre_vehicule+$nbre_exces_vit+$nbre_accident+$a+$nbre_fin_affect+$nbr_device_proche_exp+$nbr_device_exp;

				}else{
					if(!empty($USER_ID)){
						$html_device = '';
						$html_device_exp = '';
					//Notification lorsqu'il y a exces de vitesse cote proprietaire
						$psgetrequete = "CALL `getRequete`(?,?,?,?);";
				// $anomalies_req = $this->getBindParms('device_uid','tracking_data join vehicule ON vehicule.CODE=tracking_data.device_uid join users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID','1 and vitesse>=50 and STATUT_NOTIF=1 and users.USER_ID='.$USER_ID.' and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" GROUP BY device_uid','id ASC');
				// $anomalies_req=str_replace('\"', '"', $anomalies_req);
				// $anomalies_req=str_replace('\n', '', $anomalies_req);
				// $anomalies_req=str_replace('\"', '', $anomalies_req);
				// $anomalies_exces_vitesse = $this->ModelPs->getRequete($psgetrequete, $anomalies_req);

						$anomalies_exces_vitesse = $this->Model->getRequete('select device_uid FROM tracking_data join vehicule ON vehicule.CODE=tracking_data.device_uid join users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID where 1 and vitesse>=50 and STATUT_NOTIF=1 and users.USER_ID='.$USER_ID.' and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'"  GROUP BY device_uid');
						$nbre_exces_vit=0;
						if (!empty($anomalies_exces_vitesse)) {
							foreach ($anomalies_exces_vitesse as $keyexces) {

								$personal_req = $this->getBindParms('device_uid,date,vehicule.PLAQUE,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,chauffeur.NOM,chauffeur.PRENOM','tracking_data join vehicule on vehicule.code=tracking_data.device_uid join proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID join chauffeur_vehicule on chauffeur_vehicule.CODE=tracking_data.device_uid join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID','1 and chauffeur_vehicule.STATUT_AFFECT=1 and device_uid="'.$keyexces['device_uid'].'"','id ASC');
								$personal_req=str_replace('\"', '"', $personal_req);
								$personal_req=str_replace('\n', '', $personal_req);
								$personal_req=str_replace('\"', '', $personal_req);
								$personal = $this->ModelPs->getRequeteOne($psgetrequete, $personal_req);
								$heure_exces=$this->notifications->ago($personal['date'],$maintenant);



								$html.='
								<a href="' . base_url('tracking/Dashboard/tracking_chauffeur/'.md5($keyexces['device_uid'])). '" style="color:black;">
								<li class="notification-item">
								<i class="bi bi-exclamation-circle text-warning"></i>
								<div>
								<h4 class="text-warning">'.lang('h_exces_vitesse').'</h4>
								<p>'.lang('title_proprio_list').': '.$personal['NOM_PROPRIETAIRE'].' '.$personal['PRENOM_PROPRIETAIRE'].'</p>
								<p>'.lang('p_chauffeur').' : '.$personal['NOM'].' '.$personal['PRENOM'].'</p>
								<p>'.lang('label_plaque').' : '.$personal['PLAQUE'].'</p>
								<p>'.lang('franc_date_il_ya').' '.$heure_exces.' '.lang('angl_date_il_ya').'</p>
								</div>
								</li>
								</a>
								<li>
								<hr class="dropdown-divider">
								</li>
								'; 
							}
							$nbre_exces_vit=count($anomalies_exces_vitesse);
						}

				//Notification lorsqu'il y a accident (cote proprietaire)
				// $acc_req = $this->getBindParms('device_uid','tracking_data join vehicule ON vehicule.CODE=tracking_data.device_uid join users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID','1 and accident=1 and tracking_data.STATUT_NOTIF=1 and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" and users.USER_ID="'.$USER_ID.'" GROUP BY device_uid','id ASC');
				// $acc_req=str_replace('\"', '"', $acc_req);
				// $acc_req=str_replace('\n', '', $acc_req);
				// $acc_req=str_replace('\"', '', $acc_req);
				// $anomalies_accident = $this->ModelPs->getRequete($psgetrequete, $acc_req);

						$anomalies_accident = $this->Model->getRequete('SELECT device_uid FROM tracking_data join vehicule ON vehicule.CODE=tracking_data.device_uid join users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID where 1 and accident=1 and tracking_data.STATUT_NOTIF=1 and DATE_FORMAT(`date`,"%Y-%m-%d")="'.$today.'" and users.USER_ID="'.$USER_ID.'" GROUP BY device_uid');
						$nbre_accident=0;
						if (!empty($anomalies_accident)) {
							foreach ($anomalies_accident as $keyaccident) {

								$personal_req = $this->getBindParms('device_uid,date,vehicule.PLAQUE,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,chauffeur.NOM,chauffeur.PRENOM','tracking_data join vehicule on vehicule.code=tracking_data.device_uid join proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID join chauffeur_vehicule on chauffeur_vehicule.CODE=tracking_data.device_uid join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID','1 and chauffeur_vehicule.STATUT_AFFECT=1 and device_uid="'.$keyaccident['device_uid'].'"','id ASC');
								$personal_req=str_replace('\"', '"', $personal_req);
								$personal_req=str_replace('\n', '', $personal_req);
								$personal_req=str_replace('\"', '', $personal_req);
								$personal = $this->ModelPs->getRequeteOne($psgetrequete, $personal_req);
								$heure_accident=$this->notifications->ago($personal['date'],$maintenant);



								$html.='
								<a href="' . base_url('tracking/Dashboard/tracking_chauffeur/'.md5($keyaccident['device_uid'])). '" style="color:black;">
								<li class="notification-item">
								<i class="bi bi-exclamation-circle text-danger"></i>
								<div>
								<h4 class="text-danger">'.lang('h_accident').'</h4>
								<p>'.lang('title_proprio_list').' : '.$personal['NOM_PROPRIETAIRE'].' '.$personal['PRENOM_PROPRIETAIRE'].'</p>
								<p>'.lang('p_chauffeur').' : '.$personal['NOM'].' '.$personal['PRENOM'].'</p>
								<p>'.lang('label_plaque').' : '.$personal['PLAQUE'].'</p>
								<p>'.lang('franc_date_il_ya').' '.$heure_accident.' '.lang('angl_date_il_ya').'</p>
								</div>
								</li>
								</a>
								<li>
								<hr class="dropdown-divider">
								</li>
								'; 
							}
							$nbre_accident=count($anomalies_accident);
						}


						//Notification lorsqu'il y a eu depassement de la zone delimitée:geofencing cote proprietaire

						$my_select_geo_el = $this->getBindParms('id,tracking_data.date as date_vu,latitude,longitude,CEINTURE,chauffeur.NOM,chauffeur.PRENOM,tracking_data.device_uid,vehicule.CODE', 'tracking_data join chauffeur_vehicule ON chauffeur_vehicule.CODE=tracking_data.device_uid join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule on vehicule.CODE=chauffeur_vehicule.code join users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID', '1 and tracking_data.STATUT_NOTIF=1 and chauffeur_vehicule.STATUT_AFFECT=1 and users.USER_ID='.$USER_ID , '`id` ASC');

						$my_select_geo_el=str_replace('\"', '"', $my_select_geo_el);
						$my_select_geo_el=str_replace('\n', '', $my_select_geo_el);
						$my_select_geo_el=str_replace('\"', '', $my_select_geo_el);

						$elt_geofence_course = $this->ModelPs->getRequete($psgetrequete, $my_select_geo_el);
						$response=array();
						$a=0;
						if (!empty($elt_geofence_course)) {
							foreach ($elt_geofence_course as $key) {
								$my_selectdelim = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID,COORD', 'chauffeur_vehicule join chauffeur_zone_affectation on chauffeur_zone_affectation.CHAUFFEUR_VEHICULE_ID =chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND CODE ="'.$key['CODE'].'" ' , 'chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ASC');
								$my_selectdelim=str_replace('\"', '"', $my_selectdelim);
								$my_selectdelim=str_replace('\n', '', $my_selectdelim);
								$my_selectdelim=str_replace('\"', '', $my_selectdelim);
								$zones_delim = $this->ModelPs->getRequeteOne($psgetrequete, $my_selectdelim);

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


								$point_check=array($key['longitude'],$key['latitude']);
								$response[]=[$this->Model->isPointInsidePolygon($point_check, $COORD_POLY_repl),$key['NOM'],$key['PRENOM'],$key['CODE'],$this->notifications->ago($key['date_vu'],$maintenant)];



							}
							foreach ($response as $keyresponse) {

								$donnees = $keyresponse[0];
								if($donnees==2) {
									$a=1;

									$html1='
									<a href="' . base_url('tracking/Dashboard/tracking_chauffeur/'.md5($keyresponse[3])). '" style="color:black;">
									<li class="notification-item">
									<i class="bi bi-exclamation-circle text-danger"></i>
									<div>
									<h4 class="text-danger">'.lang('title_geofence').'</h4>

									<p>'.lang('p_chauffeur').' : '.$keyresponse[1].' '.$keyresponse[2].'</p>
									<p>'.lang('franc_date_il_ya').' '.$keyresponse[4].' '.lang('angl_date_il_ya').' </p>
									</div>
									</li>
									</a>
									<li>
									<hr class="dropdown-divider">
									</li>
									';
								}

							}
						}

						//Notif fin d'affectation
						$psgetrequete = "CALL `getRequete`(?,?,?,?);";

						$all_affect_check = $this->getBindParms('t1.CHAUFFEUR_VEHICULE_ID,t1.DATE_INSERTION,t1.DATE_DEBUT_AFFECTATION,t1.DATE_FIN_AFFECTATION,t1.CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM','chauffeur_vehicule t1 INNER JOIN (
							SELECT CHAUFFEUR_ID, MAX(DATE_INSERTION) AS max_date
							FROM chauffeur_vehicule
							GROUP BY CHAUFFEUR_ID
						) t2 ON t1.CHAUFFEUR_ID = t2.CHAUFFEUR_ID AND t1.DATE_INSERTION = t2.max_date join chauffeur on chauffeur.CHAUFFEUR_ID=t1.CHAUFFEUR_ID join vehicule ON vehicule.CODE=t1.CODE JOIN users ON users.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID','1 AND t1.STATUT_AFFECT=1 AND DATE_FIN_AFFECTATION <= "'.$today.'" and users.USER_ID="'.$USER_ID.'"','CHAUFFEUR_VEHICULE_ID DESC');

						$all_affect_check = str_replace('\"', '"', $all_affect_check);
						$all_affect_check = str_replace('\n', '', $all_affect_check);
						$all_affect_check = str_replace('\"', '', $all_affect_check);
						$all_affect_check = str_replace('\r', '', $all_affect_check);


						$check_all_affect = $this->ModelPs->getRequete($psgetrequete, $all_affect_check);
						$html2='';
						$nbre_fin_affect=0;
						if (!empty($check_all_affect)) {
							$nbre_fin_affect=count($check_all_affect);
							foreach ($check_all_affect as $keycheck_all_affect) {
								$interval=$this->notifications->ago($keycheck_all_affect['DATE_FIN_AFFECTATION'],$today);
								if ($interval=='1 Jr' || $interval=='2 Jrs' || $interval=='3 Jrs') {
									
									$html2.='
									<a href="' . base_url('chauffeur/Chauffeur/affecter_chauff/'.$keycheck_all_affect['CHAUFFEUR_ID']). '" style="color:black;">
									<li class="notification-item">
									<i class="bi bi-exclamation-circle text-danger"></i>
									<div>
									<h4 class="text-danger">'.lang('title_fin_affectation').'</h4>

									<p>'.lang('p_chauffeur').' : '.$keycheck_all_affect['NOM'].' '.$keycheck_all_affect['PRENOM'].'</p>
									<p>'.lang('franc_date_il_ya').' '.$interval.' '.lang('angl_date_il_ya').' </p>
									</div>
									</li>
									</a>
									<li>
									<hr class="dropdown-divider">
									</li>
									';
								}
								
							}


						}
						$nbre_anomalies=$nbre_exces_vit+$nbre_accident+$a+$nbre_fin_affect;
					}



				}

				$output = array(
					"nbre_anomalies" => $nbre_anomalies,
					"html" => $html,
					"html1" => $html1,
					"html2" =>$html2,
					"html_device" =>$html_device,
					"html_device_exp" =>$html_device_exp
				);

				echo json_encode($output);
			// print_r($nbre_exces_vit);die();
				
			}else{

				redirect(base_url('Login/logout'));

			}
			

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