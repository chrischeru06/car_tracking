<?php

class mapActions extends sfActions
{
  /**
	 * Executes 'index' function
	 *
	 * Display a list of existing objects
	 *
	 * @param sfRequest $request A request object
	 */
  public function executeIndex(sfWebRequest $request)
  {

   $this->filterstatus =  $request->getParameter('filterstatus');
   $this->zoom = 11;
   $this->center_coordinates = '-3.4274111,29.9248309';

   if($this->filterstatus!=''){

    $q = Doctrine_Query::create()
        ->from('Map z')
        ->where('z.id = ?',$this->filterstatus)
        ->orderBy('z.id ASC');

    $this->map = $q->execute();
    $this->zoom = 13;



   }else{

    $q = Doctrine_Query::create()
    ->from('Map z')
    ->orderBy('z.id ASC');
    $this->map = $q->execute();


    }

    // $this->all_datas='';

    // foreach ($zone as $key_zone) {
    //   # code...

    // $lat =floatval($key_zone->getLon());
    // $longi =floatval($key_zone->getLat());
    // $nom_ben =floatval($key_zone->getName());
    // $id =floatval($key_zone->getId());

    // $all_datas.=  '<b>1</b><br>';
 
    // }

 
    $p = Doctrine_Query::create()
        ->from('Zones p')
        ->orderBy('p.id ASC');

    $zonelist = $this->zones = $p->execute(); 

    $this->form_options=[];
        
    foreach($zonelist as $applicationform)
    {
        
    $selected = "";
    
    if($applicationform->getId() == $this->filterstatus)
    
    {

    $selected = 'selected="selected"';
    
    }
  
    $this->form_options[]= '<option value="'.$applicationform->getId().'" '.$selected.'>'.$applicationform->getName().'</option>';
            
    }



   $this->setLayout("layout");

   }

  /**
	 * Executes 'new' function
	 *
	 * Create a new object
	 *
	 * @param sfRequest $request A request object
	 */
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ZoneForm();

	  $this->setLayout("layout-settings");
  }

  /**
	 * Executes 'create' function
	 *
	 * Save a new object
	 *
	 * @param sfRequest $request A request object
	 */
  public function executeCreate(sfWebRequest $request)
  {
    //Audit 
    Audit::audit("", "Added new zone");

    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ZoneForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  /**
	 * Executes 'edit' function
	 *
	 * Edit an existing object
	 *
	 * @param sfRequest $request A request object
	 */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($zone = Doctrine_Core::getTable('Zone')->find(array($request->getParameter('id'))), sprintf('Object content does not exist (%s).', $request->getParameter('id')));
    
    $this->form = new ZoneForm($zone);
    $this->setLayout("layout-settings");
  }

  /**
	 * Executes 'update' action
	 *
	 * Update an existing object
	 *
	 * @param sfRequest $request A request object
	 */
  public function executeUpdate(sfWebRequest $request)
  {
    //Audit 
    Audit::audit("", "Updated existing zone");

    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($zone = Doctrine_Core::getTable('Zone')->find(array($request->getParameter('id'))), sprintf('Object content does not exist (%s).', $request->getParameter('id')));

    $this->form = new ZoneForm($zone);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  /**
	 * Executes 'processForm' function
	 *
	 * Validate the form and save the object
	 *
	 * @param sfRequest $request A request object
	 */
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $feecode = $form->save();

      $this->redirect('/backend.php/zone/index');
    }
  }

  /**
	 * Executes 'delete' action
	 *
	 * Delete the object
	 *
	 * @param sfRequest $request A request object
	 */
  public function executeDelete(sfWebRequest $request)
  {
    //Audit 
    Audit::audit("", "Deleted existing zone");

    $this->forward404Unless($zone = Doctrine_Core::getTable('Zone')->find(array($request->getParameter('id'))), sprintf('Object content does not exist (%s).', $request->getParameter('id')));

    $zone->delete();

    $this->redirect('/backend.php/zone/index');
  }
  public function executeUpdatezone(sfWebRequest $request)
  {
		$api=new ApiCalls();
		Doctrine_Core::getTable('ApiContent')->getApiservices('034');
		$this->getResponse()->setHttpHeader('content-type','application/json');
		return $this->renderText(json_encode(array('status' => 'success')));
  }


  public function executeGetdetails(sfWebRequest $request){

  // ->leftJoin('p.Template t')
    $q = Doctrine_Query::create()
         ->from("Map a")
         ->where("a.id = ?", $request->getParameter("info"));
    $saved_permit = $q->fetchOne();

    $htmls = '<center><font style="font-size:30px;" class="fa fa-user"></font> <br><br>'.$saved_permit.'</center><br><br><table class="table text-center"><tr><td><font class="fa fa fa-sort-numeric-asc  
  
"></font> Numéro du dossier<br><br><b>AT-A000'.$request->getParameter("info").'</b></td><td><font class="fa fa-folder-open-o"></font> Type du dossier<br><br><b> Lettre d\'attribution</b></td><td><font class="fa fa-history"></font> Statut<br><br><b style="color:green">Vérification</b></td></tr></table>';

 
    $this->getResponse()->setHttpHeader('content-type','application/json');
    return $this->renderText(json_encode(array('status' => 'success','htmls'=>$htmls)));

  }


}
