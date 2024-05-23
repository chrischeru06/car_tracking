<?php
/*
Desc      : Controlleur pour la gestion du sim management
Auteur    : Mushagalusa Byamungu Pacifique
Email     : byamungu.pacifique@mediabox.bi
Telephone : +25772496057
Date      : 20/05/2024
*/
class Sim_management extends CI_Controller
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
		$data['title'] = 'Liste';
		$this->load->view('Sim_management_liste_View',$data);
	}


	//Fonction pour l'affichage
	function listing()
	{
		$query_principal="SELECT DISTINCT device.DEVICE_ID,device.CODE,CONCAT(DESC_MARQUE,' - ',DESC_MODELE,' - ',PLAQUE) AS vehicule,proprietaire.PROPRIETAIRE_ID,if(TYPE_PROPRIETAIRE_ID = 2,CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,TYPE_PROPRIETAIRE_ID,LOGO,PHOTO_PASSPORT,DATE_INSTALL,DESC_OPERATEUR,NUMERO,DATE_ACTIVE_MEGA,DATE_EXPIRE_MEGA,device.IS_ACTIVE,device.DATE_SAVE FROM device JOIN operateur_reseau ON operateur_reseau.OPERATEUR_ID = device.OPERATEUR_ID JOIN vehicule ON vehicule.VEHICULE_ID = device.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID  WHERE 1";


		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$limit = ' LIMIT 0,10';

		if($_POST['length'] != -1)
		{
			$limit = ' LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}

		$order_by='';
		$order_column=array('device.CODE','vehicule','proprio_desc','DATE_INSTALL','DESC_OPERATEUR','NUMERO','DATE_ACTIVE_MEGA','DATE_EXPIRE_MEGA','device.IS_ACTIVE','device.DATE_SAVE');

		$order_by = ' ORDER BY device.DEVICE_ID DESC';

		$critaire = '';

		$search=!empty($_POST['search']['value']) ? (" AND (device.CODE LIKE '%$var_search%' OR CONCAT(DESC_MARQUE,' - ',DESC_MODELE,' - ',PLAQUE) LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_INSTALL LIKE '%$var_search%' OR DESC_OPERATEUR LIKE '%$var_search%' OR NUMERO LIKE '%$var_search%' OR DATE_ACTIVE_MEGA LIKE '%$var_search%' OR DATE_EXPIRE_MEGA LIKE '%$var_search%' OR device.DATE_SAVE LIKE '%$var_search%' )"):'';

		$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. ''. $limit;

		$query_filter=$query_principal.''.$critaire.''.$search;

		$fetch_data=$this->Model->datatable($query_secondaire);

		$data=array();
		$u=1;
		foreach ($fetch_data as $row)
		{
			$sub_array=array();

			$option = '<div class="dropdown text-center">
			<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
			<i class="bi bi-three-dots h5" style="color:blue;"></i>	
			<span class="caret"></span></a>
			<ul class="dropdown-menu dropdown-menu-right">
			';

			$sub_array[]=$u++;
			$sub_array[]=$row->CODE;
			$sub_array[]=$row->vehicule;

			if($row->TYPE_PROPRIETAIRE_ID == 1 && empty($row->LOGO)){
				$sub_array[] ='<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><i class="bi bi-info-square h5" ></i>
				style="border-radius:50%;width:30px;height:30px" class="bi bi-bank round text-dark"> '.'  &nbsp;   '.' ' . $row->proprio_desc . '</td></tr></tbody></a>
				';
			}
			elseif(!empty($row->LOGO)){

				$sub_array[] = '<tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->LOGO.'"></td><td> '.'     '.' ' . $row->proprio_desc . '</td></tr></tbody></a>';
			}
			else
			{
				$sub_array[] = ' <tbody><tr><td><a class="btn-md text-dark" href="' . base_url('proprietaire/Proprietaire/Detail/'.md5($row->PROPRIETAIRE_ID)). '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></td><td> '.'     '.' ' . $row->proprio_desc . '</td></tr></tbody></a>';
			}

			$sub_array[]=date('d-m-Y',strtotime($row->DATE_INSTALL));
			$sub_array[]=$row->DESC_OPERATEUR;
			$sub_array[]=$row->NUMERO;
			$sub_array[]=date('d-m-Y',strtotime($row->DATE_ACTIVE_MEGA));
			$sub_array[]=date('d-m-Y',strtotime($row->DATE_EXPIRE_MEGA));

			if($row->IS_ACTIVE == 1){
				//$sub_array[] = '<center><i class="fa fa-check text-success  small" title="device activé"></i></center>';

				$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

				<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

				<center title="Désactiver"><label class="switch"> 


				<input type="checkbox" id="myChecked" data-toggle="modal" data-target="#mystatut' . $row->DEVICE_ID. '" checked >

				<span class="slider round"></span>
				</label></center>
				</form>

				';
			}
			else{
				//$sub_array[] = '<center><i class="fa fa-close text-danger  small" title="device désactivé"></i></center>';

				$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

				<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">


				<center title="Activer"><label class="switch"> 
				<input type="checkbox" id="myCheck" data-toggle="modal" data-target="#mystatut' . $row->DEVICE_ID. '" >
				<span class="slider round"></span>
				</label></center>

				</form>

				';
			}

			if(date('Y-m-d',strtotime($row->DATE_EXPIRE_MEGA)) >= date('Y-m-d'))
			{
				$sub_array[] = '<center><i class="fa fa-check text-success small" title="Valide"></i><font class="text-success small" title="Valide"> </font></center>';
			}
			else 
			{
				$sub_array[] = '<center><i class="fa fa-close text-danger small" title="Expirée"></i><font class="text-danger small" title="Expirée"> </font></center>';

				$option .="<li class='btn-md'>
				<a class='btn-md' onclick='renouvelerForfait(".$row->DEVICE_ID.")' style='cursor:pointer;'><span class='fa fa fa-rotate-right h2'></span>&nbsp;&nbsp;Renouveler méga</a>
				</li>";

			}

			$sub_array[] = date('d-m-Y',strtotime($row->DATE_SAVE));

			$option .="<li class='btn-md'>
			<a class='btn-md' href='" . base_url('sim_management/Sim_management/ajouter/'.md5($row->DEVICE_ID)) . "'><span class='bi bi-pencil h5'></span>&nbsp;&nbsp;Modifier</a>
			</li>";

			$desc_button='';

			if ($row->IS_ACTIVE == 1)
			{
				$desc_button='Désactiver';

				$option .="<li class='btn-md'>
				<a class='btn-md' href=#'' data-toggle='modal' data-target='#mystatut" . $row->DEVICE_ID. "'><span class='fa fa-close text-danger h2'></span>&nbsp;&nbsp;Désactiver</a>
				</li>";
			}
			else if ($row->IS_ACTIVE == 2)
			{
				$desc_button='Activer';

				$option .="<li class='btn-md'>
				<a class='btn-md' href=#'' data-toggle='modal' data-target='#mystatut" . $row->DEVICE_ID. "'><span class='fa fa-check text-success h2'></span>&nbsp;&nbsp;Activer</a>
				</li>";
			}

			$option .="<li class='btn-md'>
			<a class='btn-md' href='" . base_url('sim_management/Sim_management/get_historique/'.md5($row->DEVICE_ID)) . "'><span class='fa fa-rotate-left h2'></span>&nbsp;&nbsp;Historique</a>
			</li>";

			$option.="
			</div>
			<div class='modal fade' id='mystatut" .$row->DEVICE_ID. "'>
			<div class='modal-dialog'>
			<div class='modal-content'>

			<div class='modal-header' style='background:cadetblue;color:white;'>
			<h6 class='modal-title'>Confirmation</h6>
			<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
			</div>

			<div class='modal-body'>
			<h5>Voulez-vous vraiment <strong>$desc_button</strong> le device ? </h5>

			</div>
			<div class='modal-footer'>
			<a class='btn btn-outline-success rounded-pill btn-md' href='" . base_url('sim_management/Sim_management/change_status/'. md5($row->DEVICE_ID)) . "'>Oui</a>
			<button class='btn btn-outline-danger rounded-pill btn-md' class='close' data-dismiss='modal' onclick='verif_check()'>Non</button>
			</div>
			</div>
			</div>
			</div>
			";

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


	// Appel du formulaire d'enregistrement
	function ajouter()
	{
		
		$data['btn'] = "Enregistrer";

		$device = '';
		$vehicule = '';
		$operateur = '';

		$DEVICE_ID = $this->uri->segment(4);

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		if(empty($DEVICE_ID)){ //Enregistrement

			$device = array('DEVICE_ID'=>NULL,'CODE'=>NULL,'VEHICULE_ID'=>NULL,'DATE_INSTALL'=>NULL,'OPERATEUR_ID'=>NULL,'NUMERO'=>NULL,'DATE_ACTIVE_MEGA'=>NULL,'DATE_EXPIRE_MEGA'=>NULL,'proprio_desc'=>NULL);

			$data['title'] = 'Enregistrement d\'un device';
		}
		else{  //Modification

			$device = $this->getBindParms('device.DEVICE_ID,device.CODE,vehicule.VEHICULE_ID,device.DATE_INSTALL,operateur_reseau.OPERATEUR_ID,device.NUMERO,device.DATE_ACTIVE_MEGA,device.DATE_EXPIRE_MEGA,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','device JOIN vehicule ON vehicule.VEHICULE_ID = device.VEHICULE_ID JOIN operateur_reseau ON operateur_reseau.OPERATEUR_ID = device.OPERATEUR_ID JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID',' 1 and md5(device.DEVICE_ID)="'.$DEVICE_ID.'"','device.DEVICE_ID ASC');

			$device = str_replace('\"', '"', $device);
			$device = str_replace('\n', '', $device);
			$device = str_replace('\"', '', $device);

			$device = $this->ModelPs->getRequeteOne($proce_requete, $device);

			$data['title'] = 'Modification d\'un device';
		}


		$vehicule = $this->getBindParms('vehicule.VEHICULE_ID,DESC_MARQUE,DESC_MODELE,PLAQUE', 'vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE', '1', 'vehicule.VEHICULE_ID ASC');
		$vehicule = $this->ModelPs->getRequete($proce_requete, $vehicule);

		$operateur = $this->getBindParms('OPERATEUR_ID,DESC_OPERATEUR', 'operateur_reseau ', '1', 'OPERATEUR_ID ASC');
		$operateur= $this->ModelPs->getRequete($proce_requete, $operateur);



		$data['device_data'] = $device;
		$data['vehicule'] = $vehicule;
		$data['operateur'] = $operateur;

		$this->load->view('Sim_management_add_View',$data);
	}

	//Fonction pour recuperer le proprietaire
	function get_proprietaire($VEHICULE_ID){

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$proprio = $this->getBindParms('if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire JOIN vehicule ON vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID',' 1 and VEHICULE_ID ='.$VEHICULE_ID,'proprio_desc ASC');

		$proprio=str_replace('\"', '"', $proprio);
		$proprio=str_replace('\n', '', $proprio);
		$proprio=str_replace('\"', '', $proprio);

		$proprio = $this->ModelPs->getRequeteOne($proce_requete, $proprio);
		if(!empty($proprio)){
			echo json_encode($proprio['proprio_desc']);
		}
		else
		{
			echo json_encode(' ');
		}		
	}

	//Fonction pour recuperer la date d'expiration du mega
	function get_date_expire()
	{
		$DATE_ACTIVE_MEGA = $this->input->post('DATE_ACTIVE_MEGA');
		$dateExp = $this->notifications->date_manage(30,$DATE_ACTIVE_MEGA);
		$dateExp = date('Y-m-d',strtotime($dateExp));
		echo json_encode($dateExp);
	}

	//Fonction pour verifier si le code existe deja
	function check_code()
	{
		$CODE = $this->input->post('CODE');
		$NUMERO = $this->input->post('NUMERO');
		$DEVICE_ID = $this->input->post('DEVICE_ID');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		//verification code

		if(empty($DEVICE_ID))
		{
			$check_existe_code = $this->getBindParms('CODE','device',' CODE = "'.$CODE.'"','DEVICE_ID ASC');
		}
		else{
			$check_existe_code = $this->getBindParms('CODE','device',' CODE = "'.$CODE.'" AND DEVICE_ID != "'.$DEVICE_ID.'" ','DEVICE_ID ASC');
		}

		$check_existe_code = str_replace('\"', '"', $check_existe_code);
		$check_existe_code = str_replace('\n', '', $check_existe_code);
		$check_existe_code = str_replace('\"', '', $check_existe_code);

		$check_existe_code = $this->ModelPs->getRequete($proce_requete, $check_existe_code);

		$existe_code = 0;

		if(!empty($check_existe_code))
		{
			$existe_code = 1;
		}else{$existe_code = 0;}


		//verification numero

		if(empty($DEVICE_ID))
		{
			$check_existe_numero = $this->getBindParms('NUMERO','device',' NUMERO = "'.$NUMERO.'"','DEVICE_ID ASC');
		}
		else
		{
			$check_existe_numero = $this->getBindParms('NUMERO','device',' NUMERO = "'.$NUMERO.'" AND DEVICE_ID != "'.$DEVICE_ID.'" ','DEVICE_ID ASC');
		}

		$check_existe_numero = str_replace('\"', '"', $check_existe_numero);
		$check_existe_numero = str_replace('\n', '', $check_existe_numero);
		$check_existe_numero = str_replace('\"', '', $check_existe_numero);

		$check_existe_numero = $this->ModelPs->getRequete($proce_requete, $check_existe_numero);

		$existe_numero = 0;

		if(!empty($check_existe_numero))
		{
			$existe_numero = 1;
		}else{$existe_numero = 0;}

		echo json_encode(array('existe_code'=>$existe_code,'existe_numero'=>$existe_numero));
	}


	//Fonction pour l'enregistrement et la mise à jour ds la base
	function save()
	{
		$DEVICE_ID = $this->input->post('DEVICE_ID');

			if(empty($DEVICE_ID))   //Enregistrement
			{
				$data = array(
					'CODE'=>$this->input->post('CODE'),
					'VEHICULE_ID'=>$this->input->post('VEHICULE_ID'),
					'DATE_INSTALL'=>$this->input->post('DATE_INSTALL'),
					'OPERATEUR_ID'=>$this->input->post('OPERATEUR_ID'),
					'NUMERO'=>$this->input->post('NUMERO'),
					'DATE_ACTIVE_MEGA'=>$this->input->post('DATE_ACTIVE_MEGA'),
					'DATE_EXPIRE_MEGA'=>$this->input->post('DATE_EXPIRE_MEGA'),
				);

				$table = "device";

				$DEVICE_ID = $this->Model->insert_last_id($table,$data);

					// Enregistrement dans la table d'historique

				$data_historique = array(
					'DEVICE_ID' => $DEVICE_ID,
					'DATE_ACTIVE_MEGA'=> $this->input->post('DATE_ACTIVE_MEGA'),
					'DATE_EXPIRE_MEGA' => $this->input->post('DATE_EXPIRE_MEGA'),
					'USER_ID'=>$this->session->userdata('USER_ID'),
				);

				$create = $this->Model->create('historique_device',$data_historique);
				
				if ($create)
				{
					$message['message']='<div class="alert alert-success text-center" id="message">Enregistrement device avec succès</div>';
					$this->session->set_flashdata($message);
					redirect(base_url('sim_management/Sim_management'));

				}else
				{
					$message['message']='<div class="alert alert-danger text-center" id="message">Echec d\'enregistrement </div>';
					$this->session->set_flashdata($message);
					redirect(base_url('sim_management/Sim_management'));

				}

			}else //Mise à jour
			{
				$data = array(
					'CODE'=>$this->input->post('CODE'),
					'VEHICULE_ID'=>$this->input->post('VEHICULE_ID'),
					'DATE_INSTALL'=>$this->input->post('DATE_INSTALL'),
					'OPERATEUR_ID'=>$this->input->post('OPERATEUR_ID'),
					'NUMERO'=>$this->input->post('NUMERO'),
					'DATE_ACTIVE_MEGA'=>$this->input->post('DATE_ACTIVE_MEGA'),
					'DATE_EXPIRE_MEGA'=>$this->input->post('DATE_EXPIRE_MEGA'),
				);

				$table = "device";

				$update = $this->Model->update($table,array('DEVICE_ID'=>$DEVICE_ID),$data);

					// Enregistrement dans la table d'historique

				$data_historique = array(
					'DEVICE_ID' => $DEVICE_ID,
					'DATE_ACTIVE_MEGA'=> $this->input->post('DATE_ACTIVE_MEGA'),
					'DATE_EXPIRE_MEGA' => $this->input->post('DATE_EXPIRE_MEGA'),
					'USER_ID'=>$this->session->userdata('USER_ID'),
				);

				$create = $this->Model->create('historique_device',$data_historique);
				
				if ($update && $create)
				{
					$message['message']='<div class="alert alert-success text-center" id="message">Modification device avec succès</div>';
					$this->session->set_flashdata($message);
					redirect(base_url('sim_management/Sim_management'));

				}else
				{
					$message['message']='<div class="alert alert-danger text-center" id="message">Echec de modification </div>';
					$this->session->set_flashdata($message);
					redirect(base_url('sim_management/Sim_management'));

				}

			}

			
		}

		function get_date_expire_old($DEVICE_ID)
		{
			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$dateExp = $this->getBindParms('DATE_EXPIRE_MEGA', 'device', '1 AND DEVICE_ID = '.$DEVICE_ID.'', 'DEVICE_ID ASC');
			$dateExp = $this->ModelPs->getRequeteOne($proce_requete, $dateExp);

			if(!empty($dateExp['DATE_EXPIRE_MEGA']))
			{
				echo json_encode($dateExp['DATE_EXPIRE_MEGA']);
			}
			else{
				echo json_encode('');
			}
		}

	// Fonction pour l'enregistrement du renouvelement forfait

		function save_renouv_fortait()
		{
			$DEVICE_ID = $this->input->post('DEVICE_ID');

			$data = array(
				'DATE_ACTIVE_MEGA'=>$this->input->post('DATE_ACTIVE_MEGA'),
				'DATE_EXPIRE_MEGA'=>$this->input->post('DATE_EXPIRE_MEGA'),
			);

			$table = "device";

			$update = $this->Model->update($table,array('DEVICE_ID'=>$DEVICE_ID),$data);

			// Enregistrement dans la table d'historique

			$data_historique = array(
				'DEVICE_ID' => $DEVICE_ID,
				'DATE_ACTIVE_MEGA'=> $this->input->post('DATE_ACTIVE_MEGA'),
				'DATE_EXPIRE_MEGA' => $this->input->post('DATE_EXPIRE_MEGA'),
				'USER_ID'=>$this->session->userdata('USER_ID'),
			);

			$create = $this->Model->create('historique_device',$data_historique);

			if ($update && $create)
			{
				echo json_encode(array('status' => TRUE));

			}else
			{
				echo json_encode(array('status' => false));

			}

		}

		// fonction pour activer et desactiver un device

		public function change_status($id)
		{
			$get_status=$this->Model->getOne('device',array('md5(DEVICE_ID)'=>$id));
			$desc_status='';
			if($get_status['IS_ACTIVE']==1)
			{
				$desc_status='La désactivation';
				$this->Model->update('device', array('md5(DEVICE_ID)'=>$id),array('IS_ACTIVE'=>2));

				// Enregistrement dans la table d'historique

				$data_historique = array(
					'DEVICE_ID' => $get_status['DEVICE_ID'],
					'IS_ACTIVE'=> 2,
					'USER_ID'=>$this->session->userdata('USER_ID'),
				);

				$create = $this->Model->create('historique_device',$data_historique);
			}
			else
			{
				$desc_status="L'activation";

				$this->Model->update('device', array('md5(DEVICE_ID)'=>$id),array('IS_ACTIVE'=>1));

				// Enregistrement dans la table d'historique

				$data_historique = array(
					'DEVICE_ID' => $get_status['DEVICE_ID'],
					'IS_ACTIVE'=> 1,
					'USER_ID'=>$this->session->userdata('USER_ID'),
				);

				$create = $this->Model->create('historique_device',$data_historique);

			}

			$data['message']='<div class="alert alert-success text-center" id="message">'."$desc_status".' '." du device faite avec succès ".'</div>';
			$this->session->set_flashdata($data);
			redirect(base_url('sim_management/Sim_management/'));
		}

		//Fonction pour appel de la page d'historique

		function get_historique($DEVICE_ID)
		{
			$data['DEVICE_ID'] = $DEVICE_ID;
			$this->load->view('Sim_management_historique_View',$data);
		}

		//Fonction pour la liste d'historique device

		function liste_historique1()
		{
			$DEVICE_ID = $this->input->post('DEVICE_ID');
			$ID = $this->input->post('ID');

			$critaire = ' ' ;
			if($ID == 1) //Historique activation forfait
			{
				$critaire = ' AND historique_device.IS_ACTIVE IS NULL ' ;
			}
			else if($ID == 2) //Historique statut
			{
				$critaire = ' AND historique_device.DATE_ACTIVE_MEGA IS NULL AND historique_device.DATE_EXPIRE_MEGA IS NULL' ;
			}
			

			$query_principal='SELECT device.CODE,historique_device.DATE_ACTIVE_MEGA,historique_device.DATE_EXPIRE_MEGA,historique_device.IS_ACTIVE,IDENTIFICATION,historique_device.DATE_SAVE FROM historique_device JOIN device ON device.DEVICE_ID = historique_device.DEVICE_ID JOIN users ON users.USER_ID = historique_device.USER_ID WHERE 1';

			$critaire.= ' AND md5(historique_device.DEVICE_ID) = "'.$DEVICE_ID.'"';

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('DATE_ACTIVE_MEGA','DATE_EXPIRE_MEGA','IS_ACTIVE','IDENTIFICATION','DATE_SAVE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' HISTORIQUE_D ASC';
			}

			$search = !empty($_POST['search']['value']) ? (' AND (`historique_device.DATE_ACTIVE_MEGA` LIKE "%' . $var_search . '%" OR historique_device.DATE_EXPIRE_MEGA LIKE "%' . $var_search . '%"
				OR IDENTIFICATION LIKE "%' . $var_search . '%" OR historique_device.DATE_SAVE LIKE "%' . $var_search . '%" )') : '';


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
				$sub_array[]=$row->CODE;

					if(!empty($row->DATE_ACTIVE_MEGA)){
						$sub_array[]= '<center>'.date('d-m-Y',strtotime($row->DATE_ACTIVE_MEGA)).'</center>';
					}
					else{
						$sub_array[]= '<center>N/A</center>';
					}

					if(!empty($row->DATE_EXPIRE_MEGA)){
						$sub_array[]= '<center>'.date('d-m-Y',strtotime($row->DATE_EXPIRE_MEGA)).'</center>';
					}
					else{
						$sub_array[]= '<center>N/A</center>';
					}

					if(!empty($row->DATE_EXPIRE_MEGA)){
						if(date('Y-m-d',strtotime($row->DATE_EXPIRE_MEGA)) >= date('Y-m-d'))
						{
							$sub_array[] = '<center><i class="fa fa-check text-success small" title="Valide"></i><font class="text-success small" title="Valide"> </font></center>';
						}
						else 
						{
							$sub_array[] = '<center><i class="fa fa-close text-danger small" title="Expirée"></i><font class="text-danger small" title="Expirée"> </font></center>';
						}
					}
					else{
						$sub_array[]= '<center>N/A</center>';
					}

				$sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

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


		function liste_historique2()
		{
			$DEVICE_ID = $this->input->post('DEVICE_ID');
			$ID = $this->input->post('ID');

			$critaire = ' ' ;
			if($ID == 1) //Historique activation forfait
			{
				$critaire = ' AND historique_device.IS_ACTIVE IS NULL ' ;
			}
			else if($ID == 2) //Historique statut
			{
				$critaire = ' AND historique_device.DATE_ACTIVE_MEGA IS NULL AND historique_device.DATE_EXPIRE_MEGA IS NULL' ;
			}
			

			$query_principal='SELECT device.CODE,historique_device.DATE_ACTIVE_MEGA,historique_device.DATE_EXPIRE_MEGA,historique_device.IS_ACTIVE,IDENTIFICATION,historique_device.DATE_SAVE FROM historique_device JOIN device ON device.DEVICE_ID = historique_device.DEVICE_ID JOIN users ON users.USER_ID = historique_device.USER_ID WHERE 1';

			$critaire.= ' AND md5(historique_device.DEVICE_ID) = "'.$DEVICE_ID.'"';

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('DATE_ACTIVE_MEGA','DATE_EXPIRE_MEGA','IS_ACTIVE','IDENTIFICATION','DATE_SAVE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' HISTORIQUE_D ASC';
			}

			$search = !empty($_POST['search']['value']) ? (' AND (`historique_device.DATE_ACTIVE_MEGA` LIKE "%' . $var_search . '%" OR historique_device.DATE_EXPIRE_MEGA LIKE "%' . $var_search . '%"
				OR IDENTIFICATION LIKE "%' . $var_search . '%" OR historique_device.DATE_SAVE LIKE "%' . $var_search . '%" )') : '';


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
				$sub_array[]=$row->CODE;

				if($ID == 2) //Historique statut
				{
					if($row->IS_ACTIVE == 1){
						$sub_array[] = '<center><i class="fa fa-check text-success  small" title="device activé"></i></center>';

					// $sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					// <center title="Désactiver"><label class="switch"> 


					// <input type="checkbox" id="myChecked" disabled data-toggle="modal" data-target="" checked >

					// <span class="slider round"></span>
					// </label></center>
					// </form>

					// ';
					}
					else if($row->IS_ACTIVE == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger  small" title="device désactivé"></i></center>';

					// $sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

					// <center title="Activer"><label class="switch"> 
					// <input type="checkbox" id="myCheck" disabled data-toggle="modal" data-target="">

					// <span class="slider round"></span>
					// </label></center>

					// </form>

					// ';
					}
					else{
						$sub_array[]= '<center>N/A</center>';
					}
				}
				
				
				$sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

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

		//fonction pour recuperer le nombre des devices

		function get_nbr_device()
		{
			$proce_requete = "CALL `getRequete`(?,?,?,?);";

			$device = $this->getBindParms('COUNT(DEVICE_ID) AS nombre', 'device JOIN operateur_reseau ON operateur_reseau.OPERATEUR_ID = device.OPERATEUR_ID JOIN vehicule ON vehicule.VEHICULE_ID = device.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID', ' 1', '`DEVICE_ID` ASC');

			$device = $this->ModelPs->getRequeteOne($proce_requete, $device);

			echo $device['nombre'];
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