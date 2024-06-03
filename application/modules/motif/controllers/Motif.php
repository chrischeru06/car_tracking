<?php 
/*
Auteur : Ella Dancilla 
Email : elladancilla@mediabox.bi
CONTACT : 72496057
Description : CRUD des motifs activation ou désactivation
DAte : le 25/003/2024

*/
class Motif extends CI_Controller
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

  function index()
  {
    $data['title'] = 'motif_activ_desactiv';

    // $data['categorie'] = $this->Model->getRequete('SELECT ID_CATEGORIE,DESC_CATEGORIE FROM categorie_motif WHERE 1');

    $data['type'] = $this->Model->getRequete('SELECT ID_TYPE,DESC_TYPE FROM type_motif WHERE 1');

    $this->load->view('Motif_liste_View',$data);
  }

  function listing()
  {
    $query_principal='SELECT `ID_MOTIF`,type_motif.DESC_TYPE,`DESC_MOTIF` FROM `motif` LEFT join type_motif on motif.ID_TYPE=type_motif.ID_TYPE WHERE 1';
    $var_search= !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $limit='LIMIT 0,10';

    if($_POST['length'] != -1)
    {
      $limit='LIMIT '.$_POST["start"].','.$_POST["length"];
    }
    $critaire='';

    $order_by=' ORDER BY ID_MOTIF desc';
    $order_column=array('');
    
    $search=!empty($_POST['search']['value']) ? (" AND ( DESC_TYPE LIKE '%$var_search%' OR DESC_MOTIF LIKE '%$var_search%' OR DESCR_CARD LIKE '%$var_search%')"):'';     

    $query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
    $query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

    $fetch_marche = $this->Model->datatable($query_secondaire);
    $data = array();
    $u=0;
    foreach ($fetch_marche as $row)
    {
      $u++;
      $sub_array=array();
      $sub_array[]=$u;

   
      $sub_array[] = $row->DESC_TYPE;
      $sub_array[]=$row->DESC_MOTIF;

      $option='<div class="dropdown" text-center" style="color:#fff;">
      <a class=" btn-sm dropdown-toggle" style="color:white; hover:black;" data-toggle="dropdown"><i class="bi bi-three-dots h5" style="color:blue;"></i>  <span class="caret"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-left">
      ';
      $option.='<a class="btn-md" href="#" onClick="edit_motif('.$row->ID_MOTIF.')"><span class="bi bi-pencil h5"></span>&nbsp;&nbsp;'.lang('btn_modifier').'</a>';
      $option .= " </ul>
      </div>";
      $sub_array[]=$option;
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

  // function delete($id)
  // {
  //   $table='shift';
  //   $criteres['SHIFT_ID']=$id;
  //   $data['rows']= $this->Model->getOne( $table,$criteres);
  //   $this->Model->delete($table,$criteres);
  //   $msg['message']='<div class="alert alert-success text-center " id="message">Client supprimer avec succés</div>';
  //   $this->session->set_flashdata($msg);
  // }

  function save()
  {
    // $ID_CATEGORIE = $this->input->post('ID_CATEGORIE');
    $ID_TYPE = $this->input->post('ID_TYPE');
    $DESC_MOTIF = $this->input->post('DESC_MOTIF');

    $EXIST=$this->Model->getRequeteOne("SELECT * FROM `motif` WHERE `DESC_MOTIF`='".$DESC_MOTIF."';");
    $reponse=0;
    $message=''.lang('mot_pas_enreg').'';

    if(empty($EXIST))
    {
      $data_insert=array(
        // 'ID_CATEGORIE'=>$ID_CATEGORIE,
        'ID_TYPE'=>$ID_TYPE,
        'DESC_MOTIF'=>$DESC_MOTIF);
      $this->Model->create('motif',$data_insert);
      $message=' '.lang('msg_enreg_ft_success').' ';
      $reponse=1;
    }
    else if (!empty($EXIST))
    {
      $reponse=2;
      $message=''.lang('motif_existe_dja').'';
    }

    $output = array('status'=>$reponse,'message' =>$message );
    echo json_encode($output);
  }

  function editer($id)
  {
  	$get = $this->Model->getOne('motif',array('ID_MOTIF'=>$id));

    // $categorie=$this->Model->getRequete('SELECT ID_CATEGORIE,DESC_CATEGORIE FROM categorie_motif WHERE 1');

    // $html_cat='<option value="">Séléctionner</option>';
    // foreach ($categorie as $key)
    // {
    //   $selected='';
    //   if($key['ID_CATEGORIE']==$get['ID_CATEGORIE'])
    //   {
    //     $selected=' selected';

    //   }
    //   $html_cat.='<option value="'.$key['ID_CATEGORIE'].'" '.$selected.'>'.$key['DESC_CATEGORIE'].'</option>';
    // }


    $type = $this->Model->getRequete('SELECT ID_TYPE,DESC_TYPE FROM type_motif WHERE 1');

    $html_type='<option value="">'.lang('selectionner').'</option>';
    foreach ($type as $key)
    {
      $selected='';
      if($key['ID_TYPE']==$get['ID_TYPE'])
      {
        $selected=' selected';
      }
      $html_type.='<option value="'.$key['ID_TYPE'].'"'.$selected.'>'.$key['DESC_TYPE'].'</option>';
    }

    $output=array('ID_MOTIF'=>$get['ID_MOTIF'],'DESC_MOTIF'=>$get['DESC_MOTIF'],'html_type'=>$html_type);

    echo json_encode($output);
  }

  //modificatio
  function update()
  {
    $ID_MOTIF = $this->input->post('ID_MOTIF');

    // $ID_CATEGORIE = $this->input->post('ID_CATEGORIE1');
    $ID_TYPE = $this->input->post('ID_TYPE1');
    $DESC_MOTIF = $this->input->post('DESC_MOTIF1');

    $EXIST=$this->Model->getRequeteOne("SELECT * FROM `motif` WHERE `DESC_MOTIF`='".$DESC_MOTIF."' AND ID_MOTIF != '".$ID_MOTIF."' ;");
    $reponse=0;
    $message=''.lang('mot_pas_enreg').'';

    if(empty($EXIST))
    {

      $table='motif';

      $data_update=array(
        // 'ID_CATEGORIE'=>$ID_CATEGORIE,
        'ID_TYPE'=>$ID_TYPE,
        'DESC_MOTIF'=>$DESC_MOTIF);

      $update=$this->Model->update($table,array('ID_MOTIF'=>$ID_MOTIF),$data_update);

      $message=' '.lang('msg_success_modif').' ';
      $reponse=1;
    }
    else if (!empty($EXIST))
    {
      $reponse=2;
      $message=''.lang('motif_existe_dja').'';
    }

    $output = array('status'=>$reponse,'message' =>$message );
    echo json_encode($output);
  }

//   function get_type_categorie($ID_CATEGORIE=0)
//   {
//     if($ID_CATEGORIE==4)
//     {
//       $cat=$this->Model->getRequete('SELECT STATUT_CARD_ID,DESCR_CARD FROM statut_card WHERE 1');
//       $html='<option value="">---sélectionner---</option>';
//       foreach ($cat as $key)
//       {
//         $html.='<option value="'.$key['STATUT_CARD_ID'].'">'.$key['DESCR_CARD'].'</option>';
//       }
//     }else
//     {

//      $categorie=$this->Model->getRequete('SELECT ID_TYPE,DESC_TYPE FROM type_motif WHERE 1');
//      $html='<option value="">---sélectionner---</option>';
//      foreach ($categorie as $key)
//      {
//       $html.='<option value="'.$key['ID_TYPE'].'">'.$key['DESC_TYPE'].'</option>';
//     }
//   }


//     echo json_encode($html);

// }

}

?>