<?php
/**
* @author NESTORRUHAYA
*/
class Change_pwd extends CI_Controller
{
	
	function index()
	{
		$data['title']="CHANGER LE MOT DE PASSE";
		$this->load->view('Change_pwd_view',$data);
	}

	function changer()
	{
		$this->_validate();
		$NEW_PASSWORD=$this->input->post('NEW_PASSWORD');
		$this->Model->update('users',array('USER_ID'=>$this->session->userdata('USER_ID')),array('PASSWORD'=>md5($NEW_PASSWORD)));
		echo json_encode(array('status'=>true));


	}

	function _validate()
	{
		$data=array();
		$data['error_string']=array();
		$data['inputerror']=array();
		$data['status']=TRUE;


		$getUser=$this->Model->getOne('users',array('USER_ID'=>$this->session->userdata('USER_ID')));

		if (empty($this->input->post('NEW_PASSWORD'))) 
		{
			$data['error_string'][]="Le champs est obligatoire";
			$data['inputerror'][]="NEW_PASSWORD";
			$data['status']=false;
		}

		if (empty($this->input->post('CONFIRMER_PASSWORD'))) 
		{
			$data['error_string'][]="Le champs est obligatoire";
			$data['inputerror'][]="CONFIRMER_PASSWORD";
			$data['status']=false;
		}


		if (empty($this->input->post('ANCIEN_PASSWORD'))) 
		{
			$data['error_string'][]="Le champs est obligatoire";
			$data['inputerror'][]="ANCIEN_PASSWORD";
			$data['status']=false;
		}

		if (md5($this->input->post('ANCIEN_PASSWORD'))!=$getUser['PASSWORD']) 
		{
			$data['error_string'][]="L'ancien mot de passe n'est pas correct";
			$data['inputerror'][]="ANCIEN_PASSWORD";
			$data['status']=false;
		}

		if ($this->input->post('NEW_PASSWORD') !=$this->input->post('CONFIRMER_PASSWORD')) 
		{
			$data['error_string'][]="Le mot de passe ne correspond pas";
			$data['inputerror'][]="CONFIRMER_PASSWORD";
			$data['status']=false;
		}

		if ($data['status']==false) 
		{
			# code...
			echo json_encode($data);
			exit();
		}


	}
}