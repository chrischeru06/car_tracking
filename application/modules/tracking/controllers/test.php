<?php


/**
 * 
 */
class test extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

  function updateData(){

    $code=$this->notifications->generate_UIID(6);
    $data_updaate=array(

      'CODE_COURSE'=>$code

    );

    $update=$this->Model->update('tracking_data',array('device_uid'=>4.4098856886e-314),$data_updaate);
  }

  function load($value='')
  {
   $this->load->view('view_ex');
  }

}?>