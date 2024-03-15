<?php
/*
	Auteur    : NIYOMWUNGERE Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 06-09/02/2024
	crud des chauffeurs
*/
	class Chauffeur extends CI_Controller
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
			$this->load->view('Chauffeur_List_View');
		}


		//Fonction pour l'affichage
		function listing()
		{

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";
			$critaire = "";
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','chauffeur.NOM','chauffeur.PRENOM','chauffeur.ADRESSE_PHYSIQUE ','provinces.PROVINCE_NAME','communes.COMMUNE_NAME','zones.ZONE_NAME','collines.COLLINE_NAME','chauffeur.NUMERO_TELEPHONE','chauffeur.ADRESSE_MAIL','chauffeur.NUMERO_CARTE_IDENTITE','chauffeur.PERSONNE_CONTACT_TELEPHONE','chauffeur.DATE_INSERTION');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%"
				OR chauffeur.ADRESSE_PHYSIQUE LIKE "%' . $var_search . '%" 
				OR provinces.PROVINCE_NAME LIKE "%' . $var_search . '%" 
				OR communes.COMMUNE_NAME LIKE "%' . $var_search . '%"
				OR zones.ZONE_NAME  LIKE "%' . $var_search . '%"
				OR collines.COLLINE_NAME LIKE "%' . $var_search . '%"
				OR chauffeur.NUMERO_TELEPHONE LIKE "%' . $var_search . '%"
				OR chauffeur.ADRESSE_MAIL LIKE "%' . $var_search . '%"
				OR chauffeur.NUMERO_CARTE_IDENTITE LIKE "%' . $var_search . '%"
				OR chauffeur.DATE_INSERTION LIKE "%' . $var_search . '%")') : '';

			$query_principal='SELECT CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,chauffeur.FILE_PERMIS FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID  WHERE 1';

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
				$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->CHAUFFEUR_ID. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->NOM . ' ' . $row->PRENOM . '</td></tr></tbody></a>
			
				</div>
				<div class="modal fade" id="mypicture' .$row->CHAUFFEUR_ID. '">
              <div class="modal-dialog modal-dialog-centered ">
              <div class="modal-content">
              <div class="modal-header" style="background:cadetblue;color:white;">
              <button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <center><img src = "'.base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT).'"" height="50%"  width="50%" ></center>
              </div>
              </div>
              </div>
              </div>

				';


				//fin modal

				// $sub_array[] = $row->ADRESSE_PHYSIQUE;
				// $sub_array[] = $row->PROVINCE_NAME;
				// $sub_array[] = $row->COMMUNE_NAME;
				// $sub_array[] = $row->ZONE_NAME;
				// $sub_array[] = $row->COLLINE_NAME;
				$sub_array[] = $row->NUMERO_TELEPHONE;
				$sub_array[] = $row->ADRESSE_MAIL;

				$option = '<div class="dropdown ">
				<a class=" text-black btn-sm" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-left">
				';

				$option .= "<li><a class='btn-md' href='" . base_url('chauffeur/Chauffeur/getOne/'. $row->CHAUFFEUR_ID) . "'><span class='bi bi-pencil h5'></span>&nbsp;Modifier</a></li>";

				$option.= "<li><a class='btn-md' href='#' data-toggle='modal' data-target='#info_chauf" . $row->CHAUFFEUR_ID. "'><i class='bi bi-info-square h5' ></i>&nbsp;Détails</a></li>";


				if($row->STATUT_VEHICULE==1 && $row->IS_ACTIVE==1)
				{
					$option.='<li><a class="btn-md" onClick="attribue_voiture('.$row->CHAUFFEUR_ID.',\''.$row->NOM.'\',\''.$row->PRENOM.'\')"><i class="bi bi-plus h5" ></i>&nbsp;Affecter le chauffeur</a></li>';
					
				}
				if ($row->STATUT_VEHICULE==2 && $row->IS_ACTIVE==1)
					{
						$option .= "<li><a class='btn-md' data-toggle='modal' data-target='#modal_retirer" . $row->CHAUFFEUR_ID . "'><span class='bi bi-plus h5' ></span>&nbsp;Retirer&nbsp;voiture</a></li>";

						$option.='<li><a class="btn-md" onClick="modif_affectation(\''.$row->CHAUFFEUR_ID.'\')"><span class="bi bi-pencil h5"></span>&nbsp;&nbsp;Modifier affectation</a></li>';

					}
					if($row->IS_ACTIVE==1){
					$sub_array[]=' <form enctype="multipart/form-data" name="myform_check" id="myform_check" method="POST" class="form-horizontal">

					<input type = "hidden" value="'.$row->IS_ACTIVE.'" id="status">

					<table>
					<td><label class="text-primary">Activé</label></td>
					<td><label class="switch"> 
					<input type="checkbox" id="myCheck" onclick="myFunction_desactive(' . $row->CHAUFFEUR_ID . ','.$row->STATUT_VEHICULE.')" checked>
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
					<input type="checkbox" id="myCheck" onclick="myFunction(' . $row->CHAUFFEUR_ID . ')">
					<span class="slider round"></span>
					</label></td>
					</table>
					</form>

					';
				}


					//fin activer desactiver
					//DEBUT modal pour retirer la voiture
					$option .= " </ul>
					</div>
					 <div class='modal fade' id='modal_retirer" .$row->CHAUFFEUR_ID. "'>
					 <div class='modal-dialog modal-dialog-centered modal-lg'>
					 <div class='modal-content'>
					 <div class='modal-header' style='background:cadetblue;color:white;'>
					 <button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
					 </div>
					 <div class='modal-body'>
					 <center><h5>Voulez-vous retirer la voiture à <b>" . $row->NOM .' '.$row->PRENOM. " ? </b></h5></center>
					 <div class='modal-footer'>
					 <a class='btn btn-outline-danger rounded-pill' href='".base_url('chauffeur/Chauffeur/retirer_voit/'.$row->CHAUFFEUR_ID)."' >Retirer</a>
					 </div>
					 </div>
					 </div>
					 </div>
					 </div>";

					//fin modal retire voiture
					//debut Detail cahuffeur
					if ($row->STATUT_VEHICULE==2)
						{
							$option .="
							</div>
							<div class='modal fade' tabindex='-1' data-bs-backdrop='false' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
							<div class='modal-dialog modal-dialog-centered modal-lg'>

							<div class='modal-content'>
							<div class='modal-header' style='background:cadetblue;color:white;'>
							<h6 class='modal-title'>Détails du chauffeur&nbsp;&nbsp;" .$row->NOM." "." ".$row->PRENOM."</h6>
							<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
							</div>
							<div class='modal-body'>
							<div class='row'>
							<div class='col-md-4'>
							<img src = '".base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT)."' height='auto'  width='80%'  style= 'border-radius:20px;'>
							</div>
							<div class='col-md-8'>
							<div class='table-responsive'>
							<table class='table table-borderless'>
							<tr>
							<td><label class='fa fa-book'></label> Carte d'identité</td>
							<th>".$row->NUMERO_CARTE_IDENTITE."</th>
							</tr>

							<tr>
							<td><label class='fa fa-envelope-o '></label>  Email</td>
							<th>".$row->ADRESSE_MAIL."</th>
							</tr>
							<tr>
							<td><label class='fa fa-phone'></label> Téléphone</td>
							<th>".$row->NUMERO_TELEPHONE."</th>
							</tr>

							<tr>
							<td><label class='fa fa-calendar '></label> Date naissance</td>
							<th>".$row->DATE_NAISSANCE."</th>
							</tr>

							<tr>
							<td><label class='fa fa-map-marker'></label> Aresse physique</td>
							<th>".$row->ADRESSE_PHYSIQUE."</th>
							</tr>
							<tr>
							<td><label class='fa fa-map-marker'></label> Localité</td>
							<th>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME." </th>
							</tr>

							<tr>
							<td><label class='fa fa-info'></label>Information&nbsp;du&nbsp;vehicule</td>
							<th><a href='#' data-dismiss='modal' data-toggle='modal' data-target='#info_voitu" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a></th>
							</tr>
                        
							<tr>
							<td><strong>Documents</strong></td>
						   </tr>
							<tr>
							<td><label class='fa fa-book'></label> CNI</td>
							<td><a href='#' data-toggle='modal' data-target='#info_documa" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
							</td>
							</tr>
							<tr>
							<td><label class='fa fa-book'></label> PERMIS&nbsp;&nbsp;DE&nbsp;CONDUIRE</td>
							<td><a href='#'data-toggle='modal' data-target='#info_documa2" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
							</td>
							</tr>
							


							</table>
							</div>
							</div>
							</div>
							</div>
							</div>
							</div>
							</div>

						<div class='modal fade' id='info_documa" .$row->CHAUFFEUR_ID. "'>
						<div class='modal-dialog'>
						<div class='modal-content'>
						<div class='modal-header' style='background:cadetblue;color:white;'>
				        <h6 class='modal-title'>Carte d'identité</h6>
				        <button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			      	   </div>
						<div class='modal-body'>
						<div class='scroller'>
						<div class='table-responsive'>

					       <img src = '".base_url('upload/chauffeur/'.$row->FILE_CARTE_IDENTITE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					       </div>
					       </div>
						</div>
						</div>
						</div>
						</div>
						

                            
                        <div class='modal fade' id='info_documa2" .$row->CHAUFFEUR_ID. "'>
						<div class='modal-dialog'>
						<div class='modal-content'>
						<div class='modal-header' style='background:cadetblue;color:white;'>
				        <h6 class='modal-title'>Permis de conduire</h6>
				        <button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			      	   </div>
						<div class='modal-body'>
						<div class='scroller'>
						<div class='table-responsive'>

					       <img src = '".base_url('upload/chauffeur/'.$row->FILE_PERMIS)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					       </div>
					       </div>
						</div>
						</div>
						</div>
						</div>
							";
						}else
						{
							$option .="
							</div>
							<div class='modal fade' tabindex='-1' data-bs-backdrop='false' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
							<div class='modal-dialog modal-dialog-centered modal-lg'>

							<div class='modal-content'>
							<div class='modal-header' style='background:cadetblue;color:white;'>
							<h6 class='modal-title'>Détails du chauffeur:" .$row->NOM." "." ".$row->PRENOM."</h6>
							<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
							</div>
							<div class='modal-body'>
							<div class='row'>
							<div class='col-md-4'>
							<img src = '".base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT)."' height='auto'  width='80%'  style= 'border-radius:20px;'>
							</div>
							<div class='col-md-8'>
							<div class='table-responsive'>
							<table class='table table-borderless'>
							<tr>
							<td ><label class='fa fa-book'></label> Carte d'identité</td>
							<th> ".$row->NUMERO_CARTE_IDENTITE."</th>
							</tr>

							<tr>
							<td><label class='fa fa-envelope-o '></label> Email</td>
							<th>".$row->ADRESSE_MAIL."</th>
							</tr>
 
							<tr>
							<td><label class='fa fa-phone'></label> Téléphone</td>
							<th>".$row->NUMERO_TELEPHONE."</th>
							</tr>

							<tr>
							<td><label class='fa fa-calendar '></label> Date naissance</td>
							<th>".$row->DATE_NAISSANCE."</th>
							</tr>

							<tr>
							<td><label class='fa fa-map-marker'></label> Adresse physique</td>
							<th>".$row->ADRESSE_PHYSIQUE."</th>
							</tr>
							<tr>
							<td><label class='fa fa-map-marker'></label> Localité</td>
							<th>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME." </th>
							</tr>

							<tr>
							<td><strong>Documents</strong></td>
						   </tr>
							<tr>
							<td><label class='fa fa-book'></label> CNI</td>
							<td><a href='#'data-toggle='modal' data-target='#info_documa" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
							</td>
							</tr>
							<tr>
							<td><label class='fa fa-book'></label> PERMIS&nbsp;&nbsp;DE&nbsp;CONDUIRE</td>
							<td><a href='#'data-toggle='modal' data-target='#info_documa2" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a>
							</td>
							</tr>
							


							</table>
							</div>
							</div>
							</div>
							</div>
							</div>
							</div>
							</div>

						<div class='modal fade' id='info_documa" .$row->CHAUFFEUR_ID. "'>
						<div class='modal-dialog'>
						<div class='modal-content'>
						<div class='modal-header' style='background:cadetblue;color:white;'>
				        <h6 class='modal-title'> Carte d'identité</h6>
				        <button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			      	   </div>
						<div class='modal-body'>
						<div class='scroller'>
						<div class='table-responsive'>
					       <img src = '".base_url('upload/chauffeur/'.$row->FILE_CARTE_IDENTITE)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					       </div>
					       </div>
						</div>
						</div>
						</div>
						</div>
						

                            
                        <div class='modal fade' id='info_documa2" .$row->CHAUFFEUR_ID. "'>
						<div class='modal-dialog'>
						<div class='modal-content'>
						<div class='modal-header' style='background:cadetblue;color:white;'>
				        <h6 class='modal-title'>Permis de conduire</h6>
				        <button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			      	   </div>
						<div class='modal-body'>
						<div class='scroller'>
						<div class='table-responsive'>

					       <img src = '".base_url('upload/chauffeur/'.$row->FILE_PERMIS)."' height='100%'  width='100%'  style= 'border-radius:20px;'>
					      </div>
					      </div>
						</div>
						</div>
						</div>
						</div>
					



							";
						}
						//fin debut Detail cahuffeur
						$info_vehicul=$this->ModelPs->getRequeteOne('SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR FROM chauffeur_vehicule  join vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE WHERE chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CHAUFFEUR_ID='.$row->CHAUFFEUR_ID.'');
						//debut modal de info voiture(id=info_voitu)
						if (!empty($info_vehicul)) 
						{
							$option .="
						</div>
						<div class='modal fade' id='info_voitu" .$row->CHAUFFEUR_ID. "'>
						<div class='modal-dialog'>
						<div class='modal-content'>
						<div class='modal-header' style='background:cadetblue;color:white;'>
				      <h6 class='modal-title'>Détails du véhicule</h6>
				      <button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			      	</div>
						<div class='modal-body'>
						<div class='row'>
						<div class='col-md-6' >
						<img src = '".base_url('upload/photo_vehicule/').$info_vehicul['PHOTO']."' height='100%' width='100%' >
						</div>
						<div class='col-md-6'>
						<table class='table table-borderless table-hover text-dark'>

						<tr>
						<td>Marque
						<th>".$info_vehicul['DESC_MARQUE']."</th>
						</tr>

						<tr>
						<td>Modèle</td>
						<th>".$info_vehicul['DESC_MODELE']."</th>
						</tr>

						<tr>
						<td>Couleur</td>
						<th>".$info_vehicul['COULEUR']."</th>
						</tr>

						<tr>
						<td>Plaque</td>
						<th>".$info_vehicul['PLAQUE']."</th>
						</tr>
						</table>
						</div>
						</div>
						</div>
						
						</div>
						</div>
						</div>";
						}
						
						//fin modal de info voiture(id=info_voitu)
						$sub_array[]=$option;
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


	//Fonction pour ajouter les provinces,communes,zones et collines
	function ajouter()
	{
		$data['provinces'] = $this->Model->getRequete("SELECT `PROVINCE_ID`, `PROVINCE_NAME` FROM `provinces` WHERE 1 ORDER BY PROVINCE_NAME ASC");
		$data['communes'] = $this->Model->getRequete('SELECT COMMUNE_ID, COMMUNE_NAME FROM communes WHERE 1 ORDER BY COMMUNE_NAME ASC');
		$data['zones'] = $this->Model->getRequete('SELECT ZONE_ID ,ZONE_NAME,COMMUNE_ID FROM zones WHERE 1 ORDER BY ZONE_NAME ASC');
		$data['collines'] = $this->Model->getRequete('SELECT COLLINE_ID, COLLINE_NAME FROM collines WHERE 1 ORDER BY COLLINE_NAME ASC');
		$data['title'] = 'Nouveau chauffeur';
		$data['type_genre'] = $this->Model->getRequete('SELECT GENRE_ID, DESCR_GENRE FROM syst_genre WHERE 1 ORDER BY DESCR_GENRE ASC');
		// $data['ethnie'] = $this->Model->getRequete('SELECT ETHNIE_ID, DESCR_ETHNIE FROM syst_ethnie WHERE 1 ORDER BY DESCR_ETHNIE ASC');
		$this->load->view('Chauffeur_Add_View',$data);
	}

	//Fonction pour filter les communes
	function get_communes($ID_PROVINCE=0)
	{
		$communes = $this->Model->getRequete('SELECT `COMMUNE_ID`, `COMMUNE_NAME` FROM `communes` WHERE PROVINCE_ID='.$ID_PROVINCE.' ORDER BY COMMUNE_NAME ASC');
		$html='<option value="">---sélectionner---</option>';
		foreach ($communes as $key)
		{
			$html.='<option value="'.$key['COMMUNE_ID'].'">'.$key['COMMUNE_NAME'].'</option>';
		}
		echo json_encode($html);
	}

  	//Fonction pour filtrer les zones
	function get_zones($ID_COMMUNE=0)
	{
		$zones = $this->Model->getRequete('SELECT `ZONE_ID`, `ZONE_NAME` FROM `zones` WHERE COMMUNE_ID='.$ID_COMMUNE.' ORDER BY ZONE_NAME ASC');
		$html='<option value="">---sélectionner---</option>';
		foreach ($zones as $key)
		{
			$html.='<option value="'.$key['ZONE_ID'].'">'.$key['ZONE_NAME'].'</option>';
		}
		echo json_encode($html);
	}

  	//Fonction pour filtrer les collines
	function get_collines($ID_ZONE=0)
	{
		$collines = $this->Model->getRequete('SELECT `COLLINE_ID`, `COLLINE_NAME` FROM `collines` WHERE ZONE_ID='.$ID_ZONE.' ORDER BY COLLINE_NAME ASC');
		$html='<option value="">---sélectionner---</option>';
		foreach ($collines as $key)
		{
			$html.='<option value="'.$key['COLLINE_ID'].'">'.$key['COLLINE_NAME'].'</option>';
		}
		echo json_encode($html);
	}

	//Fonction pour le controle de la date de naissance

	function verif_date()
	{
		$DATE_NAISSANCE=$this->input->post('DATE_NAISSANCE');
		
		$aujourdhui = date("Y-m-d");

		$diff = date_diff(date_create($DATE_NAISSANCE), date_create($aujourdhui));
		$data = $diff->format('%y');

		echo json_encode($data);

	}
	// Recuperation des fichiers(pdf)
	public function upload_document($nom_file,$nom_champ)
	{
		$rep_doc =FCPATH.'upload/chauffeur/';
		$fichier=basename("piece".uniqid());
		$file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
		$file_extension = strtolower($file_extension);
		$valid_ext = array('pdf');
		if(!is_dir($rep_doc)) //crée un dossier s'il n'existe pas déja   
		{
			mkdir($rep_doc,0777,TRUE);
		}  
		move_uploaded_file($nom_file, $rep_doc.$fichier.".".$file_extension);
		$pathfile=$fichier.".".$file_extension;
		return $pathfile;
	}

    function get_all_voiture()
	{
		$all_voiture = $this->Model->getRequete("SELECT vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE,vehicule.PLAQUE,vehicule.CODE FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule.ID_MODELE=vehicule_modele.ID_MODELE WHERE 1 AND vehicule.STATUT=1");
		$html='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_voiture))
		{
			foreach($all_voiture as $key)
			{
				$html.='<option value="'.$key['CODE'].'">'.$key['DESC_MARQUE'].'-'.$key['DESC_MODELE'].'  /'.$key['PLAQUE'].' </option>';
			}
		}

	   $all_zone_affectation = $this->Model->getRequete("SELECT `CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION` FROM `chauffeur_zone_affectation` WHERE 1");

		$html1='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_zone_affectation))
		{
			foreach($all_zone_affectation as $key1)
			{
				$html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'">'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
			}
		}
		$ouput= array(
         'html'=>$html,
         'html1'=>$html1,
		);
		echo json_encode($ouput);
	}

	  function get_zone_affect($CHAUFFEUR_ID)
	{
	  $zone_affect=$this->ModelPs->getRequeteOne('SELECT chauffeur_zone_affectation.`CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION`,chauffeur.CHAUFFEUR_ID,chauffeur_vehicule.DATE_DEBUT_AFFECTATION,chauffeur_vehicule.DATE_FIN_AFFECTATION FROM `chauffeur_zone_affectation` join chauffeur_vehicule on chauffeur_zone_affectation.CHAUFF_ZONE_AFFECTATION_ID=chauffeur_vehicule.CHAUFF_ZONE_AFFECTATION_ID JOIN chauffeur on chauffeur_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID WHERE chauffeur.CHAUFFEUR_ID='.$CHAUFFEUR_ID);

	   // print_r($zone_affect);exit();
	   $all_zone_affectation = $this->Model->getRequete("SELECT `CHAUFF_ZONE_AFFECTATION_ID`,`DESCR_ZONE_AFFECTATION` FROM `chauffeur_zone_affectation` WHERE 1");

		$html1='<option value="">--- Sélectionner ----</option>';
		if(!empty($all_zone_affectation))
		{
			foreach($all_zone_affectation as $key1)
			{
				if ($key1['CHAUFF_ZONE_AFFECTATION_ID']==$zone_affect['CHAUFF_ZONE_AFFECTATION_ID']) 
				{
				 $html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'" selected>'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
				}else
				{
             $html1.='<option value="'.$key1['CHAUFF_ZONE_AFFECTATION_ID'].'">'.$key1['DESCR_ZONE_AFFECTATION'].'</option>';
				}
				
			}
		}
		$ouput=array(
			'htmldbut'=>$zone_affect['DATE_DEBUT_AFFECTATION'],
			'htmlfin'=>$zone_affect['DATE_FIN_AFFECTATION'],
			'html1'=>$html1,

		);

		
		echo json_encode($ouput);
	}
	
	function save_modif_chauff()
	{
		// $statut=1 attribution avec succes;
		// $statut=2:possedent une autre voiture qu'on l'a deja attribuée;
		// $statut=3: attribution echoue
		$statut=3;
		$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID_MOD');
		$CHAUFF_ZONE_AFFECTATION_ID = $this->input->post('CHAUFF_ZONE_AFFECTATION_ID_MOD');
		$DATE_DEBUT_AFFECTATION = $this->input->post('DATE_DEBUT_AFFECTATION_MOD');
		$DATE_FIN_AFFECTATION = $this->input->post('DATE_FIN_AFFECTATION_MOD');
	    $today = date('Y-m-d H:i:s');

		$result = $this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('CHAUFF_ZONE_AFFECTATION_ID'=>$CHAUFF_ZONE_AFFECTATION_ID,'DATE_DEBUT_AFFECTATION'=>$DATE_DEBUT_AFFECTATION,'DATE_FIN_AFFECTATION'=>$DATE_FIN_AFFECTATION,'DATE_INSERTION'=>$today));
	
		if($result==true )
		{
		 $statut=1;
		}else
		{
		  $statut=2;
	   }
		echo json_encode($statut);
	}
	

	function save_voiture()
	{
		// $statut=1 attribution avec succes;
		// $statut=2:possedent une autre voiture qu'on l'a deja attribuée;
		// $statut=3: attribution echoue
		$statut=3;
		// $CODE= $this->input->post('code_vehicule');
		$CODE = $this->input->post('VEHICULE_ID');
		$CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');
		$CHAUFF_ZONE_AFFECTATION_ID = $this->input->post('CHAUFF_ZONE_AFFECTATION_ID');
		$DATE_DEBUT_AFFECTATION = $this->input->post('DATE_DEBUT_AFFECTATION');
		$DATE_FIN_AFFECTATION = $this->input->post('DATE_FIN_AFFECTATION');



		$data = array('CODE'=>$CODE,'CHAUFFEUR_ID'=>$CHAUFFEUR_ID,'CHAUFF_ZONE_AFFECTATION_ID'=>$CHAUFF_ZONE_AFFECTATION_ID,'DATE_DEBUT_AFFECTATION'=>$DATE_DEBUT_AFFECTATION,'DATE_FIN_AFFECTATION'=>$DATE_FIN_AFFECTATION,'STATUT_AFFECT'=>1);

		$CHAUFFEUR_VEH = $this->Model->create('chauffeur_vehicule',$data);
		$result = $this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('STATUT_VEHICULE'=>2));
		$result = $this->Model->update('vehicule',array('CODE'=>$CODE),array('STATUT'=>2));
		
		if($result==true )
		{
		 $statut=1;
		}else
		{
		  $statut=2;
	   }
		echo json_encode($statut);
	}

	function retirer_voiture()
	{
		$statut=2;

		echo json_encode($statut);
	}


		//fonction pour retirer la voiture
	public function retirer_voit($CHAUFFEUR_ID)
	{

		$chauf_v = $this->Model->getOne('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID));
		//print($chauf['CHAUFFEUR_ID']);exit();
		
		$this->Model->update('chauffeur',array('CHAUFFEUR_ID'=>$chauf_v['CHAUFFEUR_ID']),array('STATUT_VEHICULE'=>1));

		$this->Model->update('vehicule',array('CODE'=>$chauf_v['CODE']),array('STATUT'=>1));
		// $today = date('Y-m-d H:s');
		$this->Model->update('chauffeur_vehicule',array('CHAUFFEUR_ID'=>$chauf_v['CHAUFFEUR_ID']),array('STATUT_AFFECT'=>2));

		
		$data['message'] = '<div class="alert alert-success text-center" id="message">' . " Vous avez bien retiré la voiture" . '</div>';
		$this->session->set_flashdata($data);
		redirect(base_url('chauffeur/Chauffeur'));

		
	}
	//Fonction pour activer/desactiver un proprietaire
    function active_desactive($status,$CHAUFFEUR_ID)
    {
    	if($status==1){
    		$this->Model->update('chauffeur', array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('IS_ACTIVE'=>2));

    	}else if($status==2){
    		$this->Model->update('chauffeur', array('CHAUFFEUR_ID'=>$CHAUFFEUR_ID),array('IS_ACTIVE'=>1));
    	}

    	echo json_encode(array('status'=>$status));
    }

  	//Fonction pour inserer dans la BD
	function add()
	{
		$table ='chauffeur';
		$this->form_validation->set_rules('nom','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('prenom','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('adresse_physique','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('numero_telephone','','trim|required|is_unique[chauffeur.numero_telephone]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le téléphone doit être unique</font>'));
    	// Gestion numero carte d'identite doit etre unique
		$this->form_validation->set_rules('NUMERO_CARTE_IDENTITE','','trim|required|is_unique[chauffeur.NUMERO_CARTE_IDENTITE]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le numéro doit être unique</font>'));
			// Gestion nmail qui doit etre unique
		$this->form_validation->set_rules('adresse_email','','trim|required|is_unique[chauffeur.ADRESSE_MAIL]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le mail doit être unique</font>'));
		$this->form_validation->set_rules('CONFIRMATION_EMAIL','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('personne_contact_telephone','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('NUMERO_PERMIS','','trim|required|is_unique[chauffeur.NUMERO_PERMIS]',array('required'=>'<font style="color:red;font-size:15px;">Le champs est obligatoire</font>','is_unique'=>'<font style="color:red;font-size:15px;">*Le permis doit être unique</font>'));
		$this->form_validation->set_rules('PROVINCE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('COMMUNE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('ZONE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$this->form_validation->set_rules('COLLINE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));


		$this->form_validation->set_rules('date_naissance','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('date_expiration','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('GENRE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		if(!isset($_FILES['fichier_carte_identite']) || empty($_FILES['fichier_carte_identite']['name']))
		{
			$this->form_validation->set_rules('fichier_carte_identite',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		}

		if(!isset($_FILES['photo_passport']) || empty($_FILES['photo_passport']['name']))
		{
			$this->form_validation->set_rules('photo_passport',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		}
		if(!isset($_FILES['file_permis']) || empty($_FILES['file_permis']['name']))
		{
			$this->form_validation->set_rules('file_permis',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->ajouter();
		}
		else
		{
			$adresse_email = $this->input->post('adresse_email');
			$data_insert = array(
				'NOM' => $this->input->post('nom'),
				'PRENOM' => $this->input->post('prenom'),
				'ADRESSE_PHYSIQUE' => $this->input->post('adresse_physique'),
				'ADRESSE_MAIL' => $adresse_email,
				'NUMERO_TELEPHONE' => $this->input->post('numero_telephone'),
				'NUMERO_CARTE_IDENTITE' => $this->input->post('NUMERO_CARTE_IDENTITE'),
				'PERSONNE_CONTACT_TELEPHONE' => $this->input->post('personne_contact_telephone'),
				'NUMERO_PERMIS' => $this->input->post('NUMERO_PERMIS'),
				'FILE_CARTE_IDENTITE' => $this->upload_document($_FILES['fichier_carte_identite']['tmp_name'],$_FILES['fichier_carte_identite']['name']),

				'FILE_PERMIS' => $this->upload_document($_FILES['file_permis']['tmp_name'],$_FILES['file_permis']['name']),
				'PHOTO_PASSPORT' => $this->upload_document($_FILES['photo_passport']['tmp_name'],$_FILES['photo_passport']['name']),

				'PROVINCE_ID' => $this->input->post('PROVINCE_ID'),
				'COMMUNE_ID' => $this->input->post('COMMUNE_ID'),
				'ZONE_ID' => $this->input->post('ZONE_ID'),
				'COLLINE_ID' => $this->input->post('COLLINE_ID'),
				'DATE_NAISSANCE' => $this->input->post('date_naissance'),
				 'DATE_EXPIRATION_PERMIS' => $this->input->post('date_expiration'),
				'GENRE_ID' => $this->input->post('GENRE_ID')
			);
			
			$inser = $this->Model->create($table,$data_insert);
			// if($CHAUFFEUR_ID>0)
			// {
				if($inser)
				{
					
					
					$data['message']='<div class="alert alert-success text-center" id="message">Ajout effectuer avec succès</div>';
					$this->session->set_flashdata($data);
					redirect(base_url('chauffeur/Chauffeur/index'));
				}
				else
				{
					$this->load->view('Chauffeur_Add_View',$data);
				}
			//}
			// else
			// {
			// 	$data['message']='<div class="alert alert-success text-center" id="message">Ajout n\'est pas faite avec succès</div>';
			// 	$this->session->set_flashdata($data);
			// 	redirect(base_url('agent/Agent/index'));
			// }
		}
	}

		//Fonction pour recuperer une ligne 
	function getOne($id)
	{
		$membre = $this->Model->getRequeteOne('SELECT CHAUFFEUR_ID, NOM, PRENOM, ADRESSE_PHYSIQUE, NUMERO_TELEPHONE, ADRESSE_MAIL, NUMERO_CARTE_IDENTITE, FILE_CARTE_IDENTITE, NUMERO_PERMIS,file_permis,GENRE_ID, PERSONNE_CONTACT_TELEPHONE, PROVINCE_ID, COMMUNE_ID, ZONE_ID, COLLINE_ID,PHOTO_PASSPORT FROM chauffeur WHERE CHAUFFEUR_ID='.$id);
		$data['membre'] = $membre;
		$data['provinces'] = $this->Model->getRequete('SELECT PROVINCE_ID, PROVINCE_NAME FROM provinces WHERE 1 ORDER BY PROVINCE_NAME ASC');
		$data['communes'] = $this->Model->getRequete('SELECT COMMUNE_ID, COMMUNE_NAME, PROVINCE_ID FROM communes WHERE  PROVINCE_ID='.$membre['PROVINCE_ID'].' ORDER BY COMMUNE_NAME ASC');
		$data['zones'] = $this->Model->getRequete('SELECT ZONE_ID ,ZONE_NAME,COMMUNE_ID FROM zones WHERE COMMUNE_ID='.$membre['COMMUNE_ID'].' ORDER BY ZONE_NAME ASC');
		$data['collines'] = $this->Model->getRequete('SELECT COLLINE_ID, COLLINE_NAME FROM collines WHERE ZONE_ID='.$membre['ZONE_ID'].' ORDER BY COLLINE_NAME ASC');
		$data['title'] = "Modification d'un chauffeur";
		$this->load->view('Chauffeur_Update_View',$data);
	}

	function update()
	{
		$id = $this->input->post('CHAUFFEUR_ID');
		//print_r($id);exit();

		$this->form_validation->set_rules('NOM','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('PRENOM','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('ADRESSE_PHYSIQUE','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('NUMERO_TELEPHONE','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('NUMERO_CARTE_IDENTITE','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('PROVINCE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('COMMUNE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('ZONE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));
		$this->form_validation->set_rules('COLLINE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">Le champ est Obligatoire</font>'));

		$FILE_CARTE_IDENTITE = $this->input->post('FILE_CARTE_IDENTITE_OLD');
		if(empty($_FILES['FILE_CARTE_IDENTITE']['name']))
		{
			$file = $this->input->post('FILE_CARTE_IDENTITE_OLD');	
		}
		else
		{
			$file = $this->upload_document($_FILES['FILE_CARTE_IDENTITE']['tmp_name'],$_FILES['FILE_CARTE_IDENTITE']['name']);
		}

		$file_permis = $this->input->post('file_permis_OLD');
		if(empty($_FILES['file_permis']['name']))
		{
			$file2 = $this->input->post('file_permis_OLD');
		}
		else
		{
			$file2 = $this->upload_document($_FILES['file_permis']['tmp_name'],$_FILES['file_permis']['name']);
		}

		$PHOTO_PASSPORT = $this->input->post('PHOTO_PASSPORT_OLD');
		if(empty($_FILES['PHOTO_PASSPORT']['name']))
		{
			$file3 = $this->input->post('PHOTO_PASSPORT_OLD');
		}
		else
		{
			$file3 = $this->upload_document($_FILES['PHOTO_PASSPORT']['tmp_name'],$_FILES['PHOTO_PASSPORT']['name']);
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->getOne($id);
		}
		else
		{
			$Array = array(
				'NOM' => $this->input->post('NOM'),
				'PRENOM' => $this->input->post('PRENOM'),
				'ADRESSE_PHYSIQUE' => $this->input->post('ADRESSE_PHYSIQUE'),
				'NUMERO_TELEPHONE' => $this->input->post('NUMERO_TELEPHONE'),
				'NUMERO_CARTE_IDENTITE' => $this->input->post('NUMERO_CARTE_IDENTITE'),
				'PERSONNE_CONTACT_TELEPHONE' => $this->input->post('PERSONNE_CONTACT_TELEPHONE'),
				'ADRESSE_MAIL' => $this->input->post('ADRESSE_MAIL'),
				'FILE_CARTE_IDENTITE' => $file,
				'PHOTO_PASSPORT' => $file2,
				'PROVINCE_ID' => $this->input->post('PROVINCE_ID'),
				'COMMUNE_ID' => $this->input->post('COMMUNE_ID'),
				'ZONE_ID' => $this->input->post('ZONE_ID'),
				'COLLINE_ID' => $this->input->post('COLLINE_ID'),
				'PHOTO_PASSPORT' => $file3
			);
			$this->Model->update('chauffeur', array('CHAUFFEUR_ID' => $id), $Array);
			$datas['message'] = '<div class="alert alert-success text-center" id="message">La modification s\'est faite avec succès</div>';
			$this->session->set_flashdata($datas);
			redirect(base_url('chauffeur/Chauffeur/index'));
		}
	}
	//Fonction pour le detail du chauffeur
	function detail_chauffeur($CHAUFFEUR_ID)
	{
		$query_principal=$this->Model->getRequeteOne('SELECT vehicule.VEHICULE_ID,vehicule_marque.DESC_MARQUE,vehicule_nom.DESC_MODELE,vehicule.PLAQUE,vehicule.COULEUR,vehicule.PHOTO FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_nom ON vehicule_nom.ID_NOM=vehicule.ID_NOM JOIN agent_card ON vehicule.CODE=agent_card.CARD_UID JOIN chauffeur ON agent_card.CODE_AGENT=chauffeur.CODE_AGENT WHERE chauffeur.CHAUFFEUR_ID ='.$CHAUFFEUR_ID);
     // print_r($query_principal)

		$fichier = base_url().'upload/photo_vehicule/'.$query_principal['PHOTO'].'';

		$div_info = '<img src="'.$fichier.'" height="100%"  width="100%"  style= "border-radius:50%;" />';
		$output = array(

			"DESC_MODELE" =>$query_principal['DESC_MODELE'] ,
			"PLAQUE" =>$query_principal['PLAQUE'] ,
			"COULEUR" =>$query_principal['COULEUR'] ,
			"PHOTO" =>$div_info ,

		);
		echo json_encode($output);


	}



	}
?>