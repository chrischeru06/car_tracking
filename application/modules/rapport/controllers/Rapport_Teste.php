<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rapport_Teste extends CI_Controller
{

  
  public function index()
  {
    $series='';   
     $sql=$this->Model->getRequete('SELECT  proprietaire.PROPRIETAIRE_ID as ID,if(proprietaire.PRENOM_PROPRIETAIRE !="",CONCAT(proprietaire.NOM_PROPRIETAIRE,"   ",proprietaire.PRENOM_PROPRIETAIRE),proprietaire.NOM_PROPRIETAIRE)as NAME ,count(vehicule.`PROPRIETAIRE_ID`)  as NBR FROM `vehicule`  join proprietaire on vehicule.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE 1 GROUP BY ID,NAME');

    $categories="";
    $data_acte="";
    $total=0;
    foreach ($sql as $value) 
    {
      $categories.="'";
      $NAME=str_replace("'", "\'", $value['NAME']);
      $categories.= $NAME."',";

      $data_acte.="{y: ".$value['NBR'].",name:'".$value['NAME']."',key:'".$value['ID']."'},";
    

      $total=$total+$value['NBR'];  
    }

    $data['categories']=$categories;
  
    $data['data_acte']=$data_acte;
    
    $data['total']=$total;

   $this->load->view('Rapport_Teste_View',$data);
  }



}

?>