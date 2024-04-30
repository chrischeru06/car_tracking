<?php


/**Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * 68071895 
 * Le 29/4/2024
 * Controller pour gerer les messages des proprietaires 
 */
class Message extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->out_application();
		$this->load->helper('email');
	}
	//Fonction pour rediriger vers la page d'accueil,une fois la session perdue
	function out_application()
	{
		if(empty($this->session->userdata('USER_ID')))
		{
			redirect(base_url('Login/logout'));
		}
	}


	//Fonction pour l'affichage de la page d'envoie de message
	function index($id){
		$USER_ID = $this->session->userdata('USER_ID');
		$PROFIL_ID = $this->session->userdata('PROFIL_ID');
		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		// print_r($id);die();
		$message = $this->getBindParms('ID_MESSAGE_USER,MESSAGE,USER_ID_ENVOIE,PROFIL_ID_ENVOIE,PROFIL_ID_DESTINATAIRE,USER_ID_DESTINATAIRE', 'message_utilisateurs', '1 AND USER_ID_ENVOIE='.$id.' OR USER_ID_DESTINATAIRE='.$id, 'ID_MESSAGE_USER ASC');
		$data['message_all'] = $this->ModelPs->getRequete($proce_requete, $message);

		$name= $this->getBindParms('IDENTIFICATION', 'users', '1 AND USER_ID='.$id, 'USER_ID ASC');
		$data['name'] = $this->ModelPs->getRequeteOne($proce_requete, $name);

		if($PROFIL_ID==1){
			$this->Model->update('message_utilisateurs',array('USER_ID_ENVOIE'=>$id),array('STATUT_ADMIN'=>2));

			$this->Model->update('message_utilisateurs',array('USER_ID_DESTINATAIRE'=>$id),array('STATUT_ADMIN'=>2));

		}else{

			$this->Model->update('message_utilisateurs',array('USER_ID_ENVOIE'=>$id),array('STATUT_UTILISATEUR'=>2));

			$this->Model->update('message_utilisateurs',array('USER_ID_DESTINATAIRE'=>$id),array('STATUT_UTILISATEUR'=>2));
		}

		$data['USER_ID'] = $USER_ID;
		$data['PROFIL_ID'] =$PROFIL_ID;

		$data['id_rec']=$id;

		$this->load->view('Message_Add_View',$data);
	}

	//Fonction pour l'enregistrement des messages dans la BD
	function save_message(){
		$messageInput=$this->input->post('message');

		$USER_ID = $this->session->userdata('USER_ID');
		$PROFIL_ID = $this->session->userdata('PROFIL_ID');
		$table_users = 'message_utilisateurs';
		if($PROFIL_ID!=1){

			$data_insert_users = array(

				'MESSAGE'=>$messageInput,
				'USER_ID_ENVOIE'=>$USER_ID,
				'PROFIL_ID_ENVOIE'=>$PROFIL_ID,
				'PROFIL_ID_DESTINATAIRE'=>1,
				'USER_ID_DESTINATAIRE'=>1



			); 
			$done=$this->Model->create($table_users,$data_insert_users);


		}else{

			$id_recepteur=$this->input->post('id_recepteur');


			$data_insert_admin = array(

				'MESSAGE'=>$messageInput,
				'USER_ID_ENVOIE'=>$USER_ID,
				'PROFIL_ID_ENVOIE'=>$PROFIL_ID,
				'PROFIL_ID_DESTINATAIRE'=>2,
				'USER_ID_DESTINATAIRE'=>$id_recepteur



			); 
			$done_admin=$this->Model->create($table_users,$data_insert_admin);



		}
		
		$data=1;
		echo json_encode($data);
	}

	//Fonction pour l'affichage de la liste
	function message(){

		$this->load->view('Message_list_View');
	}
	//Fonction pour la liste des messages
	function listing(){
		$USER_ID = $this->session->userdata('USER_ID');
		$PROFIL_ID=$this->session->userdata('PROFIL_ID');

		$critaire = '' ;
		if($this->session->userdata('PROFIL_ID') != 1)
		{
			$critaire.= ' AND message_utilisateurs.USER_ID_ENVOIE = '.$USER_ID;
		}else{
			$critaire.= ' AND message_utilisateurs.PROFIL_ID_ENVOIE != 1';


		}
		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = " GROUP BY message_utilisateurs.USER_ID_ENVOIE";
		
		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}
		$order_by='';
		$order_column=array('id','IDENTIFICATION','TELEPHONE','MESSAGE');
		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' PROFIL_ID DESC';
		}
		
		$search = !empty($_POST['search']['value']) ? (' AND (users.IDENTIFICATION LIKE "%' . $var_search . '%" 
			OR users.TELEPHONE LIKE "%' . $var_search . '%" OR MESSAGE LIKE "%' . $var_search . '%")') : '';

		$query_principal='SELECT MESSAGE,USER_ID_ENVOIE,users.IDENTIFICATION,users.TELEPHONE FROM message_utilisateurs JOIN users ON users.USER_ID=message_utilisateurs.USER_ID_ENVOIE WHERE 1';
		
		
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

			if($this->session->userdata('PROFIL_ID') != 1)
			{

				$proce_requete = "CALL `getRequete`(?,?,?,?);";
				$my_select = $this->getBindParms('COUNT(ID_MESSAGE_USER) as nombre', 'message_utilisateurs', '1 AND message_utilisateurs.STATUT_UTILISATEUR=1 AND message_utilisateurs.USER_ID_DESTINATAIRE ='.$row->USER_ID_ENVOIE.'', 'message_utilisateurs.ID_MESSAGE_USER ASC');
				$NOMBRE = $this->ModelPs->getRequeteOne($proce_requete, $my_select);

			}else{

				$proce_requete = "CALL `getRequete`(?,?,?,?);";
				$my_select = $this->getBindParms('COUNT(ID_MESSAGE_USER) as nombre', 'message_utilisateurs', '1 AND message_utilisateurs.STATUT_ADMIN=1 AND message_utilisateurs.USER_ID_ENVOIE='.$row->USER_ID_ENVOIE.'', 'message_utilisateurs.ID_MESSAGE_USER ASC');
				$NOMBRE = $this->ModelPs->getRequeteOne($proce_requete, $my_select);

			}
			
		 //print_r($my_select);die();
			$i=$i+1;
			$sub_array=array();
			$sub_array[]=$i;	
			
			$sub_array[]=$row->IDENTIFICATION;
			$sub_array[]=$row->TELEPHONE;
			$sub_array[]="<center><a class='btn btn-outline-primary rounded-pill' href='" . base_url('administration/Message/index/'.$row->USER_ID_ENVOIE). "' style='font-size:10px;'><label>".$NOMBRE['nombre']."</label></a></center>";
			
			// $option = '<div class="dropdown "style="color:white;float:right;">
			// <a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
			// <i class="bi bi-three-dots h5" style="color:blue;"></i>
			// <span class="caret"></span></a>
			// <ul class="dropdown-menu dropdown-menu-left">
			// ';
			// $option .= "<li><a class='btn-md' href='" . base_url('administration/Message/index/'.$row->USER_ID_ENVOIE). "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;Discussion</a></li>";
			
			
			
			// $sub_array[]=$option;

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
}?>