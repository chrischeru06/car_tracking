<?php
/** RUGAMBA Jean Vainqueur
*Titre : infos perso, affections, présences et cartes d'un agent SBG
*Téléphone : (+257) 62 47 19 15
*Email : jean.vainqueur@mediabox.bi
*Gestion des agents
* 
*/
class Details_Chauffeur extends CI_Controller
{
	function __construct()
  {
    parent::__construct();
    $this->out_application();
  }
  function out_application()
  {
    if(empty($this->session->userdata('USER_ID')))
    {
      redirect(base_url('Login/logout'));
    }
  }

  function index($id)
  { 

    $CHAUFFEUR_ID=$this->uri->segment(4);

    // $agent = $this->Model->getRequeteOne("SELECT CHAUFFEUR_ID,CONCAT(NOM,' ',PRENOM) as info,ADRESSE_PHYSIQUE,NUMERO_TELEPHONE,ADRESSE_MAIL,NUMERO_CARTE_IDENTITE,FILE_CARTE_IDENTITE,FILE_PERMIS,chauffeur.CODE_AGENT,agent_card.CARD_UID,PHOTO_PASSPORT FROM chauffeur LEFT JOIN agent_card ON chauffeur.CODE_AGENT=agent_card.CODE_AGENT WHERE md5(CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'");

     $agent = $this->Model->getRequeteOne("SELECT CHAUFFEUR_ID,CONCAT(chauffeur.NOM,' ',chauffeur.PRENOM) as info,ADRESSE_PHYSIQUE,NUMERO_TELEPHONE,ADRESSE_MAIL,NUMERO_CARTE_IDENTITE,FILE_CARTE_IDENTITE,FILE_PERMIS,chauffeur.CODE_AGENT,agent_card.CARD_UID,chauffeur.PHOTO_PASSPORT FROM chauffeur LEFT JOIN agent_card ON chauffeur.CODE_AGENT=agent_card.CODE_AGENT  left join vehicule on agent_card.CARD_UID=vehicule.VEHICULE_ID left JOIN client on vehicule.CLIENT_ID=client.CLIENT_ID WHERE md5(CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'");

    $card = $this->Model->getRequeteOne("SELECT CHAUFFEUR_ID,CONCAT(NOM,' ',PRENOM) as info,ADRESSE_PHYSIQUE,NUMERO_TELEPHONE,ADRESSE_MAIL,chauffeur.CODE_AGENT,agent_card.CARD_UID,agent_card.STATUT_CARD,chauffeur.STATUT_CARD FROM chauffeur LEFT JOIN agent_card ON chauffeur.CODE_AGENT=agent_card.CODE_AGENT WHERE md5(CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'AND agent_card.STATUT_CARD=1");
    
    $location=$this->Model->getRequeteOne("SELECT CHAUFFEUR_ID, chauffeur.PROVINCE_ID, provinces.PROVINCE_NAME AS prov, chauffeur.COMMUNE_ID, communes.COMMUNE_NAME AS com, chauffeur.ZONE_ID, zones.ZONE_NAME AS zon, chauffeur.COLLINE_ID, collines.COLLINE_NAME AS col,chauffeur.GENRE_ID,syst_genre.DESCR_GENRE,chauffeur.DATE_NAISSANCE FROM chauffeur LEFT JOIN provinces ON chauffeur.PROVINCE_ID=provinces.PROVINCE_ID LEFT JOIN communes ON chauffeur.COMMUNE_ID=communes.COMMUNE_ID LEFT JOIN zones ON chauffeur.ZONE_ID=zones.ZONE_ID LEFT JOIN collines ON chauffeur.COLLINE_ID=collines.COLLINE_ID LEFT JOIN syst_genre ON syst_genre.GENRE_ID=chauffeur.GENRE_ID  WHERE md5(chauffeur.CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'");
    $aujourdhui = date("Y-m-d");
    $diff = date_diff(date_create($location['DATE_NAISSANCE']), date_create($aujourdhui));
    $age = $diff->format('%y');
    $data['age'] =$age;

    $data['agent'] =$agent;
    $data['card'] =$card;
    $data['location'] =$location;
    // $data['fichier_chauf'] =$fichier_chauf;
    $data['ID'] =$id;
    $data['title'] = 'Details des agents';

    $this->load->view('Details_Agent_List_view',$data);
  }

  //Tab des affectations d'un agent
  function det_affect()
  {
    $CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');
    

    $query_principal="SELECT AFFECTATION_AGENT_ID, chauffeur.CODE_AGENT, agent_affectation.CODE_POINTAGE, DATE_AFFECTATION,DATE_FIN, client_site.NOM_SITE, client_site.ADRESSE_SITE, shift.HEURE_DEBUT, shift.HEURE_FIN, CONCAT(client.NOM_CLIENT,' ',client.PRENOM) AS STR_CLIENT FROM agent_affectation JOIN client_site ON client_site.CODE_POINTAGE=agent_affectation.CODE_POINTAGE JOIN shift ON shift.SHIFT_ID=agent_affectation.SHIFT_ID JOIN client ON client.CLIENT_ID=client_site.CLIENT_ID  JOIN chauffeur ON chauffeur.CODE_AGENT=agent_affectation.CODE_AGENT  WHERE md5(chauffeur.CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'";


    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $limit = 'LIMIT 0,10';
    $draw = isset($_POST['draw']);
    $start = isset($postData['start']);

    if(isset($_POST["length"]) && $_POST["length"] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by = '';
    $order_column=array('1','client.NOM_CLIENT','client_site.NOM_SITE','client_site.ADRESSE_SITE','DATE_AFFECTATION','shift.HEURE_DEBUT');
    $order_by=isset($_POST['order']) ? ' ORDER BY '.$order_column[$_POST['order']['0']['column']] .'  '.$_POST['order']['0']['dir'] : 'ORDER BY DATE_AFFECTATION DESC';
    $search=!empty($_POST['search']['value']) ? (" AND (client.NOM_CLIENT LIKE '%$var_search%' OR client.PRENOM LIKE '%$var_search%' OR CONCAT(client.NOM_CLIENT,' ',client.PRENOM) LIKE '%$var_search%' OR client_site.NOM_SITE LIKE '%$var_search%' OR client_site.ADRESSE_SITE LIKE '%$var_search%' OR DATE_FORMAT(DATE_AFFECTATION,'%d/%m/%Y') LIKE '%$var_search%' OR shift.HEURE_DEBUT LIKE '%$var_search%' OR shift.HEURE_FIN LIKE '%$var_search%' OR CONCAT(shift.HEURE_DEBUT,' - ',shift.HEURE_FIN) LIKE '%$var_search%')"):'';
    $critaire = '';
    $query_secondaire=$query_principal.' '.$critaire.' '.$search.' '.$order_by.' '.$limit;
    $query_filter=$query_principal.' '.$critaire.' '.$search;
    $fetch_gerant = $this->Model->datatable($query_secondaire);
    //print_r($fetch_gerant);die();
    $data = array();
    $i=0;
    foreach($fetch_gerant as $row)
    {
      $date = new DateTime($row->DATE_AFFECTATION);
      $date1 = new DateTime($row->DATE_FIN);

      $i=$i+1;
      $sub_array=array();
      $sub_array[]=$i;
      $sub_array[]=$row->STR_CLIENT;
      $sub_array[]=$row->NOM_SITE;
      $sub_array[]=$row->ADRESSE_SITE;
      $sub_array[]=$date->format('d/m/Y');
      $sub_array[]=$date1->format('d/m/Y');

      $sub_array[]=$row->HEURE_DEBUT.' - '.$row->HEURE_FIN;
      $data[]=$sub_array;
    }
    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" => $this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }

  //Tab des présences d'unn agent
  function det_pres()
  { 
    $CHAUFFEUR_ID = $this->input->post('CHAUFFEUR_ID');

    // $query_principal="SELECT PRESENCE_ID,agent_pointage.CODE_POINTAGE,agent.NOM,agent.PRENOM,agent_pointage.CODE_AGENT,DATE_ENTRE,TIME_IN,TIME_OUT,STATUT,CONCAT(client.NOM_CLIENT,' ',client.PRENOM) AS info_client,client_site.NOM_SITE FROM agent_pointage JOIN agent on agent_pointage.CODE_AGENT=agent.CODE_AGENT JOIN client_site ON client_site.CODE_POINTAGE=agent_pointage.CODE_POINTAGE JOIN client ON client.CLIENT_ID=client_site.CLIENT_ID WHERE md5(agent.AGENT_ID)='".$AGENT_ID."'";

    $query_principal="SELECT PRESENCE_ID,agent_pointage.CODE_POINTAGE,chauffeur.NOM,chauffeur.PRENOM,agent_pointage.CODE_AGENT,DATE_ENTRE,TIME_IN,TIME_OUT,STATUT,CONCAT(client.NOM_CLIENT,' ',client.PRENOM) AS info_client,client_site.NOM_SITE,agent_pointage.CARD_UID AS num_carte FROM agent_pointage JOIN chauffeur on agent_pointage.CODE_AGENT=chauffeur.CODE_AGENT JOIN client_site ON client_site.CODE_POINTAGE=agent_pointage.CODE_POINTAGE JOIN client ON client.CLIENT_ID=client_site.CLIENT_ID WHERE md5(chauffeur.CHAUFFEUR_ID)='".$CHAUFFEUR_ID."'";

    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $limit = 'LIMIT 0,10';
    $draw = isset($_POST['draw']);
    $start = isset($postData['start']);

    if(isset($_POST["length"]) && $_POST["length"] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by = '';
    $order_column=array('1','chauffeur.NOM','client.NOM_CLIENT','agent_pointage.CODE_POINTAGE','agent_pointage.CODE_AGENT','agent_pointage.DATE_ENTRE','agent_pointage.TIME_IN','agent_pointage.TIME_OUT');

    $order_by = isset($_POST['order']) ? ' ORDER BY '.$order_column[$_POST['order']['0']['column']] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY agent_pointage.DATE_ENTRE, agent_pointage.TIME_IN  DESC';
    $search = !empty($_POST['search']['value']) ? (" AND (chauffeur.NOM LIKE '%$var_search%' OR chauffeur.PRENOM LIKE '%$var_search%' OR CONCAT(chauffeur.NOM,' ',chauffeur.PRENOM) LIKE '%$var_search%' OR client.NOM_CLIENT LIKE '%$var_search%' OR client.PRENOM LIKE '%$var_search%' OR client_site.NOM_SITE LIKE '%$var_search%' OR CONCAT(client.NOM_CLIENT,' ',client.PRENOM,'(',client_site.NOM_SITE,')') LIKE '%$var_search%' OR agent_pointage.CODE_POINTAGE LIKE '%$var_search%' OR `agent_pointage`.`CODE_AGENT`  LIKE '%$var_search%' OR DATE_FORMAT(`agent_pointage`.`DATE_ENTRE`,'%d/%m/%Y') LIKE '%$var_search%' OR `agent_pointage`.`TIME_IN` LIKE '%$var_search%' OR `agent_pointage`.`TIME_OUT` LIKE '%$var_search%' OR agent_pointage.CARD_UID LIKE '%$var_search%')") : '';

    $critaire = '';
    $query_secondaire=$query_principal.' '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter = $query_principal.' '.$critaire.' '.$search;
    $fetch_menu = $this->Model->datatable($query_secondaire);
    $data = array();
    $u=0;
    foreach ($fetch_menu as $row) 
    {

    	$date = new DateTime($row->DATE_ENTRE);

      $u++;
      $sub_array = array();
      $sub_array[] =  $u;
      // $sub_array[]="<strong class='text-dark'/>".$row->NOM." ".$row->PRENOM;
      $sub_array[]=$row->info_client;  
      $sub_array[]=$row->NOM_SITE;  
      $sub_array[]=$row->num_carte;
      // $sub_array[]="<strong class='text-dark'/>".$row->CODE_AGENT;
      $sub_array[]=$date->format('d/m/Y');
      $sub_array[]=$row->TIME_IN;
      $sub_array[]=$row->TIME_OUT;
      
      $data[] = $sub_array;
    }
    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" =>$this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );
    echo json_encode($output);
  }



  //Fonction pour le detail de tous les vehicules d'un client
  function detail_vehicule_chauffeur($CHAUFFEUR_ID)
  {
    
    $query_principal=$this->Model->getRequeteOne('SELECT vehicule.VEHICULE_ID,vehicule_marque.DESC_MARQUE,vehicule_nom.DESC_MODELE,vehicule.PLAQUE,vehicule.COULEUR,vehicule.PHOTO FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_nom ON vehicule_nom.ID_NOM=vehicule.ID_NOM JOIN agent_card ON vehicule.CODE=agent_card.CARD_UID JOIN chauffeur ON agent_card.CODE_AGENT=chauffeur.CODE_AGENT WHERE chauffeur.CHAUFFEUR_ID ='.$CHAUFFEUR_ID);
    // print_r($query_principal)
     
   $fichier = base_url().'upload/photo_vehicule/'.$query_principal['PHOTO'].'';

    $div_info = '<img src="'.$fichier.'" height="100%"  width="100%"  style= "border-radius:50%;" />';
        $output = array(

      "DESC_MODELE" =>$query_principal['DESC_MODELE'] ,
      "PLAQUE" =>$query_principal['PLAQUE'] ,
      "COULEUR" =>$query_principal['COULEUR'] ,
      "PHOTO" =>$div_info ,
     
    );
    echo json_encode($output);


  }
}
?>