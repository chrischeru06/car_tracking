<?php

/**Fait par Nzosaba Santa Milka
 * Santa.milka@mediabox.bi
 * 68071895
 * Gestion de profil
 * Le 26/02/2024
 * 
 * 
 */
class Profil extends CI_Controller
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


	function index()
	{

		$USER_ID=$this->session->userdata('USER_ID');

		$proce_requete = "CALL `getRequete`(?,?,?,?);";
		$my_select_utilisateur = $this->getBindParms('users.USER_ID,users.IDENTIFICATION,users.TELEPHONE,users.USER_NAME,profil.DESCRIPTION_PROFIL as profile,STATUT,proprietaire.TYPE_PROPRIETAIRE_ID,proprietaire.PHOTO_PASSPORT,proprietaire.PROPRIETAIRE_ID,proprietaire.COUNTRY_ID,countries.CommonName,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,zones.ZONE_NAME,collines.COLLINE_NAME,proprietaire.PERSONNE_REFERENCE,proprietaire.CNI_OU_NIF', ' `users` join profil on users.PROFIL_ID=profil.PROFIL_ID LEFT JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID=users.PROPRIETAIRE_ID JOIN countries ON countries.COUNTRY_ID=proprietaire.COUNTRY_ID JOIN provinces ON provinces.PROVINCE_ID=proprietaire.PROVINCE_ID JOIN communes ON communes.COMMUNE_ID=proprietaire.COMMUNE_ID JOIN zones ON zones.ZONE_ID=proprietaire.ZONE_ID JOIN collines ON collines.COLLINE_ID=proprietaire.COLLINE_ID', ' users.USER_ID='.$USER_ID.'', '`USER_ID` ASC');
		$utilisateur = $this->ModelPs->getRequeteOne($proce_requete, $my_select_utilisateur);

			// $my_select_proprio= $this->getBindParms('proprietaire.PROPRIETAIRE_ID,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,PERSONNE_REFERENCE,EMAIL,TELEPHONE,CNI_OU_NIF,RC,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,ADRESSE,PHOTO_PASSPORT','proprietaire JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID','users.USER_ID='.$USER_ID.'','PROPRIETAIRE_ID ASC');

		$my_select_proprio = $this->getBindParms('proprietaire.PROPRIETAIRE_ID,TYPE_PROPRIETAIRE_ID,NOM_PROPRIETAIRE,PRENOM_PROPRIETAIRE,PERSONNE_REFERENCE,EMAIL,proprietaire.TELEPHONE,CNI_OU_NIF,RC,PROVINCE_ID,COMMUNE_ID,ZONE_ID,COLLINE_ID,ADRESSE,PHOTO_PASSPORT,countries.CommonName,proprietaire.COUNTRY_ID', 'proprietaire JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID JOIN countries ON countries.COUNTRY_ID=proprietaire.COUNTRY_ID', ' users.USER_ID='.$USER_ID.'', '`PROPRIETAIRE_ID` ASC');

		$proprietaire=$this->ModelPs->getRequeteOne($proce_requete, $my_select_proprio);

		$pay = $this->getBindParms('CommonName,COUNTRY_ID', 'countries', '1', 'CommonName ASC');
		$pays =$this->ModelPs->getRequete($proce_requete, $pay);

		$html1='<option value="">--- Sélectionner ----</option>';
		if(!empty($pays))
		{
			foreach($pays as $key1)
			{
				if ($key1['COUNTRY_ID']==$proprietaire['COUNTRY_ID']) 
				{
					$html1.='<option value="'.$key1['COUNTRY_ID'].'" selected>'.$key1['CommonName'].'</option>';
				}else
				{
					$html1.='<option value="'.$key1['COUNTRY_ID'].'">'.$key1['CommonName'].'</option>';
				}
				
			}
		}

		$my_select_provinces = $this->getBindParms('PROVINCE_ID,PROVINCE_NAME', 'provinces', '1', '`PROVINCE_NAME` ASC');
		$provinces = $this->ModelPs->getRequete($proce_requete, $my_select_provinces);

		$html2='<option value="">--- Sélectionner ----</option>';
		if(!empty($provinces))
		{
			foreach($provinces as $key2)
			{
				if ($key2['PROVINCE_ID']==$proprietaire['PROVINCE_ID']) 
				{
					$html2.='<option value="'.$key2['PROVINCE_ID'].'" selected>'.$key2['PROVINCE_NAME'].'</option>';
				}else
				{
					$html2.='<option value="'.$key2['PROVINCE_ID'].'">'.$key2['PROVINCE_NAME'].'</option>';
				}
				
			}
		}

		if(!empty($proprietaire['PROVINCE_ID'])){

			$my_select_communes = $this->getBindParms('COMMUNE_ID,COMMUNE_NAME', 'communes', '1 AND PROVINCE_ID='.$proprietaire['PROVINCE_ID'].'', '`COMMUNE_NAME` ASC');
			$communes = $this->ModelPs->getRequete($proce_requete, $my_select_communes);

			$html3='<option value="">--- Sélectionner ----</option>';


			foreach($communes as $key3)
			{
				if ($key3['COMMUNE_ID']==$proprietaire['COMMUNE_ID']) 
				{
					$html3.='<option value="'.$key3['COMMUNE_ID'].'" selected>'.$key3['COMMUNE_NAME'].'</option>';
				}else
				{
					$html3.='<option value="'.$key3['COMMUNE_ID'].'">'.$key3['COMMUNE_NAME'].'</option>';
				}
				
			}


		}

		if(!empty($proprietaire['COMMUNE_ID'])){

			$my_select_zones = $this->getBindParms('ZONE_ID,ZONE_NAME', 'zones', '1 AND COMMUNE_ID='.$proprietaire['COMMUNE_ID'].'', '`ZONE_NAME` ASC');
			$zones = $this->ModelPs->getRequete($proce_requete, $my_select_zones);

			$html4='<option value="">--- Sélectionner ----</option>';


			foreach($zones as $key4)
			{
				if ($key4['ZONE_ID']==$proprietaire['ZONE_ID']) 
				{
					$html4.='<option value="'.$key4['ZONE_ID'].'" selected>'.$key4['ZONE_NAME'].'</option>';
				}else
				{
					$html4.='<option value="'.$key4['ZONE_ID'].'">'.$key4['ZONE_NAME'].'</option>';
				}
				
			}


		}

		if(!empty($proprietaire['ZONE_ID'])){

			$my_select_collines = $this->getBindParms('COLLINE_ID,COLLINE_NAME', 'collines', '1 AND ZONE_ID='.$proprietaire['ZONE_ID'].'', '`COLLINE_NAME` ASC');
			$collines = $this->ModelPs->getRequete($proce_requete, $my_select_collines);

			$html5='<option value="">--- Sélectionner ----</option>';


			foreach($collines as $key5)
			{
				if ($key5['COLLINE_ID']==$proprietaire['COLLINE_ID']) 
				{
					$html5.='<option value="'.$key5['COLLINE_ID'].'" selected>'.$key5['COLLINE_NAME'].'</option>';
				}else
				{
					$html5.='<option value="'.$key5['COLLINE_ID'].'">'.$key5['COLLINE_NAME'].'</option>';
				}
				
			}


		}

		$data['html1']=$html1;
		$data['html2']=$html2;
		$data['html3']=$html3;
		$data['html4']=$html4;
		$data['html5']=$html5;
		$data['utilisateur']=$utilisateur;
		$data['proprietaire']=$proprietaire;

		$this->load->view('Profil_View',$data);
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