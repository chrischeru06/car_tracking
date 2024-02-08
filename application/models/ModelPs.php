<?php
/**
 * @author jules
 */
class ModelPs extends CI_Model
{

    //execute tous les requetes  pour insertion update et delete
    public function createUpdateDelete($requete,$bindparameters=array())
    { 
        $query =$this->db->query($requete,$bindparameters);
        if ($query) {
            // code...
            return TRUE;
        }else{
            return FALSE;
        }
        
    }      

    //execute tous les requetes de selection qui retourne une ligne
    public function getRequeteOne($requete,$bindparameters=array())
    { 
        $query =$this->db->query($requete,$bindparameters);
        mysqli_next_result($this->db->conn_id);
        return $query->row_array();
    }    
    //execute tous les requetes de selection qui retourne + d'une ligne
    public function getRequete($requete,$bindparameters=array())
    { 
        $query =$this->db->query($requete,$bindparameters);
        mysqli_next_result($this->db->conn_id);
        return $query->result_array();
    }
    // debut fonction pour datatable
    public function datatable($requete)
    { 
        $query =$this->db->query($requete);
        mysqli_next_result($this->db->conn_id);
        return $query->result();
    }
    public function filtrer($requete)
    {
       $query =$this->db->query($requete);
       mysqli_next_result($this->db->conn_id);
       return $query->row_array();

   }    
   
   /* Procedure pour recuperer proprietaire par plantation*/

   function getone_proprietaire($id) {

    $stored_proc = "CALL getOneProp(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

/* Procedure pour recuperer les informations d'une plantation module mapping plantation*/

function getone_plantation($id) {

    $stored_proc = "CALL getOnePlant(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

/* Procedure pour recuperer les informations du sol module preparation du sol*/

function getone_sol($id) {

    $stored_proc = "CALL getOneSol(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

/* Procedure pour recuperer les informations des cultures A cultiver module choix de la culture*/

function getone_choix($id) {

    $stored_proc = "CALL getOneChoix(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->result_array();
}

/* Procedure pour afficher une carte avec ces informations module choix de la culture*/

function getone_map($id) {

    $stored_proc = "CALL getOneMap(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

function getone_plantation1($id_plantation) {

  $stored_proc = "CALL get_one_plant(".$id_plantation.")";
  $query =$this->db->query($stored_proc);
  mysqli_next_result($this->db->conn_id);
  return $query->row_array();
}

function get_champs($id_plantation) {

  $stored_proc = "CALL get_parcelles(".$id_plantation.")";
  $query =$this->db->query($stored_proc);
  mysqli_next_result($this->db->conn_id);
  return $query->result_array();
}

function get_resultat_preparation_sol($id_plantation) {

  $stored_proc = "CALL getSol(".$id_plantation.")";
  $query =$this->db->query($stored_proc);
  mysqli_next_result($this->db->conn_id);
  return $query->result_array();
}





function get_all_plant($id_plantation) 
{

  $stored_proc = "CALL get_all_dada_plant(".$id_plantation.")";
  $query =$this->db->query($stored_proc);
  mysqli_next_result($this->db->conn_id);
  return $query->result_array();
}



function getone_fav_cultures($id) {

    $stored_proc = "CALL getFavCul(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

function getone_responsable($id) {

    $stored_proc = "CALL getOneResp(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}


function get_champs_plant($id) {

    $stored_proc = "CALL getChampsPlant(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->result_array();
}


public function getRequeteOneJointure($requete)
{ 
    $query =$this->db->query($requete);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

    //execute toutes les requetes de selection qui retourne plus d'une ligne avec des jointures
public function getRequeteJointure($requete)
{ 
    $query =$this->db->query($requete);
    mysqli_next_result($this->db->conn_id);
    return $query->result_array();
}



     //  retrait procedure model  debut

function getone_distinfos($id) {

    $stored_proc = "CALL getOneDistInfos(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

function getone_distinfosStocks($id) {

    $stored_proc = "CALL getOneStocksDist(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->result_array();
}

function getone_jeton($id) {

    $stored_proc = "CALL getOnejetonfert(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

function getone_jetonInfos($id) {

    $stored_proc = "CALL getOnejetoninfos(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

// function getone_Caution($id) {

//     $stored_proc = "CALL getOneCaution(".$id.")";
//     $query =$this->db->query($stored_proc);
//     mysqli_next_result($this->db->conn_id);
//     return $query->row_array();
// }

// function getone_Reste($id) {

//     $stored_proc = "CALL getOneReste(".$id.")";
//     $query =$this->db->query($stored_proc);
//     mysqli_next_result($this->db->conn_id);
//     return $query->result_array();
// }

function getone_Caution($id) {

    $stored_proc = "CALL getOneCaution(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->row_array();
}

function getone_Reste($id) {

    $stored_proc = "CALL getOneReste(".$id.")";
    $query =$this->db->query($stored_proc);
    mysqli_next_result($this->db->conn_id);
    return $query->result_array();
}





}
?>