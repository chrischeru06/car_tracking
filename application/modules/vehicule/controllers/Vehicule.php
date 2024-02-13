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
				//redirect(base_url('Login/logout'));

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

			$critaire = '' ;

			$query_principal='SELECT VEHICULE_ID,CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,PHOTO,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID WHERE 1';


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

			$search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

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

				// $sub_array[]= "<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='120px' width='120px' ></a>";

				$sub_array[]=' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#proprio' . $row->VEHICULE_ID. '"><img " style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/proprietaire/photopassport/').$row->PHOTO_PASSPORT.'"></a></td><td> '.'     '.' ' . $row->desc_proprio . '</td></tr></tbody></a>';

				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE))."&nbsp;<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->VEHICULE_ID. "'><b class='text-center bi bi-eye'></b></a>";

				$option = '<div class="dropdown ">
				<a class="text-dark btn-sm" data-toggle="dropdown">
				<i class="bx bx-dots-vertical-rounded float-right"></i>
				
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-left">
				';

				$option .= "<li><a class='btn-md' href='" . base_url('vehicule/Vehicule/ajouter/'.md5($row->VEHICULE_ID)) . "'><label class='text-dark'><i class='fa fa-edit'>&nbsp;&nbsp;Modifier</i></label></a></li>";



				$option .="
				</div>
				<div class='modal fade' id='mypicture" .$row->VEHICULE_ID."' style='border-radius:40%;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:#f6f9ff;'>
                      <h5 class='modal-title'>Véhicule</h5>
                      <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
                    </div>
				<div class='modal-body'>

				<h4 class=''></h4>

				<div class='row'>

				<div class='col-md-6'>
				<img src = '".base_url('upload/photo_vehicule/'.$row->PHOTO)."' height='100%'  width='100%'  style= 'border-radius:5%;'>
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
				<td class='btn-sm'>propriétaire</td>
				<th class='btn-sm'><img src = '".base_url('upload/proprietaire/photopassport/'.$row->PHOTO_PASSPORT)."' height='5%'  width='10%'  style= 'border-radius:50%;'><strong>".$row->desc_proprio."</strong></th>
				</tr>

				</table>

				</div>
				</div>
				
				</div>
				</div>
				</div>";


				$option .="
				</div>
				<div class='modal fade' id='proprio" .$row->VEHICULE_ID."' style='border-radius:30%;'>
				<div class='modal-dialog modal-lg'>
				<div class='modal-content'>

				<div class='modal-header' style='background:#f6f9ff;'>
                      <h5 class='modal-title'>Propriétaire</h5>
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



       // Appel du formulaire d'enregistrement
		function ajouter()
		{
			$VEHICULE_ID = $this->uri->segment(4);
			$data['btn'] = "Enregistrer";
			$data['title']="Enregistrement du véhicule";

			$vehicule = array('VEHICULE_ID'=>NULL,'ID_MARQUE'=>NULL,'ID_MODELE'=>NULL,'CODE'=>NULL,'PLAQUE'=>NULL,'COULEUR'=>NULL,'PHOTO'=>NULL,'PROPRIETAIRE_ID'=>NULL);

			$marque = $this->Model->getRequete('SELECT ID_MARQUE,DESC_MARQUE FROM vehicule_marque WHERE 1 ORDER BY DESC_MARQUE ASC');

			$modele = $this->Model->getRequete("SELECT ID_MODELE ,DESC_MODELE FROM vehicule_modele WHERE 1 ORDER BY DESC_MODELE ASC");

			$proprio = $this->Model->getRequete('SELECT PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc FROM proprietaire WHERE 1 ORDER BY proprio_desc ASC');

			//print_r($proprio);die();

			if(!empty($VEHICULE_ID))
			{
				$data['btn'] = "Modifier";
				$data['title'] = "Modification du véhicule";

				$vehicule = $this->Model->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,COULEUR,PHOTO,PROPRIETAIRE_ID FROM vehicule WHERE md5(VEHICULE_ID)='".$VEHICULE_ID."'");

				if (empty($vehicule)) redirect(base_url('Login/logout'));

				$marque = $this->Model->getRequete("SELECT ID_MARQUE,DESC_MARQUE FROM vehicule_marque WHERE 1 ORDER BY DESC_MARQUE ASC");

				$modele = $this->Model->getRequete("SELECT ID_MODELE ,DESC_MODELE FROM vehicule_modele WHERE ID_MARQUE = ".$vehicule["ID_MARQUE"]." ORDER BY DESC_MODELE ASC");

				$proprio = $this->Model->getRequete('SELECT PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc FROM proprietaire WHERE 1 ORDER BY proprio_desc ASC');
			}

			$data['vehicule'] = $vehicule;
			$data['marque'] = $marque;
			$data['modele'] = $modele;
			$data['proprio'] = $proprio;


			$this->load->view('Vehicule_add_View',$data);
		}

       //Selection des noms des vehicules
		function get_modele($ID_MARQUE)
		{
			$html="<option value=''>-- Séléctionner --</option>";
			$modele = $this->Model->getRequete("SELECT ID_MODELE ,DESC_MODELE FROM vehicule_modele WHERE ID_MARQUE=".$ID_MARQUE." ORDER BY DESC_MODELE ASC");
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

				$this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ID_MODELE','ID_MODELE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

				$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				if (empty($_FILES['PHOTO_OUT']['name']))
				{
					$this->form_validation->set_rules('PHOTO_OUT','','trim|required',array('required'=>'<font style="color:red;font-size:14px;">Le champ est obligatoire</font>'));
				}

				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$PHOTO = $this->upload_file('PHOTO_OUT');
					$data = array
					(
						'CODE'=>$this->input->post('CODE'),
						'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
						'ID_MODELE'=>$this->input->post('ID_MODELE'),
						'PLAQUE'=>$this->input->post('PLAQUE'),
						'COULEUR'=>$this->input->post('COULEUR'),
						'PHOTO'=>$PHOTO,
						'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
					);

					$table = "vehicule";

					$creation=$this->Model->create($table,$data);

					if ($creation)
					{
						$message['message']='<div class="alert alert-success text-center" id="message">Enregistrement du vehicule avec succès</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule'));

					}else
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">Echec d\'enregistrement </div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule'));

					}

				}


			}else // Controle de mise à jour
			{

				// $this->form_validation->set_rules("CODE"," ","trim|required|is_unique[vehicule.CODE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le code existe déjà !</font>'));

				$this->form_validation->set_rules('ID_MARQUE','ID_MARQUE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('ID_MODELE','ID_MODELE','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				// $this->form_validation->set_rules("PLAQUE"," ","trim|required|is_unique[vehicule.PLAQUE]",array('required'=>'<font style="color:red;size:2px;">Le champ est obligatoire</font>', 'is_unique'=>'<font style="color:red;size:2px;">Le plaque existe déjà !</font>'));

				$this->form_validation->set_rules('COULEUR','COULEUR','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				$this->form_validation->set_rules('PROPRIETAIRE_ID','PROPRIETAIRE_ID','required',array('required'=>'<font style="color:red;">Le champ est obligatoire</font>'));

				if($this->form_validation->run() == FALSE)
				{
					$this->ajouter();
				}
				else
				{
					$VEHICULE_ID = $this->input->post('VEHICULE_ID');

					$check_existe = $this->Model->getRequeteOne("SELECT VEHICULE_ID,ID_MARQUE,ID_MODELE,CODE,PLAQUE,PROPRIETAIRE_ID FROM vehicule WHERE VEHICULE_ID != '".$VEHICULE_ID."'");

					if(!empty($check_existe) && $check_existe['CODE'] == $this->input->post('CODE'))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le code existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule/ajouter'));
					}
					else if(!empty($check_existe) && $check_existe['PLAQUE'] == $this->input->post('PLAQUE'))
					{
						$message['message']='<div class="alert alert-danger text-center" id="message">le plaque existe déjà !</div>';
						$this->session->set_flashdata($message);
						redirect(base_url('vehicule/Vehicule/ajouter'));
					}
					else
					{
						if (!empty($_FILES["PHOTO_OUT"]["tmp_name"])) {
							$PHOTO=$this->upload_file('PHOTO_OUT');
						}else{
							$PHOTO=$this->input->post('PHOTO');
						}

						$data=array
						(
							'CODE'=>$this->input->post('CODE'),
							'ID_MARQUE'=>$this->input->post('ID_MARQUE'),
							'ID_MODELE'=>$this->input->post('ID_MODELE'),
							'PLAQUE'=>$this->input->post('PLAQUE'),
							'COULEUR'=>$this->input->post('COULEUR'),
							'PHOTO'=>$PHOTO,
							'PROPRIETAIRE_ID'=>$this->input->post('PROPRIETAIRE_ID'),
						);

						$table = "vehicule";

						$update=$this->Model->update($table,array('VEHICULE_ID'=>$VEHICULE_ID),$data);

						if ($update)
						{
							$message['message']='<div class="alert alert-success text-center" id="message">Modification du vehicule avec succès</div>';
							$this->session->set_flashdata($message);
							redirect(base_url('vehicule/Vehicule'));

						}else
						{
							$message['message']='<div class="alert alert-danger text-center" id="message">Echec de modification </div>';
							$this->session->set_flashdata($message);
							redirect(base_url('vehicule/Vehicule'));

						}
						
					}

					

				}
			}

			
		}


		


	}


?>