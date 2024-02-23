<?php 
/**RUGAMBA Jean Vainqueur
*Titre: Rapport des sites par client
*Numero de telephone: (+257) 62 47 19 15
*Email: jean.vainqueur@mediabox.bi
* 
* 
*/
class Rapport_sites_client extends CI_Controller
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
    
    $this->load->view('Rapport_sites_client_view');
  }
   //detail pour le rapport: sites par client
  function detail_site()
  {
    $KEY=$this->input->post('key');
    $break=explode(".",$KEY);
    $ID=$KEY;
    $cond="";
    $cond = ' AND client_site.CLIENT_ID='.$KEY;
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $query_principal="SELECT SITE_ID , NOM_SITE , ADRESSE_SITE,CONCAT(client.`NOM_CLIENT`,'  ',client.`PRENOM`) as client,`PERSONE_REFERENCE`,`TELEPHONE_PERS_REFERENCE`,client.`TELEPHONE` FROM client_site JOIN client ON client_site.CLIENT_ID=client.CLIENT_ID  WHERE 1 ".$cond."";

    $limit='LIMIT 0,10';
    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }
    $order_by='';
   if($_POST['order']['0']['column']!=0)
   {
     $order_by = isset($_POST['order']) ? ' ORDER BY '.$_POST['order']['0']['column'] .'  '.$_POST['order']['0']['dir'] : ' ORDER BY NOM_SITE   DESC';
   }

   $search = !empty($_POST['search']['value']) ? (" AND ( `NOM_SITE` LIKE '%$var_search%' OR `ADRESSE_SITE` LIKE '%$var_search%' OR client.`NOM_CLIENT` LIKE '%$var_search%' OR client.`PRENOM` LIKE '%$var_search%' OR `PERSONE_REFERENCE` LIKE '%$var_search%' OR `TELEPHONE_PERS_REFERENCE` LIKE '%$var_search%' OR `TELEPHONE` LIKE '%$var_search%') ") : '';

   $critaire = ' AND client_site.CLIENT_ID='.$KEY;

   $query_secondaire=$query_principal.'  '.$critaire.' '.$search.' '.$order_by.'   '.$limit;
   $query_filter=$query_principal.'  '.$critaire.' '.$search;
   $fetch_data = $this->Model->datatable($query_secondaire);
   $u=0;
   $data = array();
   foreach ($fetch_data as $row)
   {
    
     $u++;
     $post=array();
     $post[] ="<strong class='text-dark'/>".$u; 
     $post[] ="<strong class='text-dark'/>".$row->client;
     $post[] ="<strong class='text-dark'/>".$row->NOM_SITE;
     $post[] ="<strong class='text-dark'/>".$row->ADRESSE_SITE;
     $post[] ="<strong class='text-dark'/>".$row->TELEPHONE;
     $post[] ="<strong class='text-dark'/>".$row->PERSONE_REFERENCE;
     $post[] ="<strong class='text-dark'/>".$row->TELEPHONE_PERS_REFERENCE;

     $data[] =$post;
    }

    $output = array
    (
     "draw" => intval($_POST['draw']),
     "recordsTotal" =>$this->Model->all_data($query_principal),
     "recordsFiltered" => $this->Model->filtrer($query_filter),
     "data" => $data
    );

    echo json_encode($output);
  }
  public function get_rapport()
  {
    
    $client=$this->Model->getRequete("SELECT client.CLIENT_ID as ID ,CONCAT(`NOM_CLIENT`,'  ',`PRENOM`) as NAME,count(`SITE_ID`) as NB_SITE FROM `client_site` JOIN client ON client_site.CLIENT_ID=client.CLIENT_ID WHERE 1 GROUP BY ID,NAME");
    

    $total=0;
    $donnees1="";
    foreach ($client as  $value) 
    {
      $color=$this->getcolor();

      $total+=$value['NB_SITE'];
      $name = (!empty($value['NAME'])) ? $value['NAME'] : "Aucun" ;
      $nb = (!empty($value['NB_SITE'])) ? $value['NB_SITE'] : "0" ;
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
       text: '<b> Rapport des sites par client'
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
             $(\"#titre\").html(\"LISTE DES SITES \");
             $(\"#myModal\").modal();
             var row_count ='1000000';
             $(\"#mytable\").DataTable({
             \"processing\":true,
             \"serverSide\":true,
             \"bDestroy\": true,
             \"oreder\":[],
             \"ajax\":{
             url:\"".base_url('rapport/Rapport_sites_client/detail_site')."\",
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
          name: 'sites ',
          color: '',
          data: [".$donnees1."]
        }]

        });
      </script>";
         
       echo json_encode(array('rapp'=>$rapp));
    }
  }
?>