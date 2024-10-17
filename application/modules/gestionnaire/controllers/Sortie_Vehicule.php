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


			//Fonction pour l'affichage des vehicules en attentes de validation
		function listing()
		{
			$USER_ID=$this->session->userdata('USER_ID');

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$critaire = "";

			// if($this->session->PROFIL_ID == 2) // Si c'est le proprietaire
			// {
			// 	$critaire = " AND chauffeur_vehicule.STATUT_AFFECT=1 AND users.USER_ID=".$USER_ID;
			// }
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
				)') : '';

			// $query_principal='SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE` FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP WHERE 1';
			$query_principal=('SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE`,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP join proprietaire on chauffeur.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE IS_VALIDATED=0');

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
				
				$sub_array[] = $row->NOM." ".$row->PRENOM;
				 $sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->DESC_MOTIF;
				// $sub_array[] = $row->DATE_COURSE;
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/image_url/'.$row->PHOTO_KILOMETAGE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/image_url/'.$row->PHOTO_CARBURANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
		
				// $sub_array[] = $row->IMAGE_AVANT;
			    $source = !empty($row->IMAGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_ARRIERE;

				$source = !empty($row->IMAGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

			
			// $sub_array[] = $row->IMAGE_LATERALE_GAUCHE;
			$source = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				

				


             // $sub_array[] = $row->IMAGE_LATERALE_DROITE;
			$source = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				
				// $sub_array[] = $row->IMAGE_TABLEAU_DE_BORD;
			$source = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/image_url/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

                // $sub_array[] = $row->IMAGE_SIEGE_AVANT;
			$source = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_SIEGE_ARRIERE;
				$source = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				if($row->IS_VALIDATED==0)
				{
					$sub_array[] ='En attente de validation';
				}elseif ($row->IS_VALIDATED==1) {
					$sub_array[] ='Validée';
				}else{
					$sub_array[] ='Rejetée';
				}
				$sub_array[] = $row->COMMENTAIRE;


				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if ($row->IS_VALIDATED==0 ) 
				{
					$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->ID_SORTIE . ",".$row->IS_VALIDATED.")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;Traiter demande</li></a>";

				   }elseif($row->IS_VALIDATED==1 || $row->IS_VALIDATED==2) 
				   {
					$option .= "";
				   }
					

			
		
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

			//Fonction pour l'affichage des vehicules validés
		function listing2()
		{
			$USER_ID=$this->session->userdata('USER_ID');

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$critaire = "";

			// if($this->session->PROFIL_ID == 2) // Si c'est le proprietaire
			// {
			// 	$critaire = " AND chauffeur_vehicule.STATUT_AFFECT=1 AND users.USER_ID=".$USER_ID;
			// }
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
				)') : '';

			// $query_principal='SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE` FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP WHERE 1';
			$query_principal=('SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE`,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP join proprietaire on chauffeur.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE IS_VALIDATED=1');

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
				
				$sub_array[] = $row->NOM." ".$row->PRENOM;
				 $sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->DESC_MOTIF;
				// $sub_array[] = $row->DATE_COURSE;
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/image_url/'.$row->PHOTO_KILOMETAGE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/image_url/'.$row->PHOTO_CARBURANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
		
				// $sub_array[] = $row->IMAGE_AVANT;
			    $source = !empty($row->IMAGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_ARRIERE;

				$source = !empty($row->IMAGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

			
			// $sub_array[] = $row->IMAGE_LATERALE_GAUCHE;
			$source = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				

				


             // $sub_array[] = $row->IMAGE_LATERALE_DROITE;
			$source = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				
				// $sub_array[] = $row->IMAGE_TABLEAU_DE_BORD;
			$source = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/image_url/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

                // $sub_array[] = $row->IMAGE_SIEGE_AVANT;
			$source = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_SIEGE_ARRIERE;
				$source = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				if($row->IS_VALIDATED==0)
				{
					$sub_array[] ='En attente de validation';
				}elseif ($row->IS_VALIDATED==1) {
					$sub_array[] ='Validée';
				}else{
					$sub_array[] ='Rejetée';
				}
				$sub_array[] = $row->COMMENTAIRE;


				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if ($row->IS_VALIDATED==0 ) 
				{
					$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->ID_SORTIE . ",".$row->IS_VALIDATED.")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;Traiter demande</li></a>";

				   }elseif($row->IS_VALIDATED==1 || $row->IS_VALIDATED==2) 
				   {
					$option .= "";
				   }
					

			
		
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

			//Fonction pour l'affichage des vehicules refusés
		function listing3()
		{
			$USER_ID=$this->session->userdata('USER_ID');

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$critaire = "";

			// if($this->session->PROFIL_ID == 2) // Si c'est le proprietaire
			// {
			// 	$critaire = " AND chauffeur_vehicule.STATUT_AFFECT=1 AND users.USER_ID=".$USER_ID;
			// }
			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by = '';

			$order_column = array('','vehicule.CODE','chauffeur.NOM','chauffeur.PRENOM','retour_vehicule.HEURE_RETOUR ',' retour_vehicule.COMMENTAIRE_VALIDATION','retour_vehicule.COMMENTAIRE_ANOMALIE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : 'chauffeur.CHAUFFEUR_ID ASC';
			}
			$search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR vehicule.CODE LIKE "%' . $var_search . '%"
				OR chauffeur.NOM LIKE "%' . $var_search . '%" 
				OR chauffeur.PRENOM LIKE "%' . $var_search . '%" 
				OR retour_vehicule.HEURE_RETOUR LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_VALIDATION  LIKE "%' . $var_search . '%"
				OR retour_vehicule.COMMENTAIRE_ANOMALIE LIKE "%' . $var_search . '%"
				)') : '';

			// $query_principal='SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE` FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP WHERE 1';
			$query_principal=('SELECT `ID_SORTIE`,`VEHICULE_ID`,chauffeur.NOM,chauffeur.PRENOM,`AUTEUR_COURSE`,`DESTINATION`,`HEURE_DEPART`,`HEURE_ESTIMATIVE_RETOUR`,`PHOTO_KILOMETAGE`,`PHOTO_CARBURANT`,motif_deplacement.DESC_MOTIF,`DATE_COURSE`,`IMAGE_AVANT`,`IMAGE_ARRIERE`,`IMAGE_LATERALE_GAUCHE`,`IMAGE_LATERALE_DROITE`,`IMAGE_TABLEAU_DE_BORD`,`IMAGE_SIEGE_AVANT`,`IMAGE_SIEGE_ARRIERE`,`IS_VALIDATED`,`COMMENTAIRE`,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE FROM `sortie_vehicule` join chauffeur on sortie_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join motif_deplacement on sortie_vehicule.ID_MOTIF_DEP=motif_deplacement.ID_MOTIF_DEP join proprietaire on chauffeur.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE IS_VALIDATED=2');

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
				
				$sub_array[] = $row->NOM." ".$row->PRENOM;
				 $sub_array[] = $row->DESTINATION;
				$sub_array[] = $row->HEURE_DEPART;
				$sub_array[] = $row->HEURE_ESTIMATIVE_RETOUR;
				$sub_array[] = $row->DATE_COURSE;
				$sub_array[] = $row->DESC_MOTIF;
				// $sub_array[] = $row->DATE_COURSE;
				
				$source = !empty($row->PHOTO_KILOMETAGE) ? base_url('upload/image_url/'.$row->PHOTO_KILOMETAGE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
			  //PHOTO_CARBURANT
				$source = !empty($row->PHOTO_CARBURANT) ? base_url('upload/image_url/'.$row->PHOTO_CARBURANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
		
				// $sub_array[] = $row->IMAGE_AVANT;
			    $source = !empty($row->IMAGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_ARRIERE;

				$source = !empty($row->IMAGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

			
			// $sub_array[] = $row->IMAGE_LATERALE_GAUCHE;
			$source = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				

				


             // $sub_array[] = $row->IMAGE_LATERALE_DROITE;
			$source = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/image_url/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';
				
				// $sub_array[] = $row->IMAGE_TABLEAU_DE_BORD;
			$source = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/image_url/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

                // $sub_array[] = $row->IMAGE_SIEGE_AVANT;
			$source = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				// $sub_array[] = $row->IMAGE_SIEGE_ARRIERE;
				$source = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/image_url/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				
				if($row->IS_VALIDATED==0)
				{
					$sub_array[] ='En attente de validation';
				}elseif ($row->IS_VALIDATED==1) {
					$sub_array[] ='Validée';
				}else{
					$sub_array[] ='Rejetée';
				}
				$sub_array[] = $row->COMMENTAIRE;


				$option = '<div class="dropdown text-center" style="color:#fff;">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black; cursor:pointer;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';

				if ($row->IS_VALIDATED==0 ) 
				{
					$option .= "<a class='btn-md' id='' href='#' onclick='traiter_demande(" . $row->ID_SORTIE . ",".$row->IS_VALIDATED.")' ><li class='btn-md'>&nbsp;&nbsp;&nbsp;<i class='bi bi-pen'></i>&nbsp;&nbsp;&nbsp;&nbsp;Traiter demande</li></a>";

				   }elseif($row->IS_VALIDATED==1 || $row->IS_VALIDATED==2) 
				   {
					$option .= "";
				   }
					

			
		
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


		  // // $ouput= array(
         // //        'html'=>$html,
         // //        'html1'=>$html1,
		 // );
			echo json_encode($html);
		}

	   function save_stat_demandee()
		{
			$USER_ID = $this->session->userdata('USER_ID');
		   // $statut=1 traitement avec succes;
		   // $statut=2:traitement echoue
		   // $statut=3: traitement 
			$statut=3;


			$IS_VALIDATED = $this->input->post('IS_VALIDATED');
			$ID_SORTIE = $this->input->post('SORTIE_TRAITE');
			$TRAITEMENT_DEMANDE_ID = $this->input->post('TRAITEMENT_DEMANDE_ID');
			$COMMENTAIRE = $this->input->post('COMMENTAIRE');
			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			
			$vehcul = false;

			if($statut==3){

				if ($IS_VALIDATED == 0 && $TRAITEMENT_DEMANDE_ID==1) 
				{
					// $vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>2,'CODE'=>$CODE));
					$vehcul = $this->Model->update('sortie_vehicule', array('ID_SORTIE'=>$ID_SORTIE),array('IS_VALIDATED'=>1));

			       $data = array('USER_ID'=>$USER_ID,'IS_VALIDATED'=>1,'COMMENTAIRE'=>$COMMENTAIRE,'TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'ID_SORTIE'=>$ID_SORTIE);
			     $result = $this->Model->create('historiq_valider_refus_sortie',$data);


				}
				else if ($IS_VALIDATED == 2 && $TRAITEMENT_DEMANDE_ID==1) 
				{
					// $vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>2,'CODE'=>$CODE));
					$vehcul = $this->Model->update('sortie_vehicule', array('ID_SORTIE'=>$ID_SORTIE),array('IS_VALIDATED'=>1));

			     $data = array('USER_ID'=>$USER_ID,'IS_VALIDATED'=>1,'COMMENTAIRE'=>$COMMENTAIRE,'TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'ID_SORTIE'=>$ID_SORTIE);
			     $result = $this->Model->create('historiq_valider_refus_sortie',$data);

				}
				else if ($IS_VALIDATED==0 && $TRAITEMENT_DEMANDE_ID==2) {
					// $vehcul = $this->Model->update('vehicule',array('VEHICULE_ID'=>$VEHICULE_ID,),array('TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'COMMENTAIRE'=>$COMMENTAIRE,'STATUT_VEH_AJOUT'=>3));
					$vehcul = $this->Model->update('sortie_vehicule', array('ID_SORTIE'=>$ID_SORTIE),array('IS_VALIDATED'=>2));

			     $data = array('USER_ID'=>$USER_ID,'IS_VALIDATED'=>2,'COMMENTAIRE'=>$COMMENTAIRE,'TRAITEMENT_DEMANDE_ID'=>$TRAITEMENT_DEMANDE_ID,'ID_SORTIE'=>$ID_SORTIE);
			     $result = $this->Model->create('historiq_valider_refus_sortie',$data); 
				}

				if($vehcul==true)
				{
					$statut=1;
				}else
				{
					$statut=2;
				}
			}
			
			echo json_encode($statut);
		}

	

	   // Recuperation des fichiers(pdf)
       public function upload_document($nom_file,$nom_champ)
	   {
			$rep_doc =FCPATH.'upload/image_url/';
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


	//Fonction pour valider/refuser la sortie d'un vehicule
	function valider_refuser($status,$ID_SORTIE)
	{
		$USER_ID = $this->session->userdata('USER_ID');
	
		if($status==1)
		{
			//REFUSER
			$COMMENTAIRE_refus = $this->input->post('COMMENTAIRE_REFUS');
			$this->Model->update('sortie_vehicule', array('ID_SORTIE'=>$ID_SORTIE),array('IS_VALIDATED'=>2));

			$data = array('USER_ID'=>$USER_ID,'STATUT'=>2,'COMMENTAIRE_REFUS'=>$COMMENTAIRE_refus,'ID_SORTIE'=>$ID_SORTIE);
			$result = $this->Model->create('historiq_valider_refus_sortie',$data);

		}else if($status==2)
		{
			//VALIDER
			$Commentaire_valid = $this->input->post(' COMMENTAIRE_VALID');
			$this->Model->update('sortie_vehicule', array('ID_SORTIE'=>$ID_SORTIE),array('IS_VALIDATED'=>1));

			$data = array('USER_ID'=>$USER_ID,'STATUT'=>1,'COMMENTAIRE_VALID'=>$Commentaire_valid,'ID_SORTIE'=>$ID_SORTIE);

			$result = $this->Model->create('historiq_valider_refus_sortie',$data);

		}

		echo json_encode(array('status'=>$status));
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