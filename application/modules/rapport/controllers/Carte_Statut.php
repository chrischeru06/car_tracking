<?php 
/*fait par ella Dancilla le 19/04/2023
mail:ella_dancilla@mediabox.bi
Rapport des cartes par statut
tel:71379943
*/
class Carte_Statut extends CI_Controller
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
    $this->load->view('Carte_Statut_View');
  }

  //detail pour le rapport
  function detail_cartes()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal="SELECT agent.NOM,agent.PRENOM,agent.CODE_AGENT,card_uid_pointage.CARD_UID,card_uid_pointage.STATUT,card_uid_pointage.CARD_ID,statut_carte.DESC_STATU_CARTE FROM `card_uid_pointage` join statut_carte on card_uid_pointage.STATUT=statut_carte.STATUT_CARTE_ID left JOIN agent_card on card_uid_pointage.CARD_UID=agent_card.CARD_UID left JOIN agent ON agent.CODE_AGENT=agent_card.CODE_AGENT WHERE 1 ";
  

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
    $search = !empty($_POST['search']['value']) ? (" AND ( `NOM` LIKE '%$var_search%' OR `PRENOM` LIKE '%$var_search%') ") : '';
    $critaire="  AND statut_carte.`STATUT_CARTE_ID`=".$ID;
    $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
    $query_filter=$query_principal.'  '.$critaire.' '.$search;
    $fetch_data = $this->Model->datatable($query_secondaire);
    $u=0;
    $data = array();
    foreach ($fetch_data as $row)
    {
      $affectationn="N/A ";
      if (!empty($row->CODE_AGENT)) 
      {
        $affectation=$this->Model->getRequete("SELECT `AFFECTATION_AGENT_ID`,`CODE_AGENT`,`CODE_POINTAGE`,`DATE_AFFECTATION` FROM `agent_affectation` WHERE agent_affectation .`CODE_AGENT`='".$row->CODE_AGENT."'");
      foreach($affectation as $affect)
      {
       $affectationn=$affect['DATE_AFFECTATION']."<br>";
      }
      }
      
      $u++;
      $intrant=array(); 
      $intrant[] ="<strong class='text-dark'/>".$u; 
     
      $agent = (!empty($row->NOM) || !empty($row->PRENOM)) ? $row->NOM." ".$row->PRENOM   : 'N/A' ;
       $intrant[] ="<strong class='text-dark'/>".$agent;

      $carte = (!empty($row->CARD_UID)) ? $row->CARD_UID : 'N/A' ;
      $intrant[] ="<strong class='text-dark'/>".$carte;

      $intrant[] ="<strong class='text-dark'/>".$row->DESC_STATU_CARTE;
      
      $intrant[] ="<strong class='text-dark'/>".$affectationn;
      // $intrant[] ="<strong class='text-dark'/>".$row->ADRESSE_MAIL;
      // $intrant[] ="<strong class='text-dark'/>".$row->DATE_INSERTION;
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
    $carte_statut=$this->Model->getRequete('SELECT `statut_carte`.`STATUT_CARTE_ID` as ID,`statut_carte`.`DESC_STATU_CARTE` as NAME,count(`CARD_ID`) as NBR FROM `card_uid_pointage` join statut_carte on card_uid_pointage.STATUT=statut_carte.STATUT_CARTE_ID WHERE 1 GROUP BY ID,name');
    $total=0;
    $donnees1="";
    foreach ($carte_statut as  $value) 
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
       type: 'columnpyramid'
       },
       title: 
       {
         text: '<b> Rapport des catres par statut'
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
                         $(\"#titre\").html(\"LISTE DES CATRES SELON LEUR STATUT \");
                         $(\"#myModal\").modal();
                         var row_count ='1000000';
                         $(\"#mytable\").DataTable({
                           \"processing\":true,
                           \"serverSide\":true,
                           \"bDestroy\": true,
                           \"oreder\":[],
                           \"ajax\":{
                             url:\"".base_url('rapport/Carte_Statut/detail_cartes')."\",
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
                            showInLegend: true
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
                              name: 'Cartes ',
                              color: '',
                              data: [".$donnees1."]
                              }]

                              });
                              </script>";


                              echo json_encode(array('rapp'=>$rapp));
    }
  }
?>