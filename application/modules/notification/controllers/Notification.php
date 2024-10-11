<?php
/*
Desc      : Controlleur pour la gestion des Notifications
Auteur    : Mushagalusa Byamungu Pacifique
Email     : byamungu.pacifique@mediabox.bi
Telephone : +25772496057
Date      : 09/10/2024
*/
class Notification extends CI_Controller
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

	//la fonction index visualise la liste des devices enregistrés
	function index()
	{
		$data['title'] = "".lang('title_list')."";

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$categorie = $this->getBindParms('ID_CAT_NOTIF,DESC_CATEGORIE', 'categorie_notification', '1', 'ID_CAT_NOTIF ASC');
			$data['categorie'] = $this->ModelPs->getRequete($proce_requete, $categorie);

		$this->load->view('Notification_View',$data);
	}

	//Fonction pour la liste
	function listing(){

		$STATUT = $this->input->post('STATUT');
		$CATEGORIE = $this->input->post('CATEGORIE');

		$query_principal='SELECT ID_NOTIFICATION,DESC_CATEGORIE,MESSAGE,STATUT,DATE_ENVOIE FROM notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF WHERE 1';

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";
		
		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}
		$order_by='';

		$order_column=array('ID_NOTIFICATION','DESC_CATEGORIE','MESSAGE','STATUT','DATE_ENVOIE');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_NOTIFICATION DESC';
		}
		else
		{
			$order_by='ORDER BY ID_NOTIFICATION DESC';
		}
		
		$search = !empty($_POST['search']['value']) ? (' AND (`DESC_CATEGORIE` LIKE "%' . $var_search . '%" 
			OR MESSAGE LIKE "%' . $var_search . '%" OR DATE_ENVOIE LIKE "%' . $var_search . '%")') : '';

		$critaire = '' ;
		$critaire1 = '' ;

		if(!empty($STATUT))
		{
			$critaire = ' AND STATUT = "'.$STATUT.'"';
		}

		if(!empty($CATEGORIE))
		{
			$critaire .= ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
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
		$i=0;

		foreach ($fetch_data as $row) {
			$i=$i+1;
			$sub_array=array();
			$sub_array[]=$i;	
			$sub_array[]=$row->DESC_CATEGORIE;
			$parts = explode('-', $row->MESSAGE);
          // Prise de la première partie (avant le premier "-")
			$short_message = $parts[0];
			$sub_array[]= "".$short_message."... <a class='btn-md' href='#' data-toggle='modal'
			data-target='#myMessage" . $row->ID_NOTIFICATION . "'><span class='bi bi-eye'></span></a>";

			$btn = "";

			if($row->STATUT == 1)
			{
				$sub_array[] = '<center class="text-warning">Pas encore lu</center>';

				$btn = "<a class='btn btn-outline-primary rounded-pill' href='".base_url('notification/Notification/lire_message/'.md5($row->ID_NOTIFICATION))."' ><font class='fa fa-check'></font> Marquer comme lu</a>";
			}
			else
			{
				$sub_array[] = '<center class="text-success">lu</center>';

				$btn = "";

			}
			$sub_array[]=date('d-m-Y',strtotime($row->DATE_ENVOIE));
			
			$option = '';
			$option .= '';

			$option .= " </ul>
			</div>
			<div class='modal fade' id='myMessage" .$row->ID_NOTIFICATION. "'>
			<div class='modal-dialog modal-dialog-centered modal-lg'>
			<div class='modal-content'>
			<div class='modal-header' style='background:cadetblue;color:white;'>
			<h5></h5>
			<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			</div>
			<div class='modal-body'>
			<center><p>".$row->MESSAGE."</p> </center>
			<div class='modal-footer'>

			".$btn."

			<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
			</div>
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

	//Fonction pour la liste 2
	function listing2(){

		$STATUT_PROCHE = $this->input->post('STATUT_PROCHE');
		$CATEGORIE = $this->input->post('CATEGORIE');

		$query_principal='SELECT ID_NOTIFICATION,DESC_CATEGORIE,MESSAGE,STATUT,DATE_ENVOIE FROM notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF WHERE 1';

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";
		
		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}

		$order_by='';

		$order_column=array('ID_NOTIFICATION','DESC_CATEGORIE','MESSAGE','STATUT','DATE_ENVOIE');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_NOTIFICATION DESC';
		}
		else
		{
			$order_by='ORDER BY ID_NOTIFICATION DESC';
		}
		
		$search = !empty($_POST['search']['value']) ? (' AND (`DESC_CATEGORIE` LIKE "%' . $var_search . '%" 
			OR MESSAGE LIKE "%' . $var_search . '%" OR DATE_ENVOIE LIKE "%' . $var_search . '%")') : '';

		$critaire = '' ;
		$critaire1 = '' ;

		if(!empty($STATUT_PROCHE))
		{
			$critaire = ' AND STATUT = "'.$STATUT_PROCHE.'"';
		}

		if(!empty($CATEGORIE))
		{
			$critaire .= ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
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
		$i=0;

		foreach ($fetch_data as $row) {
			$i=$i+1;
			$sub_array=array();
			$sub_array[]=$i;	
			$sub_array[]=$row->DESC_CATEGORIE;
			$parts = explode('-', $row->MESSAGE);
          // Prise de la première partie (avant le premier "-")
			$short_message = $parts[0];
			$sub_array[]= "".$short_message."... <a class='btn-md' href='#' data-toggle='modal'
			data-target='#myMessage2" . $row->ID_NOTIFICATION . "'><span class='bi bi-eye'></span></a>";

			$btn = "";

			if($row->STATUT == 1)
			{
				$sub_array[] = '<center class="text-warning">Pas encore lu</center>';

				$btn = "<a class='btn btn-outline-primary rounded-pill' href='".base_url('notification/Notification/lire_message/'.md5($row->ID_NOTIFICATION))."' ><font class='fa fa-check'></font> Marquer comme lu</a>";
			}
			else
			{
				$sub_array[] = '<center class="text-success">lu</center>';

				$btn = "";

			}
			$sub_array[]=date('d-m-Y',strtotime($row->DATE_ENVOIE));
			
			$option = '';
			$option .= '';

			$option .= " </ul>
			</div>
			<div class='modal fade' id='myMessage2" .$row->ID_NOTIFICATION. "'>
			<div class='modal-dialog modal-dialog-centered modal-lg'>
			<div class='modal-content'>
			<div class='modal-header' style='background:cadetblue;color:white;'>
			<h5></h5>
			<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			</div>
			<div class='modal-body'>
			<center><p>".$row->MESSAGE."</p> </center>
			<div class='modal-footer'>

			".$btn."

			<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
			</div>
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

	//Fonction pour la liste 3
	function listing3(){

		$STATUT_EXPIRE = $this->input->post('STATUT_EXPIRE');
		$CATEGORIE = $this->input->post('CATEGORIE');

		$query_principal='SELECT ID_NOTIFICATION,DESC_CATEGORIE,MESSAGE,STATUT,DATE_ENVOIE FROM notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF WHERE 1';

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";
		
		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}
		$order_by='';

		$order_column=array('ID_NOTIFICATION','DESC_CATEGORIE','MESSAGE','STATUT','DATE_ENVOIE');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ID_NOTIFICATION DESC';
		}
		else
		{
			$order_by='ORDER BY ID_NOTIFICATION DESC';
		}

		$search = !empty($_POST['search']['value']) ? (' AND (`DESC_CATEGORIE` LIKE "%' . $var_search . '%" 
			OR MESSAGE LIKE "%' . $var_search . '%" OR DATE_ENVOIE LIKE "%' . $var_search . '%")') : '';

		$critaire = '' ;
		$critaire1 = '' ;

		if(!empty($STATUT_EXPIRE))
		{
			$critaire = ' AND STATUT = "'.$STATUT_EXPIRE.'"';
		}

		if(!empty($CATEGORIE))
		{
			$critaire .= ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
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
		$i=0;

		foreach ($fetch_data as $row) {
			$i=$i+1;
			$sub_array=array();
			$sub_array[]=$i;	
			$sub_array[]=$row->DESC_CATEGORIE;
			$parts = explode('-', $row->MESSAGE);
          // Prise de la première partie (avant le premier "-")
			$short_message = $parts[0];
			$sub_array[]= "".$short_message."... <a class='btn-md' href='#' data-toggle='modal'
			data-target='#myMessage3" . $row->ID_NOTIFICATION . "'><span class='bi bi-eye'></span></a>";

			$btn = "";

			if($row->STATUT == 1)
			{
				$sub_array[] = '<center class="text-warning">Pas encore lu</center>';

				$btn = "<a class='btn btn-outline-primary rounded-pill' href='".base_url('notification/Notification/lire_message/'.md5($row->ID_NOTIFICATION))."' ><font class='fa fa-check'></font> Marquer comme lu</a>";
			}
			else
			{
				$sub_array[] = '<center class="text-success">lu</center>';

				$btn = "";

			}
			$sub_array[]=date('d-m-Y',strtotime($row->DATE_ENVOIE));
			
			$option = '';
			$option .= '';

			$option .= " </ul>
			</div>
			<div class='modal fade' id='myMessage3" .$row->ID_NOTIFICATION. "'>
			<div class='modal-dialog modal-dialog-centered modal-lg'>
			<div class='modal-content'>
			<div class='modal-header' style='background:cadetblue;color:white;'>
			<h5></h5>
			<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			</div>
			<div class='modal-body'>
			<center><p>".$row->MESSAGE."</p> </center>
			<div class='modal-footer'>

			".$btn."

			<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
			</div>
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


		//fonction pour annuler l'affectation du vehicule
	public function lire_message($ID_NOTIFICATION)
	{

		$this->Model->update('notification_app',array('md5(ID_NOTIFICATION)'=>$ID_NOTIFICATION),array('STATUT'=>2));

			// $update=$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);


		$data['message'] = '<div class="alert alert-success text-center" id="message">Message lu</div>';

		$this->session->set_flashdata($data);

		redirect(base_url('notification/Notification'));


	}

		//fonction pour recuperer le nombre par statut

	function get_nbr_notif($STATUT = '',$CATEGORIE = '')
	{
		$critaire = '';

		// $CATEGORIE = $this->input->post('CATEGORIE');

		if(!empty($STATUT))
		{
			$critaire = ' AND STATUT = "'.$STATUT.'"';
		}

		if(!empty($CATEGORIE))
		{
			$critaire .= ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$notif = $this->getBindParms('COUNT(ID_NOTIFICATION) AS nombre', 'notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF', ' 1 '.$critaire.'', '`ID_NOTIFICATION` ASC');

		$notif = str_replace('\"', '"', $notif);
		$notif = str_replace('\n', '', $notif);
		$notif = str_replace('\"', '', $notif);

		$notif = $this->ModelPs->getRequeteOne($proce_requete, $notif);

		echo $notif['nombre'];
	}


	//fonction pour le nombre static par forfait prohe expire 

	function get_nbr_proche($CATEGORIE = '')
	{
		$critaire = '';

		if(!empty($CATEGORIE))
		{
			$critaire = ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$notif = $this->getBindParms('COUNT(ID_NOTIFICATION) AS nombre', 'notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF', ' 1 '.$critaire.'', '`ID_NOTIFICATION` ASC');

		$notif = str_replace('\"', '"', $notif);
		$notif = str_replace('\n', '', $notif);
		$notif = str_replace('\"', '', $notif);

		$notif = $this->ModelPs->getRequeteOne($proce_requete, $notif);

		echo $notif['nombre'];
	}


	//fonction pour le nombre static par forfait exipre 

	function get_nbr_exipire($CATEGORIE = '')
	{
		$critaire = '';

		if(!empty($CATEGORIE))
		{
			$critaire = ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$notif = $this->getBindParms('COUNT(ID_NOTIFICATION) AS nombre', 'notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF', ' 1 '.$critaire.'', '`ID_NOTIFICATION` ASC');

		$notif = str_replace('\"', '"', $notif);
		$notif = str_replace('\n', '', $notif);
		$notif = str_replace('\"', '', $notif);

		$notif = $this->ModelPs->getRequeteOne($proce_requete, $notif);

		echo $notif['nombre'];
	}


	//fonction pour filtrer le nombre par statut proche expire

	function get_nbr_notif_proche($STATUT_PROCHE = '',$CATEGORIE = '1')
	{
		$critaire = '';

		if(!empty($STATUT_PROCHE))
		{
			$critaire = ' AND STATUT = "'.$STATUT_PROCHE.'"';
		}

		if(!empty($CATEGORIE))
		{
			$critaire .= ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$notif = $this->getBindParms('COUNT(ID_NOTIFICATION) AS nombre', 'notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF', ' 1 '.$critaire.'', '`ID_NOTIFICATION` ASC');

		$notif = str_replace('\"', '"', $notif);
		$notif = str_replace('\n', '', $notif);
		$notif = str_replace('\"', '', $notif);

		$notif = $this->ModelPs->getRequeteOne($proce_requete, $notif);

		echo $notif['nombre'];
	}


	//fonction pour filtrer le nombre par statut expire

	function get_nbr_notif_expire($STATUT_EXPIRE = '',$CATEGORIE = '2')
	{
		$critaire = '';

		if(!empty($STATUT_EXPIRE))
		{
			$critaire = ' AND STATUT = "'.$STATUT_EXPIRE.'"';
		}

		if(!empty($CATEGORIE))
		{
			$critaire .= ' AND notification_app.ID_CAT_NOTIF = "'.$CATEGORIE.'"';
		}

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$notif = $this->getBindParms('COUNT(ID_NOTIFICATION) AS nombre', 'notification_app JOIN categorie_notification ON categorie_notification.ID_CAT_NOTIF = notification_app.ID_CAT_NOTIF', ' 1 '.$critaire.'', '`ID_NOTIFICATION` ASC');

		$notif = str_replace('\"', '"', $notif);
		$notif = str_replace('\n', '', $notif);
		$notif = str_replace('\"', '', $notif);

		$notif = $this->ModelPs->getRequeteOne($proce_requete, $notif);

		echo $notif['nombre'];
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