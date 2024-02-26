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

		$data['html1']=$html1;

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