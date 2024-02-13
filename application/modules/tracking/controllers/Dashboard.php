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
		// $DATE_SELECT = '2024-02-10';

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

		//chauffeur

		$get_chauffeur = $this->Model->getRequeteOne("SELECT `CHAUFFEUR_VEHICULE_ID`,chauffeur_vehicule. `CODE`, chauffeur_vehicule.`CHAUFFEUR_ID`, chauffeur_vehicule.`DATE_INSERTION`,`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`DATE_NAISSANCE`,`ADRESSE_MAIL`,`NUMERO_CARTE_IDENTITE`,`FILE_CARTE_IDENTITE`,`FILE_IDENTITE_COMPLETE`,`FILE_CASIER_JUDICIAIRE`,`NUMERO_PERMIS`,`FILE_PERMIS`,`PERSONNE_CONTACT_TELEPHONE`,`PROVINCE_ID`,`COMMUNE_ID`,`ZONE_ID`,`COLLINE_ID`,PHOTO_PASSPORT,vehicule.PLAQUE,vehicule.PHOTO,vehicule.COULEUR,vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE FROM `chauffeur_vehicule` JOIN chauffeur ON chauffeur.CHAUFFEUR_ID=chauffeur_vehicule.CHAUFFEUR_ID join vehicule ON vehicule.CODE=chauffeur_vehicule.CODE join vehicule_modele on vehicule_modele.ID_MODELE=vehicule.ID_MODELE join vehicule_marque on vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE WHERE 1 AND chauffeur_vehicule.CODE = ".$CODE);

			//trajet
		$get_data = $this->Model->getRequete("SELECT `id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition` FROM `tracking_data` WHERE device_uid = ".$CODE." AND date_format(tracking_data.date,'%Y-%m-%d') = '".$DATE_SELECT."'");

// date_format(tracking_data.date,'%Y-%m-%d')
		// $proce_requete = "CALL `getRequete`(?,?,?,?);";
		// $my_select_get_data = $this->getBindParms('`id`,`latitude`,`longitude`,`vitesse`,`altitude`,`angle`,`satellites`,`mouvement`,`gnss_statut`,`device_uid`,`ignition`', '`tracking_data`', '1 AND device_uid='.$CODE.' AND date_format(tracking_data.date,"%Y-%m-%d") ="'.$DATE_SELECT. '"' , '`id` ASC');
		// $get_data = $this->ModelPs->getRequete($proce_requete, $my_select_get_data);
		// $get_data = str_replace("\'", "'", $get_data);



		$get_arret = $this->Model->getRequete("SELECT `id`,`latitude`,`longitude`,`device_uid`,`ignition`,date_format(tracking_data.date,'%H:%i') as heure FROM `tracking_data` WHERE device_uid = ".$CODE." AND date_format(tracking_data.date,'%Y-%m-%d') = '".$DATE_SELECT."' AND ignition=0");


		$arret = '';


		if(!empty($get_arret)){


			foreach ($get_arret as $key_arret) {


				$arret.="{
					'type': 'Feature',
					'properties': {
						'description':
						'<strong>Heure:</strong><p>".$key_arret['heure']."</p>'
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

  // print_r($arret);die();

				$vit_moy = $this->Model->getRequeteOne('SELECT AVG(`vitesse`) moy_vitesse,date_format(`date`,"%d/%m/%Y") as date_base FROM `tracking_data` WHERE 1 AND device_uid = '.$CODE.' AND date_format(`date`,"%Y-%m-%d") = '.$DATE_SELECT);

				$date_debfin = $this->Model->getRequeteOne('SELECT MIN(`date`) datemin,MAX(`date`) datemax,date_format(`date`,"%d/%m/%Y") as date_base FROM `tracking_data` WHERE 1 AND device_uid='.$CODE.' AND date_format(`date`,"%Y-%m-%d")='.$DATE_SELECT);

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
				


				$this->load->view('Tracking_chauffeur_view',$data);


			}

	//Fonction pour afficher la position de la voiture
			function getmap($CODE){

				$get_data = $this->Model->getRequeteOne('SELECT * FROM `tracking_data` WHERE device_uid = '.$CODE.' AND `id` = (SELECT MAX(`id`) FROM tracking_data );');

		// print_r($get_data);die();

				$data = '{"name":"iss","id":25544,"latitude":'.$get_data['latitude'].',"longitude":'.$get_data['longitude'].',"altitude":427.6731067247,"velocity":27556.638607061,"visibility":"eclipsed","footprint":4546.2965721564,"timestamp":1690338162,"daynum":2460151.5990972,"solar_lat":19.512848632241,"solar_lon":145.96751425687,"units":"kilometers"}';



				echo $data;
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
		}?>