<?php 
/*fait par NIYOMWUNGERE Ella Dancilla 
/31/0/2022
mail:ella_dancilla@mediabox.bi
Rapport chauffeurs par statut
tel:71379943
*/
class Dashboard_General extends CI_Controller
{
  public function getcolor() 
  {
    $chars = 'ABCDEF0123456789';
    $color = '#';
    for ( $i= 0; $i < 6; $i++ )
    {
      $color.= $chars[rand(0, strlen($chars) -1)];
    }
    return $color;
  }
  function index()
  {
    $reflesh=base_url();
    $data['reflesh']=$reflesh;
     //print_r($data['reflesh']);die();
    $this->load->view('Dashboard_General_View',$data);
  }


  //detail pour le rapport1:vehicules statut
  function detail_veh_statut()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT distinct VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY VEHICULE_ID   DESC';
    }
    $search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%')"):'';

    // $critaire=" AND vehicule.STATUT_VEH_AJOUT=".$ID;
    $critaire="";
    if ($ID==2) {
    $critaire=" AND vehicule.STATUT_VEH_AJOUT=2";
    }elseif ($ID==4) {
      $critaire=" AND vehicule.STATUT_VEH_AJOUT=4";
    }
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->CODE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MARQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MODELE;
      $intrant[] ="<strong class='text-dark'/>".$row->PLAQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->COULEUR;
      $intrant[] ="<strong class='text-dark'/>".$row->KILOMETRAGE;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_proprio;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_chauffeur;
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }
   //detail pour le rapport2:vehicules par marque
  function detail_veh_marque()
  {
    $KEY2=$this->input->post('key2');
    $break=explode(".",$KEY2);
    $ID=$KEY2;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT distinct VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY VEHICULE_ID   DESC';
    }
    $search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%')"):'';

    $critaire=" AND vehicule_marque.ID_MARQUE=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->CODE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MARQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MODELE;
      $intrant[] ="<strong class='text-dark'/>".$row->PLAQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->COULEUR;
      $intrant[] ="<strong class='text-dark'/>".$row->KILOMETRAGE;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_proprio;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_chauffeur;
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }
   //detail pour le rapport3:vehicules en mouvement vs en stationnement
  function detail_veh_station_mouv()
  {
    $KEY3=$this->input->post('key3');
    $break=explode(".",$KEY3);
    $ID=$KEY3;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
   
     $query_principal= 'SELECT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur FROM `vehicule` JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.mouvement as mouv FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID  WHERE 1';


    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY VEHICULE_ID   DESC';
    }
    $search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%')"):'';

    $critaire=" AND  tracking_data_deriv2.mouv=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->CODE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MARQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MODELE;
      $intrant[] ="<strong class='text-dark'/>".$row->PLAQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->COULEUR;
      $intrant[] ="<strong class='text-dark'/>".$row->KILOMETRAGE;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_proprio;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_chauffeur;
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }
   //detail pour le rapport4:vehicules en mouvement vs en stationnement
  function detail_veh_allum_etteint()
  {
    $KEY4=$this->input->post('key4');
    $break=explode(".",$KEY4);
    $ID=$KEY4;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    
        $query_principal='SELECT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur FROM`vehicule` JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.ignition as ing FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';
    

    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY VEHICULE_ID   DESC';
    }
    $search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%')"):'';

    $critaire=" AND  tracking_data_deriv2.ing=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->CODE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MARQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MODELE;
      $intrant[] ="<strong class='text-dark'/>".$row->PLAQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->COULEUR;
      $intrant[] ="<strong class='text-dark'/>".$row->KILOMETRAGE;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_proprio;
      $intrant[] ="<strong class='text-dark'/>".$row->desc_chauffeur;
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }
    //detail pour le rapport1:chauffeur par statut
  function detail_chof_statut()
  {
    $KEY5=$this->input->post('key5');
    $break=explode(".",$KEY5);
    $ID=$KEY5;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal="SELECT CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_NAISSANCE FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID  WHERE 1";


    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY NOM   DESC';
    }
    $search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
        OR chauffeur.PRENOM LIKE "%' . $var_search . '%"
        OR chauffeur.ADRESSE_PHYSIQUE LIKE "%' . $var_search . '%" 
        OR provinces.PROVINCE_NAME LIKE "%' . $var_search . '%" 
        OR communes.COMMUNE_NAME LIKE "%' . $var_search . '%"
        OR zones.ZONE_NAME  LIKE "%' . $var_search . '%"
        OR collines.COLLINE_NAME LIKE "%' . $var_search . '%"
        OR chauffeur.NUMERO_TELEPHONE LIKE "%' . $var_search . '%"
        OR chauffeur.ADRESSE_MAIL LIKE "%' . $var_search . '%"
        OR chauffeur.NUMERO_CARTE_IDENTITE LIKE "%' . $var_search . '%")') : '';

    $critaire=" AND chauffeur.IS_ACTIVE=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->NOM." ".$row->PRENOM;
      $intrant[] ="<strong class='text-dark'/>".$row->ADRESSE_PHYSIQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->NUMERO_TELEPHONE;
      $intrant[] ="<strong class='text-dark'/>".$row->ADRESSE_MAIL;
      $intrant[] ="<strong class='text-dark'/>".$row->NUMERO_CARTE_IDENTITE;
      $intrant[] ="<strong class='text-dark'/>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME;
      
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }
  //detail pour le rapport1:chauffeur par affectation
   function detail_chof_affect()
  {
    $KEY6=$this->input->post('key6');
    $break=explode(".",$KEY6);
    $ID=$KEY6;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal="SELECT CHAUFFEUR_ID,chauffeur.NOM,chauffeur.PRENOM,provinces.PROVINCE_NAME,communes.COMMUNE_NAME,collines.COLLINE_NAME,zones.ZONE_NAME,chauffeur.ADRESSE_PHYSIQUE,chauffeur.NUMERO_TELEPHONE,chauffeur.ADRESSE_MAIL,chauffeur.NUMERO_CARTE_IDENTITE,chauffeur.PERSONNE_CONTACT_TELEPHONE,chauffeur.DATE_NAISSANCE FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID  WHERE 1";


    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY NOM   DESC';
    }
    $search = !empty($_POST['search']['value']) ? (' AND (chauffeur.NOM LIKE "%' . $var_search . '%" 
        OR chauffeur.PRENOM LIKE "%' . $var_search . '%"
        OR chauffeur.ADRESSE_PHYSIQUE LIKE "%' . $var_search . '%" 
        OR provinces.PROVINCE_NAME LIKE "%' . $var_search . '%" 
        OR communes.COMMUNE_NAME LIKE "%' . $var_search . '%"
        OR zones.ZONE_NAME  LIKE "%' . $var_search . '%"
        OR collines.COLLINE_NAME LIKE "%' . $var_search . '%"
        OR chauffeur.NUMERO_TELEPHONE LIKE "%' . $var_search . '%"
        OR chauffeur.ADRESSE_MAIL LIKE "%' . $var_search . '%"
        OR chauffeur.NUMERO_CARTE_IDENTITE LIKE "%' . $var_search . '%")') : '';

    $critaire=" AND chauffeur.STATUT_VEHICULE=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->NOM." ".$row->PRENOM;
      $intrant[] ="<strong class='text-dark'/>".$row->ADRESSE_PHYSIQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->NUMERO_TELEPHONE;
      $intrant[] ="<strong class='text-dark'/>".$row->ADRESSE_MAIL;
      $intrant[] ="<strong class='text-dark'/>".$row->NUMERO_CARTE_IDENTITE;
      $intrant[] ="<strong class='text-dark'/>".$row->PROVINCE_NAME."/".$row->COMMUNE_NAME."/".$row->ZONE_NAME."/".$row->COLLINE_NAME;
      
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }

  //detail pour le rapport1:vehicules par proprietaire
  function detail_proprietaire()
  {
    $KEY7=$this->input->post('key7');
    $break=explode(".",$KEY7);
    $ID=$KEY7;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    
    // $query_principal="SELECT DISTINCT(`AGENT_ID`),`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`ADRESSE_MAIL`,agent.`DATE_INSERTION`,agent_card.CARD_UID  FROM `agent`  JOIN agent_card ON agent.CODE_AGENT=agent_card.CODE_AGENT WHERE 1";
    $query_principal="SELECT DISTINCT( proprietaire.PROPRIETAIRE_ID),vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,vehicule.COULEUR,vehicule.PLAQUE FROM `vehicule` left join proprietaire on vehicule.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID left join chauffeur_vehicule on vehicule.CODE=chauffeur_vehicule.CODE left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE   WHERE 1";
 

    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY PLAQUE   DESC';
    }
    $search = !empty($_POST['search']['value']) ? (" AND ( `DESC_MODELE` LIKE '%$var_search%' OR `DESC_MARQUE` LIKE '%$var_search%' OR `COULEUR` LIKE '%$var_search%' OR `PLAQUE` LIKE '%$var_search%') ") : '';

    $critaire=" AND proprietaire.PROPRIETAIRE_ID=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $u++;
      $intrant=array();
      $intrant[] ="<strong class='text-dark'/>".$u; 
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MODELE;
      $intrant[] ="<strong class='text-dark'/>".$row->DESC_MARQUE;
      $intrant[] ="<strong class='text-dark'/>".$row->COULEUR;
      $intrant[] ="<strong class='text-dark'/>".$row->PLAQUE;
      
      $data[] = $intrant;
    }

    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }



  public function get_rapport()
  {
    // $vehicule_statut=$this->Model->getRequete('SELECT vehicule.STATUT as ID, if(vehicule.STATUT=1,"Actif","Inactif")as statut ,COUNT(`VEHICULE_ID`) as NBR FROM `vehicule` WHERE 1 GROUP by ID,statut ');

 $vehicule_actif=$this->Model->getRequete('SELECT vehicule.STATUT_VEH_AJOUT as ID,`VEHICULE_ID` as NBR FROM `vehicule` WHERE `STATUT_VEH_AJOUT`=2 ');
  $vehicule_inactif=$this->Model->getRequete('SELECT vehicule.STATUT_VEH_AJOUT as ID,`VEHICULE_ID` as NBR FROM `vehicule` WHERE `STATUT_VEH_AJOUT`=4 ');
    $donnees1="";
    $compteur=0;
    $somme=0;
    foreach ($vehicule_actif as  $value) 
    {
      $compteur++;
      $key_id=($value['ID']>0) ? $value['ID'] : "0" ;
      $somme=$compteur;
     // $donnees9.="{name:'Accident', y:".$test9.",color:'".$color."',key9:1},";
    }
    $donnees1.="{name:'Actif:". $somme ."', y:". $somme.",key:2},";
     $donnees11="";
     $compteur1=0;
     $somme11=0;
    foreach ($vehicule_inactif as  $value) 
    { $compteur1++;
      // $color11=$this->getcolor();
      $key_id=($value['ID']>0) ? $value['ID'] : "0" ;
      // $somme11=($compteur1>0) ? $compteur1 : "0" ;
       $somme11=$compteur1;
    }
    $donnees1.="{name:'Inactif:". $somme11."', y:". $somme11.",key:4},";

  
    $rapp="
    <script type=\"text/javascript\">
    Highcharts.chart('container',
    {
      chart:
      {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
        },
        title: {
          text: 'Véhicule actif Vs inactif'
          },
          subtitle: 
          {
           text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
           },
           tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
              point: {
                valueSuffix: '%'
              }
              },
              plotOptions: {
                pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',


                  point:{
                    events: {
                     click: function()
                     {
                      $(\"#titre1\").html(\"LISTE DES CHAUFFEUR \");

                       $(\"#myModal\").modal('show');
                       var row_count ='1000000';
                       $(\"#mytable\").DataTable({
                        \"processing\":true,
                        \"serverSide\":true,
                        \"bDestroy\": true,
                        \"oreder\":[],
                        \"ajax\":{
                          url:\"".base_url('dashboard/Dashboard_General/detail_veh_statut')."\",
                          type:\"POST\",
                          data:{

                           key:this.key,

                           ZONE_ID:$('#ZONE_ID').val(),
                           QUARTIER_ID:$('#QUARTIER_ID').val(),

                         }
                         },
                         lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
                         pageLength: 10,
                         \"columnDefs\":[{
                           \"targets\":[0],
                           \"orderable\":false
                           }],

                           dom: 'Bfrtlip',
                           buttons: [
                           'excel', 'print','pdf'
                           ],
                           language: {
                            \"sProcessing\":     \"Traitement en cours...\",
                            \"sSearch\":         \"Recherche&nbsp;:\",
                            \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
                            \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
                            \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
                            \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
                            \"sInfoPostFix\":    \"\",
                            \"sLoadingRecords\": \"Chargement en cours...\",
                            \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
                            \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
                            \"oPaginate\": {
                              \"sFirst\":      \"Premier\",
                              \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
                              \"sNext\":       \"Suivant\",
                              \"sLast\":       \"Dernier\"
                              },
                              \"oAria\": {
                                \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
                                \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
                              }
                            }
                            });

                          }
                        }
                        },

                        dataLabels: {
                          enabled: true
                          },
                          showInLegend: true
                        }
                        },
                        credits: 
                        {
                          enabled: true,
                          href: \"\",
                          text: \"Mediabox\"
                          },
                          series: [
                        {
                          name: '',
                          data: [{name:'Actif:0', y:0,key:2},{name:'Inactif:3', y:3,color:'#0E62EE',key:4}, ]
                          }]
                          });
                          </script>";
        
       //rapport2:vehicule par marque
     $vehicule_marque=$this->Model->getRequete('SELECT vehicule_marque.ID_MARQUE as ID,vehicule_marque.DESC_MARQUE as NAME,COUNT(vehicule.`ID_MARQUE`) as NBR FROM `vehicule`  join vehicule_marque on vehicule.ID_MARQUE=vehicule_marque.ID_MARQUE WHERE 1 GROUP BY ID,NAME');

    $total2=0;
    $donnees2="";
    foreach ($vehicule_marque as  $value) 
    {
      $color=$this->getcolor();

      $total2+=$value['NBR'];
      // $name = (!empty($value['NAME'])) ? $value['NAME'] : "Aucun" ;
      $nb2 = (!empty($value['NBR'])) ? $value['NBR'] : "0" ;
      $donnees2.="{name:'".str_replace("'","\'",$value['NAME'])."', y:".$nb2.",color:'".$color."',key2:".$value['ID']."},"; 
      
    }

  $rapp2="<script type=\"text/javascript\">
    Highcharts.chart('container2', 
    {
     chart: 
     {
       type: 'columnpyramid'
      },
     title:
     {
      text: 'Véhicule par marque'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> <b>Total= ".$total2."'
     },
      xAxis: 
     {
      type: 'category',
      crosshair: true
     },
     yAxis: 
     {
     min: 0,
     title: 
     {
      text: ''
     }
     },
     tooltip: 
     {
      headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
      pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
      '<td style=\"padding:0\"><b>{point.y:.f} </b></td></tr>',
     footerFormat: '</table>',
     shared: true,
     useHTML: true
     },
     plotOptions: 
     {
       columnpyramid: 
       {
         pointPadding: 0.2,
         borderWidth: 0,
       
         cursor:'pointer',
         point:
         {
           events: 
           {
             click: function()
             {
               $(\"#titre\").html(\"LISTE DES AGENTS \");
               $(\"#myModal\").modal('show');
               var row_count ='1000000';
               $(\"#mytable\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_General/detail_veh_marque')."\",
               type:\"POST\",
               data:
               {
                 key2:this.key2,

                }
              },
             lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
             pageLength: 10,
             \"columnDefs\":[{
             \"targets\":[],
             \"orderable\":false
            }],
            dom: 'Bfrtlip',
            buttons: [
            'excel', 'print','pdf'
            ],
            language: 
            {
              \"sProcessing\":     \"Traitement en cours...\",
              \"sSearch\":         \"Rechercher&nbsp;:\",
              \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
              \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
              \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
              \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
              \"sInfoPostFix\":    \"\",
              \"sLoadingRecords\": \"Chargement en cours...\",
              \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
              \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
              \"oPaginate\": {
              \"sFirst\":      \"Premier\",
              \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
              \"sNext\":       \"Suivant\",
              \"sLast\":       \"Dernier\"
            },
            \"oAria\": {
            \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
            \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
          }
        }
     });
    }
     }
     },
     dataLabels: 
     {
      enabled: true,
     format: '{point.y:f}'
     },
     showInLegend: false
    }
    }, 
    credits: 
    {
    enabled: true,
    href: \"\",
    text: \"Mediabox\"
    },

    series: [
    {
     name:' total:(".$total2.")',
     data:[".$donnees2."]
    }]
    });
   </script>";

   //rapport3:vehicule en mouvement vs stationnement
  

    $vehicule_mouvet_stationnema=$this->Model->getRequete("SELECT DISTINCT `mouvement` as ID_mouv,if(mouvement=1,'Véhicule en mouvement','Véhicule en stationnement') as statut FROM `tracking_data` WHERE 1");
   
    $donnees3="";
    foreach ($vehicule_mouvet_stationnema as  $value) 
    {
      $color=$this->getcolor();
      $key_id3=($value['ID_mouv']>0) ? $value['ID_mouv'] : "0" ;
       $vehicule_mouvet_=$this->Model->getRequeteOne("SELECT count(`VEHICULE_ID`) as NBR FROM `vehicule` JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.mouvement as mouv FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE tracking_data.mouvement=1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code WHERE 1");
        $somme3=0;
        if ($vehicule_mouvet_) 
        {
         $somme3=$vehicule_mouvet_['NBR'] ;

     
        }
      $donnees3.="{name:'".$value['statut']." :". $somme3."', y:". $somme3.",color:'".$color."',key3:'". $key_id3."'},";    

    }

    $donnees3="";

    foreach ($vehicule_mouvet_stationnema as  $value) 
    {
      $color=$this->getcolor();
      $key_id3=($value['ID_mouv']>0) ? $value['ID_mouv'] : "0" ;
       $vehicule_mouvet_1=$this->Model->getRequeteOne("SELECT count(`VEHICULE_ID`) as NBR FROM `vehicule` JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.mouvement as mouv FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE tracking_data.mouvement=0) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code WHERE 1");
        $somme33=0;
        if ($vehicule_mouvet_1) 
        {
         $somme33=$vehicule_mouvet_1['NBR'] ;

     
        }
      $donnees3.="{name:'".$value['statut']." :". $somme3."', y:". $somme3.",color:'".$color."',key3:'". $key_id3."'},";    

    }

    // $rapp3="
    // <script type=\"text/javascript\">
    // Highcharts.chart('container3',
    // {
    //   chart:
    //   {
    //     plotBackgroundColor: null,
    //     plotBorderWidth: null,
    //     plotShadow: false,
    //     type: 'pie'
    //     },
    //     title: {
    //       text: 'Véhicule en mouvement Vs en stationnement'
    //       },
    //       subtitle: 
    //       {
    //        text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
    //        },
    //        tooltip: {
    //         pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    //         },
    //         accessibility: {
    //           point: {
    //             valueSuffix: '%'
    //           }
    //           },
    //           plotOptions: {
    //             pie: {
    //               allowPointSelect: true,
    //               cursor: 'pointer',


    //               point:{
    //                 events: {
    //                  click: function()
    //                  {
    //                   $(\"#titre1\").html(\"LISTE DES VEHICULES \");

    //                    $(\"#myModal\").modal('show');
    //                    var row_count ='1000000';
    //                    $(\"#mytable\").DataTable({
    //                     \"processing\":true,
    //                     \"serverSide\":true,
    //                     \"bDestroy\": true,
    //                     \"oreder\":[],
    //                     \"ajax\":{
    //                       url:\"".base_url('dashboard/Dashboard_General/detail_veh_station_mouv')."\",
    //                       type:\"POST\",
    //                       data:{

    //                        key3:this.key3,

    //                        ZONE_ID:$('#ZONE_ID').val(),
    //                        QUARTIER_ID:$('#QUARTIER_ID').val(),

    //                      }
    //                      },
    //                      lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
    //                      pageLength: 10,
    //                      \"columnDefs\":[{
    //                        \"targets\":[0],
    //                        \"orderable\":false
    //                        }],

    //                        dom: 'Bfrtlip',
    //                        buttons: [
    //                        'excel', 'print','pdf'
    //                        ],
    //                        language: {
    //                         \"sProcessing\":     \"Traitement en cours...\",
    //                         \"sSearch\":         \"Recherche&nbsp;:\",
    //                         \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
    //                         \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
    //                         \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
    //                         \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
    //                         \"sInfoPostFix\":    \"\",
    //                         \"sLoadingRecords\": \"Chargement en cours...\",
    //                         \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
    //                         \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
    //                         \"oPaginate\": {
    //                           \"sFirst\":      \"Premier\",
    //                           \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
    //                           \"sNext\":       \"Suivant\",
    //                           \"sLast\":       \"Dernier\"
    //                           },
    //                           \"oAria\": {
    //                             \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
    //                             \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
    //                           }
    //                         }
    //                         });

    //                       }
    //                     }
    //                     },

    //                     dataLabels: {
    //                       enabled: true
    //                       },
    //                       showInLegend: true
    //                     }
    //                     },
    //                      credits: 
    //                      {
    //                       enabled: true,
    //                       href: \"\",
    //                       text: \"Mediabox\"
    //                       },
    //                       series: [
    //                     {
    //                       name: '',

    //                       data: [".$donnees3." ]
    //                       }]
    //                       });
    //                       </script>";

  
    $rapp3="
    <script type=\"text/javascript\">
    Highcharts.chart('container3',
    {
      chart:
      {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
        },
        title: {
          text: 'Véhicule actif Vs inactif'
          },
          subtitle: 
          {
           text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
           },
           tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
              point: {
                valueSuffix: '%'
              }
              },
              plotOptions: {
                pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',


                  point:{
                    events: {
                     click: function()
                     {
                      $(\"#titre1\").html(\"LISTE DES CHAUFFEUR \");

                       $(\"#myModal\").modal('show');
                       var row_count ='1000000';
                       $(\"#mytable\").DataTable({
                        \"processing\":true,
                        \"serverSide\":true,
                        \"bDestroy\": true,
                        \"oreder\":[],
                        \"ajax\":{
                          url:\"".base_url('dashboard/Dashboard_General/detail_veh_statut')."\",
                          type:\"POST\",
                          data:{

                           key:this.key,

                           ZONE_ID:$('#ZONE_ID').val(),
                           QUARTIER_ID:$('#QUARTIER_ID').val(),

                         }
                         },
                         lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
                         pageLength: 10,
                         \"columnDefs\":[{
                           \"targets\":[0],
                           \"orderable\":false
                           }],

                           dom: 'Bfrtlip',
                           buttons: [
                           'excel', 'print','pdf'
                           ],
                           language: {
                            \"sProcessing\":     \"Traitement en cours...\",
                            \"sSearch\":         \"Recherche&nbsp;:\",
                            \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
                            \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
                            \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
                            \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
                            \"sInfoPostFix\":    \"\",
                            \"sLoadingRecords\": \"Chargement en cours...\",
                            \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
                            \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
                            \"oPaginate\": {
                              \"sFirst\":      \"Premier\",
                              \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
                              \"sNext\":       \"Suivant\",
                              \"sLast\":       \"Dernier\"
                              },
                              \"oAria\": {
                                \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
                                \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
                              }
                            }
                            });

                          }
                        }
                        },

                        dataLabels: {
                          enabled: true
                          },
                          showInLegend: true
                        }
                        },
                        credits: 
                        {
                          enabled: true,
                          href: \"\",
                          text: \"Mediabox\"
                          },
                          series: [
                        {
                          name: '',
                          data: [{name:'Véhicule en stationnement :2', y:2,color:'#C17CA9',key3:'0'},{name:'Véhicule en mouvement :0', y:0,color:'#70C245',key3:'1'}, ]
                          }]
                          });
                          </script>";
        

    //rapport4:vehicule en allumé vs etteintes
   $vehicule_allume_eteinte=$this->Model->getRequete("SELECT ing as ID ,if(ing=1,'Véhicule allumé','Véhicule éteint') as statut,count(`VEHICULE_ID`) as NBR FROM `vehicule` JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.ignition as ing FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code WHERE 1 group by  ing,if(ing=1,'Véhicule allumé','Véhicule ettient')");

    // SELECT ing as ID ,if(ing=1,'allu','et') as NAME,count(`VEHICULE_ID`) as NBR FROM `vehicule` JOIN (SELECT tracking_data.`device_uid` as code,tracking_data.id,tracking_data.ignition as ing FROM `tracking_data` JOIN (SELECT  max(`id`) as id_max,`device_uid` FROM `tracking_data` WHERE 1 GROUP by device_uid) as tracking_data_deriv ON tracking_data.id=tracking_data_deriv.id_max WHERE 1) tracking_data_deriv2 ON vehicule.CODE=tracking_data_deriv2.code WHERE 1 group by ing,NAME

   $total4=0;
    $donnees4="";
    foreach ($vehicule_allume_eteinte as  $value) 
    {  
      $color=$this->getcolor();
      $total4+=$value['NBR'];
      $key_id4=($value['ID']>0) ? $value['ID'] : "0" ;
      $somme4=($value['NBR']>0) ? $value['NBR'] : "0" ;
      $donnees4.="{name:'".$value['statut']." :". $somme4."', y:". $somme4.",color:'".$color."',key4:'". $key_id4."'},";
    }


  $rapp4="<script type=\"text/javascript\">
    Highcharts.chart('container4', 
    {
     chart: 
     {
       type: 'column'
      },
     title:
     {
      text: 'Véhicule allumé Vs éteint'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b>'
     },
      xAxis: 
     {
      type: 'category',
      crosshair: true
     },
     yAxis: 
     {
     min: 0,
     title: 
     {
      text: ''
     }
     },
     tooltip: 
     {
      headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
      pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
      '<td style=\"padding:0\"><b>{point.y:.f} </b></td></tr>',
     footerFormat: '</table>',
     shared: true,
     useHTML: true
     },
     plotOptions: 
     {
       column: 
       {
         pointPadding: 0.2,
         borderWidth: 0,
       
         cursor:'pointer',
         point:
         {
           events: 
           {
             click: function()
             {
             
               $(\"#titre\").html(\"LISTE DES AGENTS \");
               $(\"#myModal\").modal('show');
               var row_count ='1000000';
               $(\"#mytable\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_General/detail_veh_allum_etteint')."\",
               type:\"POST\",
               data:
               {
                 key4:this.key4,


                }
              },
             lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
             pageLength: 10,
             \"columnDefs\":[{
             \"targets\":[],
             \"orderable\":false
            }],
            dom: 'Bfrtlip',
            buttons: [
            'excel', 'print','pdf'
            ],
            language: 
            {
              \"sProcessing\":     \"Traitement en cours...\",
              \"sSearch\":         \"Rechercher&nbsp;:\",
              \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
              \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
              \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
              \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
              \"sInfoPostFix\":    \"\",
              \"sLoadingRecords\": \"Chargement en cours...\",
              \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
              \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
              \"oPaginate\": {
              \"sFirst\":      \"Premier\",
              \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
              \"sNext\":       \"Suivant\",
              \"sLast\":       \"Dernier\"
            },
            \"oAria\": {
            \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
            \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
          }
        }
     });
    }
     }
     },
     dataLabels: 
     {
      enabled: true,
     format: '{point.y:f}'
     },
     showInLegend: false
    }
    }, 
    credits: 
    {
    enabled: true,
    href: \"\",
    text: \"Mediabox\"
    },

    series: [
    {
     name:' total:(".$total4.")',
     data:[".$donnees4."]
    }]
    });
   </script>";
   //DEBUT DASHBOARD DES CHJAUFFEUR
   //Rapport1:chauffeur par ststut
   $chauffeur_statut=$this->Model->getRequete('SELECT chauffeur.IS_ACTIVE as ID, if(chauffeur.IS_ACTIVE=1,"Actif","Inactif")as statut ,COUNT(`CHAUFFEUR_ID`) as NBR FROM `chauffeur` WHERE 1 GROUP by ID,statut ');


    $donnees5="";
    foreach ($chauffeur_statut as  $value) 
    {
      $color=$this->getcolor();
      $key_id5=($value['ID']>0) ? $value['ID'] : "0" ;
      $somme5=($value['NBR']>0) ? $value['NBR'] : "0" ;
      $donnees5.="{name:'".$value['statut']." :". $somme5."', y:". $somme.",color:'".$color."',key5:'". $key_id5."'},";
    }

    $rapp5="
    <script type=\"text/javascript\">
    Highcharts.chart('container5',
    {
      chart:
      {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
        },
        title: {
          text: 'Chauffeur actif Vs inactif'
          },
          subtitle: 
          {
           text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
           },
           tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
              point: {
                valueSuffix: '%'
              }
              },
              plotOptions: {
                pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',


                  point:{
                    events: {
                     click: function()
                     {
                      $(\"#titre1\").html(\"LISTE DES CHAUFFEUR \");

                       $(\"#myModal5\").modal('show');
                       var row_count ='1000000';
                       $(\"#mytable5\").DataTable({
                        \"processing\":true,
                        \"serverSide\":true,
                        \"bDestroy\": true,
                        \"oreder\":[],
                        \"ajax\":{
                          url:\"".base_url('dashboard/Dashboard_General/detail_chof_statut')."\",
                          type:\"POST\",
                          data:{

                           key5:this.key5,

                           ZONE_ID:$('#ZONE_ID').val(),
                           QUARTIER_ID:$('#QUARTIER_ID').val(),

                         }
                         },
                         lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
                         pageLength: 10,
                         \"columnDefs\":[{
                           \"targets\":[0],
                           \"orderable\":false
                           }],

                           dom: 'Bfrtlip',
                           buttons: [
                           'excel', 'print','pdf'
                           ],
                           language: {
                            \"sProcessing\":     \"Traitement en cours...\",
                            \"sSearch\":         \"Recherche&nbsp;:\",
                            \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
                            \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
                            \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
                            \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
                            \"sInfoPostFix\":    \"\",
                            \"sLoadingRecords\": \"Chargement en cours...\",
                            \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
                            \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
                            \"oPaginate\": {
                              \"sFirst\":      \"Premier\",
                              \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
                              \"sNext\":       \"Suivant\",
                              \"sLast\":       \"Dernier\"
                              },
                              \"oAria\": {
                                \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
                                \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
                              }
                            }
                            });

                          }
                        }
                        },

                        dataLabels: {
                          enabled: true
                          },
                          showInLegend: true
                        }
                        },
                        credits: 
                        {
                          enabled: true,
                          href: \"\",
                          text: \"Mediabox\"
                          },
                        series: [
                        {
                          name: '',

                          data: [".$donnees5." ]
                          }]
                          });
                          </script>";

    $chauffeur_affectation=$this->Model->getRequete('SELECT chauffeur.STATUT_VEHICULE as ID, if(chauffeur.STATUT_VEHICULE=1,"Chaufeur affecté","Chaufeur non affecté")as statut_aff ,COUNT(`CHAUFFEUR_ID`) as NBR FROM `chauffeur` WHERE 1 GROUP by ID,statut_aff ');

    $donnees6="";

    $total6=0;
    foreach ($chauffeur_affectation as  $value) 
    {
      $color=$this->getcolor();
      $total6+=$value['NBR'];
      $key_id6=($value['ID']>0) ? $value['ID'] : "0" ;
      $somme6=($value['NBR']>0) ? $value['NBR'] : "0" ;
      $donnees6.="{name:'".$value['statut_aff']." :". $somme6."', y:". $somme6.",color:'".$color."',key6:'". $key_id6."'},";
    }
    
    $rapp6="<script type=\"text/javascript\">
    Highcharts.chart('container6', 
    {
     chart: 
     {
       type: 'columnpyramid'
      },
     title:
     {
      text: 'Chauffeur affecté Vs non affecté'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> <b>Total= ".$total6."'
     },
      xAxis: 
     {
      type: 'category',
      crosshair: true
     },
     yAxis: 
     {
     min: 0,
     title: 
     {
      text: ''
     }
     },
     tooltip: 
     {
      headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
      pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
      '<td style=\"padding:0\"><b>{point.y:.f} </b></td></tr>',
     footerFormat: '</table>',
     shared: true,
     useHTML: true
     },
     plotOptions: 
     {
       columnpyramid: 
       {
         pointPadding: 0.2,
         borderWidth: 0,
       
         cursor:'pointer',
         point:
         {
           events: 
           {
             click: function()
             {
               $(\"#titre5\").html(\"LISTE DES CHAUFFEURS\");
               $(\"#myModal5\").modal('show');
               var row_count ='1000000';
               $(\"#mytable5\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_General/detail_chof_affect')."\",
               type:\"POST\",
               data:
               {
                 key6:this.key6,

                }
              },
             lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
             pageLength: 10,
             \"columnDefs\":[{
             \"targets\":[],
             \"orderable\":false
            }],
            dom: 'Bfrtlip',
            buttons: [
            'excel', 'print','pdf'
            ],
            language: 
            {
              \"sProcessing\":     \"Traitement en cours...\",
              \"sSearch\":         \"Rechercher&nbsp;:\",
              \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
              \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
              \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
              \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
              \"sInfoPostFix\":    \"\",
              \"sLoadingRecords\": \"Chargement en cours...\",
              \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
              \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
              \"oPaginate\": {
              \"sFirst\":      \"Premier\",
              \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
              \"sNext\":       \"Suivant\",
              \"sLast\":       \"Dernier\"
            },
            \"oAria\": {
            \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
            \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
          }
        }
     });
    }
     }
     },
     dataLabels: 
     {
      enabled: true,
     format: '{point.y:f}'
     },
     showInLegend: false
    }
    }, 
    credits: 
    {
    enabled: true,
    href: \"\",
    text: \"Mediabox\"
    },

    series: [
    {
     name:' total:(".$total6.")',
     data:[".$donnees6."]
    }]
    });
   </script>";
   //rapport du proprietare

   $proprietairee=$this->Model->getRequete('SELECT  proprietaire.PROPRIETAIRE_ID as ID,if(proprietaire.PRENOM_PROPRIETAIRE !="",CONCAT(proprietaire.NOM_PROPRIETAIRE,"   ",proprietaire.PRENOM_PROPRIETAIRE),proprietaire.NOM_PROPRIETAIRE)as NAME ,count(vehicule.`PROPRIETAIRE_ID`)  as NBR FROM `vehicule`  join proprietaire on vehicule.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE 1 GROUP BY ID,NAME');
     

    $total7=0;
    $donnees7="";
    foreach ($proprietairee as  $value) 
    {
      $color=$this->getcolor();

      $total7+=$value['NBR'];
      $name = (!empty($value['NAME'])) ? $value['NAME'] : "Aucun" ;
      $nb7 = (!empty($value['NBR'])) ? $value['NBR'] : "0" ;
      $donnees7.="{name:'".str_replace("'","\'",$name)."', y:".$nb7.",color:'".$color."',key7:'".$value['ID']."'},";  
    }

 
    $rapp7="<script type=\"text/javascript\">
    Highcharts.chart('container7', 
    {
     chart: 
     {
       type: 'column'
       },
       title: 
       {
         text: 'Rapport véhicules par propriétaire'
         },
         subtitle: 
         {
           text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> Total= ".$total7." '
           },
           xAxis: 
           {
             type: 'category',
             crosshair: true
             },
             yAxis: 
             {
               min: 0,
               title: 
               {
                 text: ''
               }
               },
               tooltip: 
               {
                 headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                 pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                 '<td style=\"padding:0\"><b>{point.y:.f} </b></td></tr>',
                 footerFormat: '</table>',
                 shared: true,
                 useHTML: true
                 },
                 plotOptions: 
                 {
                   column:
                   {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    cursor:'pointer',
                    point:
                    {
                     events: 
                     { 
                       click: function()
                       {
                         $(\"#titre7\").html(\"LISTE DES PROPRIETAIRES \");
                         $(\"#myModal7\").modal('show');
                         var row_count ='1000000';
                         $(\"#mytable7\").DataTable({
                           \"processing\":true,
                           \"serverSide\":true,
                           \"bDestroy\": true,
                           \"oreder\":[],
                           \"ajax\":{
                             url:\"".base_url('dashboard/Dashboard_General/detail_proprietaire')."\",
                             type:\"POST\",
                             data:
                             {
                              key7:this.key7,

                            }
                            },
                            lengthMenu: [[10,50, 100, row_count], [10,50, 100, \"All\"]],
                            pageLength: 10,
                            \"columnDefs\":[{
                             \"targets\":[0],
                             \"orderable\":false
                             }],
                             dom: 'Bfrtlip',
                            buttons: [
                         'excel', 'print','pdf'
                           ],
                             language: 
                             {
                               \"sProcessing\":     \"Traitement en cours...\",
                               \"sSearch\":         \"Recherche&nbsp;:\",
                               \"sLengthMenu\":     \"Afficher _MENU_ &eacute;l&eacute;ments\",
                               \"sInfo\":           \"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments\",
                               \"sInfoEmpty\":      \"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment\",
                               \"sInfoFiltered\":   \"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)\",
                               \"sInfoPostFix\":    \"\",
                               \"sLoadingRecords\": \"Chargement en cours...\",
                               \"sZeroRecords\":    \"Aucun &eacute;l&eacute;ment &agrave; afficher\",
                               \"sEmptyTable\":     \"Aucune donn&eacute;e disponible dans le tableau\",
                               \"oPaginate\": {
                                 \"sFirst\":      \"Premier\",
                                 \"sPrevious\":   \"Pr&eacute;c&eacute;dent\",
                                 \"sNext\":       \"Suivant\",
                                 \"sLast\":       \"Dernier\"
                                 },
                                 \"oAria\": {
                                  \"sSortAscending\":  \": activer pour trier la colonne par ordre croissant\",
                                  \"sSortDescending\": \": activer pour trier la colonne par ordre d&eacute;croissant\"
                                }
                              }

                              });
                            }
                          }
                          },
                          dataLabels: 
                          {
                            enabled: true,
                            format: '{point.y:f}'
                            },
                            showInLegend: false
                          }
                          }, 
                          credits: 
                          {
                            enabled: true,
                            href: \"\",
                            text: \"Mediabox\"
                            },
                            series: 
                            [{
                              name: ' ',
                              color: '',
                              data: [".$donnees7."]
                              }]

                              });
                              </script>";





   

      echo json_encode(array('rapp'=>$rapp,'rapp2'=>$rapp2,'rapp3'=>$rapp3,'rapp4'=>$rapp4,'rapp5'=>$rapp5,'rapp6'=>$rapp6,'rapp7'=>$rapp7));
    }
   }
 ?>