<?php

/**
 * event actions.
 *
 * @package    yoca
 * @subpackage event
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventActions extends sfActions
{
	public function preExecute(){
		//Populate the main_nav bar
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->sections = sfConfig::get("app_dashboard_$usertype");
	}
	
  public function executeIndex(sfWebRequest $request)
  {
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	if(!$yoca_user->get('is_active')){
  		$this->redirect('event/pending');
  	}
  	if($this->getUser()->getAttribute('usertype') == 'Admin'){
	  	$this->getUser()->setAttribute('cur_page', 'manage_events');
  	}else{
	  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  	}
  }
  
  public function executePending(sfWebRequest $request){
  	
  }
  
  public function executeList(sfWebRequest $request){
  	$this->forward404Unless($type = $request->getParameter('type'));
  	
  	if($type == 'pending'){
  		$this->events = Doctrine_Core::getTable('Event')
  		->createQuery('a')
  		->where('status = ?', 0)
  		->execute();
  	}else{
	  	if($type == 'upcoming')
	  		$cond = ">";
	  	if($type == 'past')
	  		$cond = "<";
	  	
		$this->events = Doctrine_Core::getTable('Event')
	  	      ->createQuery('a')
	  	      ->where("datetime $cond ?", date('Y-m-d H:i:s'))
	  	      ->andWhere('status = ?', 1)
	  	      ->execute();
  	}
  	
  	if($this->getUser()->getAttribute('usertype') == 'Admin'){
  		$this->getUser()->setAttribute('cur_page', 'manage_events');
  	}else{
  		$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  	}
  }
  
  public function executeActivate(sfWebRequest $request){
  	$this->event = Doctrine_Core::getTable('Event')->find(array($request->getParameter('id')));
  	$mentor = Doctrine_Core::getTable('YocaUser')->findOneBy('id', $this->event->get('mentorid'));
  	$this->forward404Unless($this->event && $mentor);
  	 
  	$this->event->set('status', 1);
  	$this->event->save();
  	
  	//Send confirmation email to mentor
  	$body = "Event confirmed for Event ID {$request->getParameter('id')}";
  	$mailer = sfContext::getInstance()->getMailer();
  	$mailer->composeAndSend(sfConfig::get('app_mail_service'), $mentor->getUsername(), 'Event Confirmed', $body);
  	
  	$this->redirect('event/list?type=pending');
  }
  
  
  public function executeMentorMyEvents(sfWebRequest $request){
  	$this->events = Doctrine_Core::getTable('Event')->findMentorEvents($this->getUser()->getAttribute('userid'));
  	$this->mentor = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
  	
  	$this->form = new EventForm(array(), array('industry'=> $this->mentor->get('industry_id'), 'neighborhood' => $this->mentor->get('neighborhood')));
  }
  
  public function executeMenteeMyEvents(sfWebRequest $request){
  	$this->events = Doctrine_Core::getTable('Event')->findMenteeEvents($this->getUser()->getAttribute('userid'));
  }
  

  public function executeShow(sfWebRequest $request)
  {
    $this->event = Doctrine_Core::getTable('Event')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->event);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EventForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->events = Doctrine_Core::getTable('Event')->findMentorEvents($this->getUser()->getAttribute('userid'));
    $this->mentor = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
    
    $this->form = new EventForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('mentorMyEvents');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($event = Doctrine_Core::getTable('Event')->find(array($request->getParameter('id'))), sprintf('Object event does not exist (%s).', $request->getParameter('id')));
    $this->form = new EventForm($event);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($event = Doctrine_Core::getTable('Event')->find(array($request->getParameter('id'))), sprintf('Object event does not exist (%s).', $request->getParameter('id')));
    
    $this->events = Doctrine_Core::getTable('Event')->findMentorEvents($this->getUser()->getAttribute('userid'));
    $this->mentor = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
    
    $this->form = new EventForm($event);
    $this->processForm($request, $this->form);
    $this->setTemplate('mentorMyEvents');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($event = Doctrine_Core::getTable('Event')->find(array($request->getParameter('id'))), sprintf('Object event does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($event->get('statue')===0);
    $event->delete();

    $this->redirect('@mentor_manage_event');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $event = $form->save();

      if($form->isNew())
      	$this->redirect('@mentor_manage_event');
      else
      	$this->redirect($this->generateUrl('default', array('module' => 'event', 'action' => 'show', 'id' => $request->getParameter('id'))));
    }
  }
}
