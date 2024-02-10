<?php
/*
*
/**
 * @NIYOMWUNGERE ELEL DANCILLA
   le 06/12/2022/2022
   mail:ella_dancilla@mediabox.bi
   CONTACT:71379943
*  Gestion des Login
 */

class Login extends CI_Controller
{
	function index()
	{
		$this->load->view('Login_View');
	}
	
	function check_login()
	{

		$this->_validate_login();

		$email=$this->input->post('email');
		$pwd=$this->input->post('Passworde');

		$user=$this->Model->getOne('users',array('USER_NAME'=>$email));

		 // print_r($user);die();

		$output = null;

		if (!empty($user)) 
		{
		 	# code...
			if ($user['PASSWORD']==md5($pwd)) {

				$session=array(
					'USER_ID'=>$user['USER_ID'],
					'IDENTIFICATION'=>$user['IDENTIFICATION'],
					'USER_NAME'=>$user['USER_NAME'],
					'PASSWORD'=>$user['PASSWORD'],
					'PROFIL_ID'=>$user['PROFIL_ID'],
					'STATUT'=>$user['STATUT']
				);

		  // print_r($session);die();


				$output = array("status"=>TRUE,'message'=>'<center>Connexion en cours ....</center>');
				
			}
			else
			{
				$output = array("status"=>false,'message'=>'<center>Utilisateur n\'existe pas dans le système</center>');
			}
			
		}
		if ($output == null) {
            # code...
			$output = array("status"=>false,'message'=>'<center>Le compte n\'existe pas ou il est inactif</center>');
		}


		echo json_encode($output);
	}

	function logout()
	{
		$session=array(
			'USER_ID'=>NULL,
			'IDENTIFICATION'=>NULL,
			'USER_NAME'=>NULL,
			'PASSWORD'=>NULL,
			'PROFIL_ID'=>NULL,
			'STATUT'=>NULL
		);
		$this->session->set_userdata($session);
		$this->load->view('Login_View');
	}

	public function _validate_login()
	{
		$data = null;
		$status = true;

		$email = $this->input->post('email');
		$pwd =  $this->input->post('Passworde');

		if($email == '')
		{
			$data = array('status'=>FALSE,'message'=>'Le nom d\'utilisateur est obligatoire');
			$status = false;
		}
		if($pwd == '')
		{
			$data = array('status'=>FALSE,'message'=>'Le mot de passe est obligatoire');
			$status = false;
		}

		if($status === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	function forgotten_pwd()
	{
		$this->load->view('Oublier_Password_View');
	}

	function forget_pwd()
	{
		$this->validate_forget_pwd();
		$emailTo=$this->input->post('EMAIL_CONFIRMATION');
		$user=$this->Model->getOne('users',array('USER_NAME'=>$emailTo));
		$pwd=$this->notifications->generate_password(10);
		$this->Model->update('users',array('USER_ID'=>$user['USER_ID']),array('PASSWORD'=>md5($pwd)));
		$lien=base_url()."Login";
		$message="Cher(ère).<b>".$user['IDENTIFICATION']." ".$user['IDENTIFICATION']."</b> votre mot de passe a été renouvellé.<br>Le mot de passe actuel: <b>".$pwd."</b>.<br>Cliquer <a href='".$lien."'>ici</a> pour vous connecter.";
		$subject="Mot de passe oublié";
		$this->notifications->send_mail($emailTo, $subject, $cc_emails = array(), $message, $attach = array());
		echo json_encode(array('status'=>TRUE));
	}

	function validate_forget_pwd()
	{
		$data=array();
		$data['error_string']=array();
		$data['input_error']=array();
		$data['status']=TRUE;
		$message="Le champs est obligatoire";
		$message_verif="Email n'existe pas dans le système";

		$user=$this->Model->getOne('users',array('USER_NAME'=>$this->input->post('EMAIL_CONFIRMATION')));


		if (empty($this->input->post('EMAIL_CONFIRMATION'))) {
			$data['input_error'][]="EMAIL_CONFIRMATION";
			$data['error_string'][]=$message;
			$data['status']=FALSE;
		}

		if ($this->input->post('EMAIL_CONFIRMATION')!=$user['USER_NAME']) {
			$data['input_error'][]="EMAIL_CONFIRMATION";
			$data['error_string'][]=$message_verif;
			$data['status']=FALSE;
		}
		
		if ($data['status']==FALSE) 
		{
			echo json_encode($data);
			exit();

		}
	}

	public function do_login()
	{

		
		$username = $this->input->post('email');
		$password = $this->input->post('Passworde');


		$get_email=$this->Model->getOne('users',array('USER_NAME'=>$username,'PASSWORD'=>md5($password)));
		$user=$this->Model->getOne('users',array('USER_NAME'=>$username,'STATUT'=>1,'PROFIL_ID'=>$get_email['PROFIL_ID']));

		$admin_profil=$this->Model->getOne('profil',array('PROFIL_ID'=>$user['PROFIL_ID']));



		if (!empty($user))
		{
			if ($user['PASSWORD']==md5($password))
			{
				if ($user['STATUT'] == 1)
				{
					$session = array(

						'USER_ID'=>$user['USER_ID'],
						'IDENTIFICATION'=>$user['IDENTIFICATION'],
						'USER_NAME'=>$user['USER_NAME'],
						'PROFIL_ID'=>$user['PROFIL_ID'],
						'CODE_PROFIL' => $admin_profil['CODE_PROFIL'],
						'STATUT'=>$user['STATUT']
						

						
					);

 // print_r($session);die();

					$this->session->set_userdata($session);


					if ($this->session->userdata('CODE_PROFIL') == "ADMIN") 
						redirect(base_url('Dashboard'));
					
					if ($this->session->userdata('CODE_PROFIL') == "PROPRIETAIRE") 
						redirect(base_url('proprietaire/Proprietaire_vehicule'));
				

					

				}else {
					$sms['sms']='<br><div class="alert alert-danger text-center alert-dismissible fade in col-md-8 col-md-offset-2"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong> Oup! </strong> Contacter l\'administration ! .</div><br>' ;
					$this->session->set_flashdata($sms) ;
					redirect(base_url());
				}

			} else {
				$sms['sms']='<br><div class="alert alert-danger text-center alert-dismissible fade in col-md-8 col-md-offset-2"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong> Oup! </strong> Mot de pass incorrect ! .</div><br>' ;
				$this->session->set_flashdata($sms) ;
				redirect(base_url());
			}

		} else {
			$sms['sms']='<br><div class="alert alert-danger text-center alert-dismissible fade in col-md-8 col-md-offset-2"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong> Oup! </strong> Email incorrect ! .</div><br>' ;
			$this->session->set_flashdata($sms) ;
			redirect(base_url());
		}

		$this->session->set_flashdata($sms) ;
		redirect(base_url());

	}
}
?>