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
  
  /**
   * List events
   * @param sfWebRequest $request
   */
  public function executeList(sfWebRequest $request){
  	$this->type = $request->getParameter('type');
  	$this->keyword = $request->getParameter('keyword');
  	$this->page = $request->getParameter('page');
  	$this->start = ($this->page - 1) * sfConfig::get('app_records_num');
  	
  	$this->fetchEvents();
  	
  	if($this->getUser()->getAttribute('usertype') == 'Admin'){
  		$this->getUser()->setAttribute('cur_page', 'manage_events');
  	}else{
  		$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  	}
  }
  
  /**
   * Search event on industry, mentorID, neighborhood, address
   * @param sfWebRequest $request
   */
  public function executeSearch(sfWebRequest $request){
  	$this->type = $request->getParameter('type');
  	$this->keyword = $request->getParameter('keyword');
  	$this->page = 1;
  	$this->start = 0;
  	
  	$this->fetchEvents();
  	 
  	if($this->getUser()->getAttribute('usertype') == 'Admin'){
  		$this->getUser()->setAttribute('cur_page', 'manage_events');
  	}else{
  		$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  	}
  	
  	$this->setTemplate('list');
  }
  
  protected function fetchEvents(){
  	if($this->type == 'pending'){
  		$status = array(0);
  		$cond = ">";
  	}elseif($this->type == 'upcoming'){
  		$status = array(1, 2);
  		$cond = ">";
  	}elseif($this->type == 'past'){
  		$status = array(1, 2);
  		$cond = "<";
  	}else{
  		$this->forward404();
  	}
  	 
  	$query = Doctrine_Core::getTable('Event')
  	->createQuery('a')
  	->whereIn('status', $status)
  	->andWhere("datetime $cond ?", date('Y-m-d H:i:s'));
  	if($this->keyword){
  		$query->addWhere('industry like ? or mentorid like ? or neighborhood like ? or address like ?', array('%'.$this->keyword.'%', '%'.$this->keyword.'%', '%'.$this->keyword.'%', '%'.$this->keyword.'%'));
  	}
  	$this->total = $query->count();
  	$this->pages = ceil($this->total / sfConfig::get('app_records_num'));
  	$this->forward404if($this->total && $this->page>$this->pages);
  	 
  	$this->events = $query->limit(sfConfig::get('app_records_num'))->offset($this->start)->execute();
  }
  
  /**
   * Cancel($status - 2), delete($status - 3) or confirm($status - 1) event
   * @param sfWebRequest $request
   */
  public function executeSetStatus(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$status = $request->getParameter('status');
  	$id = $request->getParameter('id');
  	$this->forward404If(is_null($status) || is_null($id));
  	
  	$this->event = Doctrine_Core::getTable('Event')->find(array($id));
  	$mentor = Doctrine_Core::getTable('YocaUser')->findOneBy('id', $this->event->get('mentorid'));
  	$this->forward404Unless($mentor);
  	 
  	//Confirm event
  	if($status == 1){
	  	//Send confirmation email to mentor
	  	$body = "Event confirmed for Event ID $id";
	  	$mailer = sfContext::getInstance()->getMailer();
	  	$mailer->composeAndSend(sfConfig::get('app_mail_service'), $mentor->getUsername(), 'Event Confirmed', $body);
  	}
  	
  	//Cancel event
  	if($status == 2){
  		//Get all registrations for this event
  		$regs = Doctrine_Core::getTable('Registration')->getRegsByIdAndStatus($id, 1);
  		
  		$eventIdArray = $usernameArray = array();
  		foreach($regs as $reg){
  			$eventIdArray[] = $reg['id'];

	  		$username = Doctrine_Core::getTable('YocaUser')->getUsernameById($reg['mentee_id']);
	  		$usernameArray[] = $username;
  		}
  		
  		//Send email to registered mentees, and mentor
  		$body = "Event cancelled for Event ID $id";
  		$mailer = sfContext::getInstance()->getMailer();
  		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $usernameArray, 'Event Cancelled', $body);
		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $mentor->getUsername(), 'Event Cancelled', $body);

  		//Set registration status to 3 - cancelled by system
  		Doctrine_Core::getTable('Registration')->setRegStatus($eventIdArray, 3);
  		
  		//TODO: set mentee register counter - 1
  		
  		//TODO: remove event notify record
  	}
  	
  	//Delete event
  	if($status == 3){
  		//Send cancellation email to mentor
  		$body = "Event cancelled for Event ID $id";
  		$mailer = sfContext::getInstance()->getMailer();
  		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $mentor->getUsername(), 'Event Cancelled', $body);
  	}

  	$this->event->set('status', $status);
  	$this->event->save();
  	
  	//TODO: add redirect to showSuccess
  	$type = $request->getParameter('type');
  	$page = $request->getParameter('page');
  	$keyword = $request->getParameter('keyword');
  	$this->redirect("event/list?type=$type&page=$page&keyword=$keyword");
  }
  
  public function executeMentorMyEvents(sfWebRequest $request){
  	$this->events = Doctrine_Core::getTable('Event')->findMentorEvents($this->getUser()->getAttribute('userid'));
  	$this->mentor = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
  	
  	$this->form = new EventForm(array(), array('industry'=> $this->mentor->get('industry_id'), 'neighborhood' => $this->mentor->get('neighborhood')));
  	
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  }
  
  public function executeMenteeMyEvents(sfWebRequest $request){
  	$this->events = Doctrine_Core::getTable('Event')->findMenteeEvents($this->getUser()->getAttribute('userid'));
  	
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  }
  

  public function executeShow(sfWebRequest $request)
  {
  	$this->type = $request->getParameter('type');
  	$this->page = $request->getParameter('page');
  	$this->keyword = $request->getParameter('keyword');
  	
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
  	$this->type = $request->getParameter('type');
  	$this->page = $request->getParameter('page');
  	$this->keyword = $request->getParameter('keyword');
  	
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
  
  /**
   * Sign up for event notification
   * @param sfWebRequest $request
   */
  public function executeSignUpNotify(sfWebRequest $request){
  	$eventId = $request->getParameter('eventId');
  	$type = $request->getParameter('type');
  	$page = $request->getParameter('page');
  	$keyword = $request->getParameter('keyword');
  	
  	if(!Doctrine_Core::getTable('EventNotify')->isSignedUp($eventId, $this->getUser()->getAttribute('userid'))){
	  	$notify = new EventNotify();
	  	$notify->setEventId($eventId);
	  	$notify->setMenteeId($this->getUser()->getAttribute('userid'));
	  	$notify->setMenteeUsername($this->getUser()->getAttribute('username'));
	  	$notify->setStatus('signedup');
	  	$notify->setCreatedAt(date("Y-m-d H:i:s"));
	  	$notify->save();
  	}
  	
  	$this->getUser()->setFlash('notify', 'Sign up successful. We will send an email to ' . $this->getUser()->getAttribute('username') . ' once this event becomes available again.');
  	$this->redirect("event/list?type=$type&page=$page&keyword=$keyword");
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
