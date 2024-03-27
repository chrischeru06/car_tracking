<?php 
/*fait par NIYOMWUNGERE Ella Dancilla 
//22/02/2022
mail:ella_dancilla@mediabox.bi
Rapport vehicules par proprietaire
tel:71379943
*/
class Rapport_Proprietaire extends CI_Controller
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
    $this->load->view('Rapport_Proprietaire_View');
  }


  //detail pour le rapport1:vehicules par proprietaire
  function detail_proprietaire()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    
    // $query_principal="SELECT DISTINCT(`AGENT_ID`),`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`ADRESSE_MAIL`,agent.`DATE_INSERTION`,agent_card.CARD_UID  FROM `agent`  JOIN agent_card ON agent.CODE_AGENT=agent_card.CODE_AGENT WHERE 1";
    $query_principal="SELECT DISTINCT( proprietaire.PROPRIETAIRE_ID),vehicule_modele.DESC_MODELE,vehicule_marque.DESC_MARQUE,vehicule.COULEUR,vehicule.PLAQUE FROM `vehicule`   join proprietaire on vehicule.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID join chauffeur_vehicule on vehicule.CODE=chauffeur_vehicule.CODE JOIN vehicule_marque ON vehicule_marque.ID_MARQUE=vehicule.ID_MARQUE JOIN vehicule_modele ON vehicule_modele.ID_MODELE=vehicule.ID_MODELE   WHERE 1";
 

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
    $proprietairee=$this->Model->getRequete('SELECT  proprietaire.PROPRIETAIRE_ID as ID,if(proprietaire.PRENOM_PROPRIETAIRE !="",CONCAT(proprietaire.NOM_PROPRIETAIRE,"   ",proprietaire.PRENOM_PROPRIETAIRE),proprietaire.NOM_PROPRIETAIRE)as NAME ,count(vehicule.`PROPRIETAIRE_ID`)  as NBR FROM `vehicule`  join proprietaire on vehicule.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE 1 GROUP BY ID,NAME');
     

    $total=0;
    $donnees1="";
    foreach ($proprietairee as  $value) 
    {
      $color=$this->getcolor();

      $total+=$value['NBR'];
      $name = (!empty($value['NAME'])) ? $value['NAME'] : "Aucun" ;
      $nb = (!empty($value['NBR'])) ? $value['NBR'] : "0" ;
      $donnees1.="{name:'".str_replace("'","\'",$name)."', y:".$nb.",color:'".$color."',key:'".$value['ID']."'},";  
    }

 
    $rapp="<script type=\"text/javascript\">
    Highcharts.chart('container', 
    {
     chart: 
     {
       type: 'column'
       },
       title: 
       {
         text: ''
         },
         subtitle: 
         {
           text: '<b><br> Rapport du ".date('d-m-Y')."</b><br> Total= ".$total." '
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
                         $(\"#titre\").html(\"LISTE DES PROPRIETAIRES \");
                         $(\"#myModal\").modal('show');
                         var row_count ='1000000';
                         $(\"#mytable\").DataTable({
                           \"processing\":true,
                           \"serverSide\":true,
                           \"bDestroy\": true,
                           \"oreder\":[],
                           \"ajax\":{
                             url:\"".base_url('rapport/Rapport_Proprietaire/detail_proprietaire')."\",
                             type:\"POST\",
                             data:
                             {
                              key:this.key,

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
                              data: [".$donnees1."]
                              }]

                              });
                              </script>";

                              echo json_encode(array('rapp'=>$rapp));
                            }
                          }
                        ?>