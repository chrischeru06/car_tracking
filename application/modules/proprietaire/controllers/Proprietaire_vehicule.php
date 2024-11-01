<?php

/**Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * Le 10/2/2024
 * Vehicule d'un proprietaire
 * 
 */
class Proprietaire_vehicule extends CI_Controller
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

	//Fonction pour rediriger sur la page de la liste des vehicules appartenant à un proprietaire
	function index(){

		$this->load->view('Proprietaire_vehicule_View');
	}

	//Fonction pour la liste
	function listing()
	{

		$USER_ID=$this->session->userdata('USER_ID');

		if (empty($USER_ID)){
			redirect(base_url('Login'));

		}else{
			$critaire = ' AND users.USER_ID='.$USER_ID ;
			
		}


		$query_principal='SELECT VEHICULE_ID,CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,PHOTO,if(`TYPE_proprietaire_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprietaire,PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,STATUT_VEH_AJOUT,vehicule.DATE_FIN_ASSURANCE,vehicule.DATE_FIN_CONTROTECHNIK FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID LEFT JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE 1';


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

		$search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR CONCAT(PRENOM_PROPRIETAIRE,' ',NOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

		$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. ''. $limit;

		$query_filter=$query_principal.''.$critaire.''.$search;

		$fetch_data=$this->Model->datatable($query_secondaire);

		$data=array();
		foreach ($fetch_data as $row)
		{
			if(!empty($row->CODE)){
				$proce_requete = "CALL `getRequete`(?,?,?,?);";
				$my_select_chauffeur = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM,chauffeur.PHOTO_PASSPORT,chauffeur_vehicule.CODE,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.NUMERO_PERMIS', 'chauffeur_vehicule join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID', 'STATUT_AFFECT=1 AND chauffeur_vehicule.CODE='.$row->CODE.'', '`CHAUFFEUR_ID` ASC');
				$chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_chauffeur);
			}
			

			$sub_array=array();
			if(!empty($row->CODE)){
				$sub_array[]=$row->CODE;
			}else{
				$sub_array[]='N/A';

			}
			
			$sub_array[]=$row->DESC_MARQUE;
			$sub_array[]=$row->DESC_MODELE;
			$sub_array[]=$row->PLAQUE;
			if(empty($row->COULEUR)){
				$sub_array[]='N/A';
			}else{

				$sub_array[]=$row->COULEUR;

			} 
			if($row->STATUT_VEH_AJOUT==2){
				$sub_array[]= '<i class="fa fa-check fa-check fa-3x fa-fw"  style="font-size:13px;font-weight: bold;color: green;"></i><font style="font-size:13px;font-weight: bold;color: green;">Véhicule approuvé</font> 
				<span class="badge badge-pill badge-warning" ></span>';
			}elseif ($row->STATUT_VEH_AJOUT==1) 
			{
				$sub_array[] = '<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:13px;font-weight: bold;color: orange;"></i><font style="font-size:13px;font-weight: bold;color: orange;">Véhicule en attente</font><span class="badge badge-pill badge-warning" ></span>';

			}elseif ($row->STATUT_VEH_AJOUT==4) {
				$sub_array[] ='<i class="fa fa-close text-danger  small"></i></i><font style="font-size:14px;" class="text-danger"> Véhicule désactivé</font>';

			}elseif ($row->STATUT_VEH_AJOUT==3) 
			{
				$sub_array[]='<i class="fa fa-close text-danger  small"></i></i><font style="font-size:14px;" class="text-danger"> Véhicule refusé</font>';
			}

			if($row->DATE_FIN_ASSURANCE >= date('Y-m-d'))
			{
				$sub_array[] = '<i class="fa fa-check text-success small"></i><font class="text-success small"> Valide</font>';
			}
			else
			{
				$sub_array[] = '<i class="fa fa-close text-danger  small"></i><font class="text-danger small"> Expirée</font>';

				
			}

			if($row->DATE_FIN_CONTROTECHNIK >= date('Y-m-d'))
			{
				$sub_array[] = '<i class="fa fa-check text-success small"></i><font class="text-success small"> Valide</font>';
			}
			else
			{
				$sub_array[] = '<i class="fa fa-close text-danger small"></i><font class="text-danger small"> Expirée</font>';

				
			}

			if(!empty($chauffeur)){
				if(!empty($row->CODE)){
					$result_code=$row->CODE;
				}else{
					$result_code='N/A';

				}

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a  href='".base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID."'><font style='float: right;'><span class='bi bi-eye'></span></font></a>

				</div>
				<div class='modal fade' id='mypicture" .$row->VEHICULE_ID."'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Détails</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<center><h4 class=''><strong>Véhicule</strong></h4></center>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%' >
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<p><label class='fa fa'>Code &nbsp;&nbsp;<strong>".$result_code."</strong></label></p>
				<p><label class='fa fa'>Marque &nbsp;&nbsp; <strong>".$row->DESC_MARQUE."</strong></label></p>
				<p><label class='fa fa'>Modèle &nbsp;&nbsp; <strong>".$row->DESC_MODELE."</strong></label></p>
				<p><label class='fa fa'>Plaque &nbsp;&nbsp; <strong>".$row->PLAQUE."</strong></label></p>
				<p><label class='fa fa'>Couleur &nbsp;&nbsp;<strong>".$row->COULEUR."</strong></label></p>

				</div>

				</div>
				<hr>
				<center><h4><strong>Chauffeur</strong></h4></center>
				<div class='row'>
				<div class='col-md-6'>
				<img src = '".base_url('upload/chauffeur/'.$chauffeur['PHOTO_PASSPORT'])."' height='100%'  width='100%' >
				</div>
				<div class='col-md-6'>
				<table>
				<tr>
				<td>Nom & Prénom</td>
				<td><strong>".$chauffeur['NOM']." ".$chauffeur['PRENOM']."</strong></td>
				</tr>
				<tr>
				<td>Téléphone</td>
				<td><strong>".$chauffeur['NUMERO_TELEPHONE']."</strong></td>
				</tr>
				<tr>
				<td>Email</td>
				<td><strong>".$chauffeur['ADRESSE_MAIL']."</strong></td>
				</tr>
				<tr>
				<td>Adresse</td>
				<td><strong>".$chauffeur['ADRESSE_PHYSIQUE']."</strong></td>
				</tr>
				<tr>
				<td>No&nbsp;carte&nbsp;d'identité</td>
				<td><strong>".$chauffeur['NUMERO_CARTE_IDENTITE']."</strong></td>
				</tr>
				<tr>
				<td>No permis</td>
				<td><strong>".$chauffeur['NUMERO_PERMIS']."</strong></td>
				</tr>
				<tr>
				<td>Position</td>
				<td><a  href='" . base_url("tracking/Dashboard/tracking_chauffeur/".md5($chauffeur['CODE'])) . "' ><span class='bi bi-eye'></span></a></td>
				</tr>
				</table>

				</div>
				</div>
				</div>
				</div>
				</div>";

			}else{
				if(!empty($row->CODE)){
					$result_code=$row->CODE;
				}else{
					$result_code='N/A';

				}

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a  href='".base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID."'><font style='float: right;'><span class='bi bi-eye'></span></font></a>

				</div>
				<div class='modal fade' id='mypictureoff" .$row->VEHICULE_ID."'>
				<div class='modal-dialog modal-dialog-centered modal-lg'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Détails</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>


				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%' >
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<p><label class='fa fa'>Code &nbsp;&nbsp;<strong>".$result_code."</strong></label></p>
				<p><label class='fa fa'>Marque &nbsp;&nbsp; <strong>".$row->DESC_MARQUE."</strong></label></p>
				<p><label class='fa fa'>Modèle &nbsp;&nbsp; <strong>".$row->DESC_MODELE."</strong></label></p>
				<p><label class='fa fa'>Plaque &nbsp;&nbsp; <strong>".$row->PLAQUE."</strong></label></p>
				<p><label class='fa fa'>Couleur &nbsp;&nbsp;<strong>".$row->COULEUR."</strong></label></p>

				</div>

				</div>
				</div>
				</div>
				</div>";
			}
			$option = '<div class="dropdown ">
			<a class=" text-black btn-sm" data-toggle="dropdown">
			<i class="bi bi-three-dots h5" style="color:blue;"></i>
			<span class="caret"></span></a>
			<ul class="dropdown-menu dropdown-menu-left">
			';

			// if ($row->STATUT_VEH_AJOUT==1) 
			// {
			// 	$option .= "";
			// }
			if ($row->STATUT_VEH_AJOUT==1 || $row->STATUT_VEH_AJOUT==2) 
			{

				$option .= "<li><a class='btn-md' href='" . base_url('proprietaire/Vehicule/ajouter/'.md5($row->VEHICULE_ID)) . "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;&nbsp;Modifier</a></li>";
			}

			if($row->DATE_FIN_ASSURANCE <= date('Y-m-d'))
				
			{


				$option.='<li style="margin:0px;cursor:pointer;margin-top:-30px;"><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td><a class="btn-md" onclick="assure_controle(\''.$row->VEHICULE_ID .'\',1)">Renouveler l\'assurance</a></td></tr></table></li>';
			}

			if($row->DATE_FIN_CONTROTECHNIK <= date('Y-m-d'))
				
			{

				$option.='<li style="margin:0px;cursor:pointer;margin-top:-30px;"><a class="btn-md" onclick="assure_controle('.$row->VEHICULE_ID.',2)"><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td>Renouveler le contrôle technique</a></td></tr></table></li>';
			}
			
			// if($row->STATUT_VEH_AJOUT==2)
			// {


			//  // $option.='<li><a class="btn-md" onClick="attribue_voiture('.$row->VEHICULE_ID.')"><i class="bi bi-plus h5" ></i>&nbsp;Affecter la voiture</a></li>';

			// }
			// if ($row->STATUT_VEH_AJOUT==3) 
			// {
			// 	$option .= "";
			// }

			$sub_array[]=$option;
			$data[] = $sub_array;

		}
		$output = array(
			"draw" => intval($_POST['draw']),
			"recordsTotal" => $this->Model->all_data($query_principal),
			"recordsFiltered" => $this->Model->filtrer($query_filter),
			"data" => $data
		);
		echo json_encode($output);
	}

	function get_all_choffeur()
	{
		// $all_cof= $this->Model->getRequete("SELECT `CHAUFFEUR_ID`,CONCAT(`NOM`,'  ',`PRENOM`) as chof FROM `chauffeur` WHERE 1 AND vehicule.STATUT=1");

		$all_cof= $this->Model->getRequete("SELECT `CHAUFFEUR_ID`,CONCAT(`NOM`,'  ',`PRENOM`) as chof FROM `chauffeur` WHERE 1 ");
		$html='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_cof))
		{
			foreach($all_cof as $key)
			{
				$html.='<option value="'.$key['CHAUFFEUR_ID'].'">'.$key['chof'].' </option>';
			}
		}

		$all_zone_affectation = $this->Model->getRequete("SELECT `CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION` FROM `chauffeur_zone_affectation` WHERE 1");

		$html1='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_zone_affectation))
		{
			foreach($all_zone_affectation as $key1)
			{
				$html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'">'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
			}
		}
		$ouput= array(
			'html'=>$html,
			'html1'=>$html1,
		);
		echo json_encode($ouput);
	}
	function save_choffeur()
	{
		// $statut=1 attribution avec succes;
		// $statut=2:possedent une autre voiture qu'on l'a deja attribuée;
		// $statut=3: attribution echoue
		$statut=3;
		
		$VEHICULE_ID = $this->input->post('VEHICULE_ID');
		$code_veh = $this->Model->getOne('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID));

		$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');
		$CHAUFF_ZONE_AFFECTATION_ID = $this->input->post('CHAUFF_ZONE_AFFECTATION_ID');
		$DATE_DEBUT_AFFECTATION = $this->input->post('DATE_DEBUT_AFFECTATION');
		$DATE_FIN_AFFECTATION = $this->input->post('DATE_FIN_AFFECTATION');

		$data = array('CODE'=>$code_veh['CODE'],'CHAUFFEUR_ID'=>$CHAUFFEUR_ID,'CHAUFF_ZONE_AFFECTATION_ID'=>$CHAUFF_ZONE_AFFECTATION_ID,'DATE_DEBUT_AFFECTATION'=>$DATE_DEBUT_AFFECTATION,'DATE_FIN_AFFECTATION'=>$DATE_FIN_AFFECTATION,'STATUT_AFFECT'=>1);

		$CHAUFFEUR_VEH = $this->Model->create('chauffeur_vehicule',$data);

		$result = $this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('STATUT_VEHICULE'=>2));
		$result = $this->Model->update('vehicule',array('CODE'=>$code_veh['CODE']),array('STATUT'=>2));
		
		if($result==true )
		{
			$statut=1;
		}else
		{
			$statut=2;
		}
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