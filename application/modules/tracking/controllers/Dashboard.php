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
		$CODE=$this->uri->segment(4);
		$data['CODE']=$CODE;

		
		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CODE ='.$CODE.'', '`PROPRIETAIRE_ID` ASC');
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);

		$my_select_heure_trajet = $this->getBindParms('`HEURE_ID`,`HEURE`', 'heure', '1', '`HEURE_ID` ASC');
		$heure_trajet = $this->ModelPs->getRequete($proce_requete, $my_select_heure_trajet);


		$data['get_chauffeur']=$get_chauffeur;
		$data['heure_trajet']=$heure_trajet;



		$this->load->view('Tracking_chauffeur_view',$data);


	}



	//Fonction pour les filtres
	function tracking_chauffeur_filtres(){

		$fontinfo = $this->input->post('rtoggle');
		$DATE_SELECT = $this->input->post('DATE_DAT');
		$CODE = $this->input->post('CODE');
		$HEURE1 = $this->input->post('HEURE1');
		$HEURE2 = $this->input->post('HEURE2');


		$distance_finale=0;
		$distance_arrondie=0;
		$score_finale=0;
		$critere="";

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

		if (!empty($HEURE1) && !empty($HEURE2)) 
		{
			$critere.= ' AND date_format(tracking_data.`date`,"%H:%i:%s") between "'.$heure_select1['HEURE'].'" AND "'.$my_select_heure2['HEURE'].'"';



		}

		$info = '';

		if($fontinfo == ''){

			$info = 'streets';

		}else{

			$info = $fontinfo;
		}

		$data['info'] = $info;

		// requete pour recuperer le chauffeur
		$my_selectget_chauffeur = $this->getBindParms('`CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,KILOMETRAGE', '`chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE', '1 AND chauffeur_vehicule.STATUT_AFFECT=1 AND chauffeur_vehicule.CODE ='.$CODE.'', '`PROPRIETAIRE_ID` ASC');
		$get_chauffeur = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_chauffeur);


		//requete pour recuperer tout le trajet parcouru
		$my_selectget_data = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`,date', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"'.$critere.'' , '`id` ASC');
		$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
		$my_selectget_data=str_replace('\n', '', $my_selectget_data);
		$my_selectget_data=str_replace('\"', '', $my_selectget_data);

		$get_data = $this->ModelPs->getRequete($proce_requete, $my_selectget_data);

		
		//requete pour recuperer les arrets
		$my_selectget_arret = $this->getBindParms('`id`,`latitude`,`longitude`,`device_uid`,`ignition`,tracking_data.date,date_format(tracking_data.date,"%H:%i") as heure,ignition', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'" AND ignition=0'.$critere.'' , '`id` ASC');
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

					}else{
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


		//calcul du score			
		$my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'" AND ignition=0' , '`id` ASC');
		$my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
		$my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);

		$get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);


		$my_selectmin_arret = $this->getBindParms('MIN(id)', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
		$my_selectmin_arret=str_replace('\"', '"', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\n', '', $my_selectmin_arret);
		$my_selectmin_arret=str_replace('\"', '', $my_selectmin_arret);

		$min_arret = $this->ModelPs->getRequete($proce_requete, $my_selectmin_arret);

		$point_final=0;

		$point_point=20;
		if(!empty($get_arret_date)){

			$data_arret=array();


			foreach ($get_arret_date as $keyget_arret) {
				$data_arret[]=$keyget_arret['id'];

			}

			$nbre=count($data_arret);

			$valeur_valeur=array();
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

			if (!empty($tabl)) {
				foreach ($tabl as $keytabl) {

					$ligne_arret.=" <div class='activity-item d-flex'>
					<div class='activite-label'>".$keytabl."</div>
					<i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
					<div class='activity-content'>
					<a href='#' class='fw-bold text-dark'>".$keytabl."</a> 
					</div>
					</div>";

				}
			}else{

				$ligne_arret.=" 
				Pas d'arret
				";


			}



		}

		//calcul du carburant consommé
		if(!empty($get_chauffeur['KILOMETRAGE'])){

			$carburant = $get_chauffeur['KILOMETRAGE'] * $distance_arrondie;



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
						'<center><img src=\'".base_url('upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT'].'')."\' width=\'80px\' height=\'80px\' style=\'border-radius: 50%\' alt=\'\'></center><hr><i class=\'bi bi-person-fill\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['NOM']." ".$get_chauffeur['PRENOM']."<br><i class=\'bi bi-phone\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['NUMERO_TELEPHONE']."<br><i class=\'bi bi-envelope\'></i> &nbsp;&nbsp;&nbsp;".$get_chauffeur['ADRESSE_MAIL']."<br><i class=\'bi bi-textarea-resize\'></i>&nbsp;&nbsp;&nbsp;".$get_chauffeur['PLAQUE']."<br><i class=\'bi bi-stopwatch\'></i>&nbsp;&nbsp;&nbsp;".$key_arret['heure']."'
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



				$my_selectvit_moy = $this->getBindParms('id,AVG(`vitesse`) moy_vitesse,date_format(`date`,"%d/%m/%Y") as date_base', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
				$my_selectvit_moy=str_replace('\"', '"', $my_selectvit_moy);
				$my_selectvit_moy=str_replace('\n', '', $my_selectvit_moy);
				$my_selectvit_moy=str_replace('\"', '', $my_selectvit_moy);

				$vit_moy = $this->ModelPs->getRequeteOne($proce_requete, $my_selectvit_moy);
				$my_selectdate_debfin = $this->getBindParms('id,MIN(`date`) datemin,MAX(`date`) datemax,date_format(`date`,"%d/%m/%Y") as date_base', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
				$my_selectdate_debfin=str_replace('\"', '"', $my_selectdate_debfin);
				$my_selectdate_debfin=str_replace('\n', '', $my_selectdate_debfin);
				$my_selectdate_debfin=str_replace('\"', '', $my_selectdate_debfin);

				$date_debfin = $this->ModelPs->getRequeteOne($proce_requete, $my_selectdate_debfin);


				$my_selectvitesse_max= $this->getBindParms(' MAX(vitesse) AS max_vitesse', 'tracking_data', '1 AND device_uid ='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT.'"' , '`id` ASC');
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
				$data['distance_finale'] = $distance_arrondie;
				$data['carburant'] = $carburant;
				$data['CODE'] = $CODE;
				$data['DATE'] = $DATE_SELECT;
				$data['score'] = $score_finale;
				$data['ligne_arret'] = $ligne_arret;
				

				$map_filtre = $this->load->view('Maptracking_view',$data,TRUE);

				$output = array(
					"distance_finale" => $distance_arrondie,
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

			//Fonction pour afficher la position de la voiture
			function getmap($CODE){

				$DATE_SELECT = $this->input->post('DATE_DAT');

				$proce_requete = "CALL `getRequete`(?,?,?,?);";

				$my_selectget_data= $this->getBindParms(' id,latitude,longitude', 'tracking_data', '1 AND device_uid ='.$CODE.' AND `id` = (SELECT MAX(`id`) FROM tracking_data ) ' , '`id` ASC');
				$my_selectget_data=str_replace('\"', '"', $my_selectget_data);
				$my_selectget_data=str_replace('\n', '', $my_selectget_data);
				$my_selectget_data=str_replace('\"', '', $my_selectget_data);

				$get_data = $this->ModelPs->getRequeteOne($proce_requete, $my_selectget_data);


				$data = '{"name":"iss","id":25544,"latitude":'.$get_data['latitude'].',"longitude":'.$get_data['longitude'].',"altitude":427.6731067247,"velocity":27556.638607061,"visibility":"eclipsed","footprint":4546.2965721564,"timestamp":1690338162,"daynum":2460151.5990972,"solar_lat":19.512848632241,"solar_lon":145.96751425687,"units":"kilometers"}';



				echo $data;
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