<?php
/*
	Auteur    : NIYOMWUNGERE Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 021-08/02/2024
	crud des chauffeurs
*/
	class Liste_Etat_Vehicule extends CI_Controller
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
			$this->load->view('Liste_Etat_Vehicule_View');
		}


		//Fonction pour l'affichage
		function listing()
		{
		  // $USER_ID = $this->session->userdata('USER_ID');
		  // $chauff=$this->Model->getRequeteOne('SELECT `USER_ID`,`CHAUFFEUR_ID`,`PROFIL_ID` FROM `users` WHERE USER_ID='.$USER_ID);
		  // $critaire2='';
		  // if($chauff['PROFIL_ID']==1)
		  // {
    //        $critaire2='';
		  // }else{
		  // 	 $critaire2='etat_vehicule.CHAUFFEUR_ID=='.$chauff['CHAUFFEUR_ID'];
		  // }


          $ID_OPERATION=$this->input->post('ID_OPERATION');
			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$critaire = " and etat_vehicule.ID_OPERATION=".$ID_OPERATION;

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','etat_vehicule.NOM_CHAUFFEUR','etat_vehicule.PRENOM_CHAUFFEUR','operation_vehicule.DESC_OPERATION','anomalie.DESC_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (etat_vehicule.NOM_CHAUFFEUR LIKE "%' . $var_search . '%" 
				OR etat_vehicule.PRENOM_CHAUFFEUR LIKE "%' . $var_search . '%"
				OR operation_vehicule.DESC_OPERATION LIKE "%' . $var_search . '%" 
				OR anomalie.DESC_ANOMALIE LIKE "%' . $var_search . '%")') : '';

			$query_principal='SELECT `ID_ETAT`,`NOM_CHAUFFEUR`,`PRENOM_CHAUFFEUR`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_CHAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,operation_vehicule.DESC_OPERATION,etat_vehicule.ID_OPERATION,anomalie.DESC_ANOMALIE
			  FROM `etat_vehicule` join operation_vehicule on etat_vehicule.ID_OPERATION=operation_vehicule.ID_OPERATION left join anomalie on etat_vehicule.ID_ANOMALIE=anomalie.ID_ANOMALIE WHERE 1 ';

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
				if($row->ID_OPERATION==1 || $row->ID_OPERATION==2 )
				{
              $sub_array[] = $row->NOM_CHAUFFEUR.'  '.$row->PRENOM_CHAUFFEUR;
				
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_AVANT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_AVANT).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				
			
				// $sub_array[] = $row->IMAGE_ARRIERE;

				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture2' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_ARRIERE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture2' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title2').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_ARRIERE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
						// $sub_array[] = $row->IMAGE_LATERALE_CHAUCHE;

				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture3' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_LATERALE_CHAUCHE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture3' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title3').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_LATERALE_CHAUCHE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
					// $sub_array[] = $row->IMAGE_LATERALE_DROITE;

				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture4' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_LATERALE_DROITE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture4' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title4').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_LATERALE_DROITE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
					
				// $sub_array[] = $row->IMAGE_TABLEAU_DE_BORD;
					
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture5' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_TABLEAU_DE_BORD.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture5' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title5').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_TABLEAU_DE_BORD).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				// $sub_array[] = $row->IMAGE_SIEGE_AVANT;
					
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture6' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_SIEGE_AVANT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture6' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title6').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_SIEGE_AVANT).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				// $sub_array[] = $row->IMAGE_SIEGE_ARRIERE;
				
				$sub_array[] ='<tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture7' . $row->ID_ETAT. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/image_url/').$row->IMAGE_SIEGE_ARRIERE.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp  </td></tr></tbody></a>

				<div class="modal fade" id="mypicture7' .$row->ID_ETAT.'" style="border-radius:100px;">
				<div class="modal-dialog modal-lg">
				<div class="modal-content">

				<div class="modal-header" style="background:cadetblue;color:white;">
				<h6 class="modal-title">'.lang('img_avant_title7').'</h6>
				<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<embed src = "'.base_url("upload/image_url/".$row->IMAGE_SIEGE_ARRIERE).'"   style="border-radius: 5px;height:500px;width: 100%;">

				</div>
				</div>
				</div>
				</div>
				';
				// $sub_array[] = $row->DESC_OPERATION;
				}else
				{
				$sub_array[] = $row->NOM_CHAUFFEUR.'  '.$row->PRENOM_CHAUFFEUR;
				
				// $sub_array[] = $row->DESC_OPERATION;
				$sub_array[] = $row->DESC_ANOMALIE;

			}
				
				

				
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