<?php
/*
	Auteur    : Mushagalusa Byamungu Pacifique
	Email     : byamungu.pacifique@mediabox.bi
	Telephone : +25772496057
	Date      : 21/02/2024
*/
	class Centre_situation extends CI_Controller
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

			$critere = ' ';

			$PROPRIETAIRE_ID = $this->input->post('PROPRIETAIRE_ID');

			$filtre_pro = $this->Model->getOne('proprietaire',array('PROPRIETAIRE_ID'=>$PROPRIETAIRE_ID));

			$USER_ID = $this->session->userdata('USER_ID');

			if($this->session->userdata('PROFIL_ID') != 1)
			{
				$critere.= ' AND users.USER_ID = '.$USER_ID;
			}
			else
			{
				if(!empty($PROPRIETAIRE_ID))
				{
					$critere.= ' AND proprietaire.PROPRIETAIRE_ID = '.$PROPRIETAIRE_ID;
				}
			}

			$psgetrequete = "CALL `getRequete`(?,?,?,?);";

			$proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc','proprietaire LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID',' 1 '.$critere.'','proprio_desc ASC');

			$proprio=str_replace('\"', '"', $proprio);
			$proprio=str_replace('\n', '', $proprio);
			$proprio=str_replace('\"', '', $proprio);

			$proprio = $this->ModelPs->getRequete($psgetrequete, $proprio);


			$get_vihicule = $this->Model->getRequete('SELECT tracking_data.id,latitude,longitude,VEHICULE_ID,CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS proprio_desc,COULEUR,KILOMETRAGE,PHOTO FROM tracking_data JOIN vehicule ON vehicule.CODE = tracking_data.device_uid JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID WHERE 1 '.$critere.' GROUP BY VEHICULE_ID ORDER BY id DESC');

			$donnees_vehicule = '';

			if(!empty($get_vihicule))
			{
				$nbrVehicule = count($get_vihicule);

				foreach ($get_vihicule as $key) {

					$VEHICULE_ID = $key['VEHICULE_ID'];

					if (empty($key['latitude'])) {
						$latitude ='-1';
					}else
					{
						$latitude = $key['latitude'];  
					}

					if (empty($key['longitude'])) {
						$longitude = '-1';
					}else
					{
						$longitude = $key['longitude'];  
					}

					$CODE=trim($key['CODE']);
					$CODE = str_replace("\n","",$CODE);
					$CODE = str_replace("\r","",$CODE);
					$CODE = str_replace("\t","",$CODE);
					$CODE = str_replace('"','',$CODE);
					$CODE = str_replace("'",'',$CODE);

					$DESC_MARQUE=trim($key['DESC_MARQUE']);
					$DESC_MARQUE = str_replace("\n","",$DESC_MARQUE);
					$DESC_MARQUE = str_replace("\r","",$DESC_MARQUE);
					$DESC_MARQUE = str_replace("\t","",$DESC_MARQUE);
					$DESC_MARQUE = str_replace('"','',$DESC_MARQUE);
					$DESC_MARQUE = str_replace("'",'',$DESC_MARQUE);

					$DESC_MODELE=trim($key['DESC_MODELE']);
					$DESC_MODELE = str_replace("\n","",$DESC_MODELE);
					$DESC_MODELE = str_replace("\r","",$DESC_MODELE);
					$DESC_MODELE = str_replace("\t","",$DESC_MODELE);
					$DESC_MODELE = str_replace('"','',$DESC_MODELE);
					$DESC_MODELE = str_replace("'",'',$DESC_MODELE);

					$PLAQUE=trim($key['PLAQUE']);
					$PLAQUE = str_replace("\n","",$PLAQUE);
					$PLAQUE = str_replace("\r","",$PLAQUE);
					$PLAQUE = str_replace("\t","",$PLAQUE);
					$PLAQUE = str_replace('"','',$PLAQUE);
					$PLAQUE = str_replace("'",'',$PLAQUE);

					$proprio_desc=trim($key['proprio_desc']);
					$proprio_desc = str_replace("\n","",$proprio_desc);
					$proprio_desc = str_replace("\r","",$proprio_desc);
					$proprio_desc = str_replace("\t","",$proprio_desc);
					$proprio_desc = str_replace('"','',$proprio_desc);
					$proprio_desc = str_replace("'",'',$proprio_desc);

					$COULEUR=trim($key['COULEUR']);
					$COULEUR = str_replace("\n","",$COULEUR);
					$COULEUR = str_replace("\r","",$COULEUR);
					$COULEUR = str_replace("\t","",$COULEUR);
					$COULEUR = str_replace('"','',$COULEUR);
					$COULEUR = str_replace("'",'',$COULEUR);

					$KILOMETRAGE=trim($key['KILOMETRAGE']);
					$KILOMETRAGE = str_replace("\n","",$KILOMETRAGE);
					$KILOMETRAGE = str_replace("\r","",$KILOMETRAGE);
					$KILOMETRAGE = str_replace("\t","",$KILOMETRAGE);
					$KILOMETRAGE = str_replace('"','',$KILOMETRAGE);
					$KILOMETRAGE = str_replace("'",'',$KILOMETRAGE);

					$PHOTO=trim($key['PHOTO']);
					$PHOTO = str_replace("\n","",$PHOTO);
					$PHOTO = str_replace("\r","",$PHOTO);
					$PHOTO = str_replace("\t","",$PHOTO);
					$PHOTO = str_replace('"','',$PHOTO);
					$PHOTO = str_replace("'",'',$PHOTO);


					$donnees_vehicule = $donnees_vehicule.$VEHICULE_ID.'<>'.$latitude.'<>'.$longitude.'<>'.$CODE.'<>'.$DESC_MARQUE.'<>'.$DESC_MODELE.'<>'.$PLAQUE.'<>'.$COULEUR.'<>'.$KILOMETRAGE.'<>'.$proprio_desc.'<>'.$PHOTO.'<>@';
				}
			}

			$data['proprio'] = $proprio;
			$data['filtre_pro'] = $filtre_pro;
			$data['donnees_vehicule'] = $donnees_vehicule;
			$data['nbrVehicule'] = $nbrVehicule;

			$this->load->view('Centre_situation_View',$data);
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