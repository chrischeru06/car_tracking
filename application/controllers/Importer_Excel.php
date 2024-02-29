
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importer_Excel extends CI_Controller
{
  public function index()
  {
    $this->load->view('Importer_Excel_View');
  }
  public function visa()
    {
        if(isset($_FILES["file"]["name"]))
        {
          $highestRow=0;
          $path = $_FILES["file"]["tmp_name"];
          $object = PHPExcel_IOFactory::load($path);
          $ind =1;
        
        foreach($object->getWorksheetIterator() as $worksheet)
              {
              $highestRow.=$worksheet->getHighestRow();
               $highestRow=34;
              $highestColumn=$worksheet->getHighestColumn();
              for($row=2; $row<=$highestRow; $row++)
                 {
              
               $array_str_sanitaire[] = array 
               (
                 'latitude'=>$worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                 'longitude'=>$worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                          
                 'device_uid'=>$worksheet->getCellByColumnAndRow(2, $row)->getValue(),
              );
                }
              $ind ++;
            }
            $this->Model->insert_batch('tracking_data',$array_str_sanitaire);
          // $msg['msg'] = "<div class='alert alert-success'>Un fichier de ".$ind." enregistrement a été bien chargé.</div>";
          // $this->session->set_flashdata($msg);
          redirect(base_url('chauffeur/Chauffeur/index'));
        }
        else
        {
          $msg = "Le fichier non trouvé";
          $this->index($msg);
        }
    }






}
?>



