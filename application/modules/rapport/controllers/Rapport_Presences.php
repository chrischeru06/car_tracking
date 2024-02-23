<?php 
/*fait par @ella Dancilla le 8/12/2022
mail:ella_dancilla@mediabox.bi
Rapport des presences
*/
class Rapport_Presences extends CI_Controller
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
    $this->load->view('Rapport_Presences_View');
  }

  //detail pour le rapport:membres par menage
  function detail_presence()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal="SELECT `PRESENCE_ID`,`CODE_POINTAGE`,agent_pointage.`CODE_AGENT`,`DATE_ENTRE`,`DATE_SORTIE`,`TIME_OUT`,`TIME_IN`,agent.NOM,agent.PRENOM,agent_pointage.`STATUT`,agent_card.CARD_UID,agent.NUMERO_TELEPHONE  FROM `agent_pointage` JOIN agent ON agent_pointage.CODE_AGENT=agent.CODE_AGENT JOIN agent_card ON agent.CODE_AGENT=agent_card.CODE_AGENT JOIN card_uid_pointage ON agent_card.CARD_UID=card_uid_pointage.CARD_UID WHERE 1";
     //SELECT `PRESENCE_ID`,`CODE_POINTAGE`,agent_pointage.`CODE_AGENT`,`DATE_ENTRE`,`DATE_SORTIE`,`TIME_OUT`,`TIME_IN`,agent.NOM,agent.PRENOM,agent_pointage.`STATUT`,agent_card.CARD_UID FROM `agent_pointage` JOIN agent ON agent_pointage.CODE_AGENT=agent.CODE_AGENT JOIN agent_card ON agent.CODE_AGENT=agent_card.CODE_AGENT JOIN card_uid_pointage ON agent_card.CARD_UID=card_uid_pointage.CARD_UID WHERE 1
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

    $search = !empty($_POST['search']['value']) ? (" AND ( agent.`NOM` LIKE '%$var_search%' OR agent.`PRENOM` LIKE '%$var_search%' OR agent_card.CARD_UID LIKE '%$var_search%' OR agent.NUMERO_TELEPHONE LIKE '%$var_search%') ") : '';
    $critaire=" AND agent_pointage.STATUT=".$ID;

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
      $intrant[] ="<strong class='text-dark'/>".$row->NOM."  ".$row->PRENOM;
      $intrant[] ="<strong class='text-dark'/>".$row->NUMERO_TELEPHONE;
      $intrant[] ="<strong class='text-dark'/>".$row->CARD_UID;
      if ($row->STATUT==2) 
      {
        $intrant[] ="<strong class='text-dark'/>Pointage complet ";
      }else
      {
       $intrant[] ="<strong class='text-dark'/>Pointage incomplet";
      }
      $intrant[] ="<strong class='text-dark'/>".$row->TIME_IN;
      $rentrer = (!empty($row->TIME_OUT)) ? $row->TIME_OUT : 'N/A' ;
      $intrant[] ="<strong class='text-dark'/>".$rentrer;
    


      // $intrant[] ="<strong class='text-dark'/>".$row->CODE_AGENT;
      // $intrant[] ="<strong class='text-dark'/>".$row->DATE_ENTRE;
      // $intrant[] ="<strong class='text-dark'/>".$row->DATE_SORTIE;
      //$intrant[] ="<strong class='text-dark'/>".$row->STATUT;
      
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
    $Presence=$this->Model->getRequete('SELECT  `statut_presence`.`STATUT_PRESENCE_ID` AS ID, if(agent_pointage.STATUT=2,"Complet","Incomplet")as statut,COUNT(`PRESENCE_ID`)AS NBR FROM `agent_pointage` JOIN statut_presence ON statut_presence.STATUT_PRESENCE_ID=agent_pointage.STATUT WHERE 1 GROUP BY ID,statut');
    $donnees1="";
    foreach ($Presence as  $value) 
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
          text: 'Rapport  des présences'
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


                       $(\"#titre1\").html(\"LISTE DES PRESENCES \");

                       $(\"#myModal\").modal();
                       var row_count ='1000000';
                       $(\"#mytable\").DataTable({
                        \"processing\":true,
                        \"serverSide\":true,
                        \"bDestroy\": true,
                        \"oreder\":[],
                        \"ajax\":{
                          url:\"".base_url('rapport/rapport_Presences/detail_presence')."\",
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

                          echo json_encode(array('rapp'=>$rapp));
                        }
                      }
                    ?>