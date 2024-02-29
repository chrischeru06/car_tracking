<?php


/**Fait par Nzosaba Santa Milka
 * santa.milka@mediabox.bi
 * 68071895
 * Le 28/2/2024
 * Gestion des Chauffeurs d'un proprietaire
 */
class Proprietaire_chauffeur extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->out_application();
		$this->load->helper('email');
	}

	//Fonction pour rediriger sur la page de connexion si une fois la session est perdue
	function out_application()
	{
		if(empty($this->session->userdata('USER_ID')))
		{
			redirect(base_url('Login/logout'));

		}
	}

	//Fonction pour l'affichage de la liste
	function index(){

		$this->load->view('Proprietaire_chauffeur_List_View');
	}

	//Fonction pour la liste
	function listing()
	{

		$USER_ID=$this->session->userdata('USER_ID');
		$get_user=$this->Model->getOne('users',array('USER_ID'=>$USER_ID));

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";
		$critaire = " AND proprietaire.PROPRIETAIRE_ID=".$get_user['PROPRIETAIRE_ID'];
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

		$query_principal='SELECT  chauffeur.CHAUFFEUR_ID,chauffeur.PHOTO_PASSPORT,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.FILE_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_INSERTION,chauffeur.IS_ACTIVE,chauffeur.STATUT_VEHICULE,chauffeur.DATE_NAISSANCE,proprietaire.PROPRIETAIRE_ID,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE FROM `chauffeur_vehicule` join chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID join vehicule on vehicule.CODE=chauffeur_vehicule.CODE join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID WHERE `STATUT_AFFECT`=1';

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
			$sub_array[] = ' <tbody><tr><td><a title=" " href="#"  data-toggle="modal" data-target="#mypicture' . $row->CHAUFFEUR_ID. '"><img alt="Avtar" style="border-radius:50%;width:30px;height:30px" src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->NOM . ' ' . $row->PRENOM . '</td></tr></tbody></a>
			
			</div>
			<div class="modal fade" id="mypicture' .$row->CHAUFFEUR_ID. '">
			<div class="modal-dialog modal-dialog-centered ">
			<div class="modal-content">
			<div class="modal-header" style="background:cadetblue;color:white;">
			<button type="button" class="btn btn-close text-light" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<img src = "'.base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT).'"" height="100%"  width="100%" >
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


			$option.= "<li><a class='btn-md' href='#' data-toggle='modal' data-target='#info_chauf" . $row->CHAUFFEUR_ID. "'><i class='bi bi-info-square h5' ></i>&nbsp;Détails</a></li>";


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

			
			$option .="
			</div>
			<div class='modal fade' tabindex='-1' data-bs-backdrop='false' id='info_chauf" .$row->CHAUFFEUR_ID. "'>
			<div class='modal-dialog modal-dialog-centered modal-lg'>

			<div class='modal-content'>
			<div class='modal-header' style='background:cadetblue;color:white;'>
			<h6 class='modal-title'>Détails du chauffeur ".$row->NOM." ".$row->PRENOM."</h6>
			<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
			</div>
			<div class='modal-body'>
			<div class='row'>
			<div class='col-md-6'>
			<img src = '".base_url('upload/chauffeur/'.$row->PHOTO_PASSPORT)."' height='100%'  width='100%' >
			</div>
			<div class='col-md-6'>

			<div class='table-responsive'>
			<table class= 'table table-borderless'>
			<tr>
			<td>Carte d'identité</td>
			<td><strong>".$row->NUMERO_CARTE_IDENTITE."</strong></td>
			</tr>

			<tr>
			<td>Email</td>
			<td><strong>".$row->ADRESSE_MAIL."</strong></td>
			</tr>

			<tr>
			<td>Téléphone</td>
			<td><strong>".$row->NUMERO_TELEPHONE."</strong></td>
			</tr>

			<tr>
			<td>Date naissance</td>
			<td><strong>".$row->DATE_NAISSANCE."</strong></td>
			</tr>

			<tr>
			<td>Adresse physique</td>
			<td><strong>".$row->ADRESSE_PHYSIQUE."</strong></td>
			</tr>
			<tr>
			<td>Localité</td>
			<td><strong>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME."</strong></td>
			</tr>

			<tr>
			<td>Information&nbsp;du&nbsp;vehicule</td>
			<td><a href='#' data-dismiss='modal' data-toggle='modal' data-target='#info_voitu" .$row->CHAUFFEUR_ID. "'><b class='text-primary bi bi-eye' style = 'margin-left:100px;'></b></a></td>
			</tr>
			</table>
			</div>
			</div>
			</div>
			</div>
			</div>
			</div>
			</div>";
			
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
				<div class='table-responsive'>

				<table class='table table-borderless'>

				<tr>
				<td>Marque
				<td><strong>".$info_vehicul['DESC_MARQUE']."</strong></td>
				</tr>

				<tr>
				<td>Modèle</td>
				<td><strong>".$info_vehicul['DESC_MODELE']."</strong></td>
				</tr>

				<tr>
				<td>Couleur</td>
				<td><strong>".$info_vehicul['COULEUR']."</strong></td>
				</tr>

				<tr>
				<td>Plaque</td>
				<td><strong>".$info_vehicul['PLAQUE']."</strong></th>
				</tr>
				</table>
				</div>
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

}






?>