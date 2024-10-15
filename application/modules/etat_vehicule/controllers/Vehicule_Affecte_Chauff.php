<?php
/*
	Auteur    : Niyomwungere Ella Dancilla
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 30/01/2024
*/
	class Vehicule_Affecte_Chauff extends CI_Controller
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
			$PROFIL_ID = $this->session->userdata('PROFIL_ID');

			
			
			$this->load->view('Vehicule_Affecte_Chauff_List_View');
		}

		
          //Fonction pour l'affichage
		function listing()
		{
			$USER_ID = $this->session->userdata('USER_ID');

			$CHECK_VALIDE = $this->input->post('CHECK_VALIDE');
			$PROFIL_ID=$this->session->userdata('PROFIL_ID');

			// print_r($PROFIL_ID);die();

			$critaire = '' ;
			$critaire_doc_valide = '' ;

			$date_now = date('Y-m-d');

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critaire.= ' AND users.USER_ID = '.$USER_ID;
			}

			if($CHECK_VALIDE == 1) // Assurance valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 2) // Assurance invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_ASSURANCE < "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 3) // Controle technique valide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK >= "'.$date_now.'"';
			}
			else if($CHECK_VALIDE == 4) // Controle technique invalide
			{
				$critaire_doc_valide = ' AND DATE_FIN_CONTROTECHNIK < "'.$date_now.'"';
			}

			$query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,PHOTO,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,proprietaire.PHOTO_PASSPORT,proprietaire.EMAIL,proprietaire.ADRESSE,proprietaire.TELEPHONE,DATE_SAVE,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,STATUT_VEH_AJOUT,`LOGO`,vehicule.FILE_ASSURANCE,vehicule.FILE_CONTRO_TECHNIQUE,vehicule.STATUT FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID   join chauffeur_vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN chauffeur on chauffeur_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join users on chauffeur.CHAUFFEUR_ID=users.CHAUFFEUR_ID WHERE vehicule.`STATUT`=2 '.$critaire_doc_valide.'';

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

			$search=!empty($_POST['search']['value']) ? (" AND (vehicule.CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%' OR DATE_SAVE LIKE '%$var_search%' )"):'';

			$query_secondaire=$query_principal.''.$critaire.''.$search.''.$order_by. ''. $limit;

			$query_filter=$query_principal.''.$critaire.''.$search;

			//print_r($query_filter);die();

			$fetch_data=$this->Model->datatable($query_secondaire);

			$data=array();
			$u=1;
			foreach ($fetch_data as $row)
			{
				$sub_array=array();
				$sub_array[]=$u++;

				if($this->session->userdata('PROFIL_ID') == 1)
				{
					if (!empty($row->CODE)) {
						$sub_array[]=$row->CODE;
					}else{
						$sub_array[]="".lang('lste_n_a')."";

					}
					
				}

				$sub_array[]=$row->PLAQUE;
				$sub_array[]=$row->DESC_MARQUE;
				// $sub_array[]=$row->DESC_MODELE;
				$sub_array[]=$row->COULEUR;

				$sub_array[]=date('d-m-Y',strtotime($row->DATE_SAVE))."&nbsp;<a href='".base_url('etat_vehicule/Vehicule_Affecte_Chauff/get_detail_vehicule/').$row->VEHICULE_ID."'>&nbsp;&nbsp;&nbsp;<b class='text-center bi bi-eye' id='eye'></b></a>";



				$option = '<div class="dropdown text-center">
				<a class="btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown">
				<i class="bi bi-three-dots h5" style="color:blue;"></i>	
				<span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-right">
				';


				if(!empty($row->DATE_FIN_ASSURANCE))
				{
					if(date('Y-m-d',strtotime($row->DATE_FIN_ASSURANCE)) >= date('Y-m-d'))
					{
						$sub_array[] = '<center><i class="fa fa-check text-success small" title='.lang('title_valide').'></i><font class="text-success small" title='.lang('title_valide').'> </font></center>';
					}
					else 
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger small" title='.lang('title_expire').'></i><font class="text-danger small" title='.lang('title_expire').'> </font></center>';

						$option.='<a class="btn-md" style="cursor:pointer;" onclick="assure_controle(\''.$row->VEHICULE_ID .'\',1)"> <li class="btn-md" style=""><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td>Assurance</td></tr></table></li></a>';
						
					}
				}
				else
				{
					$sub_array[] = '<center><font class="small" title="">'.lang('lste_n_a').'</font></center>';
				}
				
				if(!empty($row->DATE_FIN_CONTROTECHNIK))
				{
					if($row->DATE_FIN_CONTROTECHNIK >= date('Y-m-d'))
					{
						$sub_array[] = '<center><i class="fa fa-check text-success small" title='.lang('title_valide').'></i><font class="text-success small" title='.lang('title_valide').'> </font></center>';
					}
					else
					{
						$sub_array[] = '<center><i class="fa fa-close text-danger small" title='.lang('title_expire').'></i><font class="text-danger small" title='.lang('title_expire').'> </font></center>';

						$option.='<a class="btn-md" style="cursor:pointer;" onclick="assure_controle('.$row->VEHICULE_ID.',2)"><li class="btn-md" style=""><table><tr><td><i class="fa fa-rotate-right h5" ></i></td><td>'.lang('td_ctrl_technique').'</td></tr></table></li></a>';
					}
				}
				else
				{
					$sub_array[] = '<center><font class="small" title="">'.lang('lste_n_a').'</font></center>';
				}

				

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

		// Fonction pour le detail du vehicule

		function get_detail_vehicule($VEHICULE_ID = '')
		{
			$infos_vehicule = $this->Model->getRequeteOne('SELECT tracking_data.id,latitude,longitude,tracking_data.mouvement,tracking_data.ignition,VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,vehicule.STATUT_VEH_AJOUT,DATE_DEBUT_ASSURANCE,DATE_FIN_ASSURANCE,DATE_DEBUT_CONTROTECHNIK,DATE_FIN_CONTROTECHNIK,FILE_ASSURANCE,vehicule.DATE_SAVE,FILE_CONTRO_TECHNIQUE,proprietaire.PROPRIETAIRE_ID,STATUT_VEH_AJOUT,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.LOGO,proprietaire.PHOTO_PASSPORT AS photo_pro,COULEUR,KILOMETRAGE,PHOTO,chauffeur.CHAUFFEUR_ID,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS chauffeur_desc,chauffeur.PHOTO_PASSPORT AS photo_chauf,tracking_data.accident,chauffeur_vehicule.STATUT_AFFECT FROM vehicule LEFT JOIN tracking_data ON vehicule.CODE = tracking_data.device_uid LEFT JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE LEFT JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1 AND VEHICULE_ID = "'.$VEHICULE_ID.'" ORDER BY chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID DESC LIMIT 1');
			
			$data['infos_vehicule'] = $infos_vehicule;

			//determination de nombre de jours qu'un vehicule a était enregistré
			//$date = date('Y-m-d',strtotime($infos_vehicule['DATE_SAVE']));

			$aujourdhui = date("Y-m-d");

			// $nbr_jours = $this->NbJours($date, $aujourdhui);

			$nbr_jours = $this->notifications->ago($infos_vehicule['DATE_SAVE'],$aujourdhui);

			$explode = explode(" ",$nbr_jours);

			if($explode[1] == 'Moiss')
			{
				$nbr_jours = substr($nbr_jours, 0, -1);
			}

			$data['nbr_jours'] = $nbr_jours;

			$this->load->view('Vehicule_Affecte_Chauff_Detail_View',$data);
		}


		//Fonction pour la liste d'historique d'etat du vehicule
		function liste_etat_vehicule()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;
			$critaire_user=" ";

			$USER_ID = $this->session->userdata('USER_ID');
		
			$critaire_user.= ' AND users.USER_ID = '.$USER_ID;
			


			$query_principal='SELECT vehicule.`VEHICULE_ID`,vehicule.`CODE`,vehicule.`IMAGE_AVANT`,vehicule.`IMAGE_ARRIERE`,vehicule.`IMAGE_LATERALE_GAUCHE`,vehicule.`IMAGE_LATERALE_DROITE`,vehicule.`IMAGE_TABLEAU_DE_BORD`,vehicule.`IMAGE_SIEGE_ARRIERE`,chauffeur_vehicule.CODE,chauffeur.NOM,chauffeur.PRENOM,users.USER_ID FROM `vehicule`  join chauffeur_vehicule on vehicule.CODE=chauffeur_vehicule.CODE join chauffeur on chauffeur_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join users on  chauffeur.CHAUFFEUR_ID=users.CHAUFFEUR_ID  WHERE 1 '.$critaire_user.' AND chauffeur_vehicule.STATUT_AFFECT=1  ';

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('VEHICULE_ID','IMAGE_AVANT','IMAGE_ARRIERE','vehicule.IMAGE_LATERALE_GAUCHE','vehicule.IMAGE_LATERALE_DROITE','vehicule.IMAGE_TABLEAU_DE_BORD');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' VEHICULE_ID ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`VEHICULE_ID` LIKE "%' . $var_search . '%" 
				)') : '';


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

				$source = !empty($row->IMAGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';
				
				$source = !empty($row->IMAGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

			
				$source = !empty($row->IMAGE_LATERALE_GAUCHE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_GAUCHE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				$source = !empty($row->IMAGE_LATERALE_DROITE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_LATERALE_DROITE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				$source = !empty($row->IMAGE_TABLEAU_DE_BORD) ? base_url('upload/photo_vehicule/'.$row->IMAGE_TABLEAU_DE_BORD) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				$source = !empty($row->IMAGE_SIEGE_AVANT) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_AVANT) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

			
				$source = !empty($row->IMAGE_SIEGE_ARRIERE) ? base_url('upload/photo_vehicule/'.$row->IMAGE_SIEGE_ARRIERE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';

				// $sub_array[]=$row->IDENTIFICATION;
				// $sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";

			
				


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


		//Fonction pour une liste d'historique assurance
		function liste_assurance()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;
			$critaire_user=' ';
			$USER_ID = $this->session->userdata('USER_ID');
		
			$critaire_user.= ' AND users.USER_ID = '.$USER_ID;
			

			$query_principal='SELECT DISTINCT(vehicule.`VEHICULE_ID`),vehicule.`CODE`,vehicule.DATE_DEBUT_ASSURANCE,vehicule.DATE_FIN_ASSURANCE,vehicule.FILE_ASSURANCE,users.USER_ID,assureur.ASSURANCE,vehicule.DATE_SAVE FROM `vehicule`  join chauffeur_vehicule on vehicule.CODE=chauffeur_vehicule.CODE join chauffeur on chauffeur_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join users on  chauffeur.CHAUFFEUR_ID=users.CHAUFFEUR_ID  join historique_assurance on vehicule.VEHICULE_ID=historique_assurance.VEHICULE_ID  join assureur on historique_assurance.ID_ASSUREUR=assureur.ID_ASSUREUR WHERE 1 and chauffeur_vehicule.STATUT_AFFECT=1 '.$critaire_user.'';
			

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('VEHICULE_ID','vehicule.DATE_DEBUT_ASSURANCE','vehicule.DATE_FIN_ASSURANCE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' VEHICULE_ID ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`VEHICULE_ID` LIKE "%' . $var_search . '%"  OR vehicule.DATE_DEBUT_ASSURANCE LIKE "%' . $var_search . '%" OR vehicule.DATE_FIN_ASSURANCE LIKE "%' . $var_search . '%"  )') : '';


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
				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#mypicture" . $row->ID_HISTORIQUE_ASSURANCE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";
				$source = !empty($row->FILE_ASSURANCE) ? base_url('upload/photo_vehicule/'.$row->FILE_ASSURANCE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			    href="'.$source.'" ><b class="text-center fa fa-eye" id="eye"></b></a></td></tr></tbody></table></a>';


				$sub_array[]= date('d-m-Y',strtotime($row->DATE_DEBUT_ASSURANCE));
				$sub_array[]= date('d-m-Y',strtotime($row->DATE_FIN_ASSURANCE));
				$sub_array[]=$row->ASSURANCE;
				// $sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";

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

		//Fonction pour une liste d'historique controle technique
		function liste_controle()
		{
			$VEHICULE_ID = $this->input->post('VEHICULE_ID');

			$critaire = '' ;
			$critaire_user=' ';
			$USER_ID = $this->session->userdata('USER_ID');
		
			$critaire_user.= ' AND users.USER_ID = '.$USER_ID;
			
			$query_principal='SELECT DISTINCT(vehicule.`VEHICULE_ID`),vehicule.`CODE`,vehicule.DATE_DEBUT_CONTROTECHNIK,vehicule.DATE_FIN_CONTROTECHNIK,vehicule.FILE_CONTRO_TECHNIQUE,users.USER_ID,vehicule.DATE_SAVE FROM `vehicule`  join chauffeur_vehicule on vehicule.CODE=chauffeur_vehicule.CODE join chauffeur on chauffeur_vehicule.CHAUFFEUR_ID=chauffeur.CHAUFFEUR_ID join users on  chauffeur.CHAUFFEUR_ID=users.CHAUFFEUR_ID  join historique_controle_technique on vehicule.VEHICULE_ID=historique_controle_technique.VEHICULE_ID  WHERE 1 and chauffeur_vehicule.STATUT_AFFECT=1 '.$critaire_user.'';

			$critaire.= ' AND vehicule.VEHICULE_ID = '.$VEHICULE_ID;

			$var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
			$var_search = str_replace("'", "\'", $var_search);
			$group = "";

			$limit = 'LIMIT 0,1000';
			if ($_POST['length'] != -1) {
				$limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
			}
			$order_by='';

			$order_column=array('VEHICULE_ID','vehicule.DATE_DEBUT_CONTROTECHNIK','vehicule.DATE_FIN_CONTROTECHNIK','vehicule.DATE_SAVE');

			if ($_POST['order']['0']['column'] != 0) {
				$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' VEHICULE_ID ASC';
			}



			$search = !empty($_POST['search']['value']) ? (' AND (`VEHICULE_ID` LIKE "%' . $var_search . '%"  OR vehicule.DATE_DEBUT_CONTROTECHNIK LIKE "%' . $var_search . '%" OR vehicule.DATE_FIN_CONTROTECHNIK LIKE "%' . $var_search . '%" OR vehicule.DATE_SAVE LIKE "%' . $var_search . '%" )') : '';


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
				// $sub_array[]="<a hre='#' data-toggle='modal' data-target='#mypicture2" . $row->ID_HISTORIQUE_CONTROLE. "'>&nbsp;<b class='text-center fa fa-eye' id='eye'></b></a>";

			
			    $source = !empty($row->FILE_CONTRO_TECHNIQUE) ? base_url('upload/image_url/'.$row->FILE_CONTRO_TECHNIQUE) : base_url('upload/images/user.png');
				$sub_array[]='<table class="table-borderless"> <tbody><tr><td><a title="' . $source . '" data-gallery="photoviewer" data-title="" data-group="a"
			href="'.$source.'" ><img alt="Avtar" style="border-radius:50%;width:30px;height:30px ;" src="' . $source . '"></a></td></tr></tbody></table></a>';

				$sub_array[]= date('d-m-Y',strtotime($row->DATE_DEBUT_CONTROTECHNIK));
				$sub_array[]= date('d-m-Y',strtotime($row->DATE_FIN_CONTROTECHNIK));
				// $sub_array[]=$row->IDENTIFICATION;
				$sub_array[]=date('d-m-Y H:i:s',strtotime($row->DATE_SAVE));

				$option = " ";
				
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