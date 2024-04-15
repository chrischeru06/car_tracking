<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 30/01/2024
*/
	class Vehicule extends CI_Controller
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
			$this->load->view('Vehicule_liste_View');
		}


		//Fonction pour l'affichage
		function listing()
		{
			$USER_ID = $this->session->userdata('USER_ID');

			$critaire = '' ;

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}

			// $query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,proprietaire.PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur,STATUT_VEH_AJOUT,`LOGO` FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';

			$query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,vehicule.IS_ACTIVE,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur,STATUT_VEH_AJOUT,`LOGO`,vehicule.FILE_ASSURANCE,vehicule.FILE_CONTRO_TECHNIQUE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


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

			$search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

			$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. ''. $limit;

			$query_filter=$query_principal.''.$critaire.''.$search;

			$fetch_data=$this->Model->datatable($query_secondaire);

			$data=array();
			$u=1;
			foreach ($fetch_data as $row)
			{
				$sub_array=array();
				$sub_array[]=$u++;
				
				// if ($row->TYPE_PROPRIETAIRE_ID==1) 
				// {
				//  $sub_array[]=' <table><tr><td style = "width:5000px;"><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->VEHICULE_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></a></td><td> '.'     '.' ' . $row->NOM_PROPRIETAIRE . ' </td></tr></table></a>';
				// }else
				// {
                //  $sub_array[]=' <table><tr><td style = "width:5000px;"><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->VEHICULE_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->NOM_PROPRIETAIRE . ''.'  ' . $row->PRENOM_PROPRIETAIRE . '</td></tr></table></a>';
				// }
				

				// $sub_array[]=$row->CODE;
				$sub_array[]=$row->PLAQUE;
				$sub_array[]=$row->DESC_MARQUE;
				// $sub_array[]=$row->DESC_MODELE;
				$sub_array[]=$row->COULEUR;
				// $sub_array[]=(isset($row->KILOMETRAGE)?$row->KILOMETRAGE.' litres / KM' : 'N/A');

				// $sub_array[]= "<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='120px' width='120px' ></a>";

				

				// $sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'>&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a href='".base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID."'>&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				if($row->STATUT_VEH_AJOUT==2){
					$sub_array[]= '<i class="fa fa-check fa-check fa-3x fa-fw"  style="font-size:13px;font-weight: bold;color: green;"></i><font style="font-size:13px;font-weight: bold;color: green;">Véhicule approuvé</font> 
					<span class="badge badge-pill badge-warning" ></span>';
				}elseif ($row->STATUT_VEH_AJOUT==1) 
				{
					$sub_array[] = '<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:13px;font-weight: bold;color: orange;"></i><font style="font-size:13px;font-weight: bold;color: orange;">Véhicule en attente</font><span class="badge badge-pill badge-warning" ></span>';

				}else
				{
					$sub_array[]='<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:13px;font-weight: bold;color: red;"></i><font style="font-size:13px;font-weight: bold;color: red;">Véhicule refusé</font><span class="badge badge-pill badge-warning" ></span>';
				}

				if($row->IS_ACTIVE==1){
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-primary small">Activé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_desactive(' . $row->VEHICULE_ID . ')" checked>
					<span class="slider round"></span>
					</label></td>
					</table>

					
					
					</form>

					';
				}else{
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-danger small">Désactivé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_active(' . $row->VEHICULE_ID . ')">
					<span class="slider round"></span>
					</label></td>
					</table>
					</form>

					';
				}

				$option = '<div class="dropdown text-center">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i> 
				
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';


				if($row->DATE_FIN_ASSURANCE >= date('Y-m-d'))
				{
					$sub_array[] = '<i class="fa fa-check text-success small"></i><font class="text-success small"> Valide</font>';
				}
				else
				{
					$sub_array[] = '<i class="fa fa-close text-danger  small"></i><font class="text-danger small"> Expirée</font>';

					$option.='<li style="margin:0px;cursor:pointer;"><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td><a class="text-dark" onclick="assure_controle('.$row->VEHICULE_ID.',1)">Renouveler l\'assurance</a></td></tr></table></li>';
				}
				
				if($row->DATE_FIN_CONTROTECHNIK >= date('Y-m-d'))
				{
					$sub_array[] = '<i class="fa fa-check text-success small"></i><font class="text-success small"> Valide</font>';
				}
				else
				{
					$sub_array[] = '<i class="fa fa-close text-danger small"></i><font class="text-danger small"> Expirée</font>';

					$option.='<li style="margin:0px;cursor:pointer;"><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td><a class="text-dark" onclick="assure_controle('.$row->VEHICULE_ID.',2)">Renouveler le contrôle technique</a></td></tr></table></li>';
				}

				
				if ($row->STATUT_VEH_AJOUT==1)
				{
					$option .= "<li><a class='btn-md' href='" . base_url('vehicule/Vehicule/ajouter/'.md5($row->VEHICULE_ID)) . "'><label class='text-dark'><i class='bi bi-pencil'></i>&nbsp;&nbsp;Modifier</label></a></li>";
				}


				$option .="
				</div>
				<div class='modal fade' id='mypicture" .$row->VEHICULE_ID."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Détail du véhicule</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<h4 class=''></h4>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<table class='table table-borderless'>

				<tr>
				<td class='btn-sm'>Code</td>
				<th class='btn-sm'>".$row->CODE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Marque</td>
				<th class='btn-sm'>".$row->DESC_MARQUE."</th>
				</tr>

				<tr class='btn-sm'>
				<td>Modèle</td>
				<th class='btn-sm'>".$row->DESC_MODELE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Plaque</td>
				<th class='btn-sm'>".$row->PLAQUE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Couleur</td>
				<th class='btn-sm'>".$row->COULEUR."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Consommation / km</td>
				<th class='btn-sm'>".$row->KILOMETRAGE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>propriétaire</td>
				<th class='btn-sm'><strong>".$row->NOM_PROPRIETAIRE."</strong></th>
				</tr>

				<tr>
				<td class='btn-sm'>Chauffeur</td>
				<th class='btn-sm'><strong>".(isset($row->desc_chauffeur)?$row->desc_chauffeur:'N/A')."</strong></th>
				</tr>

				<tr>
				<td><strong>Documents</strong></td>
				</tr>
				<tr>
				<td><label class='fa fa-book'></label> ASSURANCE</td>
				<td><a href='#' data-toggle='modal' data-target='#info_documa" .$row->VEHICULE_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
				</td>
				</tr>
				<tr>
				<td><label class='fa fa-book'></label>CONTROLE TECHNIQUE</td>
				<td><a href='#' data-toggle='modal' data-target='#info_documa2" .$row->VEHICULE_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
				</td>
				</tr>

				</table>

				</div>
				</div>
				
				</div>
				</div>
				</div>

				<div class='modal fade' id='info_documa" .$row->VEHICULE_ID. "'>
				<div class='modal-dialog'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Assurance</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<div class='scroller'>
				<div class='table-responsive'>

				<img src = '".base_url('upload/photo_vehicule/'.$row->FILE_ASSURANCE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>

				<div class='modal fade' id='info_documa2" .$row->VEHICULE_ID. "'>
				<div class='modal-dialog'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Contrôle technique</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<div class='scroller'>
				<div class='table-responsive'>

				<img src = '".base_url('upload/photo_vehicule/'.$row->FILE_CONTRO_TECHNIQUE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>

				";








				
				if ($row->TYPE_PROPRIETAIRE_ID==1)
				{
					$option .="
					</div>
					<div class='modal fade' id='proprio" .$row->VEHICULE_ID."' style='border-radius:100px;'>
					<div class='modal-dialog modal-lg'>
					<div class='modal-content'>

					<div class='modal-header' style='background:cadetblue;color:white;'>
					<h5 class='modal-title'>Information du propriétaire</h5>
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
					<td class='btn-sm'>Nom</td>

					<th class='btn-sm'>".$row->NOM_PROPRIETAIRE."</th>
					</tr>

					<tr>
					<td class='btn-sm'>Adresse</td>

					<th class='btn-sm'>".$row->ADRESSE."</th>
					</tr>

					<tr class='btn-sm'>
					<td>Email</td>

					<th class='btn-sm'>".$row->EMAIL."</th>
					</tr>

					<tr>
					<td class='btn-sm'>Téléphone</td>

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
					<h5 class='modal-title'>Information du propriétaire</h5>
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
					<td class='btn-sm'>Nom</td>
					<th class='btn-sm'>".$row->NOM_PROPRIETAIRE."</th>
					</tr>

					<tr>
					<td class='btn-sm'>Adresse</td>

					<th class='btn-sm'>".$row->ADRESSE."</th>
					</tr>

					<tr class='btn-sm'>
					<td>Email</td>

					<th class='btn-sm'>".$row->EMAIL."</th>
					</tr>

					<tr>
					<td class='btn-sm'>Téléphone</td>

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
			$html='<option value="">--- Sélectionner ----</option>';
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
		// $statut=2:possedent une autre chauffeur;
		// $statut=3: traitement echoue
			$statut=3;
		// $CODE= $this->input->post('code_vehicule');

			$STATUT_VEH_AJOUT = $this->input->post('STATUT_VEH_AJOUT');

			$VEHICULE_ID = $this->input->post('VEHICULE_ID');
			$TRAITEMENT_DEMANDE_ID = $this->input->post('TRAITEMENT_DEMANDE_ID');
			$COMMENTAIRE = $this->input->post('COMMENTAIRE');

			if ($STATUT_VEH_AJOUT==1) 
			{
				$vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>2));
			}else
			{ 
				$vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>3)); 
			}
			if($vehcul==true )
			{
				$statut=1;
			}else
			{
				$statut=2;
			}
			echo json_encode($statut);
		}

		//Fonction pour activer/desactiver un vehicule
		function active_desactive($status,$VEHICULE_ID){

			if($status == 1){
				$this->Model->update('vehicule', array('VEHICULE_ID'=>$VEHICULE_ID),array('IS_ACTIVE'=>2));

				$status = 2;

			}else if($status == 2){
				$this->Model->update('vehicule', array('VEHICULE_ID'=>$VEHICULE_ID),array('IS_ACTIVE'=>1));
				$status = 1;
			}

			echo json_encode(array('status'=>$status));

		}

       // Appel du formulaire d'enregistrement
		function ajouter()
		{			

			$VEHICULE_ID = $this->uri->segment(4);

			$data['btn'] = "Enregistrer";
			$data['title']="Enregistrement du véhicule";

			$vehicule = array('VEHICULE_ID'=>NULL,'ID_MARQUE'=>NULL,'ID_MODELE'=>NULL,'CODE'=>NULL,'PLAQUE'=>NULL,'COULEUR'=>NULL,'KILOMETRAGE'=>NULL,'PHOTO'=>NULL,'PROPRIETAIRE_ID'=>NULL,'ANNEE_FABRICATION'=>NULL,'NUMERO_CHASSIS'=>NULL,'USAGE_ID'=>NULL,'DATE_FIN_CONTROTECHNIK'=>NULL,'DATE_FIN_ASSURANCE'=>NULL,'DATE_DEBUT_CONTROTECHNIK'=>NULL,'DATE_DEBUT_ASSURANCE'=>NULL,'FILE_CONTRO_TECHNIQUE'=>NULL,'FILE_ASSURANCE'=>NULL);
			
			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			$marque = $this->getBindParms('ID_MARQUE,DESC_MARQUE','vehicule_marque',' 1 ','DESC_MARQUE ASC');
			$marque = $this->ModelPs->getRequete($psgetrequete, $marque);
			$usage = $this->getBindParms('USAGE_ID,USAGE_DESC','veh_usage',' 1 ','USAGE_DESC ASC');
			$usage = $this->ModelPs->getRequete($psgetrequete, $usage);

			$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' 1 ','DESC_MODELE ASC');
			$modele = $this->ModelPs->getRequete($psgetrequete, $modele);
			$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');

			$proprio=str_replace('\"', '"', $proprio);
			$proprio=str_replace('\n', '', $proprio);
			$proprio=str_replace('\"', '', $proprio);

			$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);


			if(!empty($VEHICULE_ID))
			{
				$data['btn'] = "Modifier";
				$data['title'] = "Modification du véhicule";

				$vehicule = $this->Model->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,PROPRIETAIRE_ID,NUMERO_CHASSIS,USAGE_ID,ANNEE_FABRICATION,DATE_FIN_CONTROTECHNIK,DATE_FIN_ASSURANCE,DATE_DEBUT_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,FILE_CONTRO_TECHNIQUE,FILE_ASSURANCE FROM vehicule WHERE md5(VEHICULE_ID)='".$VEHICULE_ID."'");

				// if(empty($vehicule))
				// {
				// 	redirect(base_url('Login/logout'));
				// }

				$marque = $this->getBindParms('ID_MARQUE,DESC_MARQUE','vehicule_marque',' 1 ','DESC_MARQUE ASC');
				$marque = $this->ModelPs->getRequete($psgetrequete, $marque);

				$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' 1 ','DESC_MODELE ASC');
				$modele = $this->ModelPs->getRequete($psgetrequete, $modele);
				$usage = $this->getBindParms('USAGE_ID,USAGE_DESC','veh_usage',' 1 ','USAGE_DESC ASC');
				$usage = $this->ModelPs->getRequete($psgetrequete, $usage);


				$proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');

				$proprio=str_replace('\"', '"', $proprio);
				$proprio=str_replace('\n', '', $proprio);
				$proprio=str_replace('\"', '', $proprio);

				$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);

			}

			$data['vehicule'] = $vehicule;
			$data['marque'] = $marque;
			$data['modele'] = $modele;
			$data['usage'] = $usage;
			$data['proprio'] = $proprio;


			$this->load->view('Vehicule_add_View',$data);
		}

       //Selection des noms des vehicules
		function get_modele($ID_MARQUE)
		{
			$html="<option value=''>-- Séléctionner --</option>";

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

				$this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

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
				}if (empty($_FILES['FILE_ASSURANCE']['name']))
				{
					$this->form_validation->set_rules('FILE_ASSURANCE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
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

					$data = array(
						'CODE'=>$this->input->post('CODE'),
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

						
					);

					$table = "vehicule";

					$creation=$this->Model->create($table,$data);

					if ($creation)
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

					$check_existe = $this->getBindParms('VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,"PLAQUE",PROPRIETAIRE_ID','vehicule',' VEHICULE_ID !='.$VEHICULE_ID.' and CODE='.$this->input->post('CODE').'','VEHICULE_ID ASC');
					$check_existe=str_replace('\"', '"', $check_existe);
					$check_existe=str_replace('\n', '', $check_existe);
					$check_existe=str_replace('\"', '', $check_existe);

					$check_existe1 = $this->ModelPs->getRequete($psgetrequete, $check_existe);
					$val_plaque=$this->input->post('PLAQUE');
					$check_existe_plak = $this->getBindParms('VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,"PLAQUE",PROPRIETAIRE_ID','vehicule',' VEHICULE_ID !='.$VEHICULE_ID.' and PLAQUE="'.$val_plaque.'"','VEHICULE_ID ASC');
					$check_existe_plak=str_replace('\"', '"', $check_existe_plak);
					$check_existe_plak=str_replace('\n', '', $check_existe_plak);
					$check_existe_plak=str_replace('\"', '', $check_existe_plak);

					$check_existe_plak1 = $this->ModelPs->getRequete($psgetrequete, $check_existe_plak);
					 // print_r($check_existe1);die();
					if(!empty($check_existe1) )
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le code existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule/ajouter'));
					}
					else if(!empty($check_existe_plak1))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le plaque existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule/ajouter'));
					}
					else
					{
						// if (!empty($_FILES["PHOTO_OUT"]["tmp_name"])) {
						// 	$PHOTO=$this->upload_file('PHOTO_OUT');
						// }else{
						// 	$PHOTO=$this->input->post('PHOTO');
						// }

						$PHOTO_OUT = $this->input->post('PHOTO');
						if(empty($_FILES['PHOTO_OUT']['name']))
						{
							$file_contro = $this->input->post('PHOTO');
						}
						else
						{
							$file_contro = $this->upload_file($_FILES['PHOTO_OUT']['tmp_name'],$_FILES['PHOTO_OUT']['name']);
						}			

						$FILE_CONTRO_TECHNIQUE = $this->input->post('FILE_CONTRO_TECHNIQUE_OLD');
						if(empty($_FILES['FILE_CONTRO_TECHNIQUE']['name']))
						{
							$file_contro = $this->input->post('FILE_CONTRO_TECHNIQUE_OLD');
						}
						else
						{
							$file_contro = $this->upload_file($_FILES['FILE_CONTRO_TECHNIQUE']['tmp_name'],$_FILES['FILE_CONTRO_TECHNIQUE']['name']);
						}
						$FILE_ASSURANCE = $this->input->post('FILE_ASSURANCE_OLD');
						if(empty($_FILES['FILE_ASSURANCE']['name']))
						{
							$file_assurance = $this->input->post('FILE_ASSURANCE_OLD');
						}
						else
						{
							$file_assurance = $this->upload_file($_FILES['FILE_ASSURANCE']['tmp_name'],$_FILES['FILE_ASSURANCE']['name']);
						}

						$data=array
						(
							'CODE'=>$this->input->post('CODE'),
							'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
							'ID_MODELE'=>$this->input->post('ID_MODELE'),
							'PLAQUE'=>$this->input->post('PLAQUE'),
							'COULEUR'=>$this->input->post('COULEUR'),
							'KILOMETRAGE'=>$this->input->post('KILOMETRAGE'),
							'PHOTO'=>$PHOTO_OUT,
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

						);

						$table = "vehicule";

						$update=$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

						if ($update)
						{
							$message['message']='<div class="alert alert-success text-center" id="message">Modification du vehicule avec succès</div>';
							$this->session->set_flashdata($message);
							redirect(base_url('vehicule/Vehicule'));

						}else
						{
							$message['message']='<div class="alert alert-danger text-center" id="message">Echec de modification </div>';
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
			$infos_vehicule = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.IS_ACTIVE,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,FILE_ASSURANCE,FILE_CONTRO_TECHNIQUE,proprietaire.PROPRIETAIRE_ID,STATUT_VEH_AJOUT,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,proprietaire.PROPRIETAIRE_ID,proprietaire.PHOTO_PASSPORT AS photo_pro,COULEUR,KILOMETRAGE,PHOTO,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,chauffeur.PHOTO_PASSPORT AS photo_chauf,tracking_data.accident FROM vehicule LEFT JOIN tracking_data ON vehicule.CODE = tracking_data.device_uid LEFT JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE LEFT JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1  AND VEHICULE_ID = "'.$VEHICULE_ID.'" ORDER BY id DESC LIMIT 1');

			// print_r($infos_vehicule);die();
			
			$data['infos_vehicule'] = $infos_vehicule;

			$this->load->view('Vehicule_detail_View',$data);
		}

        // Fonction pour afficher les assureurs
		function select_assureur()
		{
			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select = $this->getBindParms('`ID_ASSUREUR`, `ASSURANCE`', 'assureur', '1', '`ASSURANCE` ASC');
			$assureur = $this->ModelPs->getRequete($proce_requete, $my_select);

			$html_assureur='<option value="">Sélectionner</option>';
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
			$sub_array[]="<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->ID_HISTORIQUE_ASSURANCE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
			$sub_array[]= date('d-m-Y',strtotime($row->DATE_DEBUT_ASSURANCE));
			$sub_array[]= date('d-m-Y',strtotime($row->DATE_FIN_ASSURANCE));
			$sub_array[]=$row->ASSURANCE;
			$sub_array[]=$row->IDENTIFICATION;
			$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

            $option = " ";
			$option .="
				<div class='modal fade' id='mypicture" .$row->ID_HISTORIQUE_ASSURANCE."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Document d'assurance</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<img src = '".base_url('upload/photo_vehicule/'.$row->FILE_ASSURANCE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>

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