<?php

/**
 * Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * 68071895
 * Le 21/2/2024
 * Gestion de profil
 */
class Profil extends CI_Controller
{
	//Fonction pour rediriger sur la page de connexion si une fois la session est perdue
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

	//Fonction pour l'affichage de la liste des profils
	function index(){

		$this->load->view('Profil_List_view');
	}

	//Fonction pour la liste
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
		
		$search = !empty($_POST['search']['value']) ? (' AND (`DESCRIPTION_PROFIL` LIKE "%' . $var_search . '%" 
			OR CODE_PROFIL LIKE "%' . $var_search . '%")') : '';

		$query_principal='SELECT PROFIL_ID, DESCRIPTION_PROFIL, CODE_PROFIL,ROLE FROM profil WHERE 1';
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
			$sub_array[]=$row->DESCRIPTION_PROFIL;
			if(!empty($row->ROLE)){
				$sub_array[]=$row->ROLE;	

			}else{
				$sub_array[]=''.lang('lste_n_a').'';	

			}

			$option = '';
			$option .= '<div class="dropdown" style="float:right">
			<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
			<i class="bi bi-three-dots h5" style="color:blue;"></i>

			<span class="caret"></span></a>
			<ul class="dropdown-menu dropdown-menu-left">
			';
			$option .= "<li><a class='btn-md' href='" . base_url('administration/Profil/getOne/'. $row->PROFIL_ID) . "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;".lang('Modifier')."</a></li>";
			$option .= "<li><a class='btn-md' href='#' data-toggle='modal'
			data-target='#mydelete" . $row->PROFIL_ID . "'><span class='bi bi-trash h5'></span>&nbsp;&nbsp;".lang('btn_supprimer')."</a></li>";
			$option .= " </ul>
			</div>
			<div class='modal fade' id='mydelete" .$row->PROFIL_ID. "' tabindex='-1'>
			<div class='modal-dialog modal-dialog-centered modal-lg'>
			<div class='modal-content'>
			<div class='modal-header'>
			<h5 class='modal-title'></h5>
			<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
			</div>

			<div class='modal-body'>
			<center><h5>".lang('msg_suppr')." <b style='color:cadetblue;'> '".$row->DESCRIPTION_PROFIL."'</b> ?</h5></center>
			</div>

			<div class='modal-footer'>
			<a class='btn btn-danger btn-md' href='" . base_url('administration/Profil/delete/' .$row->PROFIL_ID). "'>".lang('btn_supprimer')."</a>
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
	function getOne()
	{
		$data['error']='';
		$PROFIL_ID=$this->uri->segment(4);
		$data['profiles']=$this->Model->getOne('profil',array('PROFIL_ID'=>$PROFIL_ID));

		$this->load->view('Profil_Update_View',$data);
	}

	 //Modification d'un profil
	function update()
	{

		$this->form_validation->set_rules('DESCRIPTION_PROFIL','','trim|required',array('required'=>'<font style="color:red;font-size:15px;">*'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('CODE_PROFIL','','trim|required',array('required'=>'<font style="color:red;font-size:15px;">*'.lang('msg_validation').'</font>'));

		if($this->form_validation->run()==TRUE)
		{
			$id=$this->input->post('ID');


			$data_prof=array(
				'DESCRIPTION_PROFIL' =>$this->input->post('DESCRIPTION_PROFIL'),
				'CODE_PROFIL' =>$this->input->post('CODE_PROFIL'),


			);

			$condition=$this->Model->getOne('profil',array('PROFIL_ID'=>$this->input->post('ID')));
			$update=$this->Model->update('profil',array('PROFIL_ID'=>$this->input->post('ID')), $data_prof);

			if ($update) 
			{
				$message['message']='<div class="alert alert-primary text-center" id="message">'.lang('msg_success_modif').'</div>';
				$this->session->set_flashdata($message);
				redirect(base_url('administration/Profil/index'));
			}
			else
			{
				$message['message']='<div class="alert alert-warning text-center" id="message">'.lang('msg_error_modif').'</div>';
				$this->session->set_flashdata($message);
				redirect(base_url('administration/Profil/index'));

			}


		}
		else
		{
			$message['message']='<div class="alert alert-success text-center" id="message">'.lang('modif_msg_prfil').' '.$this->input->post('DESCRIPTION_PROFIL').' '.lang('modif_msg_prfil_echoue').'</div>';
			$this->session->set_flashdata($message);
			$data['error']='';
			$data['profiles']=$this->Model->getOne('profil',array('PROFIL_ID'=>$this->input->post('ID')));
			$this->load->view('Profils_Update_View',$data);
		} 
	}


	//Suppression d'un profil
	function delete($id)
	{
		$PROFIL_ID=$this->uri->segment(4);
		$delete=$this->Model->delete('profil',array('PROFIL_ID'=>$PROFIL_ID));
		if ($delete)
		{
			$message['message']='<div class="alert alert-primary text-center" id="message">'.lang('msg_suppression_succes').'</div>';
			$this->session->set_flashdata($message);
			redirect(base_url('administration/Profil/index'));

		}
		else
		{
			$message['message']='<div class="alert alert-warning text-center" id="message">'.lang('msg_suppression_error').'</div>';
			$this->session->set_flashdata($message);    
		}
		redirect(base_url('administration/Profil/index'));
	}

	 //Page d'ajout d'un profil
	function add()
	{
		$data['error']='';
		$this->load->view('Profil_Add_View',$data);
	}

  	//Enregistrement d'un profil
	function save()
	{
		$this->form_validation->set_rules('DESCRIPTION_PROFIL','','trim|required|is_unique[profil.DESCRIPTION_PROFIL]',array('required'=>'<font style="color:red;font-size:15px;">*'.lang('msg_validation').'</font>','is_unique'=>'<font style="color:red;font-size:15px;">*'.lang('msg_unique_prfil').'</font>'));
		$this->form_validation->set_rules('CODE_PROFIL','','trim|required|is_unique[profil.CODE_PROFIL]',array('required'=>'<font style="color:red;font-size:15px;">*'.lang('msg_validation').'</font>','is_unique'=>'<font style="color:red;font-size:15px;">*'.lang('msg_unique_code').'</font>'));

		$this->form_validation->set_rules('ROLE','','required',array('required'=>'<font style="color:red;font-size:15px;">*'.lang('msg_validation').'</font>'));

		if($this->form_validation->run()==TRUE)
		{

			$data_prof=array(
				'DESCRIPTION_PROFIL' =>$this->input->post('DESCRIPTION_PROFIL'),
				'CODE_PROFIL' =>$this->input->post('CODE_PROFIL'),
				'ROLE' =>$this->input->post('ROLE'),



			);
			$table='profil';
			$this->Model->create($table,$data_prof);
			$data['message']='<div class="alert alert-success text-center" id="message">'.lang('ajout_success').'</div>';
			$this->session->set_flashdata($data);
			redirect(base_url('administration/Profil/index'));

		}
		else
		{
			$data['error']='';
			$this->load->view('Profil_Add_View',$data);
		}	

	}
}


?>