<?php
// Auteur : Pacifique
//description : Traitement des données odk pour l'etat du vehicule
//date    :   le 08/08/2024
//email   : byamungu.pacifique@mediabox.bi
//tel     : +25772496057
defined('BASEPATH') OR exit('No direct script access allowed');

class Traitement_odk extends CI_Controller {


    // cette fonction recupere le flux json en provenance de serveur de kobbo du DÉCLARATION  INCIDENT
	public function getFromKoboCarTracking($value='')
	{
        # code...
        $fluxjson = file_get_contents('php://input');//recuperation du flux json
        if(!empty($fluxjson))
        {
        	$save = $this->Model->create('json_data_etat_vehicule',array('DATA_JSON'=>$fluxjson));

        	if($save)
        	{
        		echo "Enregistrement dans la table json";

                $this->traitement();
        	}
        	else
        	{
        		echo "Echec d'enregistrement";
        	}
        }
        else
        {
        	echo "json est vide";

        }  
    }


   //Fonction pour le traitement de donnees
    public function traitement($value='')
    {
    	date_default_timezone_set('Africa/Bujumbura');

    	$donnees = $this->Model->getList("json_data_etat_vehicule",array('IS_TRAITE'=>0));

        echo "Affichage de data json <br>";

        print_r($donnees);die();

    }

}

?>