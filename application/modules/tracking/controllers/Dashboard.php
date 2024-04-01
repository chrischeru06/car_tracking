<?php 
/**
 * Fait par Nzosaba Santa Milka
 * 68071895
 * santa.milka@mediabox.bi
 * Le 11/2/2024
 * Dashboard de tracking des chauffeurs
 */
class Dashboard extends CI_Controller
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


	//Fonction pour afficher le dashbboard pour tracker le chauffeur 
	function tracking_chauffeur($CODE){

		// echo sqrt(9);
		// echo pow(9,3);

		$fontinfo = $this->input->post('rtoggle');

		$DATE_SELECT = $this->input->post('DATE');

		$distance_arrondie=0;
		if(empty($DATE_SELECT)){

			$DATE_SELECT=date('Y-m-d');

		}


		$info = '';

		if($fontinfo == ''){

			$info = 'streets';

		}else{

			$info = $fontinfo;
		}

		$data['info'] = $info;
		$CODE_VEH=$this->uri->segment(4);
		$data['CODE_VEH']=$CODE_VEH;

		
		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND md5(chauffeur_vehicule.CODE) ="'.$CODE_VEH.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
		$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);

		$my_selectvehicule = $this->getBindParms('VEHICULE_ID,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,vehicule.CODE,vehicule.KILOMETRAGE', 'vehicule join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND md5(vehicule.CODE) ="'.$CODE_VEH.'"', '`VEHICULE_ID` ASC');
		$my_selectvehicule=str_replace('\"', '"', $my_selectvehicule);
		$my_selectvehicule=str_replace('\n', '', $my_selectvehicule);
		$my_selectvehicule=str_replace('\"', '', $my_selectvehicule);
		$get_vehicule = $this->ModelPs->getRequeteOne($proce_requete, $my_selectvehicule);

		$my_select_heure_trajet = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', '1', '`HEURE_ID` ASC');
		$heure_trajet = $this->ModelPs->getRequete($proce_requete, $my_select_heure_trajet);


		$data['get_chauffeur']=$get_chauffeur;
		$data['heure_trajet']=$heure_trajet;
		$data['get_vehicule']=$get_vehicule;
		



		$this->load->view('Tracking_chauffeur_view',$data);


	}



	//Fonction pour les filtres
	function tracking_chauffeur_filtres(){

		$fontinfo = $this->input->post('rtoggle');
		$DATE_SELECT = $this->input->post('DATE_DAT');
		$DATE_DAT_FIN = $this->input->post('DATE_DAT_FIN');	
		$CODE = $this->input->post('CODE');
		$HEURE1 = $this->input->post('HEURE1');
		$HEURE2 = $this->input->post('HEURE2');
		$CODE_COURSE = $this->input->post('CODE_COURSE');


		$distance_finale=0;
		$distance_arrondie=0;
		$score_finale=0;
		$critere='';
		$critere1='';


		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_heure1 = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', 'HEURE_ID="'.$HEURE1.'"', '`HEURE_ID` ASC');
		$my_select_heure1=str_replace('\"', '"', $my_select_heure1);
		$my_select_heure1=str_replace('\n', '', $my_select_heure1);
		$my_select_heure1=str_replace('\"', '', $my_select_heure1);
		$heure_select1 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_heure1);


		$my_select_heure2 = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', 'HEURE_ID="'.$HEURE2.'"', '`HEURE_ID` ASC');
		$my_select_heure2=str_replace('\"', '"', $my_select_heure2);
		$my_select_heure2=str_replace('\n', '', $my_select_heure2);
		$my_select_heure2=str_replace('\"', '', $my_select_heure2);
		$my_select_heure2 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_heure2);

		if(!empty($DATE_SELECT) && !empty($DATE_DAT_FIN)){

			$critere.=' AND date_format(tracking_data.date,"%Y-%m-%d")between "'.$DATE_SELECT.'" AND "'.$DATE_DAT_FIN.'" ';


		}

		if (!empty($HEURE1) && !empty($HEURE2)) 
		{
			$critere.=' AND date_format(tracking_data.`date`,"%H:%i:%s") between "'.$heure_select1['HEURE'].'" AND "'.$my_select_heure2['HEURE'].'" ';
			

		}

		if (!empty($CODE_COURSE)) 
		{
			$critere1.=' AND md5(tracking_data.CODE_COURSE) ="'.$CODE_COURSE.'"';
			

		}
		

		$info = '';

		if($fontinfo == ''){

			$info = 'streets';

		}else{

			$info = $fontinfo;
		}

		$data['info'] = $info;

		// requete pour recuperer le chauffeur
		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,KILOMETRAGE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND md5(chauffeur_vehicule.CODE) ="'.$CODE.'"', 'vehicule.`PROPRIETAIRE_ID` ASC');
		$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);


		//requete pour recuperer tout le trajet parcouru
		$my_selectget_data = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date', 'tracking_data', ' md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\n', '', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '', $my_selectget_data);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"

		$get_data = $this->ModelPs->getRequete($proce_requete, $my_selectget_data);
		 // print_r('select `id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date from tracking_data where md5(device_uid) ="'.$CODE.'" '.$critere.'');die();

		
		//requete pour recuperer les arrets
		$my_selectget_arret = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H:%i") as heure', 'tracking_data', ' ignition=0 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"
		$my_selectget_arret=str_replace('\"', '"', $my_selectget_arret);
		$my_selectget_arret=str_replace('\"', '"', $my_selectget_arret);
		$my_selectget_arret=str_replace('\n', '', $my_selectget_arret);
		$my_selectget_arret=str_replace('\"', '', $my_selectget_arret);


		$get_arret = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret);


		$distance=0;
		
		//Calcul de la distance parcourue
		if(!empty($get_data)){

			$i=0;
			foreach ($get_data as $value_get_arret) {
				if ($value_get_arret['ignition']==1) {

					if ($i==0) {
						$pointdepart=[$value_get_arret['latitude'],$value_get_arret['longitude']];

					}
					else{
						$pointactuel=[$value_get_arret['latitude'],$value_get_arret['longitude']];


						$distance+=$this->Model->getDistance($pointdepart['0'],$pointdepart['1'],$pointactuel['0'],$pointactuel['1']);

					}

				}else{
					$pointdepart=[$value_get_arret['latitude'],$value_get_arret['longitude']];



				}
				$i++;

			}

			$distance_finale=$distance;
			$distance_arrondie=round($distance_finale);


		}


		//calcul de la distance		
		$my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.'  AND ignition=0' , '`id` ASC');
		$my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"
		$get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);
		$nvldistance=0;

		$my_selectmin_arret = $this->getBindParms('MIN(id) as minimum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.' ' , '`id` ASC');
		$my_selectmin_arret=str_replace('\"', '"', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\n', '', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\"', '', $my_selectmin_arret);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"
		$min_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmin_arret);

		$my_selectmax_arret = $this->getBindParms('MAX(id) as maximum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.'' , '`id` ASC');
		$my_selectmax_arret=str_replace('\"', '"', $my_selectmax_arret);
		$my_selectmax_arret=str_replace('\n', '', $my_selectmax_arret);
		$my_selectmax_arret=str_replace('\"', '', $my_selectmax_arret);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"
		$max_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmax_arret);

		$min_arret_plus=$min_arret['minimum']+1;
		for ($i=$min_arret['minimum'],$j=$min_arret_plus; $i <$max_arret['maximum'],$j <$max_arret['maximum'] ; $i++,$j++) {

			$my_selectarret1= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$i.'"' , '`id` ASC');
			$my_selectarret1=str_replace('\"', '"', $my_selectarret1);
			$my_selectarret1=str_replace('\n', '', $my_selectarret1);
			$my_selectarret1=str_replace('\"', '', $my_selectarret1);

			$point_distance = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret1);

			$my_selectarret2= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$j.'"' , '`id` ASC');
			$my_selectarret2=str_replace('\"', '"', $my_selectarret2);
			$my_selectarret2=str_replace('\n', '', $my_selectarret2);
			$my_selectarret2=str_replace('\"', '', $my_selectarret2);

			$point_distance2 = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret2); 
			$nvldistance+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
			
		}
		$nvldistance_arrondie=round($nvldistance);

		$point_final=20;

		$point_point=20;
		if(!empty($get_arret_date)){

			$data_arret=array();


			foreach ($get_arret_date as $keyget_arret) {
				$data_arret[]=$keyget_arret['id'];

			}

			$nbre=count($data_arret);

			$valeur_valeur=array();
			$point_distance_fin=array();
			for($i=0,$j=1;$i<$nbre,$j<$nbre;$i++,$j++){


				$my_selectrequete = $this->getBindParms('count(id) as idsup', 'tracking_data', '1 AND vitesse>60 AND tracking_data.id between "'.$data_arret[$i].'" AND "'.$data_arret[$j].'"' , '`id` ASC');
				$my_selectrequete=str_replace('\"', '"', $my_selectrequete);
				$my_selectrequete=str_replace('\n', '', $my_selectrequete);
				$my_selectrequete=str_replace('\"', '', $my_selectrequete);

				$requete = $this->ModelPs->getRequete($proce_requete, $my_selectrequete);

				$valeur_valeur[]=$requete;

				

			}



			$add=0;

			foreach ($valeur_valeur as $keyvaleur_valeur) {

				foreach ($keyvaleur_valeur as $keykeyvaleur_valeur) {


					foreach ($keykeyvaleur_valeur as $final_final) {

						if($final_final>0){

							$add=$add+1;
						}
					}

				}
				
			}

			$point_final=$point_point-$add;

		}




		// $ligne_arret='';
		$card_card='';

		
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"

		$get_data_arret = $this->Model->getRequete('SELECT CODE_COURSE FROM tracking_data WHERE md5(device_uid) ="'.$CODE.'" '.$critere.'  GROUP BY CODE_COURSE');

		$get_data_arret_prime = $this->Model->getRequete('SELECT CODE_COURSE FROM tracking_data WHERE md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' GROUP BY CODE_COURSE');
		$tabl=' ';
		$mark_v='';
		$mark_vprim='';
		if (!empty($get_data_arret_prime)) {
			$tabl_prime=array();

			foreach ($get_data_arret_prime as $value_get_arret_prim) {
				$my_selectone_element_prim = $this->getBindParms('id,tracking_data.date as date_actu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,ignition,latitude,longitude', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_prim['CODE_COURSE'].'" ' , '`id` ASC');
				$my_selectone_element_prim=str_replace('\"', '"', $my_selectone_element_prim);
				$my_selectone_element_prim=str_replace('\n', '', $my_selectone_element_prim);
				$my_selectone_element_prim=str_replace('\"', '', $my_selectone_element_prim);

				$one_element_prim = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element_prim);
				$date_compare1_prim=$one_element_prim['date_actu'];

				
				$my_select_date_compare2_prim = $this->getBindParms('tracking_data.date as date_actu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,latitude,longitude,date_format(tracking_data.date,"%d %m") as day_month', 'tracking_data', ' CODE_COURSE="'.$value_get_arret_prim['CODE_COURSE'].'" ', 'id DESC');
				$my_select_date_compare2_prim=str_replace('\"', '"', $my_select_date_compare2_prim);
				$my_select_date_compare2_prim=str_replace('\n', '', $my_select_date_compare2_prim);
				$my_select_date_compare2_prim=str_replace('\"', '', $my_select_date_compare2_prim);
				$date_compare2_prim = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date_compare2_prim);
				$tabl_prime[]=[$this->notifications->ago($date_compare1_prim,$date_compare2_prim['date_actu']),$one_element_prim['CODE_COURSE'],$one_element_prim['date_actu'],$date_compare2_prim['date_actu'],$one_element_prim['hour'],$one_element_prim['sec'],$date_compare2_prim['hour'],$date_compare2_prim['sec'],$one_element_prim['latitude'],$one_element_prim['longitude'],$date_compare2_prim['latitude'],$date_compare2_prim['longitude'],$one_element_prim['ignition'],$one_element_prim['day_month'],$date_compare2_prim['day_month']];
				


			}

			if (!empty($tabl_prime)) {
				foreach ($tabl_prime as $keytablprim) {

					$mark_vprim=$mark_vprim.$keytablprim[9].'<>'.$keytablprim[8].'<>'.$keytablprim[11].'<>'.$keytablprim[10].'<>'.$keytablprim[12].'<>@';

				}
			}
		}
		$distdislegend=0;
		//calcul du temps d'arret
		if(!empty($get_data_arret)){
			$tabl=array();



			foreach ($get_data_arret as $value_get_arret) {
				$my_selectone_element = $this->getBindParms('id,tracking_data.date,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,md5(CODE_COURSE) as code_course_crypt,ignition,latitude,longitude,CEINTURE,CLIM', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret['CODE_COURSE'].'" ' , '`id` ASC');
				$my_selectone_element=str_replace('\"', '"', $my_selectone_element);
				$my_selectone_element=str_replace('\n', '', $my_selectone_element);
				$my_selectone_element=str_replace('\"', '', $my_selectone_element);

				$one_element = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element);
				$date_compare1=$one_element['date'];

				
				$my_select_date_compare2 = $this->getBindParms('id,tracking_data.date,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,latitude,longitude,date_format(tracking_data.date,"%d %m") as day_month', 'tracking_data', ' CODE_COURSE="'.$value_get_arret['CODE_COURSE'].'" ', 'id DESC');
				$my_select_date_compare2=str_replace('\"', '"', $my_select_date_compare2);
				$my_select_date_compare2=str_replace('\n', '', $my_select_date_compare2);
				$my_select_date_compare2=str_replace('\"', '', $my_select_date_compare2);
				$date_compare2 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date_compare2);
				$min_arret_plus=$one_element['id']+1;
				for ($i=$one_element['id'],$j=$min_arret_plus; $i <$date_compare2['id'],$j <$date_compare2['id'] ; $i++,$j++) {

					$my_selectarret1= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$i.'"' , '`id` ASC');
					$my_selectarret1=str_replace('\"', '"', $my_selectarret1);
					$my_selectarret1=str_replace('\n', '', $my_selectarret1);
					$my_selectarret1=str_replace('\"', '', $my_selectarret1);

					$point_distance = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret1);

					$my_selectarret2= $this->getBindParms('latitude,longitude', 'tracking_data', '1 AND tracking_data.id = "'.$j.'"' , '`id` ASC');
					$my_selectarret2=str_replace('\"', '"', $my_selectarret2);
					$my_selectarret2=str_replace('\n', '', $my_selectarret2);
					$my_selectarret2=str_replace('\"', '', $my_selectarret2);

					$point_distance2 = $this->ModelPs->getRequeteOne($proce_requete, $my_selectarret2);

					


					$distdislegend+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
				}
				
				$tabl[]=[$this->notifications->ago($date_compare1,$date_compare2['date']),$one_element['code_course_crypt'],$one_element['date'],$date_compare2['date'],$one_element['hour'],$one_element['sec'],$date_compare2['hour'],$date_compare2['sec'],$one_element['latitude'],$one_element['longitude'],$date_compare2['latitude'],$date_compare2['longitude'],$one_element['ignition'],$one_element['day_month'],$date_compare2['day_month'],round($distdislegend),$one_element['CEINTURE'],$one_element['CLIM']];
				


			}

			// print_r($tabl);die();

			$data['tabl'] = $tabl;

			$v=1;
			if (!empty($tabl)) 
			{
				foreach ($tabl as $keytabl) 
				{

					$mark_v=$mark_v.$keytabl[9].'<>'.$keytabl[8].'<>'.$keytabl[11].'<>'.$keytabl[10].'<>'.$keytabl[12].'<>@';

					$lat = $keytabl[8];
					$lng = $keytabl[9];
					if($keytabl[16]==1){
						$valeur_ceinture='<div class="fa fa-check" style="color:green"></div>';
					}else{
						$valeur_ceinture='<div class="fa fa-close" style="color:red"></div>';
					}
					if($keytabl[17]==1){
						$valeur_clim='<div class="fa fa-check" style="color:green"></div>';
					}else{
						$valeur_clim='<div class="fa fa-close" style="color:red"></div>';
					}

					$dataplace = '<script>
					var url ="https://api.mapbox.com/geocoding/v5/mapbox.places/'.$lng.','.$lat.'.json?access_token=pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A";
					$.get(url, function(data){
						return data;

						});

						</script>';

			            // print_r($dataplace);die();
						if ($keytabl[12]==1) {

							$card_card.='<div class="card" onclick="change_carte(\''.$keytabl[1].'\')">
							<div class="jss408">
							<div class="jss491" style="cursor: pointer;">
							<div class="jss490">
							<div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation0 css-wmagas" style="margin: 4px 7px 7px auto; color: grey; font-size: 11px;">
							<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="pen-to-square" class="svg-inline--fa fa-pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="font-size: 18px;"><path fill="currentColor" d="M386.7 22.63C411.7-2.365 452.3-2.365 477.3 22.63L489.4 34.74C514.4 59.74 514.4 100.3 489.4 125.3L269 345.6C260.6 354.1 249.9 359.1 238.2 362.7L147.6 383.6C142.2 384.8 136.6 383.2 132.7 379.3C128.8 375.4 127.2 369.8 128.4 364.4L149.3 273.8C152 262.1 157.9 251.4 166.4 242.1L386.7 22.63zM454.6 45.26C442.1 32.76 421.9 32.76 409.4 45.26L382.6 72L440 129.4L466.7 102.6C479.2 90.13 479.2 69.87 466.7 57.37L454.6 45.26zM180.5 281L165.3 346.7L230.1 331.5C236.8 330.2 242.2 327.2 246.4 322.1L417.4 152L360 94.63L189 265.6C184.8 269.8 181.8 275.2 180.5 281V281zM208 64C216.8 64 224 71.16 224 80C224 88.84 216.8 96 208 96H80C53.49 96 32 117.5 32 144V432C32 458.5 53.49 480 80 480H368C394.5 480 416 458.5 416 432V304C416 295.2 423.2 288 432 288C440.8 288 448 295.2 448 304V432C448 476.2 412.2 512 368 512H80C35.82 512 0 476.2 0 432V144C0 99.82 35.82 64 80 64H208z"></path>
							</svg>
							</div>
							</div>
							<div class="jss503">
							<div class="jss509">
							<div class="jss511"><sup class="jss507">'.$keytabl[13].'</sup><span class="jss510 jss512">'.$keytabl[4].'<span class="jss494">:'.$keytabl[5].'&nbsp;</span></span><span class="jss517">['.$keytabl[8].','.$keytabl[9].']</span></div><div class="jss513">'.$keytabl[0].'<span style="float: right;">'.$keytabl[15].' km</span></div><div class="jss511"><sup class="jss507">'.$keytabl[14].'</sup><span class="jss510 jss514">'.$keytabl[6].'<span class="jss494">:'.$keytabl[7].'</span></span><span class="jss518">['.$keytabl[10].','.$keytabl[11].']</span>
							</div>
							</div>
							<span class="jss510">Ceinture<span class="jss494">&nbsp;&nbsp;'.$valeur_ceinture.'</span></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="jss518">Climatiseur&nbsp;&nbsp;'.$valeur_clim.'</span>
							</div>							
							</div>
							</div>
							</div>';
						}
						elseif ($keytabl[12]==0) 
						{
							$card_card.='<div class="card" onclick="change_carte(\''.$keytabl[1].'\')">
							<div class="jss110" style="cursor: pointer;">
							<div class="jss111">
							<div class="jss112" style="width: 78px; font-size: 11px; font-weight: 500;"><p><sup class="jss500 jss501"> '.$keytabl[13].'</sup>'.$keytabl[4].'<span class="jss119">:'.$keytabl[5].'</span></p><span style="display: block; height: 2px;"></span><p style="position: relative;"><sup class="jss500 jss501">'.$keytabl[14].'</sup>'.$keytabl[6].'<span class="jss119">:'.$keytabl[7].'&nbsp;</span></p>
							</div>
							<div class="jss112 jss113" style="width: 61%;"><span class="jss114" style="width: unset; padding: 0px;">['.$keytabl[9].','.$keytabl[8].']</span><p class="jss515">Garé pendant '.$keytabl[0].'</p>
							</div>
							</div>
							</div>
							</div>
							 ';

														
						}

						

						$v++;
					}

				}



			}



		//calcul du carburant consommé
			if(!empty($get_chauffeur['KILOMETRAGE'])){

				$carburant_before=$get_chauffeur['KILOMETRAGE'] * $nvldistance_arrondie;

				$carburant = round($carburant_before);



			}else{

				$carburant='N/A  ';
			}



		//carte
			$arret = '';

			if(!empty($get_data_arret)){


				foreach ($get_data_arret as $key_arret) {

					$my_selectone_element = $this->getBindParms('id,tracking_data.date,date_format(tracking_data.date,"%H:%i") as heure,latitude,longitude', 'tracking_data', 'CODE_COURSE= "'.$key_arret['CODE_COURSE'].'"' , '`id` ASC');
					$my_selectone_element=str_replace('\"', '"', $my_selectone_element);
					$my_selectone_element=str_replace('\n', '', $my_selectone_element);
					$my_selectone_element=str_replace('\"', '', $my_selectone_element);

					$one_element = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element);

					$arret.="{
						'type': 'Feature',
						'properties': {
							'description':
							'<center><img src=\'".base_url('upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT'].'')."\' width=\'80px\' height=\'80px\' style=\'border-radius: 50%\' alt=\'\'></center><hr><i class=\'bi bi-person-fill\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['NOM']." ".$get_chauffeur['PRENOM']."<br><i class=\'bi bi-phone\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['NUMERO_TELEPHONE']."<br><i class=\'bi bi-envelope\'></i> &nbsp;&nbsp;&nbsp;".$get_chauffeur['ADRESSE_MAIL']."<br><i class=\'bi bi-textarea-resize\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['PLAQUE']."<br><i class=\'bi bi-stopwatch\'></i>&nbsp;&nbsp;&nbsp;".$one_element['heure']."<br><i class=\'bi bi-geo-fill\'></i>&nbsp;&nbsp;&nbsp;[".$one_element['latitude'].",".$one_element['longitude']."]'
							},
							'geometry': {
								'type': 'Point',
								'coordinates': [".$one_element['longitude'].", ".$one_element['latitude']."]
							}
							},
							";					

						}

					}else{
						$number='1';

						$arret.='['.$number.','.$number.'],';

					}

					$arret.='';

					$arret = str_replace('', "", $arret);

					$my_selectprovinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME,OBJECTIF,PROVINCE_LATITUDE,PROVINCE_LONGITUDE,POLY,COLOR', 'provinces', '1 ' , 'PROVINCE_ID ASC');
					$provinces = $this->ModelPs->getRequete($proce_requete, $my_selectprovinces);
					$limites='';
					$i=1;
					foreach ($provinces as $key_provinces) {
						$polyg = $key_provinces['POLY'];
						$prov_name = $key_provinces['PROVINCE_NAME'];

					// $limites.= 'var world'.$i.' = 
					// 	'.$polyg.'

					// var styleState'.$i.' = {
					// 	color: "'.$key_provinces['COLOR'].'",
					// 	weight: 1 
					// };

					// myLayer =

					// L.geoJson(world'.$i.', {
					// 	onEachFeature: function(feature, layer) {
					// 		layer.bindPopup(\'<table><tr><td><i class="mdi mdi-spin"></i> <b>'.$prov_name.'</b></td></tr></table>\');
					// 		},style: styleState'.$i.'
					// 	}).addTo(map_map);';

					// 	$i++;

					}
					$my_selectvit_moy = $this->getBindParms('id,AVG(`vitesse`) moy_vitesse,date_format(`date`,"%d/%m/%Y") as date_base', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
					$my_selectvit_moy=str_replace('\"', '"', $my_selectvit_moy);
					$my_selectvit_moy=str_replace('\n', '', $my_selectvit_moy);
					$my_selectvit_moy=str_replace('\"', '', $my_selectvit_moy);

					$vit_moy = $this->ModelPs->getRequeteOne($proce_requete, $my_selectvit_moy);
					$my_selectdate_debfin = $this->getBindParms('id,MIN(`date`) datemin,MAX(`date`) datemax,date_format(`date`,"%d/%m/%Y") as date_base', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
					$my_selectdate_debfin=str_replace('\"', '"', $my_selectdate_debfin);
					$my_selectdate_debfin=str_replace('\n', '', $my_selectdate_debfin);
					$my_selectdate_debfin=str_replace('\"', '', $my_selectdate_debfin);

					$date_debfin = $this->ModelPs->getRequeteOne($proce_requete, $my_selectdate_debfin);


					$my_selectvitesse_max= $this->getBindParms(' MAX(vitesse) AS max_vitesse', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
					$my_selectvitesse_max=str_replace('\"', '"', $my_selectvitesse_max);
					$my_selectvitesse_max=str_replace('\n', '', $my_selectvitesse_max);
					$my_selectvitesse_max=str_replace('\"', '', $my_selectvitesse_max);

					$vitesse_max = $this->ModelPs->getRequeteOne($proce_requete, $my_selectvitesse_max);

					if(empty($vitesse_max['max_vitesse']))

					{
						$vitesse_max['max_vitesse']=0;
					}




					$track = '';

					if ($CODE_COURSE!='') {
						if(!empty($get_data)){


							foreach ($get_data as $key) {

								$track.='['.$key['longitude'].','.$key['latitude'].'],';
							}



						}else{
							$number='1';

							$track.='['.$number.','.$number.'],';


						}

						$track.='@';

						$track = str_replace(',@', "", $track);
					}else{

						$track.= '';

					}


					
					
					

					$data['track'] = $track;
					$data['vit_moy'] = $vit_moy;
					$data['date_debfin'] = $date_debfin;
					$data['arret'] = $arret;
					$data['get_chauffeur'] = $get_chauffeur;
					$data['get_arret'] = $get_arret;
					$data['distance_finale'] = $nvldistance_arrondie;
					$data['carburant'] = $carburant;
					$data['CODE'] = $CODE;
					$data['DATE'] = $DATE_SELECT;
					$data['score'] = $score_finale;
				// $data['ligne_arret'] = $ligne_arret;
				// $data['delimit_prov'] = $delimit_prov;
					$data['limites']=$limites;
					$data['card_card']=$card_card;
					$data['tabl']=$tabl;
					$data['mark_vprim']=$mark_vprim;


					$map_filtre = $this->load->view('Maptracking_view',$data,TRUE);

					$output = array(
						"distance_finale" => $nvldistance_arrondie,
						"carburant" => $carburant,
						"DATE"=>$DATE_SELECT,
						"CODE"=>$CODE,
						"map_filtre"=>$map_filtre,
						"score_finale"=>$point_final,
						"vitesse_max"=>$vitesse_max['max_vitesse'],
					// "ligne_arret"=>$ligne_arret


					);

					echo json_encode($output);

				}

			//Fonction pour voir la position de la voiture
				function position_voiture($CODE){
					$fontinfo = $this->input->post('rtoggle');
					$info = '';

					if($fontinfo == ''){

						$info = 'streets';

					}else{

						$info = $fontinfo;
					}

					$data['info'] = $info;
					$CODE_VEH=$this->uri->segment(4);
					$data['CODE_VEH']=$CODE_VEH;
					$this->load->view('Position_vehicule_View',$data);



				}


			//Fonction pour afficher la position de la voiture
				function getmap($CODE){

					$DATE_SELECT = $this->input->post('DATE_DAT');

					$proce_requete = "CALL `getRequete`(?,?,?,?);";

				// $my_selectget_data= $this->getBindParms(' id,latitude,longitude', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" AND `id` = (SELECT MAX(`id`) FROM tracking_data ) ' , '`id` ASC');
					$my_selectget_data= $this->getBindParms('id,latitude,longitude,ignition,vitesse', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" AND  `id` = (SELECT MAX(`id`) FROM tracking_data)' , '`id` ASC');
					$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
					$my_selectget_data=str_replace('\n', '', $my_selectget_data);
					$my_selectget_data=str_replace('\"', '', $my_selectget_data);

					$get_data = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_data);


					if ($get_data['ignition']==0) 
					{
					// color red
						$color = '255, 0, 0';
					}
					else
					{
					//color blue
						$color = '20, 100, 500';

					}



					$data = '{"name":"iss","id":25544,"latitude":'.$get_data['latitude'].',"longitude":'.$get_data['longitude'].',"altitude":427.6731067247,"vitesse":'.$get_data['vitesse'].',"ignition":"'.$color.'","footprint":4546.2965721564,"timestamp":1690338162,"daynum":2460151.5990972,"solar_lat":19.512848632241,"solar_lon":145.96751425687,"units":"kilometers"}';


					echo $data;
				}

			 //Fonction pour la selection des heures
				function get_heures()
				{
					$html="<option value=''>Séléctionner</option>";

					$proce_requete = "CALL `getRequete`(?,?,?,?);";



					$my_select_heure_trajet = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', '1', '`HEURE_ID` ASC');
					$heure_trajet = $this->ModelPs->getRequete($proce_requete, $my_select_heure_trajet);
					foreach ($heure_trajet as $heure_trajets)
					{
						$html.="<option value='".$heure_trajets['HEURE_ID']."'>".$heure_trajets['HEURE']."</option>";
					}

					echo json_encode($html);
				}



			//fonction clones alerte exces de vitesse
				function alerte_exces_vitesse()
				{
					$proce_requete = "CALL `getRequete`(?,?,?,?);";



					$get_device = $this->Model->getRequete('SELECT device_uid FROM tracking_data where 1 GROUP BY device_uid');
					foreach ($get_device as $keyget_device) {


						$my_selectget_data= $this->getBindParms('max(id) as maximum,latitude,longitude,ignition,vitesse','tracking_data',' MESSAGE=0 AND device_uid ="'.$keyget_device['device_uid'].'"' , '`id` ASC');
						$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
						$my_selectget_data=str_replace('\n', '', $my_selectget_data);
						$my_selectget_data=str_replace('\"', '', $my_selectget_data);

						$get_data = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_data);
						if($get_data['vitesse']>50){


							$my_selectget_proprio=$this->getBindParms('vehicule.`PROPRIETAIRE_ID`,proprietaire.EMAIL,proprietaire.NOM_PROPRIETAIRE,proprietaire.PRENOM_PROPRIETAIRE,vehicule.PLAQUE,vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE', 'vehicule join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE', '1 AND vehicule.CODE ="'.$keyget_device['device_uid'].'"' , 'vehicule.CODE ASC');
							$my_selectget_proprio=str_replace('\"', '"', $my_selectget_proprio);
							$my_selectget_proprio=str_replace('\n', '', $my_selectget_proprio);
							$my_selectget_proprio=str_replace('\"', '', $my_selectget_proprio);

							$get_proprio = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_proprio);

							$my_selectget_chauffeur=$this->getBindParms('chauffeur_vehicule.`CHAUFFEUR_ID`,chauffeur.ADRESSE_MAIL,chauffeur.NOM,chauffeur.PRENOM', '`chauffeur_vehicule` join chauffeur on chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID', '1 AND `STATUT_AFFECT`=1 AND CODE ="'.$keyget_device['device_uid'].'"' , 'chauffeur_vehicule.CODE ASC');
							$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
							$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
							$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);

							$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);

						//Notification au proprietaire du vehicule
							$mess="Cher(e) <b>".$get_proprio['NOM_PROPRIETAIRE']." ".$get_proprio['PRENOM_PROPRIETAIRE']."</b>,<br><br>

							Votre véhicule ".$get_proprio['DESC_MARQUE']." / ".$get_proprio['DESC_MODELE']." ayant ".$get_proprio['PLAQUE']." comme plaque d'immatriculation  est en train d'être conduit  à ".$get_data['vitesse']." Km/h !<br>
							Veuillez contacter votre chauffeur ".$get_chauffeur['NOM']." ".$get_chauffeur['PRENOM']." !
							";
							$subjet="Excès de vitesse";
							$message1=$this->notifications->send_mail(array($get_proprio['EMAIL']),$subjet,array(),$mess,array());
						//Notification au chauffeur
							$mess2="Cher(e) <b>".$get_chauffeur['NOM']." ".$get_chauffeur['PRENOM']."</b>,<br><br>
							Vous êtes entrain de conduire à une vitesse de ".$get_data['vitesse']." Km/h <br>
							Veuillez ralentir pour votre sécurité!

							";
							$subjet="Excès de vitesse";
							$message2=$this->notifications->send_mail(array($get_chauffeur['ADRESSE_MAIL']),$subjet,array(),$mess2,array());

							$update=$this->Model->update('tracking_data',array('id'=>$get_data['maximum']),array('MESSAGE'=>1));
						}

					}

				}
			//fonction clones alerte assurance termine
				function check_assurance(){
					$proce_requete = "CALL `getRequete`(?,?,?,?);";
					$DATE_JOUR=date('Y-m-d');
					$my_selectget_assurance=$this->getBindParms('DATE_DEBUT_ASSURANCE,DATE_FORMAT(DATE_FIN_ASSURANCE,"%Y/%m/%d") as date_fin,DATE_FORMAT(DATE_FIN_ASSURANCE,"%d/%m/%Y") as date_fin_format,DATE_FORMAT(DATE_FIN_CONTROTECHNIK,"%Y/%m/%d") as date_fin_contr_technik,DATE_FORMAT(DATE_FIN_CONTROTECHNIK,"%d/%m/%Y") as date_fin_contr_technikformat,proprietaire.EMAIL,vehicule.PLAQUE,vehicule_marque.DESC_MARQUE,vehicule_modele.DESC_MODELE', 'vehicule join proprietaire on proprietaire.PROPRIETAIRE_ID=vehicule.PROPRIETAIRE_ID JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE', '1 AND vehicule.IS_ACTIVE=1 AND proprietaire.IS_ACTIVE=1' , 'proprietaire.PROPRIETAIRE_ID ASC');
					$my_selectget_assurance=str_replace('\"', '"', $my_selectget_assurance);
					$my_selectget_assurance=str_replace('\n', '', $my_selectget_assurance);
					$my_selectget_assurance=str_replace('\"', '', $my_selectget_assurance);

					$get_assurance = $this->ModelPs->getRequete($proce_requete, $my_selectget_assurance);

				// print_r(expression)
					foreach ($get_assurance as $key) {
						$nb_jr_new=1;
						$your_date_new = strtotime("-".$nb_jr_new." day", strtotime($key['date_fin']));
						$new_date_new = date("Y-m-d", $your_date_new++);

						$your_date_new_technik = strtotime("-".$nb_jr_new." day", strtotime($key['date_fin_contr_technik']));
						$new_date_new_technik = date("Y-m-d", $your_date_new_technik++);
						if ($DATE_JOUR==$new_date_new) {
							$subjet="Assurance expirée";

							$email = $key['EMAIL'];
							$message="Cher propriétaire du véhicule ".$key['DESC_MARQUE']." / ".$key['DESC_MODELE']." : ".$key['PLAQUE']." ,Votre assurance expirera demain le '".$key['date_fin_format']."'!<br> veuillez la renouveler !";
							$this->notifications->send_mail(array($email),$subjet,array(),$message,array());
						}
						if($DATE_JOUR==$new_date_new_technik){

							$subjet="Contrôle technique expiré";

							$email = $key['EMAIL'];
							$message="Cher propriétaire  du véhicule ".$key['DESC_MARQUE']." / ".$key['DESC_MODELE']." : ".$key['PLAQUE'].",Votre Contrôle technique expirera demain le '".$key['date_fin_contr_technikformat']."'! <br> veuillez la renouveler !";
							$this->notifications->send_mail(array($email),$subjet,array(),$message,array());

						}
					}

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
			}?>