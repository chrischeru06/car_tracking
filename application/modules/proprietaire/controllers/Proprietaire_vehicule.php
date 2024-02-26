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
	}

	function index(){

		$this->load->view('Proprietaire_vehicule_View');
	}

	function listing(){

		$USER_ID=$this->session->userdata('USER_ID');

		if (empty($USER_ID)){
			redirect(base_url('Login'));

		}else{
			$critaire = ' AND users.USER_ID='.$USER_ID ;
			
		}


		$query_principal='SELECT VEHICULE_ID,CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,PHOTO,if(`TYPE_proprietaire_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprietaire,PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID LEFT JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE 1';


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
			
			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_select_chauffeur = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM,chauffeur.PHOTO_PASSPORT,chauffeur_vehicule.CODE', 'chauffeur_vehicule join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID', 'STATUT_AFFECT=1 AND chauffeur_vehicule.CODE='.$row->CODE.'', '`CHAUFFEUR_ID` ASC');
			$chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_chauffeur);

			$sub_array=array();
			$sub_array[]=$row->CODE;
			$sub_array[]=$row->DESC_MARQUE;
			$sub_array[]=$row->DESC_MODELE;
			$sub_array[]=$row->PLAQUE;
			if(empty($row->COULEUR)){
				$sub_array[]='N/A';
			}else{

				$sub_array[]=$row->COULEUR;

			}

			if(!empty($chauffeur)){

				// $sub_array[]=$chauffeur['NAME_CHAUFFEUR'];
				$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $chauffeur['CHAUFFEUR_ID']. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/chauffeur/').$chauffeur['PHOTO_PASSPORT'].'"></a></td><td> '.'     '.' ' . $chauffeur['NOM'] . ' '.$chauffeur['PRENOM'].'</td></tr></tbody></a>
				<a  href="' . base_url("tracking/Dashboard/tracking_chauffeur/".$chauffeur['CODE']) . '" ><font style="float: right;"><span class="bi bi-eye"></span></font></a>

				<div class="modal fade" id="mypicture' .$chauffeur['CHAUFFEUR_ID'].'">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-body">
				<img src = "'.base_url('upload/chauffeur/'.$chauffeur['PHOTO_PASSPORT']).'" height="100%"  width="100%" >
				</div>
				<div class="modal-footer">
				<button class="btn btn-primary btn-md" class="close" data-dismiss="modal">Fermer</button>
				</div>
				</div>
				</div>
				</div>';


			}else{

				$sub_array[]='N/A';



			}



			$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE))."&nbsp;<a href='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><font style='float: right;'><span class='bi bi-eye'></span></font></a>

			</div>
			<div class='modal fade' id='mypicture" .$row->VEHICULE_ID."'>
			<div class='modal-dialog'>
			<div class='modal-content'>
			<div class='modal-body'>

			<h4 class=''></h4>

			<div class='row'>

			<div class='col-md-6'>
			<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%' >
			</div>

			<div class='col-md-6'>

			<h4></h4>

			<p><label class='fa fa'>Code &nbsp;&nbsp;<strong>".$row->CODE."</strong></label></p>
			<p><label class='fa fa'>Marque &nbsp;&nbsp; <strong>".$row->DESC_MARQUE."</strong></label></p>
			<p><label class='fa fa'>Modèle &nbsp;&nbsp; <strong>".$row->DESC_MODELE."</strong></label></p>
			<p><label class='fa fa'>Plaque &nbsp;&nbsp; <strong>".$row->PLAQUE."</strong></label></p>
			<p><label class='fa fa'>Couleur &nbsp;&nbsp;<strong>".$row->COULEUR."</strong></label></p>
			
			</div>

			</div>
			<div class='modal-footer'>
			<button class='btn btn-primary btn-md' class='close' data-dismiss='modal'>Fermer</button>
			</div>
			</div>
			</div>
			</div>";

			


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