<?php
 /*
	Auteur    : NIYOMWUNGERE Ella Dancilla
	Email     : ella_dancilla@mediabox.bi
	Telephone : +25771379943
	Date      : 03-05/09/2024
	crud de l'etat du vehicule 
 */
	class Sortie_Vehicule extends CI_Controller
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

		//la fonction index visualise la liste
		function index()
		{
			
			$this->load->view('Sortie_Vehicule_List_View');
		}


	    //Liste demandes en attente de validation
		function listing()
		{

			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL = $this->session->userdata('PROFIL_ID');
			$IS_VALIDATED = $this->input->post('IS_VALIDATED');
			$critaire = "";

			if($PROFIL == 3) //Si c'est le chuffeur
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}
			else if($PROFIL == 4) //Si c'est le Gestionnaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE = '.$get_user['ID_GESTIONNAIRE_VEHICULE'];
				}
			}
			else if($PROFIL == 2) //Si c'est le propriétaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$get_user['PROPRIETAIRE_ID'];
				}
			}

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'sortie_vehicule.ID_SORTIE DESC DESC';
			}
			else
			{
				$order_by = ' ORDER BY sortie_vehicule.ID_SORTIE DESC';
			}

			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
			)') : '';

			$query_principal='SELECT `ID_SORTIE`,vehicule.VEHICULE_ID,vehicule.PHOTO,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule,chauffeur.CHAUFFEUR_ID,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) as desc_chauf,chauffeur.PHOTO_PASSPORT,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,sortie_vehicule.IMAGE_AVANT,sortie_vehicule.IMAGE_ARRIERE,sortie_vehicule.IMAGE_LATERALE_GAUCHE,sortie_vehicule.IMAGE_LATERALE_DROITE,sortie_vehicule.IMAGE_TABLEAU_DE_BORD,sortie_vehicule.IMAGE_SIEGE_AVANT,sortie_vehicule.IMAGE_SIEGE_ARRIERE,`IS_VALIDATED`,IS_EXCHANGE FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID = chauffeur.CHAUFFEUR_ID join vehicule on sortie_vehicule.VEHICULE_ID = vehicule.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE join proprietaire on proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID JOIN gestionnaire_vehicule ON gestionnaire_vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP = motif_deplacement.ID_MOTIF_DEP join users on chauffeur.CHAUFFEUR_ID = users.CHAUFFEUR_ID WHERE 1 AND sortie_vehicule.IS_VALIDATED = '.$IS_VALIDATED.'';


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

				
				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('chauffeur/Chauffeur_New/Detail/'.md5($row->CHAUFFEUR_ID)).'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_chauf . '</td></tr></tbody></a>';

				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID.'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/photo_vehicule/').$row->PHOTO.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_vehicule . '</td></tr></tbody></a>';

				$source = base_url('upload/user.png');

				$sub_array[]='<tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px;" src="' . $source . '"></a></td><td>' . $row->AUTEUR_COURSE . '</td></tr></tbody></a>';

				$sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;				
				$sub_array[] = $row->DESC_MOTIF;
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/photo_vehicule/'.$row->PHOTO_KILOMETAGE) : base_url('upload/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/photo_vehicule/'.$row->PHOTO_CARBURANT) : base_url('upload/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				if($row->IS_EXCHANGE == 1)
				{
					$sub_array[] = '<center>Non</center>';
				}
				else if($row->IS_EXCHANGE == 2)
				{
					$sub_array[] = "<center><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_info_supp" . $row->ID_SORTIE . "'>Oui &nbsp; <font class='fa fa-eye' id='eye'></font></a></center>";
				}

				$source_img_avant = !empty($row->IMAGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_AVANT) : base_url('upload/user.png');

				$source_img_arriere = !empty($row->IMAGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_ARRIERE) : base_url('upload/user.png');

				$source_img_lat_gauche = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/user.png');
				
				$source_img_lat_droite = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/user.png');
				
				$source_img_tableau = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/photo_vehicule/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/user.png');

				$source_img_sige_avant = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/user.png');

				$source_img_sige_arriere = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/user.png');

				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if($PROFIL == 3) // Si c'est le chauffeur
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';

						$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";

						$option .= "<li class='btn-md'><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_supp" . $row->ID_SORTIE . "'><span class='fa fa-minus h5' ></span>&nbsp;&nbsp;&nbsp;Supprimer</a></li>";
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

						$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";
					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}
				else if($PROFIL == 4) // Si c'est le gestionnaire
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';

						$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_traiter')."</li></a>";
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}
				else // Si c'est l'admin ou le proprietaire
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}

				

				
				
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous variment supprimer cette demande ?</h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('etat_vehicule/Sortie_Vehicule/delete/'.$row->ID_SORTIE)."' >Supprimer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";

				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_info_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5>Images d'etat du véhicule</h5>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<center>
				<table class='table table-borderless'>

				<tr>
				<td>Image&nbsp;avant</td>
				<td><a title='" . $source_img_avant ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_avant."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_avant ."'></a></td>
				</tr>

				<tr>
				<td>Image&nbsp;arrière</td>
				<td><a title='" . $source_img_arriere ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_arriere."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_arriere ."'></a></td>
				</tr>

				<tr>
				<td>Latérale&nbsp;gauche</td>
				<td><a title='" . $source_img_lat_gauche ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_lat_gauche."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_lat_gauche ."'></a></td>
				</tr>

				<tr>
				<td>Latérale&nbsp;droite</td>
				<td><a title='" . $source_img_lat_droite ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_lat_droite."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_lat_droite ."'></a></td>
				</tr>

				<tr>
				<td>Tableau&nbsp;de&nbsp;bord</td>
				<td><a title='" . $source_img_tableau ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_tableau."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_tableau ."'></a></td>
				</tr>

				<tr>
				<td>Siège&nbsp;avant</td>
				<td><a title='" . $source_img_sige_avant ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_sige_avant."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_sige_avant ."'></a></td>
				</tr>

				<tr>
				<td>Siège&nbsp;arrière</td>
				<td><a title='" . $source_img_sige_arriere ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_sige_arriere."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_sige_arriere ."'></a></td>
				</tr>

				</table>
				</center>

				<div class='modal-footer'>

				<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
				</div>
				</div>
				</div>
				</div>
				</div>";



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

        //Liste demandes validée
		function listing2()
		{

			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL = $this->session->userdata('PROFIL_ID');
			$critaire = "";

			if($PROFIL == 3) //Si c'est le chuffeur
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}
			else if($PROFIL == 4) //Si c'est le Gestionnaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE = '.$get_user['ID_GESTIONNAIRE_VEHICULE'];
				}
			}
			else if($PROFIL == 2) //Si c'est le propriétaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$get_user['PROPRIETAIRE_ID'];
				}
			}

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'sortie_vehicule.ID_SORTIE DESC DESC';
			}
			else
			{
				$order_by = ' ORDER BY sortie_vehicule.ID_SORTIE DESC';
			}

			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
			)') : '';

			$query_principal='SELECT `ID_SORTIE`,vehicule.VEHICULE_ID,vehicule.PHOTO,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule,chauffeur.CHAUFFEUR_ID,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) as desc_chauf,chauffeur.PHOTO_PASSPORT,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,sortie_vehicule.IMAGE_AVANT,sortie_vehicule.IMAGE_ARRIERE,sortie_vehicule.IMAGE_LATERALE_GAUCHE,sortie_vehicule.IMAGE_LATERALE_DROITE,sortie_vehicule.IMAGE_TABLEAU_DE_BORD,sortie_vehicule.IMAGE_SIEGE_AVANT,sortie_vehicule.IMAGE_SIEGE_ARRIERE,`IS_VALIDATED`,IS_EXCHANGE FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID = chauffeur.CHAUFFEUR_ID join vehicule on sortie_vehicule.VEHICULE_ID = vehicule.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE join proprietaire on proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID JOIN gestionnaire_vehicule ON gestionnaire_vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP = motif_deplacement.ID_MOTIF_DEP join users on chauffeur.CHAUFFEUR_ID = users.CHAUFFEUR_ID WHERE 1';


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

				
				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('chauffeur/Chauffeur_New/Detail/'.md5($row->CHAUFFEUR_ID)).'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_chauf . '</td></tr></tbody></a>';

				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID.'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/photo_vehicule/').$row->PHOTO.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_vehicule . '</td></tr></tbody></a>';

				$source = base_url('upload/user.png');

				$sub_array[]='<tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px;" src="' . $source . '"></a></td><td>' . $row->AUTEUR_COURSE . '</td></tr></tbody></a>';

				$sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;				
				$sub_array[] = $row->DESC_MOTIF;
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/photo_vehicule/'.$row->PHOTO_KILOMETAGE) : base_url('upload/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/photo_vehicule/'.$row->PHOTO_CARBURANT) : base_url('upload/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				if($row->IS_EXCHANGE == 1)
				{
					$sub_array[] = '<center>Non</center>';
				}
				else if($row->IS_EXCHANGE == 2)
				{
					$sub_array[] = "<center><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_info_supp" . $row->ID_SORTIE . "'>Oui &nbsp; <font class='fa fa-eye' id='eye'></font></a></center>";
				}

				$source_img_avant = !empty($row->IMAGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_AVANT) : base_url('upload/user.png');

				$source_img_arriere = !empty($row->IMAGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_ARRIERE) : base_url('upload/user.png');

				$source_img_lat_gauche = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/user.png');
				
				$source_img_lat_droite = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/user.png');
				
				$source_img_tableau = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/photo_vehicule/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/user.png');

				$source_img_sige_avant = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/user.png');

				$source_img_sige_arriere = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/user.png');

				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if($PROFIL == 3) // Si c'est le chauffeur
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';

						$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";

						$option .= "<li class='btn-md'><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_supp" . $row->ID_SORTIE . "'><span class='fa fa-minus h5' ></span>&nbsp;&nbsp;&nbsp;Supprimer</a></li>";
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

						$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";
					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}
				else if($PROFIL == 4) // Si c'est le gestionnaire
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';

						$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_traiter')."</li></a>";
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}
				else // Si c'est l'admin ou le proprietaire
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}

				

				
				
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous variment supprimer cette demande ?</h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('etat_vehicule/Sortie_Vehicule/delete/'.$row->ID_SORTIE)."' >Supprimer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";

				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_info_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5>Images d'etat du véhicule</h5>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<center>
				<table class='table table-borderless'>

				<tr>
				<td>Image&nbsp;avant</td>
				<td><a title='" . $source_img_avant ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_avant."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_avant ."'></a></td>
				</tr>

				<tr>
				<td>Image&nbsp;arrière</td>
				<td><a title='" . $source_img_arriere ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_arriere."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_arriere ."'></a></td>
				</tr>

				<tr>
				<td>Latérale&nbsp;gauche</td>
				<td><a title='" . $source_img_lat_gauche ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_lat_gauche."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_lat_gauche ."'></a></td>
				</tr>

				<tr>
				<td>Latérale&nbsp;droite</td>
				<td><a title='" . $source_img_lat_droite ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_lat_droite."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_lat_droite ."'></a></td>
				</tr>

				<tr>
				<td>Tableau&nbsp;de&nbsp;bord</td>
				<td><a title='" . $source_img_tableau ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_tableau."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_tableau ."'></a></td>
				</tr>

				<tr>
				<td>Siège&nbsp;avant</td>
				<td><a title='" . $source_img_sige_avant ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_sige_avant."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_sige_avant ."'></a></td>
				</tr>

				<tr>
				<td>Siège&nbsp;arrière</td>
				<td><a title='" . $source_img_sige_arriere ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_sige_arriere."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_sige_arriere ."'></a></td>
				</tr>

				</table>
				</center>

				<div class='modal-footer'>

				<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
				</div>
				</div>
				</div>
				</div>
				</div>";



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


		//Liste demandes validée
		function listing3()
		{

			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL = $this->session->userdata('PROFIL_ID');
			$critaire = "";

			if($PROFIL == 3) //Si c'est le chuffeur
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}
			else if($PROFIL == 4) //Si c'est le Gestionnaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND gestionnaire_vehicule.ID_GESTIONNAIRE_VEHICULE = '.$get_user['ID_GESTIONNAIRE_VEHICULE'];
				}
			}
			else if($PROFIL == 2) //Si c'est le propriétaire
			{
				$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

				if(!empty($get_gestionnaire) && !empty($get_user))
				{
					$critaire.= ' AND proprietaire.PROPRIETAIRE_ID = '.$get_user['PROPRIETAIRE_ID'];
				}
			}

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'sortie_vehicule.ID_SORTIE DESC DESC';
			}
			else
			{
				$order_by = ' ORDER BY sortie_vehicule.ID_SORTIE DESC';
			}

			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
			)') : '';

			$query_principal='SELECT `ID_SORTIE`,vehicule.VEHICULE_ID,vehicule.PHOTO,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule,chauffeur.CHAUFFEUR_ID,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) as desc_chauf,chauffeur.PHOTO_PASSPORT,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,sortie_vehicule.IMAGE_AVANT,sortie_vehicule.IMAGE_ARRIERE,sortie_vehicule.IMAGE_LATERALE_GAUCHE,sortie_vehicule.IMAGE_LATERALE_DROITE,sortie_vehicule.IMAGE_TABLEAU_DE_BORD,sortie_vehicule.IMAGE_SIEGE_AVANT,sortie_vehicule.IMAGE_SIEGE_ARRIERE,`IS_VALIDATED`,IS_EXCHANGE FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID = chauffeur.CHAUFFEUR_ID join vehicule on sortie_vehicule.VEHICULE_ID = vehicule.VEHICULE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE join proprietaire on proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID JOIN gestionnaire_vehicule ON gestionnaire_vehicule.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP = motif_deplacement.ID_MOTIF_DEP join users on chauffeur.CHAUFFEUR_ID = users.CHAUFFEUR_ID WHERE 1';


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

				
				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('chauffeur/Chauffeur_New/Detail/'.md5($row->CHAUFFEUR_ID)).'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/chauffeur/').$row->PHOTO_PASSPORT.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_chauf . '</td></tr></tbody></a>';

				$sub_array[] = ' <tbody><tr><td><a title=" " href='.base_url('vehicule/Vehicule/get_detail_vehicule/').$row->VEHICULE_ID.'><img alt="Avtar" style="border-radius:50%;width:30px;height:30px " src="'.base_url('upload/photo_vehicule/').$row->PHOTO.'"></a></td><td> '.' &nbsp;&nbsp;&nbsp;&nbsp   '.' ' . $row->desc_vehicule . '</td></tr></tbody></a>';

				$source = base_url('upload/user.png');

				$sub_array[]='<tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px;" src="' . $source . '"></a></td><td>' . $row->AUTEUR_COURSE . '</td></tr></tbody></a>';

				$sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;				
				$sub_array[] = $row->DESC_MOTIF;
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/photo_vehicule/'.$row->PHOTO_KILOMETAGE) : base_url('upload/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/photo_vehicule/'.$row->PHOTO_CARBURANT) : base_url('upload/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
				href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				if($row->IS_EXCHANGE == 1)
				{
					$sub_array[] = '<center>Non</center>';
				}
				else if($row->IS_EXCHANGE == 2)
				{
					$sub_array[] = "<center><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_info_supp" . $row->ID_SORTIE . "'>Oui &nbsp; <font class='fa fa-eye' id='eye'></font></a></center>";
				}

				$source_img_avant = !empty($row->IMAGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_AVANT) : base_url('upload/user.png');

				$source_img_arriere = !empty($row->IMAGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_ARRIERE) : base_url('upload/user.png');

				$source_img_lat_gauche = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/user.png');
				
				$source_img_lat_droite = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/user.png');
				
				$source_img_tableau = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/photo_vehicule/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/user.png');

				$source_img_sige_avant = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/user.png');

				$source_img_sige_arriere = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/user.png');

				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if($PROFIL == 3) // Si c'est le chauffeur
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';

						$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";

						$option .= "<li class='btn-md'><a class='btn-md' href='#' data-toggle='modal' data-target='#modal_supp" . $row->ID_SORTIE . "'><span class='fa fa-minus h5' ></span>&nbsp;&nbsp;&nbsp;Supprimer</a></li>";
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

						$option .= "<li class='btn-md'>
						<a class='btn-md' href='" . base_url('etat_vehicule/Sortie_Vehicule/getOne/'. $row->ID_SORTIE) . "'><i class='bi bi-pencil h5'></i>&nbsp;&nbsp;".lang('btn_modifier')."</a>
						</li>";
					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}
				else if($PROFIL == 4) // Si c'est le gestionnaire
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';

						$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;".lang('btn_traiter')."</li></a>";
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}
				else // Si c'est l'admin ou le proprietaire
				{
					if($row->IS_VALIDATED == 0)
					{
						$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';
					}
					else if($row->IS_VALIDATED == 1)
					{
						$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
					}
					else if($row->IS_VALIDATED == 2)
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

					}

					$option .= "<a class='btn-md' id='' href='#' onclick='get_historique(" . $row->ID_SORTIE . ")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='fa fa-history'></i>&nbsp;&nbsp;&nbsp;&nbsp;Historique</li></a>";
				}

				

				
				
				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>
				<center><h5>Voulez-vous variment supprimer cette demande ?</h5></center>
				<div class='modal-footer'>
				<a class='btn btn-outline-danger rounded-pill' href='".base_url('etat_vehicule/Sortie_Vehicule/delete/'.$row->ID_SORTIE)."' >Supprimer</a>
				</div>
				</div>
				</div>
				</div>
				</div>";

				$option .= " </ul>
				</div>
				<div class='modal fade' id='modal_info_supp" .$row->ID_SORTIE. "'>
				<div class='modal-dialog modal-dialog-centered modal-md'>
				<div class='modal-content'>
				<div class='modal-header' style='background:cadetblue;color:white;'>
				<h5>Images d'etat du véhicule</h5>
				<button type='button' class='btn btn-close text-light' data-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body'>

				<center>
				<table class='table table-borderless'>

				<tr>
				<td>Image&nbsp;avant</td>
				<td><a title='" . $source_img_avant ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_avant."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_avant ."'></a></td>
				</tr>

				<tr>
				<td>Image&nbsp;arrière</td>
				<td><a title='" . $source_img_arriere ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_arriere."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_arriere ."'></a></td>
				</tr>

				<tr>
				<td>Latérale&nbsp;gauche</td>
				<td><a title='" . $source_img_lat_gauche ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_lat_gauche."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_lat_gauche ."'></a></td>
				</tr>

				<tr>
				<td>Latérale&nbsp;droite</td>
				<td><a title='" . $source_img_lat_droite ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_lat_droite."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_lat_droite ."'></a></td>
				</tr>

				<tr>
				<td>Tableau&nbsp;de&nbsp;bord</td>
				<td><a title='" . $source_img_tableau ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_tableau."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_tableau ."'></a></td>
				</tr>

				<tr>
				<td>Siège&nbsp;avant</td>
				<td><a title='" . $source_img_sige_avant ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_sige_avant."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_sige_avant ."'></a></td>
				</tr>

				<tr>
				<td>Siège&nbsp;arrière</td>
				<td><a title='" . $source_img_sige_arriere ."' data-gallery='photoviewer' data-title='' data-group='a'
				href='".$source_img_sige_arriere."' ><img alt='Avtar' style='border-radius:50%;width:30px;height:30px ;' src='" . $source_img_sige_arriere ."'></a></td>
				</tr>

				</table>
				</center>

				<div class='modal-footer'>

				<button type='button' class='btn btn-outline-warning rounded-pill' data-dismiss='modal' aria-label='Close'><font class='fa fa-close'></font> ".lang('modal_btn_quitter')."</button>
				</div>
				</div>
				</div>
				</div>
				</div>";



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

		//Fonction pour ajouter les chauffeur,vehicule et motif deplacement
		function ajouter()
		{
			$data['title'] = 'Enrégistrement sortie véhicule';

			$data['chauffeuri'] = $this->Model->getRequete('SELECT CHAUFFEUR_ID, CONCAT(`NOM`," ",`PRENOM`)  AS chauffeur_desc FROM chauffeur WHERE 1 ORDER BY NOM ASC');

			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');

			$critaire = '';

			$get_chauffeur = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			if(!empty($get_chauffeur))
			{
				$get_user = $this->Model->getRequeteOne('SELECT users.USER_ID FROM users JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID JOIN chauffeur ON chauffeur.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID WHERE chauffeur.CHAUFFEUR_ID = '.$get_chauffeur['CHAUFFEUR_ID'].'');

				if(!empty($get_user))
				{
					$critaire.= ' AND users.USER_ID = '.$get_user['USER_ID'];
				}

				$vehicule = $this->Model->getRequete('SELECT vehicule.VEHICULE_ID,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire.' ORDER BY desc_vehicule ASC');
			}

			$data['motif'] = $this->Model->getRequete('SELECT `ID_MOTIF_DEP`,`DESC_MOTIF` FROM `motif_deplacement` WHERE 1 ORDER BY DESC_MOTIF ASC');

			$data['vehiculee'] = $vehicule;

			$this->load->view('Sortie_Vehicule_Add_View',$data);
		}


		// Recuperation des fichiers(pdf)
		public function upload_document($nom_file,$nom_champ)
		{
			$rep_doc =FCPATH.'upload/photo_vehicule/';
			$fichier=uniqid();
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


  	//Fonction pour inserer dans la BD
	function add()
	{
		$this->form_validation->set_rules('VEHICULE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('AUTEUR_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DESTINATION','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_DEPART','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_ESTIMATIVE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$this->form_validation->set_rules('ID_MOTIF_DEP','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DATE_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));


		if(!isset($_FILES['photo_kilometrage']) || empty($_FILES['photo_kilometrage']['name']))
		{
			$this->form_validation->set_rules('photo_kilometrage',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		if(!isset($_FILES['carburant']) || empty($_FILES['carburant']['name']))
		{
			$this->form_validation->set_rules('carburant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		}

		// if(!isset($_FILES['photo_avant']) || empty($_FILES['photo_avant']['name']))
		// {
		// 	$this->form_validation->set_rules('photo_avant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }
		// if(!isset($_FILES['photo_arriere']) || empty($_FILES['photo_arriere']['name']))
		// {
		// 	$this->form_validation->set_rules('photo_arriere',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }
		// if(!isset($_FILES['photolateral_gauche']) || empty($_FILES['photolateral_gauche']['name']))
		// {
		// 	$this->form_validation->set_rules('photolateral_gauche',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }
		// if(!isset($_FILES['photo_lat_droite']) || empty($_FILES['photo_lat_droite']['name']))
		// {
		// 	$this->form_validation->set_rules('photo_lat_droite',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }

		// if(!isset($_FILES['photo_tableau']) || empty($_FILES['photo_tableau']['name']))
		// {
		// 	$this->form_validation->set_rules('photo_tableau',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }
		// if(!isset($_FILES['siege_avant']) || empty($_FILES['siege_avant']['name']))
		// {
		// 	$this->form_validation->set_rules('siege_avant',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }
		// if(!isset($_FILES['siege_arriere']) || empty($_FILES['siege_arriere']['name']))
		// {
		// 	$this->form_validation->set_rules('siege_arriere',' ', 'trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		// }


		if($this->form_validation->run() == FALSE)
		{
			$this->ajouter();
		}
		else
		{
			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID=$this->session->userdata('PROFIL_ID');

			$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			$CHAUFFEUR_ID = $get_user['CHAUFFEUR_ID'];

			if(!empty($_FILES['photo_kilometrage']['name']))
			{
				$PHOTO_KILOMETAGE = $this->upload_document($_FILES['photo_kilometrage']['tmp_name'],$_FILES['photo_kilometrage']['name']);
			}else{
				$PHOTO_KILOMETAGE = null;
			}

			if(!empty($_FILES['carburant']['name']))
			{
				$PHOTO_CARBURANT = $this->upload_document($_FILES['carburant']['tmp_name'],$_FILES['carburant']['name']);
			}else{
				$PHOTO_CARBURANT = null;
			}

			if(!empty($_FILES['photo_avant']['name']))
			{
				$IMAGE_AVANT = $this->upload_document($_FILES['photo_avant']['tmp_name'],$_FILES['photo_avant']['name']);
			}else{
				$IMAGE_AVANT = null;
			}

			if(!empty($_FILES['photo_arriere']['name']))
			{
				$IMAGE_ARRIERE = $this->upload_document($_FILES['photo_arriere']['tmp_name'],$_FILES['photo_arriere']['name']);
			}else{
				$IMAGE_ARRIERE = null;
			}

			if(!empty($_FILES['photolateral_gauche']['name']))
			{
				$IMAGE_LATERALE_GAUCHE = $this->upload_document($_FILES['photolateral_gauche']['tmp_name'],$_FILES['photolateral_gauche']['name']);
			}else{
				$IMAGE_LATERALE_GAUCHE = null;
			}

			if(!empty($_FILES['photo_lat_droite']['name']))
			{
				$IMAGE_LATERALE_DROITE = $this->upload_document($_FILES['photo_lat_droite']['tmp_name'],$_FILES['photo_lat_droite']['name']);
			}else{
				$IMAGE_LATERALE_DROITE = null;
			}

			if(!empty($_FILES['photo_tableau']['name']))
			{
				$IMAGE_TABLEAU_DE_BORD = $this->upload_document($_FILES['photo_tableau']['tmp_name'],$_FILES['photo_tableau']['name']);
			}else{
				$IMAGE_TABLEAU_DE_BORD = null;
			}

			if(!empty($_FILES['siege_avant']['name']))
			{
				$IMAGE_SIEGE_AVANT = $this->upload_document($_FILES['siege_avant']['tmp_name'],$_FILES['siege_avant']['name']);
			}else{
				$IMAGE_SIEGE_AVANT = null;
			}

			if(!empty($_FILES['siege_arriere']['name']))
			{
				$IMAGE_SIEGE_ARRIERE = $this->upload_document($_FILES['siege_arriere']['tmp_name'],$_FILES['siege_arriere']['name']);
			}else{
				$IMAGE_SIEGE_ARRIERE = null;
			}



			$data_insert = array(
				'VEHICULE_ID' => $this->input->post('VEHICULE_ID'),
				'CHAUFFEUR_ID' => $CHAUFFEUR_ID,
				'AUTEUR_COURSE' => $this->input->post('AUTEUR_COURSE'),
				'DESTINATION' => $this->input->post('DESTINATION'),
				'HEURE_DEPART' => $this->input->post('HEURE_DEPART'),
				'HEURE_ESTIMATIVE_RETOUR'=> $this->input->post('HEURE_ESTIMATIVE_RETOUR'),
				'ID_MOTIF_DEP' => $this->input->post('ID_MOTIF_DEP'),
				'DATE_COURSE' => $this->input->post('DATE_COURSE'),

				'PHOTO_KILOMETAGE' => $PHOTO_KILOMETAGE,
				'PHOTO_CARBURANT' => $PHOTO_CARBURANT,

				'IS_EXCHANGE' => $this->input->post('IS_EXCHANGE'),

				'IMAGE_AVANT' => $IMAGE_AVANT,
				'IMAGE_ARRIERE' => $IMAGE_ARRIERE,
				'IMAGE_LATERALE_GAUCHE' => $IMAGE_LATERALE_GAUCHE,
				'IMAGE_LATERALE_DROITE' => $IMAGE_LATERALE_DROITE,
				'IMAGE_TABLEAU_DE_BORD' => $IMAGE_TABLEAU_DE_BORD,
				'IMAGE_SIEGE_AVANT' => $IMAGE_SIEGE_AVANT,
				'IMAGE_SIEGE_ARRIERE' => $IMAGE_SIEGE_ARRIERE,

			);

			// print_r($data_insert);die();

			$table ='sortie_vehicule';
			$insert=$this->Model->create($table,$data_insert);


			if($insert)
			{
				$data['message']='<div class="alert alert-success text-center" id="message">'.lang('msg_enreg_ft_success').'</div>';
				$this->session->set_flashdata($data);
				redirect(base_url('etat_vehicule/Sortie_Vehicule'));
			}
			else
			{
				$this->load->view('Retour_Vehicule_Add_View',$data);
			}

		}
	}

	function delete($id)
	{
		$table='sortie_vehicule';
		$criteres['ID_SORTIE']=$id;
		$detete=$this->Model->delete($table,$criteres);
		echo json_encode($detete);
		if($detete)
		{
			$data['message']='<div class="alert alert-success text-center" id="message">La Suppression effectuée avec succès</div>';
			$this->session->set_flashdata($data);
			redirect(base_url('etat_vehicule/Sortie_Vehicule'));
		}
	}

		//Fonction pour recuperer une ligne 
	function getOne($id)
	{
		$data['title'] = "Modification de la sortie du véhicule";

		$membre = $this->Model->getRequeteOne('SELECT `ID_SORTIE`,`VEHICULE_ID`,`CHAUFFEUR_ID`,AUTEUR_COURSE,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,`ID_MOTIF_DEP`,`DATE_COURSE`,IS_EXCHANGE,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`DATE_SAVE`,`COMMENTAIRE`,`IS_VALIDATED` FROM `sortie_vehicule` WHERE  ID_SORTIE='.$id);
		$data['membre'] = $membre;

		$data['chauffeuri'] = $this->Model->getRequete('SELECT CHAUFFEUR_ID, CONCAT(`NOM`," ",`PRENOM`)  AS chauffeur_desc FROM chauffeur WHERE 1 ORDER BY NOM ASC');

		$USER_ID = $this->session->userdata('USER_ID');
		$PROFIL_ID = $this->session->userdata('PROFIL_ID');

		$critaire = '';

		$get_chauffeur = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

		if(!empty($get_chauffeur))
		{
			$get_user = $this->Model->getRequeteOne('SELECT users.USER_ID FROM users JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID JOIN chauffeur ON chauffeur.PROPRIETAIRE_ID = proprietaire.PROPRIETAIRE_ID WHERE chauffeur.CHAUFFEUR_ID = '.$get_chauffeur['CHAUFFEUR_ID'].'');

			if(!empty($get_user))
			{
				$critaire.= ' AND users.USER_ID = '.$get_user['USER_ID'];
			}

			$vehicule = $this->Model->getRequete('SELECT vehicule.VEHICULE_ID,CONCAT(DESC_MARQUE," - ",DESC_MODELE," - ",PLAQUE) as desc_vehicule FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID  WHERE 1 '.$critaire.' ORDER BY desc_vehicule ASC');
		}

		$data['motif'] = $this->Model->getRequete('SELECT `ID_MOTIF_DEP`,`DESC_MOTIF` FROM `motif_deplacement` WHERE 1 ORDER BY DESC_MOTIF ASC');

		$data['vehiculee'] = $vehicule;

		$this->load->view('Sortie_Vehicule_Update_View',$data);
	}

	function update()
	{
		$id = $this->input->post('ID_SORTIE');
		//print_r($id);exit();

		$this->form_validation->set_rules('VEHICULE_ID','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('AUTEUR_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DESTINATION','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_DEPART','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('HEURE_ESTIMATIVE_RETOUR','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));

		$this->form_validation->set_rules('ID_MOTIF_DEP','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>'));
		$this->form_validation->set_rules('DATE_COURSE','','trim|required',array('required'=>'<font style="color:red;size:2px;">'.lang('msg_validation').'</font>')); 


		if(empty($_FILES['PHOTO_CARBURANT']['name']))
		{
			$PHOTO_CARBURANT = $this->input->post('PHOTO_CARBURANT_OLD');	
		}
		else
		{
			$PHOTO_CARBURANT = $this->upload_document($_FILES['PHOTO_CARBURANT']['tmp_name'],$_FILES['PHOTO_CARBURANT']['name']);
		}

		if(empty($_FILES['PHOTO_KILOMETAGE']['name']))
		{
			$PHOTO_KILOMETAGE = $this->input->post('PHOTO_KILOMETAGE_OLD');
		}
		else
		{
			$PHOTO_KILOMETAGE = $this->upload_document($_FILES['PHOTO_KILOMETAGE']['tmp_name'],$_FILES['PHOTO_KILOMETAGE']['name']);
		}

		$IS_EXCHANGE = $this->input->post('IS_EXCHANGE');

		if($IS_EXCHANGE == 2)
		{
			if(empty($_FILES['IMAGE_SIEGE_ARRIERE']['name']))
			{
				$IMAGE_SIEGE_ARRIERE = $this->input->post('IMAGE_SIEGE_ARRIERE_OLD');
			}
			else
			{
				$IMAGE_SIEGE_ARRIERE = $this->upload_document($_FILES['IMAGE_SIEGE_ARRIERE']['tmp_name'],$_FILES['IMAGE_SIEGE_ARRIERE']['name']);
			}
		}
		else
		{
			$IMAGE_SIEGE_ARRIERE = null;
		}


		if($IS_EXCHANGE == 2)
		{
			$IMAGE_SIEGE_AVANT = $this->input->post('IMAGE_SIEGE_AVANT_OLD');
			if(empty($_FILES['IMAGE_SIEGE_AVANT']['name']))
			{
				$IMAGE_SIEGE_AVANT = $this->input->post('IMAGE_SIEGE_AVANT_OLD');
			}
			else
			{
				$IMAGE_SIEGE_AVANT = $this->upload_document($_FILES['IMAGE_SIEGE_AVANT']['tmp_name'],$_FILES['IMAGE_SIEGE_AVANT']['name']);
			}
		}
		else
		{
			$IMAGE_SIEGE_AVANT = null;
		}


		if($IS_EXCHANGE == 2)
		{

			if(empty($_FILES['IMAGE_TABLEAU_DE_BORD']['name']))
			{
				$IMAGE_TABLEAU_DE_BORD = $this->input->post('IMAGE_TABLEAU_DE_BORD_OLD');
			}
			else
			{
				$IMAGE_TABLEAU_DE_BORD = $this->upload_document($_FILES['IMAGE_TABLEAU_DE_BORD']['tmp_name'],$_FILES['IMAGE_TABLEAU_DE_BORD']['name']);
			}
		}
		else
		{
			$IMAGE_TABLEAU_DE_BORD = null;
		}


		if($IS_EXCHANGE == 2)
		{
			if(empty($_FILES['IMAGE_LATERALE_DROITE']['name']))
			{
				$IMAGE_LATERALE_DROITE = $this->input->post('IMAGE_LATERALE_DROITE_OLD');
			}
			else
			{
				$IMAGE_LATERALE_DROITE = $this->upload_document($_FILES['IMAGE_LATERALE_DROITE']['tmp_name'],$_FILES['IMAGE_LATERALE_DROITE']['name']);
			}
		}
		else
		{
			$IMAGE_LATERALE_DROITE = null;
		}

		if($IS_EXCHANGE == 2)
		{
			if(empty($_FILES['IMAGE_LATERALE_GAUCHE']['name']))
			{
				$IMAGE_LATERALE_GAUCHE = $this->input->post('IMAGE_LATERALE_GAUCHE_OLD');
			}
			else
			{
				$IMAGE_LATERALE_GAUCHE = $this->upload_document($_FILES['IMAGE_LATERALE_GAUCHE']['tmp_name'],$_FILES['IMAGE_LATERALE_GAUCHE']['name']);
			}
		}
		else
		{
			$IMAGE_LATERALE_GAUCHE = null;
		}

		if($IS_EXCHANGE == 2)
		{
			if(empty($_FILES['IMAGE_ARRIERE']['name']))
			{
				$IMAGE_ARRIERE = $this->input->post('IMAGE_ARRIERE_OLD');
			}
			else
			{
				$IMAGE_ARRIERE = $this->upload_document($_FILES['IMAGE_ARRIERE']['tmp_name'],$_FILES['IMAGE_ARRIERE']['name']);
			}
		}
		else
		{
			$IMAGE_ARRIERE = null;
		}

		if($IS_EXCHANGE == 2)
		{
			if(empty($_FILES['IMAGE_AVANT']['name']))
			{
				$IMAGE_AVANT = $this->input->post('IMAGE_AVANT_OLD');
			}
			else
			{
				$IMAGE_AVANT = $this->upload_document($_FILES['IMAGE_AVANT']['tmp_name'],$_FILES['IMAGE_AVANT']['name']);
			}
		}
		else
		{
			$IMAGE_AVANT = null;
		}


		if($this->form_validation->run() == FALSE)
		{
			$this->getOne($id);
		}
		else
		{
			$USER_ID = $this->session->userdata('USER_ID');
			$PROFIL_ID=$this->session->userdata('PROFIL_ID');

			$get_user = $this->Model->getOne('users',array('USER_ID'=>$USER_ID));

			$CHAUFFEUR_ID = $get_user['CHAUFFEUR_ID'];

			$stat = $this->Model->getRequeteOne('SELECT IS_VALIDATED FROM sortie_vehicule WHERE  ID_SORTIE = '.$id.' AND CHAUFFEUR_ID = '.$CHAUFFEUR_ID.'');

			if ($stat['IS_VALIDATED'] != 1 )  // Si la demande n'est pas validée
			{
				$Array = array(
					'VEHICULE_ID' => $this->input->post('VEHICULE_ID'),
					'CHAUFFEUR_ID' => $CHAUFFEUR_ID,
					'AUTEUR_COURSE' => $this->input->post('AUTEUR_COURSE'),
					'DESTINATION' => $this->input->post('DESTINATION'),
					'HEURE_DEPART' => $this->input->post('HEURE_DEPART'),
					'HEURE_ESTIMATIVE_RETOUR'=> $this->input->post('HEURE_ESTIMATIVE_RETOUR'),
					'ID_MOTIF_DEP' => $this->input->post('ID_MOTIF_DEP'),
					'DATE_COURSE' => $this->input->post('DATE_COURSE'),

					'PHOTO_KILOMETAGE' => $PHOTO_KILOMETAGE,
					'PHOTO_CARBURANT' => $PHOTO_CARBURANT,

					'IS_EXCHANGE' => $this->input->post('IS_EXCHANGE'),

					'IMAGE_AVANT' => $IMAGE_AVANT,
					'IMAGE_ARRIERE' => $IMAGE_ARRIERE,
					'IMAGE_LATERALE_GAUCHE' => $IMAGE_LATERALE_GAUCHE,
					'IMAGE_LATERALE_DROITE' => $IMAGE_LATERALE_DROITE,
					'IMAGE_TABLEAU_DE_BORD' => $IMAGE_TABLEAU_DE_BORD,
					'IMAGE_SIEGE_AVANT' => $IMAGE_SIEGE_AVANT,
					'IMAGE_SIEGE_ARRIERE' => $IMAGE_SIEGE_ARRIERE,
					'IS_VALIDATED' => 0,

				);
				// print_r($Array);exit();
				$this->Model->update('sortie_vehicule', array('ID_SORTIE' => $id), $Array);

				$datas['message'] = '<div class="alert alert-success text-center" id="message">'.lang('msg_success_modif').'</div>';
				$this->session->set_flashdata($datas);
				redirect(base_url('etat_vehicule/Sortie_Vehicule'));
			}
			else
			{
				$datas['message'] = '<div class="alert alert-danger text-center" id="message">Impossible de modifier car la demande est déjà validée !</div>';
				$this->session->set_flashdata($datas);
				redirect(base_url('etat_vehicule/Sortie_Vehicule/getOne/'.$id));
			}
			
			
		}
	}

    // Fonction pour recuperer les status (approuver ou refuser)
	function get_all_statut()
	{
		$all_statut = $this->Model->getRequete("SELECT `TRAITEMENT_DEMANDE_ID`,`DESC_TRATDEMANDE` FROM `traitement_demande` WHERE 1");
		$html='<option value="">--- '.lang('selectionner').' ----</option>';
		if(!empty($all_statut))
		{
			foreach($all_statut as $key)
			{
				$html.='<option value="'.$key['TRAITEMENT_DEMANDE_ID'].'">'.$key['DESC_TRATDEMANDE'].' </option>';
			}
		}

		echo json_encode($html);
	}

   // Enregistrement de la reponse pour la demande
	function save_stat_demande()
	{
		$statut = 0;

		$demande = false;

		$ID_SORTIE = $this->input->post('ID_SORTIE');
		$IS_VALIDATED = $this->input->post('IS_VALIDATED');
		$COMMENTAIRE = $this->input->post('COMMENTAIRE');
		$USER_ID = $this->input->post('USER_ID');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";

		$demande = $this->Model->update('sortie_vehicule',array('ID_SORTIE'=>$ID_SORTIE,),array('IS_VALIDATED'=>$IS_VALIDATED,'COMMENTAIRE'=>$COMMENTAIRE));

			// Enregistrement dans la table d'historique demande sortie

		$data_histo = array(
			'ID_SORTIE' => $ID_SORTIE,
			'IS_VALIDATED'=> $this->input->post('IS_VALIDATED'),
			'COMMENTAIRE'=> $this->input->post('COMMENTAIRE'),
			'USER_ID' => $this->input->post('USER_ID'),
		);

		$demande = $this->Model->create('histo_sortie_vehicule',$data_histo);


		if ($IS_VALIDATED == 1)  // Demande approuvée
		{
			$get_sortie = $this->Model->getOne('sortie_vehicule',array('ID_SORTIE'=>$ID_SORTIE));

			// Mise à jour d'etat ds la table vehicule

			$data_update = array(
				'IMAGE_AVANT' => $get_sortie['IMAGE_AVANT'],
				'IMAGE_ARRIERE'=> $get_sortie['IMAGE_ARRIERE'],
				'IMAGE_LATERALE_GAUCHE'=> $get_sortie['IMAGE_LATERALE_GAUCHE'],
				'IMAGE_LATERALE_DROITE' => $get_sortie['IMAGE_LATERALE_DROITE'],
				'IMAGE_TABLEAU_DE_BORD' => $get_sortie['IMAGE_TABLEAU_DE_BORD'],
				'IMAGE_SIEGE_AVANT' => $get_sortie['IMAGE_SIEGE_AVANT'],
				'IMAGE_SIEGE_ARRIERE' => $get_sortie['IMAGE_SIEGE_ARRIERE'],
			);

			$demande = $this->Model->update('vehicule', array('VEHICULE_ID' => $get_sortie['VEHICULE_ID']), $data_update);

			// Enregistrement ds la table historique d'etat

			$data_save = array(
				'VEHICULE_ID' => $get_sortie['VEHICULE_ID'],
				'IMAGE_AVANT' => $get_sortie['IMAGE_AVANT'],
				'IMAGE_ARRIERE'=> $get_sortie['IMAGE_ARRIERE'],
				'IMAGE_LATERALE_GAUCHE'=> $get_sortie['IMAGE_LATERALE_GAUCHE'],
				'IMAGE_LATERALE_DROITE' => $get_sortie['IMAGE_LATERALE_DROITE'],
				'IMAGE_TABLEAU_DE_BORD' => $get_sortie['IMAGE_TABLEAU_DE_BORD'],
				'IMAGE_SIEGE_AVANT' => $get_sortie['IMAGE_SIEGE_AVANT'],
				'IMAGE_SIEGE_ARRIERE' => $get_sortie['IMAGE_SIEGE_ARRIERE'],
				'USER_ID' => $this->input->post('USER_ID'),
			);

			$demande = $this->Model->create('historique_etat_vehicule',$data_save);
		}

		if($demande == true)
		{
			$statut=1;
		}else
		{
			$statut=2;
		}


		echo json_encode($statut);
	}

  // Historique demande sortie vehicule
	function getHistorique($id)
	{

		$query_principal='SELECT `HISTO_SORTIE_ID`,histo_sortie_vehicule.IS_VALIDATED,histo_sortie_vehicule.COMMENTAIRE,IDENTIFICATION,histo_sortie_vehicule.DATE_SAVE FROM histo_sortie_vehicule JOIN sortie_vehicule ON sortie_vehicule.ID_SORTIE = histo_sortie_vehicule.ID_SORTIE JOIN users ON users.USER_ID = histo_sortie_vehicule.USER_ID  WHERE 1';

		$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
		$var_search = str_replace("'", "\'", $var_search);
		$group = "";


		$limit = 'LIMIT 0,1000';
		if ($_POST['length'] != -1) {
			$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
		}

		$search = !empty($_POST['search']['value']) ? (' AND (HISTO_SORTIE_ID LIKE "%' . $var_search . '%" 
			OR histo_sortie_vehicule.IS_VALIDATED LIKE "%' . $var_search . '%" 
			OR histo_sortie_vehicule.COMMENTAIRE LIKE "%' . $var_search . '%" 
			OR IDENTIFICATION LIKE "%' . $var_search . '%"
			OR histo_sortie_vehicule.DATE_SAVE  LIKE "%' . $var_search . '%")') : '';

		$order_by = '';

		$order_column = array('','HISTO_SORTIE_ID','histo_sortie_vehicule.IS_VALIDATED','histo_sortie_vehicule.COMMENTAIRE','IDENTIFICATION ',' histo_sortie_vehicule.DATE_SAVE');

		if ($_POST['order']['0']['column'] != 0) {
			$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'HISTO_SORTIE_ID DESC DESC';
		}
		else
		{
			$order_by = ' ORDER BY HISTO_SORTIE_ID  DESC';
		}

		$critaire = "";
		$critaire.= ' AND sortie_vehicule.ID_SORTIE = '.$id;


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

			if($row->IS_VALIDATED == 0)
			{
				$sub_array[] = '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" text-warning style="font-size:15px;color: orange;" title="En attente de validation"></i></center>';
			}
			else if($row->IS_VALIDATED == 1)
			{
				$sub_array[] = '<center><i class="fa fa-check text-success"  style="" title="demande validée"></i></center>';
			}
			else if($row->IS_VALIDATED == 2)
			{
				$sub_array[] = '<center><i class="fa fa-close text-danger"  style="" title="demande refusée"></i></center>';

			}

			$sub_array[] = $row->COMMENTAIRE;
			$sub_array[] = $row->IDENTIFICATION;
			$sub_array[] = date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

			// $sub_array[]=$option;
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



	//fonction pour la selection des collonnes de la base de données en utilisant les procedures stockées
	public function getBindParms($columnselect, $table, $where, $orderby)
	{
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