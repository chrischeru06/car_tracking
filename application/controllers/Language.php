<?php


class Language extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
      
      $array_session_language = array('site_lang' => $this->uri->segment(3));
      $this->session->set_userdata($array_session_language);
      // $this->load->helper('url');
      redirect($_SERVER['HTTP_REFERER']);
      // redirect(base_url('#'));
    }

  }

?>