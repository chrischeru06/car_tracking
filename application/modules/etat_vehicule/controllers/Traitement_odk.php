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
 public function traitement()
 {
     date_default_timezone_set('Africa/Bujumbura');

     $donnees = $this->Model->getList("json_data_etat_vehicule",array('IS_TRAITE'=>0));

     foreach ($donnees as $key)
     {

        $DATA_JSON = $key['DATA_JSON'];
        $ID=$key['ID'];
        $DATA_JSON = str_replace("/","_", $DATA_JSON);
        $DATA_JSON = json_decode($DATA_JSON);

   //  echo "<pre>";

   // print_r($DATA_JSON);die();
   // echo "</pre>";


        $DEVICEID = (isset($DATA_JSON->deviceid)) ? $DATA_JSON->deviceid : '';

        $nom  = NULL;         
        if (isset($DATA_JSON->identification_nom)) {

            $nom = $DATA_JSON->identification_nom;
        }

        $prenom  = NULL;         
        if (isset($DATA_JSON->identification_prenom)) {

            $prenom = $DATA_JSON->identification_prenom;
        }

        $operation  = NULL;         
        if (isset($DATA_JSON->identification_operation)) {

            $operation = $DATA_JSON->identification_operation;
        }

        $imageAvant  = NULL;         
        if (isset($DATA_JSON->imagesExternes_imageAvant)) {

            $imageAvant = $DATA_JSON->imagesExternes_imageAvant;
        }

        $imageArriere  = NULL;         
        if (isset($DATA_JSON->imagesExternes_imageArriere)) {

            $imageArriere = $DATA_JSON->imagesExternes_imageArriere;
        }

        $imageLateraleGauche  = NULL;         
        if (isset($DATA_JSON->imagesExternes_imageLateraleGauche)) {

            $imageLateraleGauche = $DATA_JSON->imagesExternes_imageLateraleGauche;
        }

        $imageLateraleDroite  = NULL;         
        if (isset($DATA_JSON->imagesExternes_imageLateraleDroite)) {

            $imageLateraleDroite = $DATA_JSON->imagesExternes_imageLateraleDroite;
        }

        $imageTableauDeBord  = NULL;         
        if (isset($DATA_JSON->imagesInternes_imageTableauDeBord)) {

            $imageTableauDeBord = $DATA_JSON->imagesInternes_imageTableauDeBord;
        }

        $imageSiegeAvant  = NULL;         
        if (isset($DATA_JSON->imagesInternes_imageSiegeAvant)) {

            $imageSiegeAvant = $DATA_JSON->imagesInternes_imageSiegeAvant;
        }

        $imageSiegeArriere  = NULL;         
        if (isset($DATA_JSON->imagesInternes_imageSiegeArriere)) {

            $imageSiegeArriere = $DATA_JSON->imagesInternes_imageSiegeArriere;
        }

        $anomalie  = NULL;         
        if (isset($DATA_JSON->selectionAnomalie_anomalie)) {

            $anomalie = $DATA_JSON->selectionAnomalie_anomalie;
        }

        $autreAnomalie  = NULL;         
        if (isset($DATA_JSON->selectionAnomalie_autreAnomalie)) {

            $autreAnomalie = $DATA_JSON->selectionAnomalie_autreAnomalie;
        }

        $commentaire  = NULL;         
        if (isset($DATA_JSON->selectionAnomalie_commentaire)) {

            $commentaire = $DATA_JSON->selectionAnomalie_commentaire;
        }

        $image_url = null;

        if(isset($DATA_JSON->_attachments)) 
        {
            $image_url = $DATA_JSON->_attachments;
        }


    // print_r(count($image_url));die();


        //Enregistrement des donnees

        $data = array(
            'NOM_CHAUFFEUR' => $nom,
            'PRENOM_CHAUFFEUR' => $prenom,
            'ID_OPERATION' => $operation,
            'IMAGE_AVANT' => $imageAvant,
            'IMAGE_ARRIERE' => $imageArriere,
            'IMAGE_LATERALE_CHAUCHE' => $imageLateraleGauche,
            'IMAGE_LATERALE_DROITE' => $imageLateraleDroite,
            'IMAGE_TABLEAU_DE_BORD' => $imageTableauDeBord,
            'IMAGE_SIEGE_AVANT' => $imageSiegeAvant,
            'IMAGE_SIEGE_ARRIERE' => $imageSiegeArriere,
            'ID_ANOMALIE' => $anomalie,
            'AUTRE_ANOMALIE' => $autreAnomalie,
            'COMMENTAIRE' => $commentaire,
        );

        $create = $this->Model->create('etat_vehicule',$data);

            //Telechargement des fichiers uploads

        foreach ($image_url as $image_key) {

            $image_file = $image_key->download_medium_url;

            $image_file = str_replace("https:__kc.humanitarianresponse.info_media_medium", "https://kc.humanitarianresponse.info/media/medium", $image_file);
            $image_file = str_replace("https:__kc.kobotoolbox.org_media_medium", "https://kc.kobotoolbox.org/media/medium", $image_file);

            $strImage = explode('%2F', $image_file);
            $imag = end($strImage);

            $context = stream_context_create([
                'http' => [
                    'header' => 'Authorization: Basic ' . base64_encode('car_tracking:car_tracking@mbx2024')
                ]
            ]);

            $src_file = 'C:/xampp/htdocs/car_tracking/upload/image_url';

            if(!is_dir($src_file)){
                mkdir($src_file ,0777 ,TRUE);
            }

            file_put_contents($src_file . "/" . $imag, file_get_contents($image_file, false, $context));
        }

        if($create)
        {
            $this->Model->update('json_data_etat_vehicule',array('ID' =>$ID),array('IS_TRAITE'=>1));

            echo "Enregistrement avec succès !<br>";
        }
        else
        {
            echo "Erreur d'enregistrement";
        }


    }

}

}

?>