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
		$CHAUFFEUR_ID=$this->uri->segment(5);
		// if (!empty($CHAUFFEUR_ID)) {
		// 	$proce_requete = "CALL `getRequete`(?,?,?,?);";
		// 	$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND md5(chauffeur_vehicule.CHAUFFEUR_ID) ="'.$CHAUFFEUR_ID.'" and md5(chauffeur_vehicule.CODE) ="'.$CODE_VEH.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
		// 	$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
		// 	$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
		// 	$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
		// 	$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);
		// }else{

			$proce_requete = "CALL `getRequete`(?,?,?,?);";
			$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND md5(chauffeur_vehicule.CODE) ="'.$CODE_VEH.'"', '`CHAUFFEUR_VEHICULE_ID` ASC');
			$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
			$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
			$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
			$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);

		// }
		
		

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

		// print_r($CODE);die();
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
			$critere1.=' AND md5(tracking_data.CODE_COURSE) ="'.$CODE_COURSE.'" AND md5(tracking_data.device_uid)="'.$CODE.'"';
			

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
		$get_data = $this->ModelPs->getRequete($proce_requete, $my_selectget_data);

		// print_r($get_data);die();

		//Requete pour recuperer partout ou il ya eu exces de vitesse
		$my_selectget_exces_vitesse = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H %i") as hour', 'tracking_data', ' vitesse >= 50 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_exces_vitesse=str_replace('\"', '"', $my_selectget_exces_vitesse);
		$my_selectget_exces_vitesse=str_replace('\"', '"', $my_selectget_exces_vitesse);
		$my_selectget_exces_vitesse=str_replace('\n', '', $my_selectget_exces_vitesse);
		$my_selectget_exces_vitesse=str_replace('\"', '', $my_selectget_exces_vitesse);
		$get_data_exces_vitesse = $this->ModelPs->getRequete($proce_requete, $my_selectget_exces_vitesse);

		////Requete pour recuperer partout ou il ya eu accident
		$my_selectget_accident= $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H %i") as hour', 'tracking_data', ' accident=1 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
		$my_selectget_accident=str_replace('\"', '"', $my_selectget_accident);
		$my_selectget_accident=str_replace('\"', '"', $my_selectget_accident);
		$my_selectget_accident=str_replace('\n', '', $my_selectget_accident);
		$my_selectget_accident=str_replace('\"', '', $my_selectget_accident);
		$get_data_accident = $this->ModelPs->getRequete($proce_requete, $my_selectget_accident);
		
		//requete pour recuperer les arrets
		$my_selectget_arret = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H:%i") as heure', 'tracking_data', ' ignition=0 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' ', '`id` ASC');
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


		//calcul de la distance	par filtre	
		$my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.'  AND ignition=0' , '`id` ASC');
		$my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);
		$get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);
		$nvldistance=0;

		$my_selectmin_arret = $this->getBindParms('MIN(id) as minimum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.' ' , '`id` ASC');
		$my_selectmin_arret=str_replace('\"', '"', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\n', '', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\"', '', $my_selectmin_arret);
		$min_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmin_arret);

		$my_selectmax_arret = $this->getBindParms('MAX(id) as maximum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.'' , '`id` ASC');
		$my_selectmax_arret=str_replace('\"', '"', $my_selectmax_arret);
		$my_selectmax_arret=str_replace('\n', '', $my_selectmax_arret);
		$my_selectmax_arret=str_replace('\"', '', $my_selectmax_arret);
		$max_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmax_arret);


		$my_selectmin_arret_plus = $this->getBindParms('id as arret_min_deux', 'tracking_data', '1 AND id = (SELECT MIN(id) FROM tracking_data WHERE id NOT IN (SELECT MIN(id) FROM tracking_data)) AND md5(device_uid) ="'.$CODE.'"'.$critere.' '.$critere1.'' , '`id` ASC');
		$my_selectmin_arret_plus=str_replace('\"', '"', $my_selectmin_arret_plus);
		$my_selectmin_arret_plus=str_replace('\n', '', $my_selectmin_arret_plus);
		$my_selectmin_arret_plus=str_replace('\"', '', $my_selectmin_arret_plus);
		$min_arret_plus = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmin_arret_plus);
		// $min_arret_plus=$min_arret['minimum']+1;

		// print_r($max_arret);die();
		$nvldistance_arrondie=0;
		if(!empty($min_arret) && !empty($min_arret_plus) && !empty($max_arret)){

			for ($i=$min_arret['minimum'],$j=$min_arret_plus['arret_min_deux']; $i <$max_arret['maximum'],$j <$max_arret['maximum'] ; $i++,$j++) {

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
				if(!empty($point_distance) && !empty($point_distance2)){

					$nvldistance+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
				}else{

					$nvldistance+=0;
				} 


			}
			$nvldistance_arrondie=round($nvldistance);
		}
		

		//Calcul du score
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

				$my_selectrequete = $this->getBindParms('count(id) as idsup', 'tracking_data', '1 AND vitesse >= 50 AND tracking_data.id between "'.$data_arret[$i].'" AND "'.$data_arret[$j].'"' , '`id` ASC');
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
		if(!empty($CODE_COURSE)){
			$my_selectrequete_excsee = $this->getBindParms('id', 'tracking_data', '1 AND vitesse >= 50 AND md5(tracking_data.CODE_COURSE)= "'.$CODE_COURSE.'"' , '`id` ASC');
			$my_selectrequete_excsee=str_replace('\"', '"', $my_selectrequete_excsee);
			$my_selectrequete_excsee=str_replace('\n', '', $my_selectrequete_excsee);
			$my_selectrequete_excsee=str_replace('\"', '', $my_selectrequete_excsee);
			$requete_exces = $this->ModelPs->getRequete($proce_requete, $my_selectrequete_excsee);
			 // print_r($requete_exces);die();
			if(!empty($requete_exces)){

				$point_final=$point_point-1;
				
			}
		}

		//le Resume des courses se trouvant sur la carte
		$card_card='';		
		$get_data_arret_prime = $this->Model->getRequete('SELECT CODE_COURSE FROM tracking_data WHERE md5(device_uid) ="'.$CODE.'" '.$critere.' '.$critere1.' GROUP BY CODE_COURSE');
		$tabl=' ';
		$mark_v='';
		$mark_vprim='';
		if (!empty($CODE_COURSE) && !empty($get_data_arret_prime)) {
			$tabl_prime=array();

			foreach ($get_data_arret_prime as $value_get_arret_prim) {
				$my_selectone_element_prim = $this->getBindParms('id,date as date_actu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,ignition,latitude,longitude', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_prim['CODE_COURSE'].'" and md5(tracking_data.device_uid)="'.$CODE.'" ' , '`id` ASC');
				$my_selectone_element_prim=str_replace('\"', '"', $my_selectone_element_prim);
				$my_selectone_element_prim=str_replace('\n', '', $my_selectone_element_prim);
				$my_selectone_element_prim=str_replace('\"', '', $my_selectone_element_prim);

				$one_element_prim = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element_prim);

				
				$my_select_date_compare2_prim = $this->getBindParms('date as date_actu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,latitude,longitude,date_format(tracking_data.date,"%d %m") as day_month', 'tracking_data', ' CODE_COURSE="'.$value_get_arret_prim['CODE_COURSE'].'" and md5(tracking_data.device_uid)="'.$CODE.'" ', 'id DESC');
				$my_select_date_compare2_prim=str_replace('\"', '"', $my_select_date_compare2_prim);
				$my_select_date_compare2_prim=str_replace('\n', '', $my_select_date_compare2_prim);
				$my_select_date_compare2_prim=str_replace('\"', '', $my_select_date_compare2_prim);
				$date_compare2_prim = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date_compare2_prim);
				$tabl_prime[]=[$this->notifications->ago($one_element_prim['date_actu'],$date_compare2_prim['date_actu']),$one_element_prim['CODE_COURSE'],$one_element_prim['date_actu'],$date_compare2_prim['date_actu'],$one_element_prim['hour'],$one_element_prim['sec'],$date_compare2_prim['hour'],$date_compare2_prim['sec'],$one_element_prim['latitude'],$one_element_prim['longitude'],$date_compare2_prim['latitude'],$date_compare2_prim['longitude'],$one_element_prim['ignition'],$one_element_prim['day_month'],$date_compare2_prim['day_month']];
				


			}

			if (!empty($tabl_prime)) {
				foreach ($tabl_prime as $keytablprim) {

					$mark_vprim=$mark_vprim.$keytablprim[9].'<>'.$keytablprim[8].'<>'.$keytablprim[11].'<>'.$keytablprim[10].'<>'.$keytablprim[12].'<>'.$keytablprim[4].'<>'.$keytablprim[6].'<>@';

				}
			}
		}
		$distdislegend=0;
		$get_data_arret = $this->Model->getRequete('SELECT CODE_COURSE FROM tracking_data WHERE CODE_COURSE IS NOT NULL and  md5(device_uid) ="'.$CODE.'" '.$critere.'  GROUP BY CODE_COURSE');
		$dataplace = '';
		$dataplace1 = '';
		$card_card1='';
		//calcul du temps d'arret
		if(!empty($get_data_arret)){
			$tabl=array();
			$dist_geofenc=array();
			$depasse_zone=0;
			foreach ($get_data_arret as $value_get_arret_code) {
				$my_selectone_element = $this->getBindParms('id,tracking_data.date as date_vu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,md5(CODE_COURSE) as code_course_crypt,ignition,latitude,longitude,CEINTURE,CLIM', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_code['CODE_COURSE'].'" and md5(tracking_data.device_uid)="'.$CODE.'" ' , '`id` ASC');
				$my_selectone_element=str_replace('\"', '"', $my_selectone_element);
				$my_selectone_element=str_replace('\n', '', $my_selectone_element);
				$my_selectone_element=str_replace('\"', '', $my_selectone_element);
				$one_element = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element);

				$my_select_date_compare2 = $this->getBindParms('id,tracking_data.date as date_vu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,latitude,longitude,date_format(tracking_data.date,"%d %m") as day_month', 'tracking_data', ' CODE_COURSE="'.$value_get_arret_code['CODE_COURSE'].'" and md5(tracking_data.device_uid)="'.$CODE.'" ', 'id DESC');
				$my_select_date_compare2=str_replace('\"', '"', $my_select_date_compare2);
				$my_select_date_compare2=str_replace('\n', '', $my_select_date_compare2);
				$my_select_date_compare2=str_replace('\"', '', $my_select_date_compare2);
				$date_compare2 = $this->ModelPs->getRequeteOne($proce_requete, $my_select_date_compare2);

				$my_selectone_element_moins = $this->getBindParms('id', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_code['CODE_COURSE'].'" and md5(tracking_data.device_uid)="'.$CODE.'" AND id > "'.$one_element['id'].'" ' , '`id` ASC');
				$my_selectone_element_moins=str_replace('\"', '"', $my_selectone_element_moins);
				$my_selectone_element_moins=str_replace('\n', '', $my_selectone_element_moins);
				$my_selectone_element_moins=str_replace('\"', '', $my_selectone_element_moins);

				$min_arret_plus_plus = $this->ModelPs->getRequeteOne($proce_requete, $my_selectone_element_moins);

				
				// print_r($date_compare2);die();
				if(!empty($one_element) && !empty($min_arret_plus_plus) && !empty($date_compare2)){

					for ($i=$one_element['id'],$j=$min_arret_plus_plus['id']; $i <$date_compare2['id'],$j <$date_compare2['id'] ; $i++,$j++) {


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
						if(!empty($point_distance) && !empty($point_distance2)){

							$distdislegend+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
						}else{

							$distdislegend+=0;

						}


					}

				}
				

				//geofence

				$my_select_geo_el = $this->getBindParms('id,tracking_data.date as date_vu,date_format(tracking_data.date,"%H %i") as hour,date_format(tracking_data.date,"%s") as sec,date_format(tracking_data.date,"%d %m") as day_month,CODE_COURSE,md5(CODE_COURSE) as code_course_crypt,ignition,latitude,longitude,CEINTURE,CLIM', 'tracking_data', 'CODE_COURSE= "'.$value_get_arret_code['CODE_COURSE'].'" and md5(tracking_data.device_uid)="'.$CODE.'"' , '`id` ASC');
				$my_select_geo_el=str_replace('\"', '"', $my_select_geo_el);
				$my_select_geo_el=str_replace('\n', '', $my_select_geo_el);
				$my_select_geo_el=str_replace('\"', '', $my_select_geo_el);

				$elt_geofence_course = $this->ModelPs->getRequete($proce_requete, $my_select_geo_el);

				$my_selectdelim = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID,COORD', 'chauffeur_vehicule join chauffeur_zone_affectation on chauffeur_zone_affectation.CHAUFFEUR_VEHICULE_ID =chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND md5(CODE) ="'.$CODE.'" ' , 'chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ASC');
				$my_selectdelim=str_replace('\"', '"', $my_selectdelim);
				$my_selectdelim=str_replace('\n', '', $my_selectdelim);
				$my_selectdelim=str_replace('\"', '', $my_selectdelim);
				$zones_delim = $this->ModelPs->getRequeteOne($proce_requete, $my_selectdelim);

				$COORD_POLYGONE = $zones_delim['COORD'];
				$COORD_POLYGONE = str_replace('[[', '', $COORD_POLYGONE);
				$COORD_POLYGONE = str_replace(']]', '', $COORD_POLYGONE);

				$COORD_POLYGONE_P = $COORD_POLYGONE;

				$COORD_POLY = $COORD_POLYGONE_P;

				$COORD_POLY = explode('],[', $COORD_POLY);
				$COORD_POLY = str_replace('[', '', $COORD_POLY);
				$COORD_POLY = str_replace(']', '', $COORD_POLY);

				$COORD_POLY_SEND=array();

				for ($i=0; $i < count($COORD_POLY); $i++) { 


					$COORD_POLY2 = $COORD_POLY[$i];

					$COORD_POLY2 = str_replace(']', '', $COORD_POLY2);
					$COORD_POLY2 = str_replace('[', '', $COORD_POLY2);

					$COORD_POLY2 = explode(',', $COORD_POLY2);

					$COORD_POLY_SEND[].= $COORD_POLY2[0].','.$COORD_POLY2[1];

				}
				
				$polygon_die = array();
				foreach ($COORD_POLY_SEND as $coord) {
					list($x, $y) = explode(",", $coord);
					$polygon_die[] = array((string)$x, (string)$y);
				}
				$COORD_POLY_repl = str_replace('[', '', $polygon_die);
				
				foreach ($elt_geofence_course as $key_geo) {

					
					$point_check=array($key_geo['longitude'],$key_geo['latitude']);
					$response[]=$this->Model->isPointInsidePolygon($point_check, $COORD_POLY_repl);

				}

				foreach ($response as $keyresponse) {
					if($keyresponse==1){
						$depasse_zone=1;
					}else{
						$depasse_zone=2;
					}
				}

				$tabl[]=[$this->notifications->ago($one_element['date_vu'],$date_compare2['date_vu']),$one_element['code_course_crypt'],$one_element['date_vu'],$date_compare2['date_vu'],$one_element['hour'],$one_element['sec'],$date_compare2['hour'],$date_compare2['sec'],$one_element['latitude'],$one_element['longitude'],$date_compare2['latitude'],$date_compare2['longitude'],$one_element['ignition'],$one_element['day_month'],$date_compare2['day_month'],round($distdislegend),$one_element['CEINTURE'],$one_element['CLIM'],$depasse_zone];


			}

			$data['tabl'] = $tabl;


			$v=1;
			if (!empty($tabl)) 
			{

				$getplacesname = 0;
				$getplacesname1 = 0;

				foreach ($tabl as $keytabl) 
				{

					$getplacesname++;
					$getplacesname1++;


					$mark_v=$mark_v.$keytabl[9].'<>'.$keytabl[8].'<>'.$keytabl[11].'<>'.$keytabl[10].'<>'.$keytabl[12].'<>@';


					if($keytabl[16]==1){
						// $valeur_ceinture='<div class="fa fa-check" style="color:green"></div>';
						$valeur_ceinture='<label style="color:green">ON</label>';

					}else{
						// $valeur_ceinture='<div class="fa fa-close" style="color:red"></div>';
						$valeur_ceinture='<label style="color:#dc3545">OFF</label>';

					}
					if($keytabl[17]==1){
						// $valeur_clim='<div class="fa fa-check" style="color:green"></div>';
						$valeur_clim='<label style="color:green">ON</label>';

					}else{
						// $valeur_clim='<div class="fa fa-close" style="color:red"></div>';
						$valeur_clim='<label style="color:#dc3545">OFF</label>';

					}
					$lat = $keytabl[8];
					$lng = $keytabl[9];

					if($keytabl[18]==1){
						$ch_color='border: solid 1px rgba(128, 128, 128, 0.3);';
						$alert='';

					}else{
						$ch_color='border: solid 1px rgba(220, 53, 69, 1);';
						$card_card1='<div class="card">
						<center><h5 class="card-title" style="font-size: .8rem; color:#dc3545;"><span class="fa fa-warning text-danger"></span> Il a dépassé la zone<span style="font-size: .8rem;"></span></h5></center>

						</div>';
						$alert='<div style="top: 10px;position: absolute;right: 10px;font-size: .8rem; color:#dc3545;"><span class="fa fa-warning text-danger"></span></div>';
					}

					$dataplace.= '<script>

					$(document).ready(function() {


						var url ="https://api.mapbox.com/geocoding/v5/mapbox.places/'.$lng.','.$lat.'.json?access_token=pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A";
						var url1 ="https://api.mapbox.com/geocoding/v5/mapbox.places/'.$keytabl[11].','.$keytabl[10].'.json?access_token=pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A";

						$.get(url, function(data){

							var feature = data.features[0];
							var name = feature.text;
							var type = feature.type;


							$("#getplacesname'.$getplacesname.'").html(name)

							console.log("Les donnees ",data)

							});

							$.get(url1, function(data){

								var feature = data.features[0];
								var name = feature.text;
								var type = feature.type;

								$("#getplacesname1'.$getplacesname1.'").html(name)

								console.log("Les donnees ",data)

								});

								});

								</script>';




								if ($keytabl[12]==1) {

									$card_card.='<div class="card" onclick="change_carte(\''.$keytabl[1].'\')">
									<div class="jss408" style="'.$ch_color.'">
									<div class="jss491" style="cursor: pointer;">
									<div class="jss490">
									<div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation0 css-wmagas" style="margin: 4px 7px 7px auto; color: grey; font-size: 11px;">
									<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="pen-to-square" class="svg-inline--fa fa-pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="font-size: 18px;"><path fill="currentColor" d="M386.7 22.63C411.7-2.365 452.3-2.365 477.3 22.63L489.4 34.74C514.4 59.74 514.4 100.3 489.4 125.3L269 345.6C260.6 354.1 249.9 359.1 238.2 362.7L147.6 383.6C142.2 384.8 136.6 383.2 132.7 379.3C128.8 375.4 127.2 369.8 128.4 364.4L149.3 273.8C152 262.1 157.9 251.4 166.4 242.1L386.7 22.63zM454.6 45.26C442.1 32.76 421.9 32.76 409.4 45.26L382.6 72L440 129.4L466.7 102.6C479.2 90.13 479.2 69.87 466.7 57.37L454.6 45.26zM180.5 281L165.3 346.7L230.1 331.5C236.8 330.2 242.2 327.2 246.4 322.1L417.4 152L360 94.63L189 265.6C184.8 269.8 181.8 275.2 180.5 281V281zM208 64C216.8 64 224 71.16 224 80C224 88.84 216.8 96 208 96H80C53.49 96 32 117.5 32 144V432C32 458.5 53.49 480 80 480H368C394.5 480 416 458.5 416 432V304C416 295.2 423.2 288 432 288C440.8 288 448 295.2 448 304V432C448 476.2 412.2 512 368 512H80C35.82 512 0 476.2 0 432V144C0 99.82 35.82 64 80 64H208z"></path>
									</svg>
									</div>
									</div>
									<div class="jss503">
									<div class="jss509">'.$alert.'
									<div class="jss511"><sup class="jss507">'.$keytabl[13].'</sup><span class="jss510 jss512">'.$keytabl[4].'<span class="jss494">:'.$keytabl[5].'&nbsp;</span></span><span class="jss517"><label id="getplacesname'.$getplacesname.'"></label></span></div><div class="jss513">'.$keytabl[0].'<span style="float: right;">'.$keytabl[15].' km</span></div><div class="jss511"><sup class="jss507">'.$keytabl[14].'</sup><span class="jss510 jss514">'.$keytabl[6].'<span class="jss494">:'.$keytabl[7].'</span></span><span class="jss518"><label id="getplacesname1'.$getplacesname1.'"></label></span>
									</div>
									</div>
									<span class="jss510" style="color:#7D7E7F;">Ceinture<span class="jss494">&nbsp;&nbsp;'.$valeur_ceinture.'</span></span><span class="jss518" style="color:#7D7E7F;">Climatiseur&nbsp;&nbsp;'.$valeur_clim.'</span>
									</div>							
									</div>
									</div>
									</div>';
								}
								elseif ($keytabl[12]==0) 
								{
									$card_card.='<div class="card"  onclick="change_carte(\''.$keytabl[1].'\')">
									<div class="jss110" style="cursor: pointer;'.$ch_color.'" >'.$alert.'
									<div class="jss111">
									<div class="jss112" style="width: 78px; font-size: 11px; font-weight: 500;"><p><sup class="jss500 jss501"> '.$keytabl[13].'</sup>'.$keytabl[4].'<span class="jss119">:'.$keytabl[5].'</span></p><span style="display: block; height: 2px;"></span><p style="position: relative;"><sup class="jss500 jss501">'.$keytabl[14].'</sup>'.$keytabl[6].'<span class="jss119">:'.$keytabl[7].'&nbsp;</span></p>
									</div>
									<div class="jss112 jss113" style="width: 61%;"><span class="jss114" style="padding: 0px;"> <label id="getplacesname'.$getplacesname.'"></label></span><p class="jss515">'.$keytabl[0].' d\'arrêt </p>
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



					//delimitation : geofencing
					$my_selectprovinces = $this->getBindParms('chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID,COORD', 'chauffeur_vehicule join chauffeur_zone_affectation on chauffeur_zone_affectation.CHAUFFEUR_VEHICULE_ID =chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ', '1 AND md5(CODE) ="'.$CODE.'" ' , 'chauffeur_vehicule.CHAUFFEUR_VEHICULE_ID ASC');
					$my_selectprovinces=str_replace('\"', '"', $my_selectprovinces);
					$my_selectprovinces=str_replace('\n', '', $my_selectprovinces);
					$my_selectprovinces=str_replace('\"', '', $my_selectprovinces);
					$provinces_delim = $this->ModelPs->getRequete($proce_requete, $my_selectprovinces);
					$limites='';
					if(!empty($provinces_delim)){

						foreach ($provinces_delim as $keyprovinces_delim) {
							// $limites.=$keyprovinces_delim['COORD'];

							$limites.="{
								'type': 'Feature',
								'properties': {
									'osm_id': '1694988',
									'osm_way_id': null,
									'boundary': 'administrative',
									'admin_leve': '4',
									'name': 'Bujumbura Mairie',
									'ref_COG': null
									},
									'geometry': {
										'type': 'MultiPolygon',
										'coordinates': [".$keyprovinces_delim['COORD']."]
									}
									},
									";

								}

							}
							$i=1;

					//carte
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
							$vitesse_exces = '';
							$geojsonexces='';

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


								if (!empty($get_data_exces_vitesse)) {

									foreach ($get_data_exces_vitesse as $value_data_exces_vitesse) {
										$vitesse_exces.='['.$value_data_exces_vitesse['longitude'].','.$value_data_exces_vitesse['latitude'].'],';
										$geojsonexces.="{
											'type': 'Feature',
											'properties': {
												'description':
												'<span class=\"fa fa-warning\">&nbsp;&nbsp;Excès de vitesse</span>&nbsp;&nbsp;".$value_data_exces_vitesse['vitesse']." Km/h<br><i class=\"fa fa-clock-o \">&nbsp;&nbsp;".$value_data_exces_vitesse['hour']."</p>'
												},
												'geometry': {
													'type': 'Point',
													'coordinates': [".$value_data_exces_vitesse['longitude'].", ".$value_data_exces_vitesse['latitude']."]
												}
												},
												" ;



											}
											$vitesse_exces.='@';

											$vitesse_exces = str_replace(',@', "", $vitesse_exces);
										}else{

											$vitesse_exces.='[1,1],';

										}
							// $vitesse_exces.='@';
							// $vitesse_exces = str_replace(',@', "", $vitesse_exces);
									}else{

										$track.= '';
										$vitesse_exces.= '[1,1]';


									}
									$accident='';
									$geojsonaccident='';
									if(!empty($track)){
										if(!empty($get_data_accident)){
											foreach ($get_data_accident as $keyget_data_accident) {
												$accident.='['.$keyget_data_accident['longitude'].','.$keyget_data_accident['latitude'].'],';
												$geojsonaccident.="{
													'type': 'Feature',
													'properties': {
														'description':
														'<font  style=\"color:red;\">Accident !</font><br><i class=\"fa fa-clock-o\" style=\"color:red;\">&nbsp;&nbsp;".$keyget_data_accident['hour']."</i>'
														},
														'geometry': {
															'type': 'Point',
															'coordinates': [".$keyget_data_accident['longitude'].", ".$keyget_data_accident['latitude']."]
														}
														},
														" ;

														$accident.='@';

														$accident = str_replace(',@', "", $accident);
													}
												}else{

													$accident.='[1,1]';
												}
											}




											$data['geojsonaccident'] = $geojsonaccident;
											$data['track'] = $track;
											$data['get_chauffeur'] = $get_chauffeur;
											$data['get_arret'] = $get_arret;
											$data['distance_finale'] = $nvldistance_arrondie;
											$data['carburant'] = $carburant;
											$data['CODE'] = $CODE;
											$data['DATE'] = $DATE_SELECT;
											$data['score'] = $score_finale;
											$data['limites']=$limites;
											$data['card_card']=$card_card;
											$data['tabl']=$tabl;
											$data['mark_vprim']=$mark_vprim;
											$data['dataplace']=$dataplace;
											$data['vitesse_exces'] = $vitesse_exces;
											$data['geojsonexces'] = $geojsonexces;
											$data['card_card1'] = $card_card1;

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

								//Fonction pour voir la position du vehicule
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