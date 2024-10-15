<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 15/10/2024
*/
	class Gestionnaire_v extends CI_Controller
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