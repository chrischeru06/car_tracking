<?php 
/*fait par NIYOMWUNGERE Ella Dancilla 
/27/02/2022
mail:ella_dancilla@mediabox.bi
Dashboard vehicules
tel:71379943
*/
class Rapport_Chauffeur_Statut extends CI_Controller
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
    $this->load->view('Rapport_Chauffeur_Statut_View');
  }


  //detail pour le rapport1:vehicules par proprietaire
  function detail_chof_statut()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    
    // $query_principal="SELECT DISTINCT(`AGENT_ID`),`NOM`,`PRENOM`,`ADRESSE_PHYSIQUE`,`NUMERO_TELEPHONE`,`ADRESSE_MAIL`,agent.`DATE_INSERTION`,agent_card.CARD_UID  FROM `agent`  JOIN agent_card ON agent.CODE_AGENT=agent_card.CODE_AGENT WHERE 1";
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

  public function get_rapport()
  {
    $chauffeur_statut=$this->Model->getRequete('SELECT chauffeur.IS_ACTIVE as ID, if(chauffeur.IS_ACTIVE=1,"Actif","Inactif")as statut ,COUNT(`CHAUFFEUR_ID`) as NBR FROM `chauffeur` WHERE 1 GROUP by ID,statut ');


    $donnees1="";
    foreach ($chauffeur_statut as  $value) 
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
          text: ''
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
                          url:\"".base_url('rapport/Rapport_Chauffeur_Statut/detail_chof_statut')."\",
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