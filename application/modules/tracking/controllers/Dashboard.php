<?php 

/**
 * Fait par Nzosaba Santa Milka
 * 68071895
 * santa.milka@mediabox.bi
 * Le 11/2/2024
 * Dashboards
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

		$my_selectvehicule = $this->getBindParms('VEHICULE_ID,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,vehicule.CODE', 'vehicule join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND md5(vehicule.CODE) ="'.$CODE_VEH.'"', '`VEHICULE_ID` ASC');
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


		$distance_finale=0;
		$distance_arrondie=0;
		$score_finale=0;
		$critere='';

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
		

		$info = '';

		if($fontinfo == ''){

			$info = 'streets';

		}else{

			$info = $fontinfo;
		}

		$data['info'] = $info;

		// requete pour recuperer le chauffeur
		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,KILOMETRAGE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND md5(chauffeur_vehicule.CODE) ="'.$CODE.'"', '`PROPRIETAIRE_ID` ASC');
		$my_selectget_chauffeur=str_replace('\"', '"', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\n', '', $my_selectget_chauffeur);
		$my_selectget_chauffeur=str_replace('\"', '', $my_selectget_chauffeur);
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);


		//requete pour recuperer tout le trajet parcouru
		$my_selectget_data = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date', 'tracking_data', ' md5(device_uid) ="'.$CODE.'" '.$critere.' ', '`id` ASC');
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\n', '', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '', $my_selectget_data);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"

		$get_data = $this->ModelPs->getRequete($proce_requete, $my_selectget_data);

		 // print_r('select `id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date from tracking_data where md5(device_uid) ="'.$CODE.'" '.$critere.'');die();

		
		//requete pour recuperer les arrets
		

		$my_selectget_arret = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date,date_format(tracking_data.date,"%H:%i") as heure', 'tracking_data', ' ignition=0 AND md5(device_uid) ="'.$CODE.'" '.$critere.' ', '`id` ASC');
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
		$my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'" '.$critere.'  AND ignition=0' , '`id` ASC');
		$my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"
		$get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);
		$nvldistance=0;

		$my_selectmin_arret = $this->getBindParms('MIN(id) as minimum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.'' , '`id` ASC');
		$my_selectmin_arret=str_replace('\"', '"', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\n', '', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\"', '', $my_selectmin_arret);
		//AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"
		$min_arret = $this->ModelPs->getRequeteOne($proce_requete, $my_selectmin_arret);

		$my_selectmax_arret = $this->getBindParms('MAX(id) as maximum', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"'.$critere.'' , '`id` ASC');
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




		$ligne_arret='';

		//calcul du temps d'arret
		if(!empty($get_data)){

			$tabl=array();

			foreach ($get_data as $value_get_arret) {
				if ($value_get_arret['ignition']==0) {

					$date_compare1=$value_get_arret['date'];

					$id2=$value_get_arret['id']+1;


					$my_select_date_compare2 = $this->getBindParms('tracking_data.date', 'tracking_data', 'id='.$id2, 'id ASC');
					$date_compare2 = $this->ModelPs->getRequete($proce_requete, $my_select_date_compare2);


					foreach ($date_compare2 as $keydate_compare2) {
						$tabl[]=$this->notifications->ago($date_compare1,$keydate_compare2['date']);
					}

					

				}

			}
			$v=1;
			if (!empty($tabl)) {
				foreach ($tabl as $keytabl) {



					$ligne_arret.=" <div class='activity-item d-flex'>
					<div class='activite-label'>Arrêt ".$v."</div>
					<i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
					<div class='activity-content'>
					<a href='#' class='fw-bold text-dark'>".$keytabl."</a> 
					</div>
					</div>";
					$v++;
				}
			}else{

				$ligne_arret.=" 
				Pas d'arret
				";


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

		if(!empty($get_arret)){


			foreach ($get_arret as $key_arret) {

				$arret.="{
					'type': 'Feature',
					'properties': {
						'description':
						'<center><img src=\'".base_url('upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT'].'')."\' width=\'80px\' height=\'80px\' style=\'border-radius: 50%\' alt=\'\'></center><hr><i class=\'bi bi-person-fill\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['NOM']." ".$get_chauffeur['PRENOM']."<br><i class=\'bi bi-phone\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['NUMERO_TELEPHONE']."<br><i class=\'bi bi-envelope\'></i> &nbsp;&nbsp;&nbsp;".$get_chauffeur['ADRESSE_MAIL']."<br><i class=\'bi bi-textarea-resize\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['PLAQUE']."<br><i class=\'bi bi-stopwatch\'></i>&nbsp;&nbsp;&nbsp;".$key_arret['heure']."<br><i class=\'bi bi-geo-fill\'></i>&nbsp;&nbsp;&nbsp;[".$key_arret['latitude'].",".$key_arret['longitude']."]'
						},
						'geometry': {
							'type': 'Point',
							'coordinates': [".$key_arret['longitude'].", ".$key_arret['latitude']."]
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
				// $provinces = $this->ModelPs->getRequete($proce_requete, $my_selectprovinces);
				// $delimit_prov='';
				// foreach ($provinces as $key_provinces) {
				// 	$delimit_prov.="{

				// 		'type': 'geojson',
				// 		'data': '".$key_provinces['POLY']."'
				// 	},


				// 	";

				// }
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
				$data['ligne_arret'] = $ligne_arret;
				// $data['delimit_prov'] = $delimit_prov;

				

				$map_filtre = $this->load->view('Maptracking_view',$data,TRUE);

				$output = array(
					"distance_finale" => $nvldistance_arrondie,
					"carburant" => $carburant,
					"DATE"=>$DATE_SELECT,
					"CODE"=>$CODE,
					"map_filtre"=>$map_filtre,
					"score_finale"=>$point_final,
					"vitesse_max"=>$vitesse_max['max_vitesse'],
					"ligne_arret"=>$ligne_arret





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
				$my_selectget_data= $this->getBindParms('max(id),latitude,longitude,ignition', 'tracking_data', '1 AND md5(device_uid) ="'.$CODE.'"' , '`id` ASC');
				$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
				$my_selectget_data=str_replace('\n', '', $my_selectget_data);
				$my_selectget_data=str_replace('\"', '', $my_selectget_data);

				$get_data = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_data);

				

				$data = '{"name":"iss","id":25544,"latitude":'.$get_data['latitude'].',"longitude":'.$get_data['longitude'].',"altitude":427.6731067247,"velocity":27556.638607061,"visibility":"eclipsed","footprint":4546.2965721564,"timestamp":1690338162,"daynum":2460151.5990972,"solar_lat":19.512848632241,"solar_lon":145.96751425687,"units":"kilometers"}';



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



			//fonction des clones

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
					// print_r($get_data);die();
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