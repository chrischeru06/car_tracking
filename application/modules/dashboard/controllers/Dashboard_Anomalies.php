<?php 
/*fait par NIYOMWUNGERE Ella Dancilla 
/10/04/2024
mail:ella_dancilla@mediabox.bi
dashboard des anomalies
tel:71379943
*/
ini_set('max_execution_time', 2000);
ini_set('memory_limit','2048M');

class Dashboard_Anomalies extends CI_Controller
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
    $this->load->view('Dashboard_Anomalies_View',$data);
  }
   //detail pour le rapport2:vehicules par exces vs normal
  function detail_exces()
  {
    $KEY8=$this->input->post('key8');
    $break=explode(".",$KEY8);
    $ID=$KEY8;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur FROM`vehicule`  JOIN tracking_data on vehicule.CODE=tracking_data.device_uid left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';

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
     $critaire="";
    if ($ID==8) {
    $critaire=" AND tracking_data.vitesse>50";
    }elseif ($ID==81) {
      $critaire=" AND vehicule.VEHICULE_ID not in (SELECT DISTINCT vehicule.VEHICULE_ID   FROM `tracking_data`  join vehicule on tracking_data.device_uid=vehicule.CODE WHERE 1 and tracking_data.CODE_COURSE in (SELECT DISTINCT(tracking_data.CODE_COURSE) FROM `tracking_data` WHERE 1 and `vitesse`>50))";
    }

   
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $nbr_exces=$this->Model->getRequeteOne('SELECT  COUNT(`CODE_COURSE`) AS NBR,`vitesse`,`device_uid` FROM `tracking_data` WHERE `device_uid`='.$row->CODE.'  AND vitesse>50');
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
      $intrant[] = '<a onclick="listing_exces('.$row->CODE.')" href="javascript:;" ><button >
           ' . $nbr_exces['NBR'].'</button></a>';
      // $intrant[] ="<strong class"text-dark"/>".$nbr_exces["NBR"]; 

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
    //list du detail nbr exces par voiture
  function listing_exces($CODE)
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT DISTINCT CODE_COURSE,tracking_data.device_uid,tracking_data.vitesse,tracking_data.date,tracking_data.CODE_COURSE FROM`vehicule`  JOIN tracking_data on vehicule.CODE=tracking_data.device_uid  WHERE 1 AND vitesse>50  and device_uid='.$CODE;


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
    $search=!empty($_POST['search']['value']) ? (" AND (CODE_COURSE LIKE '%$var_search%'   OR vitesse LIKE '%$var_search%'  OR vitesse LIKE '%$var_search%')"):'';
     $critaire="";

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
      $intrant[] ="<strong class='text-dark'/>".$row->CODE_COURSE;
      $intrant[] ="<strong class='text-dark'/>".$row->vitesse;
      $intrant[] ="<strong class='text-dark'/>".$row->date;
   
      

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
  //detail pour le rapport :vehicules accident Vs sans 
  function detail_accident()
  {
    $KEY9=$this->input->post('key9');
    $break=explode(".",$KEY9);
    $ID=$KEY9;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur FROM`vehicule`  JOIN tracking_data on vehicule.CODE=tracking_data.device_uid left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


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
     $critaire="";
    if ($ID==9) {
    $critaire=" AND tracking_data.accident=1";
    }elseif ($ID==91) {
      $critaire=" AND vehicule.VEHICULE_ID not in (SELECT DISTINCT vehicule.VEHICULE_ID   FROM `tracking_data`  join vehicule on tracking_data.device_uid=vehicule.CODE WHERE 1 and tracking_data.CODE_COURSE in (SELECT DISTINCT(tracking_data.CODE_COURSE) FROM `tracking_data` WHERE 1 and accident=1))";
    }

   
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $nbr_accident=$this->Model->getRequeteOne('SELECT  COUNT(`CODE_COURSE`) AS NBR,`vitesse`,`device_uid` FROM `tracking_data` WHERE `device_uid`='.$row->CODE.'  AND accident=1');
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
      $intrant[] = '
            <a onclick="listing_acc('.$row->CODE.')" href="javascript:;" ><button  >
           ' . $nbr_accident['NBR'].'</button></a>';
      // $intrant[] ="<strong class"text-dark"/>".$nbr_exces["NBR"]; 

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
   //list dudetail du nbr accident par voiture
  function listing_acc($CODE)
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT DISTINCT CODE_COURSE,tracking_data.device_uid,tracking_data.vitesse,tracking_data.date,tracking_data.CODE_COURSE FROM`vehicule`  JOIN tracking_data on vehicule.CODE=tracking_data.device_uid  WHERE 1 AND accident=1  and device_uid='.$CODE;


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
    $search=!empty($_POST['search']['value']) ? (" AND (CODE_COURSE LIKE '%$var_search%'   OR vitesse LIKE '%$var_search%'  OR vitesse LIKE '%$var_search%')"):'';
     $critaire="";

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
      $intrant[] ="<strong class='text-dark'/>".$row->CODE_COURSE;
      $intrant[] ="<strong class='text-dark'/>".$row->vitesse;
      $intrant[] ="<strong class='text-dark'/>".$row->date;
   
      


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

    $DATE_DEBUT = $this->input->post('DATE_DAT');
    $DATE_DAT_FIN = $this->input->post('DATE_DAT_FIN');
    $critere=" ";
  
   if(!empty($DATE_DEBUT) && !empty($DATE_DAT_FIN))
   {

      $critere.=' AND date_format(tracking_data.date,"%Y-%m-%d")between "'.$DATE_DEBUT.'" AND "'.$DATE_DAT_FIN.'" ';
    }
     $proce_requete = "CALL `getRequete`(?,?,?,?);";
     $my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 '.$critere.'' , '`id` ASC');
    $my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
    $my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
    $my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);
    $get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);

    $vehicule_exces=$this->Model->getRequeteOne('SELECT COUNT( DISTINCT vehicule.VEHICULE_ID ) as nbr  FROM `tracking_data`  join vehicule on tracking_data.device_uid=vehicule.CODE WHERE 1 and tracking_data.CODE_COURSE in (SELECT DISTINCT(tracking_data.CODE_COURSE) FROM `tracking_data` WHERE 1 and `accident`=1 '.$critere.')') ;

      $vehicule_normal=$this->Model->getRequeteOne('SELECT COUNT( DISTINCT vehicule.VEHICULE_ID ) as nbr  FROM  vehicule  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1 '.$critere.' and vehicule.VEHICULE_ID not in (SELECT DISTINCT vehicule.VEHICULE_ID   FROM `tracking_data`  join vehicule on tracking_data.device_uid=vehicule.CODE WHERE 1 and tracking_data.CODE_COURSE in (SELECT DISTINCT(tracking_data.CODE_COURSE) FROM `tracking_data` WHERE 1 and `accident`=1))') ;


    $donnees9="";
    $test=0;
     $color=$this->getcolor();
     if (!empty($vehicule_exces))
     {

      $test9=$vehicule_exces['nbr'];  
     }
     $donnees9.="{name:'Accident', y:".$test9.",color:'".$color."',key9:1},";
      $donnees91="";
    $test91=0;
     if (!empty($vehicule_normal))
     {
      $test91=$vehicule_normal['nbr'];  
     }
     $donnees91.="{name:'Sans accident', y:".$test91.",color:'".$color."',key9:0},";
  
  $rapp="<script type=\"text/javascript\">
    Highcharts.chart('container9', 
    {
     chart: 
     {
       type: 'column'
      },
     title:
     {
      text: 'Véhicule avec accident Vs sans accident'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
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
              // alert(this.key9),
               $(\"#titre\").html(\"LISTE DES AGENTS \");
               $(\"#myModal\").modal('show');
               var row_count ='1000000';
               $(\"#mytable\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_Anomalies/detail_accident')."\",
               type:\"POST\",
               data:
               {
                 key9:this.key9,

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
     name:'',
     data:[".$donnees9."]
    },{
     name:'',
     data:[".$donnees91."]
    }]
    });
   </script>";



     $vehicule_exces=$this->Model->getRequeteOne('SELECT COUNT( DISTINCT vehicule.VEHICULE_ID ) as nbr  FROM `tracking_data`  join vehicule on tracking_data.device_uid=vehicule.CODE WHERE 1 and tracking_data.CODE_COURSE in (SELECT DISTINCT(tracking_data.CODE_COURSE) FROM `tracking_data` WHERE 1 and `vitesse`>50 '.$critere.')') ;

      $vehicule_normal=$this->Model->getRequeteOne('SELECT COUNT( DISTINCT vehicule.VEHICULE_ID ) as nbr  FROM  vehicule  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1 '.$critere.'  and vehicule.VEHICULE_ID not in (SELECT DISTINCT vehicule.VEHICULE_ID   FROM `tracking_data`  join vehicule on tracking_data.device_uid=vehicule.CODE WHERE 1 and tracking_data.CODE_COURSE in (SELECT DISTINCT(tracking_data.CODE_COURSE) FROM `tracking_data` WHERE 1 and `vitesse`>50 '.$critere.'))') ;

    $donnees8="";
    $test=0;
     $color=$this->getcolor();
     if (!empty($vehicule_exces))
     {

      $test=$vehicule_exces['nbr'];  
     }
     $donnees8.="{name:'Exces', y:".$test.",color:'".$color."',key8:8},";
      $donnees81="";
    $test1=0;
     if (!empty($vehicule_normal))
     {
      $test1=$vehicule_normal['nbr'];  
     }
     $donnees81.="{name:'Normal', y:".$test1.",color:'".$color."',key8:81},";
        
    

  $rapp2="<script type=\"text/javascript\">
    Highcharts.chart('container2', 
    {
     chart: 
     {
       type: 'columnpyramid'
      },
     title:
     {
      text: 'Véhicule avec excès de vitesse vs normal'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
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
              // alert(this.key8),
               $(\"#titre\").html(\"LISTE DES AGENTS \");
               $(\"#myModal\").modal('show');
               var row_count ='1000000';
               $(\"#mytable\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_Anomalies/detail_exces')."\",
               type:\"POST\",
               data:
               {
                 key8:this.key8,

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
     name:'',
     data:[".$donnees8."]
    },{
     name:'',
     data:[".$donnees81."]
    }]
    });
   </script>";

   //rapport des conmsomation par vehicules
   $DATE_DEBUT = $this->input->post('DATE_DAT');
   $DATE_DAT_FIN = $this->input->post('DATE_DAT_FIN');
    $critere=" ";
  
   if(!empty($DATE_DEBUT) && !empty($DATE_DAT_FIN)){

      $critere.=' AND date_format(tracking_data.date,"%Y-%m-%d")between "'.$DATE_DEBUT.'" AND "'.$DATE_DAT_FIN.'" ';
    }

    // $vehicule_consomation=$this->Model->getRequete('SELECT DISTINCT vehicule.VEHICULE_ID as ID,vehicule.PLAQUE as NAME,vehicule.KILOMETRAGE as NBR FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1  GROUP BY ID,NAME');
    $proce_requete = "CALL `getRequete`(?,?,?,?);";
   $my_selectget_arret_date = $this->getBindParms('id,tracking_data.date', 'tracking_data', '1 '.$critere.'' , '`id` ASC');
    $my_selectget_arret_date=str_replace('\"', '"', $my_selectget_arret_date);
    $my_selectget_arret_date=str_replace('\n', '', $my_selectget_arret_date);
    $my_selectget_arret_date=str_replace('\"', '', $my_selectget_arret_date);
    $get_arret_date = $this->ModelPs->getRequete($proce_requete, $my_selectget_arret_date);

   // $vehicule_code=$this->Model->getRequete('SELECT DISTINCT vehicule.VEHICULE_ID as ID,vehicule.PLAQUE as NAME,vehicule.KILOMETRAGE as NBR,`latitude`,`longitude`,CODE FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1  GROUP BY ID,NAME,latitude,longitude');
    $vehicule_code=$this->Model->getRequete('SELECT `device_uid` as CODE FROM `tracking_data` WHERE 1 GROUP BY device_uid');
    $nvldistance=0;
   foreach ($vehicule_code as $key) 
   {
      $min_arret=$this->Model->getRequeteOne('SELECT MIN(id) as minimum FROM `tracking_data` WHERE 1 '.$critere.' and tracking_data.device_uid='.$key['CODE']);
      $max_arret=$this->Model->getRequeteOne('SELECT MAX(id) as maximum FROM `tracking_data` WHERE 1 '.$critere.' and tracking_data.device_uid='.$key['CODE']);
      
            
      $min_arret_plus=$min_arret['minimum']+1;
      // $min_arret_plus=$this->Model->getRequeteOne('select id as arret_min_deux from tracking_data where id = (SELECT MIN(id) FROM tracking_data WHERE id NOT IN (SELECT MIN(id) FROM tracking_data)) and tracking_data.device_uid='.$key['CODE']);
      // if(!empty($min_arret) && !empty($min_arret_plus) && !empty($max_arret))
      // {


          for ($i=$min_arret['minimum'],$j=$min_arret_plus; $i <$max_arret['maximum'],$j <$max_arret['maximum'] ; $i++,$j++)
        { 
        $point_distance=$this->Model->getRequeteOne('SELECT latitude,longitude FROM `tracking_data` WHERE 1 AND tracking_data.id = "'.$i.'"');
        $point_distance2=$this->Model->getRequeteOne('SELECT latitude,longitude FROM `tracking_data` WHERE 1 AND tracking_data.id = "'.$j.'"');

        if(!empty($point_distance) && !empty($point_distance2))
        {

          $nvldistance+=$this->Model->getDistance($point_distance['latitude'],$point_distance['longitude'],$point_distance2['latitude'],$point_distance2['longitude']);
        }else
        {

          $nvldistance+=0;
        } 
      }
      $nvldistance_arrondie=round($nvldistance);
      }
     //print_r($nvldistance_arrondie);exit();

    $vehicule_consomation=$this->Model->getRequete('SELECT DISTINCT vehicule.VEHICULE_ID as ID,vehicule.PLAQUE as NAME,vehicule.KILOMETRAGE as NBR FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1  GROUP BY ID,NAME');
    $donnees10="";
    $donnees101="";
    foreach ($vehicule_consomation as  $value) 
    {
       $littre_consom=$value['NBR']*$nvldistance_arrondie;
       $km=$nvldistance_arrondie;
      $color=$this->getcolor();
      $nb10 = (!empty($littre_consom)) ? $littre_consom : "0" ;
      $donnees10.="{name:'".str_replace("'","\'",$value['NAME'])."', y:".$nb10.",color:'green',key2:".$value['ID']."},";
      $donnees101.="{name:'".str_replace("'","\'",$value['NAME'])."', y:".$km.",color:'bleu',key2:".$value['ID']."},"; 
      
    }
     //print_r($donnees101);exit();



   

    $rapp10="<script type=\"text/javascript\">
    Highcharts.chart('container10', 
    {
     chart: 
     {
       type: 'column'
      },
     title:
     {
      text: 'Km parcouru Vs consommation par Véhicule'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br>'
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
               $(\"#myModal\").modal('');
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
     name:'Consomation en litre',
     color:'green',
     data:[".$donnees10."]
    },{
     name:'Km parcouru',
     color:'bleu',
     data:[".$donnees101."]
    }]
    });
   </script>";
   //RAPPORT4:NBR ARRET PAR VEHICULE

   $vehi_code_course=$this->Model->getRequete('SELECT DISTINCT vehicule.VEHICULE_ID,tracking_data.CODE_COURSE FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE mouvement=0');
    foreach ($vehi_code_course as  $value) 
    {

    $vehicule_consomation=$this->Model->getRequete('SELECT DISTINCT vehicule.VEHICULE_ID as IDENTIIANT,vehicule.PLAQUE as NAME,COUNT(mouvement) as  NBR,id FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE mouvement=0 AND tracking_data.CODE_COURSE="'.$value['CODE_COURSE'].'" GROUP BY IDENTIIANT,NAME');

    }
    //print_r($vehicule_consomation);exit();


      $donnees11="";
    foreach ($vehicule_consomation as  $value) 
    {
       //$littre_consom=$value['NBR']*$nvldistance_arrondie;
      $color=$this->getcolor();
      $nb11 = (!empty($value['NBR'])) ? $value['NBR'] : "0" ;
      $donnees11.="{name:'".str_replace("'","\'",$value['NAME'])."', y:".$nb11.",color:'".$color."',key2:".$value['IDENTIIANT']."},"; 
      
    }
     $rapp11="<script type=\"text/javascript\">
    Highcharts.chart('container11', 
    {
     chart: 
     {
       type: 'bar'
      },
     title:
     {
      text: 'Rapport arrêt par course'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
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
       bar: 
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
              // alert(this.key9),
               $(\"#titre\").html(\"LISTE DES AGENTS \");
               $(\"#myModal\").modal('show');
               var row_count ='1000000';
               $(\"#mytable\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_Anomalies/detail_accident')."\",
               type:\"POST\",
               data:
               {
                 key9:this.key9,

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
     name:'',
     data:[".$donnees11."]
    }]
    });
   </script>";
   //rapport nbr course par voiture
   $course_voiture=$this->Model->getRequete('SELECT DISTINCT vehicule.CODE as IDENTIIANT,vehicule.PLAQUE as NAME,COUNT(DISTINCT `CODE_COURSE`) as  NBR,id FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1 '.$critere.' GROUP BY IDENTIIANT,NAME'); 
   
    //print_r($vehicule_consomation);exit();


    $donnees12="";
    foreach ($course_voiture as  $value) 
    {
       //$littre_consom=$value['NBR']*$nvldistance_arrondie;
      $color=$this->getcolor();
      $nb12 = (!empty($value['NBR'])) ? $value['NBR'] : "0" ;
      $donnees12.="{name:'".str_replace("'","\'",$value['NAME'])."', y:".$nb12.",color:'".$color."',key12:".$value['IDENTIIANT']."},"; 
      
    }

      $rapp12="<script type=\"text/javascript\">
    Highcharts.chart('container12', 
    {
     chart: 
     {
       type: 'bar'
      },
     title:
     {
      text: 'Rapport course par véhicule'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> '
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
       bar: 
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
              // alert(this.key9),
               $(\"#titre\").html(\"LISTE DES AGENTS \");
               $(\"#myModal12\").modal('show');
               var row_count ='1000000';
               $(\"#mytable12\").DataTable({
               \"processing\":true,
               \"serverSide\":true,
               \"bDestroy\": true,
               \"oreder\":[],
               \"ajax\":{
               url:\"".base_url('dashboard/Dashboard_Anomalies/detail_course_voiture')."\",
               type:\"POST\",
               data:
               {
                 key12:this.key12,

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
     name:'',
     data:[".$donnees12."]
    }]
    });
   </script>";





      echo json_encode(array('rapp'=>$rapp,'rapp2'=>$rapp2,'rapp10'=>$rapp10,'rapp11'=>$rapp11,'rapp12'=>$rapp12));
    }
    //fonction pour la selection des collonnes de la base de données en utilisant les procedures stockées
            public function getBindParms($columnselect, $table, $where, $orderby)
            {
              $bindparams = array(
                'columnselect' => mysqli_real_escape_string($this->db->conn_id,$columnselect),
                'table' => mysqli_real_escape_string($this->db->conn_id,$table) ,
                'where' => mysqli_real_escape_string($this->db->conn_id,$where) ,
                'orderby' => mysqli_real_escape_string($this->db->conn_id,$orderby) ,
              );
              return $bindparams;
            }

               //detail di rapport nbr course par voiture

  function detail_course_voiture()
  {
    $KEY12=$this->input->post('key12');
    $break=explode(".",$KEY12);
    $ID=$KEY12;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    // $query_principal='SELECT DISTINCT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,CODE_COURSE,date,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur,CODE_COURSE FROM`vehicule` LEFT JOIN tracking_data on vehicule.CODE=tracking_data.device_uid left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';
       // $query_principal='SELECT  vehicule.CODE ,vehicule.PLAQUE,  `CODE_COURSE` as  NBR,id FROM `vehicule`  join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1 ';
   $query_principal='SELECT  vehicule.CODE ,vehicule.PLAQUE,  `CODE_COURSE`,DESC_MARQUE,DESC_MODELE,PLAQUE,CODE_COURSE,date,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur FROM `vehicule` LEFT  join tracking_data on vehicule.CODE=tracking_data.device_uid left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1
    ';
    

    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }

    $order_by='';
    if($_POST['order']['0']['column']!=0)
    {
      $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY IDENTIIANT   DESC';
    }
    $search=!empty($_POST['search']['value']) ? (" AND (CODE LIKE '%$var_search%' OR DESC_MARQUE LIKE '%$var_search%' OR DESC_MODELE LIKE '%$var_search%' OR PLAQUE LIKE '%$var_search%' OR COULEUR LIKE '%$var_search%' OR KILOMETRAGE LIKE '%$var_search%' OR CONCAT(NOM_PROPRIETAIRE,' ',PRENOM_PROPRIETAIRE) LIKE '%$var_search%' OR NOM_PROPRIETAIRE LIKE '%$var_search%')"):'';
     $critaire="";
  
    $critaire=" AND tracking_data.device_uid='".$ID."'";

    $GROUPby='';
    
      $GROUPby ='GROUP BY CODE_COURSE  ';
    


    
   
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$GROUPby.' '.$order_by.'   '.$limit;
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
      $intrant[] ="<strong class='text-dark'/>".$row->CODE_COURSE;
      $intrant[] ="<strong class='text-dark'/>".$row->date;
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
   }
 ?>