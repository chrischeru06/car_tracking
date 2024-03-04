<?php 
/*fait par NIYOMWUNGERE Ella Dancilla 
/27/02/2022
mail:ella_dancilla@mediabox.bi
Rapport chauffeurs par statut
tel:71379943
*/
class Dashboard_Vehicule extends CI_Controller
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
    $this->load->view('Dashboard_Vehicule_View');
  }


  //detail pour le rapport1:vehicules statut
  function detail_veh_statut()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


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

    $critaire=" AND vehicule.STATUT=".$ID;
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
    $query_principal='SELECT VEHICULE_ID,vehicule.CODE,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE,"&nbsp;",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM,"&nbsp;",chauffeur.PRENOM) AS desc_chauffeur FROM vehicule JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID WHERE 1';


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
   //detail pour le rapport1:vehicules en mouvement vs en stationnement
  function detail_veh_station_mouv()
  {
    $KEY3=$this->input->post('key3');
    $break=explode(".",$KEY3);
    $ID=$KEY3;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT VEHICULE_ID,vehicule.CODE,tracking_data.device_uid,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur,tracking_data.mouvement FROM vehicule left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID left join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1';


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

    $critaire=" AND  tracking_data.mouvement=".$ID;
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
   //detail pour le rapport1:vehicules en mouvement vs en stationnement
  function detail_veh_allum_etteint()
  {
    $KEY4=$this->input->post('key4');
    $break=explode(".",$KEY4);
    $ID=$KEY4;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal='SELECT VEHICULE_ID,vehicule.CODE,tracking_data.device_uid,DESC_MARQUE,DESC_MODELE,PLAQUE,COULEUR,KILOMETRAGE,if(`TYPE_PROPRIETAIRE_ID`=2,CONCAT(NOM_PROPRIETAIRE," ",PRENOM_PROPRIETAIRE),NOM_PROPRIETAIRE) AS desc_proprio,vehicule.IS_ACTIVE,CONCAT(chauffeur.NOM," ",chauffeur.PRENOM) AS desc_chauffeur,tracking_data.mouvement FROM vehicule left JOIN vehicule_marque ON vehicule_marque.ID_MARQUE = vehicule.ID_MARQUE left JOIN vehicule_modele ON vehicule_modele.ID_MODELE = vehicule.ID_MODELE left JOIN proprietaire ON proprietaire.PROPRIETAIRE_ID = vehicule.PROPRIETAIRE_ID LEFT JOIN users ON proprietaire.PROPRIETAIRE_ID = users.PROPRIETAIRE_ID LEFT JOIN chauffeur_vehicule ON chauffeur_vehicule.CODE = vehicule.CODE LEFT JOIN chauffeur ON chauffeur.CHAUFFEUR_ID = chauffeur_vehicule.CHAUFFEUR_ID left join tracking_data on vehicule.CODE=tracking_data.device_uid WHERE 1';


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

    $critaire=" AND  tracking_data.ignition=".$ID;
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



  public function get_rapport()
  {
    $vehicule_statut=$this->Model->getRequete('SELECT vehicule.STATUT as ID, if(vehicule.STATUT=1,"Actif","Inactif")as statut ,COUNT(`VEHICULE_ID`) as NBR FROM `vehicule` WHERE 1 GROUP by ID,statut ');

    $donnees1="";
    foreach ($vehicule_statut as  $value) 
    {
      $color=$this->getcolor();
      $key_id=($value['ID']>0) ? $value['ID'] : "0" ;
      $somme=($value['NBR']>0) ? $value['NBR'] : "0" ;
      $donnees1.="{name:'".$value['statut']." :". $somme."', y:". $somme.",color:'".$color."',key:'". $key_id."'},";
    }
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
                          url:\"".base_url('dashboard/Dashboard_Vehicule/detail_veh_statut')."\",
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
                        series: [
                        {
                          name: '',

                          data: [".$donnees1." ]
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
               url:\"".base_url('dashboard/Dashboard_Vehicule/detail_veh_marque')."\",
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
   $vehicule_mouvet_stationnema=$this->Model->getRequete('SELECT tracking_data.mouvement as ID, if(tracking_data.mouvement=1,"Véhicule en mouvement","Véhicule en stationnement")as statut ,COUNT(tracking_data.device_uid) as NBR FROM `tracking_data` JOIN vehicule ON  tracking_data.device_uid=vehicule.CODE  WHERE 1 GROUP by tracking_data.mouvement,statut  ');
   
    $donnees3="";
    foreach ($vehicule_mouvet_stationnema as  $value) 
    {
      $color=$this->getcolor();
      $key_id3=($value['ID']>0) ? $value['ID'] : "0" ;
      $somme3=($value['NBR']>0) ? $value['NBR'] : "0" ;
      $donnees3.="{name:'".$value['statut']." :". $somme3."', y:". $somme3.",color:'".$color."',key3:'". $key_id3."'},";
    }
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
          text: 'Véhicule en mouvement Vs en stationnement'
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
                      $(\"#titre1\").html(\"LISTE DES VEHICULES \");

                       $(\"#myModal\").modal('show');
                       var row_count ='1000000';
                       $(\"#mytable\").DataTable({
                        \"processing\":true,
                        \"serverSide\":true,
                        \"bDestroy\": true,
                        \"oreder\":[],
                        \"ajax\":{
                          url:\"".base_url('dashboard/Dashboard_Vehicule/detail_veh_station_mouv')."\",
                          type:\"POST\",
                          data:{

                           key3:this.key3,

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
                        series: [
                        {
                          name: '',

                          data: [".$donnees3." ]
                          }]
                          });
                          </script>";
    //rapport4:vehicule en allumé vs etteintes
   $vehicule_allume_eteinte=$this->Model->getRequete('SELECT tracking_data.ignition as ID, if(tracking_data.`ignition`=1,"Véhicule allumé","Véhicule etteint")as statut ,COUNT(tracking_data.ignition) as NBR FROM `tracking_data` JOIN vehicule ON  tracking_data.device_uid=vehicule.CODE  WHERE 1 GROUP by  tracking_data.ignition,statut');
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
      text: 'Véhicule allumé Vs etteint'
     },
     subtitle:
     {
     text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> <b>Total= ".$total4."'
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
               url:\"".base_url('dashboard/Dashboard_Vehicule/detail_veh_allum_etteint')."\",
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

   

      echo json_encode(array('rapp'=>$rapp,'rapp2'=>$rapp2,'rapp3'=>$rapp3,'rapp4'=>$rapp4));
    }
   }
 ?>