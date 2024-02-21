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
		
		$search = !empty($_POST['search']['value']) ? (' AND (`IDENTIFICATION` LIKE "%' . $var_search . '%" 
			OR TELEPHONE LIKE "%' . $var_search . '%" OR USER_NAME LIKE "%' . $var_search . '%" OR DESCRIPTION_PROFIL LIKE "%' . $var_search . '%")') : '';

		$query_principal='SELECT `USER_ID`,`IDENTIFICATION`,TELEPHONE,`USER_NAME`,`profil`.`DESCRIPTION_PROFIL` as profile,STATUT FROM `users` join profil on users.PROFIL_ID=profil.PROFIL_ID WHERE 1';
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

			$sub_array[]=$row->IDENTIFICATION;
			$sub_array[]=$row->USER_NAME.'<br>'.$row->TELEPHONE;
			$sub_array[]=$row->profile;
			
			if($row->STATUT==1){
				$sub_array[]='<form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->STATUT.'" id="status">

					<table>
					<td><label class="text-primary">Activé</label></td>
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
					<td><label class="text-danger">Désactivé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="myFunction(' . $row->USER_ID . ')">
					<span class="slider round"></span>
					</label></td>
					</table>
					</form>

				';
			}
			$option = '<div class="dropdown "style="color:white;float:right;">
			<a class="btn-sm dropdown-toggle" data-toggle="dropdown">
			<i class="bi bi-three-dots h5"></i>
			<span class="caret"></span></a>
			<ul class="dropdown-menu dropdown-menu-left">
			';
			$option .= "<li><a class='btn-md' href='" . base_url('administration/Users/getOne/'.$row->USER_ID). "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;Modifier</a></li>";
			$option .= "<li><a class='btn-md' href='#' data-toggle='modal'
			data-target='#mydelete" . $row->USER_ID . "'><span class='bi bi-trash h5'></span>&nbsp;&nbsp;Supprimer</a></li>";
			
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
			<center><h5>Voulez-vous supprimer l'utilisateur <b style='color:cadetblue;'>' " .$row->IDENTIFICATION." '</b> ?</h5></center>
			</div>

			<div class='modal-footer'>
			<a class='btn btn-danger btn-md' href='" . base_url('administration/Users/delete/'.$row->USER_ID) . "'>Supprimer</a>
			<button class='btn btn-secondary btn-md' data-dismiss='modal'>Quitter</button>
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

		$this->form_validation->set_rules('IDENTIFICATION', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('E-MAIL', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('PROFIL', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('numero_telephone', '', 'trim|required', array('required' => '<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));


		if($this->form_validation->run()==FALSE){
			$this->getOne($id_aff);

		}

		else{
			$array= array('IDENTIFICATION'=>$identity,'USER_NAME'=>$email,'PROFIL_ID'=>$profil,'TELEPHONE'=>$tel);
			$this->Model->update('users',array('USER_ID'=>$id_aff),$array);

			$data['message'] = '<div class="alert alert-primary text-center" id="message">' . "Modification faite avec succès" . '</div>';
			$this->session->set_flashdata($data);
			redirect(base_url('administration/Users'));

		}
	}


	//Fonction pour activer/desactiver un proprietaire
	function active_desactive($status,$USER_ID){

		if($status==1){
			$this->Model->update('users', array('USER_ID'=>$USER_ID),array('STATUT'=>2));

			$data['message'] = '<div class="alert alert-danger text-center" id="message">' . "Désactivation faite avec succès" . '</div>';
			$this->session->set_flashdata($data);

		}else if($status==2){
			$this->Model->update('users', array('USER_ID'=>$USER_ID),array('STATUT'=>1));
			$data['message'] = '<div class="alert alert-primary text-center" id="message">' . "Activation faite avec succès" . '</div>';
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
		$data['message']='<div class="alert alert-success text-center" id="message">Utilisateur supprimé avec succès</div>';
		$this->session->set_flashdata($data);
		redirect(base_url('administration/Users/index'));
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