<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 30/01/2024
*/
	class Vehicule extends CI_Controller
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
			$this->load->view('Vehicule_liste_View');
		}


		//Fonction pour l'affichage
		function listing()
		{
			$USER_ID = $this->session->userdata('USER_ID');

			$critaire = '' ;

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}

			$query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,proprietaire.PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


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

			$search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

			$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. ''. $limit;

			$query_filter=$query_principal.''.$critaire.''.$search;

			$fetch_data=$this->Model->datatable($query_secondaire);

			$data=array();
			foreach ($fetch_data as $row)
			{
				$sub_array=array();
				$sub_array[]=$row->CODE;
				$sub_array[]=$row->DESC_MARQUE;
				$sub_array[]=$row->DESC_MODELE;
				$sub_array[]=$row->PLAQUE;
				$sub_array[]=$row->COULEUR;
				$sub_array[]=(isset($row->KILOMETRAGE)?$row->KILOMETRAGE.' litres / KM' : 'N/A');

				// $sub_array[]= "<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='120px' width='120px' ></a>";

				$sub_array[]=' <table><tr><td style = "width:5000px;"><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->VEHICULE_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->desc_proprio . '</td></tr></table></a>';

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "' >&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";

				if($row->IS_ACTIVE==1){
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-primary">Activé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_desactive(' . $row->VEHICULE_ID . ')" checked>
					<span class="slider round"></span>
					</label></td>
					</table>

					
					
					</form>

					';
				}else{
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_checked" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-danger">Désactivé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="statut_active(' . $row->VEHICULE_ID . ')">
					<span class="slider round"></span>
					</label></td>
					</table>
					</form>

					';
				}

				$option = '<div class="dropdown text-center">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i> 
				
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				$option .= "<li><a class='btn-md' href='" . base_url('proprietaire/Vehicule/ajouter/'.md5($row->VEHICULE_ID)) . "'><label class='text-dark'><i class='bi bi-pencil'></i>&nbsp;&nbsp;Modifier</label></a></li>";
				// $option .= "<li><a class='btn-md' href='" . base_url('tracking/Dashboard/tracking_chauffeur/'.$row->CODE) . "'><label class='text-dark'><i class='bi bi-car'></i>&nbsp;&nbsp;Suivi</label></a></li>";



				$option .="
				</div>
				<div class='modal fade' id='mypicture" .$row->VEHICULE_ID."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h6 class='modal-title'>Détail du véhicule</h6>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<h4 class=''></h4>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<table class='table table-borderless'>

				<tr>
				<td class='btn-sm'>Code</td>
				<th class='btn-sm'>".$row->CODE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Marque</td>
				<th class='btn-sm'>".$row->DESC_MARQUE."</th>
				</tr>

				<tr class='btn-sm'>
				<td>Modèle</td>
				<th class='btn-sm'>".$row->DESC_MODELE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Plaque</td>
				<th class='btn-sm'>".$row->PLAQUE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Couleur</td>
				<th class='btn-sm'>".$row->COULEUR."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Consommation / km</td>
				<th class='btn-sm'>".$row->KILOMETRAGE."</th>
				</tr>

				<tr>
				<td class='btn-sm'>propriétaire</td>
				<th class='btn-sm'><strong>".$row->desc_proprio."</strong></th>
				</tr>

				<tr>
				<td class='btn-sm'>Chauffeur</td>
				<th class='btn-sm'><strong>".(isset($row->desc_chauffeur)?$row->desc_chauffeur:'N/A')."</strong></th>
				</tr>

				</table>

				</div>
				</div>
				
				</div>
				</div>
				</div>";


				$option .="
				</div>
				<div class='modal fade' id='proprio" .$row->VEHICULE_ID."' style='border-radius:100px;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5 class='modal-title'>Information du propriétaire</h5>
				<button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<h4 class=''></h4>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT)."' height='100%'  width='100%'  style= 'border-radius:50%;'>
				</div>

				<div class='col-md-6'>

				<h4></h4>

				<table class='table table-borderless'>

				<tr>
				<td class='btn-sm'>Nom</td>
				</tr>

				<tr>
				<th class='btn-sm'>".$row->desc_proprio."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Adresse</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->ADRESSE."</th>
				</tr>

				<tr class='btn-sm'>
				<td>Email</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->EMAIL."</th>
				</tr>

				<tr>
				<td class='btn-sm'>Téléphone</td>
				</tr>
				<tr>
				<th class='btn-sm'>".$row->TELEPHONE."</th>
				</tr>

				</table>

				</div>
				</div>
				
				</div>
				</div>
				</div>";

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

		//Fonction pour activer/desactiver un vehicule
		function active_desactive($status,$VEHICULE_ID){

			if($status == 1){
				$this->Model->update('vehicule', array('VEHICULE_ID'=>$VEHICULE_ID),array('IS_ACTIVE'=>2));

				$status = 2;

			}else if($status == 2){
				$this->Model->update('vehicule', array('VEHICULE_ID'=>$VEHICULE_ID),array('IS_ACTIVE'=>1));
				$status = 1;
			}

			echo json_encode(array('status'=>$status));

		}

       // Appel du formulaire d'enregistrement
		function ajouter()
		{	
			$USER_ID = $this->session->userdata('USER_ID');

			$VEHICULE_ID = $this->uri->segment(4);

			$data['btn'] = "Enregistrer";
			$data['title']="Enregistrement du véhicule";

			$vehicule = array('VEHICULE_ID'=>NULL,'ID_MARQUE'=>NULL,'ID_MODELE'=>NULL,'CODE'=>NULL,'PLAQUE'=>NULL,'COULEUR'=>NULL,'KILOMETRAGE'=>NULL,'PHOTO'=>NULL,'PROPRIETAIRE_ID'=>NULL,'ANNEE_FABRICATION'=>NULL,'NUMERO_CHASSIS'=>NULL,'USAGE_ID'=>NULL,'DATE_FIN_CONTROTECHNIK'=>NULL,'DATE_FIN_ASSURANCE'=>NULL,'DATE_DEBUT_CONTROTECHNIK'=>NULL,'DATE_DEBUT_ASSURANCE'=>NULL,'FILE_CONTRO_TECHNIQUE'=>NULL,'FILE_ASSURANCE'=>NULL,'ID_ASSUREUR'=>NULL);
			
			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			$marque = $this->getBindParms('ID_MARQUE,DESC_MARQUE','vehicule_marque',' 1 ','DESC_MARQUE ASC');
			$marque = $this->ModelPs->getRequete($psgetrequete, $marque);
			$usage = $this->getBindParms('USAGE_ID,USAGE_DESC','veh_usage',' 1 ','USAGE_DESC ASC');
			$usage = $this->ModelPs->getRequete($psgetrequete, $usage);

			$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' 1 ','DESC_MODELE ASC');
			$modele = $this->ModelPs->getRequete($psgetrequete, $modele);
			$proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc ','proprietaire join users on proprietaire.PROPRIETAIRE_ID=users.PROPRIETAIRE_ID ',' 1  and users.USER_ID='.$USER_ID.'','proprio_desc ASC');

			$proprio=str_replace('\"', '"', $proprio);
			$proprio=str_replace('\n', '', $proprio);
			$proprio=str_replace('\"', '', $proprio);

			$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);

			$assureur = $this->getBindParms('`ID_ASSUREUR`, `ASSURANCE`', 'assureur', '1', '`ASSURANCE` ASC');
			$assureur = $this->ModelPs->getRequete($psgetrequete, $assureur);

			if(!empty($VEHICULE_ID))
			{
				$data['btn'] = "Modifier";
				$data['title'] = "Modification du véhicule";

				$vehicule = $this->Model->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,PROPRIETAIRE_ID,NUMERO_CHASSIS,USAGE_ID,ANNEE_FABRICATION,DATE_FIN_CONTROTECHNIK,DATE_FIN_ASSURANCE,DATE_DEBUT_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,FILE_CONTRO_TECHNIQUE,FILE_ASSURANCE,ID_ASSUREUR FROM vehicule WHERE md5(VEHICULE_ID)='".$VEHICULE_ID."'");

				// if(empty($vehicule))
				// {
				// 	redirect(base_url('Login/logout'));
				// }

				$marque = $this->getBindParms('ID_MARQUE,DESC_MARQUE','vehicule_marque',' 1 ','DESC_MARQUE ASC');
				$marque = $this->ModelPs->getRequete($psgetrequete, $marque);

				$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' 1 ','DESC_MODELE ASC');
				$modele = $this->ModelPs->getRequete($psgetrequete, $modele);
				$usage = $this->getBindParms('USAGE_ID,USAGE_DESC','veh_usage',' 1 ','USAGE_DESC ASC');
				$usage = $this->ModelPs->getRequete($psgetrequete, $usage);


				// $proprio = $this->getBindParms('PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire',' 1 ','proprio_desc ASC');
				$proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc ','proprietaire join users on proprietaire.PROPRIETAIRE_ID=users.PROPRIETAIRE_ID ',' 1  and users.USER_ID='.$USER_ID.'','proprio_desc ASC');

				$proprio=str_replace('\"', '"', $proprio);
				$proprio=str_replace('\n', '', $proprio);
				$proprio=str_replace('\"', '', $proprio);

				$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);
				

			}

			$data['vehicule'] = $vehicule;
			$data['marque'] = $marque;
			$data['modele'] = $modele;
			$data['usage'] = $usage;
			$data['proprio'] = $proprio;
			$data['assureur'] = $assureur;


			$this->load->view('Vehicule_add_View',$data);
		}

       //Selection des noms des vehicules
		function get_modele($ID_MARQUE)
		{
			$html="<option value=''>-- Séléctionner --</option>";

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			$modele = $this->getBindParms('ID_MODELE ,DESC_MODELE','vehicule_modele',' ID_MARQUE='.$ID_MARQUE.'','DESC_MODELE ASC');
			$modele = $this->ModelPs->getRequete($psgetrequete, $modele);

			// $modele = $this->Model->getRequete("SELECT ID_MODELE ,DESC_MODELE FROM vehicule_modele WHERE ID_MARQUE=".$ID_MARQUE." ORDER BY DESC_MODELE ASC");

			foreach ($modele as $modele)
			{
				$html.="<option value='".$modele['ID_MODELE']."'>".$modele['DESC_MODELE']."</option>";
			}
			echo json_encode($html);
		}

		   //PERMET L'UPLOAD DE L'IMAGE
		public function upload_file($input_name)
		{
			$nom_file = $_FILES[$input_name]['tmp_name'];
			$nom_champ = $_FILES[$input_name]['name'];
			$ext=pathinfo($nom_champ, PATHINFO_EXTENSION);
			$repertoire_fichier = FCPATH . 'upload/photo_vehicule/';
			$code=uniqid();
			$name=$code. 'VEHICULE.' .$ext;
			$file_link = $repertoire_fichier . $name;

			if (!is_dir($repertoire_fichier)) {
				mkdir($repertoire_fichier, 0777, TRUE);
			}
			move_uploaded_file($nom_file, $file_link);
			return $name;
		}

        //Verification des donnees uniques pour les vehicules

		// function check_existe($VEHICULE_ID=0,$CODE="",$PLAQUE="")
		// {
		// 	print_r($PLAQUE);die();
		// }

		//Fonction pour l'enregistrement de données dans la base de données ainsi que la mise à jour
		function save()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			if(empty($VEHICULE_ID))   //Controle d'enregistrement
			{

				// $this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

				$this->form_validation->set_rules('ID_ASSUREUR','ID_ASSUREUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ID_MODELE','ID_MODELE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

				$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('KILOMETRAGE','KILOMETRAGE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('USAGE_ID','USAGE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('NUMERO_CHASSIS','NUMERO_CHASSIS','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('ANNEE_FABRICATION','ANNEE_FABRICATION','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('DATE_DEBUT_CONTROTECHNIK','DATE_DEBUT_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_CONTROTECHNIK','DATE_FIN_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_DEBUT_ASSURANCE','DATE_DEBUT_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_ASSURANCE','DATE_FIN_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				if (empty($_FILES['PHOTO_OUT']['name']))
				{
					$this->form_validation->set_rules('PHOTO_OUT','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				if (empty($_FILES['FILE_CONTRO_TECHNIQUE']['name']))
				{
					$this->form_validation->set_rules('FILE_CONTRO_TECHNIQUE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}if (empty($_FILES['FILE_ASSURANCE']['name']))
				{
					$this->form_validation->set_rules('FILE_ASSURANCE','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}
				

				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$PHOTO = $this->upload_file('PHOTO_OUT');
					$file_controtechnik = $this->upload_file('FILE_CONTRO_TECHNIQUE');
					$file_assurance = $this->upload_file('FILE_ASSURANCE');

					$data = array(
						'ID_ASSUREUR'=>$this->input->post('ID_ASSUREUR'),
						'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
						'ID_MODELE'=>$this->input->post('ID_MODELE'),
						'PLAQUE'=>$this->input->post('PLAQUE'),
						'COULEUR'=>$this->input->post('COULEUR'),
						'KILOMETRAGE'=>$this->input->post('KILOMETRAGE'),
						'PHOTO'=>$PHOTO,
						'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
						'USAGE_ID'=>$this->input->post('USAGE_ID'),
						'NUMERO_CHASSIS'=>$this->input->post('NUMERO_CHASSIS'),
						'ANNEE_FABRICATION'=>$this->input->post('ANNEE_FABRICATION'),
						'DATE_DEBUT_ASSURANCE'=>$this->input->post('DATE_DEBUT_ASSURANCE'),
						'DATE_FIN_ASSURANCE'=>$this->input->post('DATE_FIN_ASSURANCE'),
						'DATE_DEBUT_CONTROTECHNIK'=>$this->input->post('DATE_DEBUT_CONTROTECHNIK'),
						'DATE_FIN_CONTROTECHNIK'=>$this->input->post('DATE_FIN_CONTROTECHNIK'),
						'FILE_ASSURANCE'=>$file_assurance,
						'FILE_CONTRO_TECHNIQUE'=>$file_controtechnik,
						
					);
					

					$table = "vehicule";

					$creation=$this->Model->create($table,$data);

					if ($creation)
					{
						$message['message']='<div class="alert alert-success text-center" id="message">Enregistrement du vehicule avec succès</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('proprietaire/Proprietaire_vehicule'));

					}else
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">Echec d\'enregistrement </div>';
						$this->session->set_flashdata($message);
						redirect(base_url('proprietaire/Proprietaire_vehicule'));

					}

				}


			}else // Controle de mise à jour
			{

				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ID_MODELE','ID_MODELE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				// $this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

				$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('KILOMETRAGE','KILOMETRAGE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('USAGE_ID','USAGE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('NUMERO_CHASSIS','NUMERO_CHASSIS','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));$this->form_validation->set_rules('ANNEE_FABRICATION','ANNEE_FABRICATION','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('DATE_DEBUT_CONTROTECHNIK','DATE_DEBUT_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_CONTROTECHNIK','DATE_FIN_CONTROTECHNIK','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_DEBUT_ASSURANCE','DATE_DEBUT_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));
				$this->form_validation->set_rules('DATE_FIN_ASSURANCE','DATE_FIN_ASSURANCE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));


				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$VEHICULE_ID = $this->input->post('VEHICULE_ID');

					// $check_existe = $this->Model->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,PROPRIETAIRE_ID FROM vehicule WHERE VEHICULE_ID != '".$VEHICULE_ID."'");

					$psgetrequete = "CALL `getRequete`(?,?,?,?);";

					// $check_existe = $this->getBindParms('VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,PROPRIETAIRE_ID','vehicule',' VEHICULE_ID !='.$VEHICULE_ID.' and CODE='.$this->input->post('CODE').'','VEHICULE_ID ASC');
					// $check_existe1 = $this->ModelPs->getRequete($psgetrequete, $check_existe);
					$check_existe_plak = $this->getBindParms('VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,PROPRIETAIRE_ID','vehicule',' VEHICULE_ID !='.$VEHICULE_ID.' and PLAQUE="'.$this->input->post('PLAQUE').'"','VEHICULE_ID ASC');
					$check_existe_plak=str_replace('\"', '"', $check_existe_plak);
					$check_existe_plak=str_replace('\n', '', $check_existe_plak);
					$check_existe_plak=str_replace('\"', '', $check_existe_plak);
					$check_existe_plak1 = $this->ModelPs->getRequete($psgetrequete, $check_existe_plak);
					 // print_r($check_existe1);die();
					// if(!empty($check_existe1) )
					// {
					// 	$message['message']='<div class="alert alert-danger text-center" id="message">le code existe déjà !</div>';
					// 	$this->session->set_flashdata($message);
					// 	redirect(base_url('proprietaire/Proprietaire_vehicule/ajouter'));
					// }
					if(!empty($check_existe_plak1))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le plaque existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('proprietaire/Proprietaire_vehicule/ajouter'));
					}
					else
					{
						if (!empty($_FILES["PHOTO_OUT"]["tmp_name"])) {
							$PHOTO=$this->upload_file('PHOTO_OUT');
						}else{
							$PHOTO=$this->input->post('PHOTO');
						}
						$FILE_CONTRO_TECHNIQUE = $this->input->post('FILE_CONTRO_TECHNIQUE_OLD');
						if(empty($_FILES['FILE_CONTRO_TECHNIQUE']['name']))
						{
							$file_contro = $this->input->post('FILE_CONTRO_TECHNIQUE_OLD');
						}
						else
						{
							$file_contro = $this->upload_document($_FILES['FILE_CONTRO_TECHNIQUE']['tmp_name'],$_FILES['FILE_CONTRO_TECHNIQUE']['name']);
						}
						$FILE_ASSURANCE = $this->input->post('FILE_ASSURANCE_OLD');
						if(empty($_FILES['FILE_ASSURANCE']['name']))
						{
							$file_assurance = $this->input->post('FILE_ASSURANCE_OLD');
						}
						else
						{
							$file_assurance = $this->upload_document($_FILES['FILE_ASSURANCE']['tmp_name'],$_FILES['FILE_ASSURANCE']['name']);
						}

						$data=array
						(
							'CODE'=>$this->input->post('CODE'),
							'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
							'ID_MODELE'=>$this->input->post('ID_MODELE'),
							'PLAQUE'=>$this->input->post('PLAQUE'),
							'COULEUR'=>$this->input->post('COULEUR'),
							'KILOMETRAGE'=>$this->input->post('KILOMETRAGE'),
							'PHOTO'=>$PHOTO,
							'FILE_ASSURANCE'=>$file_assurance,
							'FILE_CONTRO_TECHNIQUE'=>$file_contro,

							'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
							'USAGE_ID'=>$this->input->post('USAGE_ID'),
							'NUMERO_CHASSIS'=>$this->input->post('NUMERO_CHASSIS'),
							'ANNEE_FABRICATION'=>$this->input->post('ANNEE_FABRICATION'),
							'DATE_DEBUT_ASSURANCE'=>$this->input->post('DATE_DEBUT_ASSURANCE'),
							'DATE_FIN_ASSURANCE'=>$this->input->post('DATE_FIN_ASSURANCE'),
							'DATE_DEBUT_CONTROTECHNIK'=>$this->input->post('DATE_DEBUT_CONTROTECHNIK'),
							'DATE_FIN_CONTROTECHNIK'=>$this->input->post('DATE_FIN_CONTROTECHNIK'),

						);

						$table = "vehicule";

						$update=$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

						if ($update)
						{
							$message['message']='<div class="alert alert-success text-center" id="message">Modification du vehicule avec succès</div>';
							$this->session->set_flashdata($message);
							redirect(base_url('proprietaire/Proprietaire_vehicule'));

						}else
						{
							$message['message']='<div class="alert alert-danger text-center" id="message">Echec de modification </div>';
							$this->session->set_flashdata($message);
							redirect(base_url('proprietaire/Proprietaire_vehicule'));

						}
						
					}

					

				}
			}

			
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