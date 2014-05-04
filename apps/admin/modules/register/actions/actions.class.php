<?php

/**
 * register actions.
 *
 * @package    yoca
 * @subpackage register
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class registerActions extends sfActions
{
	public function executeRegister(sfWebRequest $request){
		$type = $request->getParameter('type');
	  	$page = $request->getParameter('page');
	  	$keyword = $request->getParameter('keyword');
	  	
	  	$clientIp = $request->getRemoteAddress();
	  	
	  	//check if current userregcounter is smaller than cap
	  	$this->getLogger()->log("Event Register[$clientIp]: === START EVENT REGISTER ===", sfLogger::DEBUG);
	  	$counter = $this->getUser()->getAttribute('userregcounter');
	  	$this->getLogger()->log("Event Register[$clientIp]: current userregcounter is $counter", sfLogger::DEBUG);
	  	$this->forward404If($counter >= sfConfig::get('app_const_reg_cap'));
	  	
		//check if event full
		$event = Doctrine_Core::getTable('Event')->find($request->getParameter('eventId'));
		$this->getLogger()->log("Event Register[$clientIp]: event id is {$event->getId()}", sfLogger::DEBUG);
		$this->getLogger()->log("Event Register[$clientIp]: current event capacity is {$event->getBooked()}/{$event->getCapacity()}", sfLogger::DEBUG);
		$this->forward404Unless($event && $event->getCapacity()>$event->getBooked());
		
		//insert registration record
		$reg = new Registration();
		$reg->setEventId($event->getId());
		$reg->setMenteeId($this->getUser()->getAttribute('userid'));
		$reg->setStatus(1);
		$reg->setCreatedAt(date("Y-m-d H:i:s"));
		$reg->save();
		$this->getLogger()->log("Event Register[$clientIp]: reg created with id {$reg->getId()}", sfLogger::DEBUG);
		
		//update booked for this event
		$event->setBooked($event->getBooked()+1);
		$event->save();
		$this->getLogger()->log("Event Register[$clientIp]: new event capacity is {$event->getBooked()}/{$event->getCapacity()}", sfLogger::DEBUG);
		
		//bump up userregcounter
	  	$this->getUser()->setAttribute('userregcounter', $counter+1);
	  	$this->getLogger()->log("Event Register[$clientIp]: new userregcounter is {$this->getUser()->getAttribute('userregcounter')}", sfLogger::DEBUG);
	  	
		//prepare email
		$mentee = $reg->getYocaUser();
		$mentor = $event->getYocaUser();
		
		$body = "Mentee: {$mentee->getLastName()}, {$mentee->getFirstName()}\n";
		$body .= "Mentor ID: {$mentor->getMentorId()}\n";
		$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
		$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
		$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
		$body .= "Event Capacity: ".$event->getCapacity()."\n";
		$body .= "Booked Up Till Now: ".$event->getBooked()."\n";
		$body .= "Event Time: ".$event->getDatetime()."\n";
		$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
		$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
		
		$survey = "Follow-up survey:\n";
		$survey .= "Please fill out this survey within 24 hours following the completion of the event. All of your responses will be kept strictly anonymous for our internal reviews. ";
		$survey .= "https://docs.google.com/forms/d/15ogqNH2-1KLwiSOtd7M56j4FeisR9kiMUFL2DNfqGv0/viewform\n\n";
		
		$footer = "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
		$footer .= "Yours,\n";
		$footer .= "YOCA Team";
		
		//send email confirmation to mentee
		$message = sfContext::getInstance()->getMailer()
			->compose(sfConfig::get('app_email_service'), $mentee->getUsername(), "YOCA Office Hour Registration Confirmed", $body.$survey.$footer)
			->setBcc(sfConfig::get('app_email_dev'));
		$ret = sfContext::getInstance()->getMailer()->send($message);
		if($ret){
			$this->getLogger()->log("Event Register[$clientIp]: sent email to mentee {$mentee->getUsername()}", sfLogger::DEBUG);
		}else{
			$this->getLogger()->log("Event Register[$clientIp]: failed sending email to mentee {$mentee->getUsername()}", sfLogger::DEBUG);
		} 
		
		//send email confirmation to mentor
		$message = sfContext::getInstance()->getMailer()
			->compose(sfConfig::get('app_email_service'), $mentor->getUsername(), "Mentee Registered For Your Upcoming Office Hour", $body.$footer)
			->setBcc(sfConfig::get('app_email_dev'));
		$ret = sfContext::getInstance()->getMailer()->send($message);
		if($ret){
			$this->getLogger()->log("Event Register[$clientIp]: sent email to mentor {$mentor->getUsername()}", sfLogger::DEBUG);
		}else{
			$this->getLogger()->log("Event Register[$clientIp]: failed sending email to mentor {$mentor->getUsername()}", sfLogger::DEBUG);
		}
		$this->getLogger()->log("Event Register[$clientIp]: === END EVENT REGISTER ===", sfLogger::DEBUG);
		
	  	$this->getUser()->setFlash('register', 'Registration successful.');
	  	$this->redirect("event/list?type=$type&page=$page&keyword=$keyword");
	}
	
	/**
	 * Cancel registration
	 * @param sfWebRequest $request
	 */
  public function executeCancel(sfWebRequest $request){
  		$eventId = $request->getParameter('eventId');
  		$type = $request->getParameter('type');
  		$page = $request->getParameter('page');
  		$keyword = $request->getParameter('keyword');
  		
  		$clientIp = $request->getRemoteAddress();
  		
  		$event = Doctrine_Core::getTable('Event')->find($eventId);
  		$this->forward404Unless($event); //event not found
  		$this->getLogger()->log("Event Cancel[$clientIp]: === START EVENT CANCEL ===", sfLogger::DEBUG);
  		$this->getLogger()->log("Event Cancel[$clientIp]: event id is {$event->getId()}", sfLogger::DEBUG);
  		
  		//get regId from eventId and userid, mark registration status to 2
  		$reg = Doctrine_Core::getTable('Registration')->getMenteeEventRegs($eventId, $this->getUser()->getAttribute('userid'), 1);
  		$this->forward404Unless($reg); //reg not found
  		$this->getLogger()->log("Event Cancel[$clientIp]: reg id is {$reg[0]['id']}", sfLogger::DEBUG);
  		Doctrine_Core::getTable('Registration')->setRegStatus($reg[0]['id'], 2);
  		$this->getLogger()->log("Event Cancel[$clientIp]: set reg status to 2", sfLogger::DEBUG);
  		
  		//mark booked to booked-1 for event
		$this->getLogger()->log("Event Cancel[$clientIp]: current event booked is {$event->getBooked()}", sfLogger::DEBUG);
		$event->setBooked($event->getBooked()-1);
		$event->save();
		$this->getLogger()->log("Event Cancel[$clientIp]: new event booked is {$event->getBooked()}", sfLogger::DEBUG);
	  		
  		$mentee = Doctrine_Core::getTable('YocaUser')->find($reg[0]['mentee_id']);
		$mentor = $event->getYocaUser();

	  	//prepare email	
		$body = "Mentee: {$mentee->getLastName()}, {$mentee->getFirstName()}\n";
		$body .= "Mentor ID: {$mentor->getMentorId()}\n";
		$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
		$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
		$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
		$body .= "Event Capacity: ".$event->getCapacity()."\n";
		$body .= "Booked Up Till Now: ".$event->getBooked()."\n";
		$body .= "Event Time: ".$event->getDatetime()."\n";
		$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
		$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
  		$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
  		$body .= "Yours,\n";
  		$body .= "YOCA Team";
  		
  		//send confirmation email to mentee
  		$message = $this->getMailer()
  			->compose(sfConfig::get('app_email_service'), $mentee->getUsername(), "YOCA Office Hour Registration Cancelled", $body)
  			->setBcc(sfConfig::get('app_email_dev'));
  		$ret = $this->getMailer()->send($message);
  		if($ret){
  			$this->getLogger()->log("Event Cancel[$clientIp]: sent email to mentee {$mentee->getUsername()}", sfLogger::DEBUG);
  		}else{
  			$this->getLogger()->log("Event Cancel[$clientIp]: failed sending email to mentee {$mentee->getUsername()}", sfLogger::DEBUG);
  		}
  		
  		//send confirmation email to mentor
  		$message = $this->getMailer()
  			->compose(sfConfig::get('app_email_service'), $mentor->getUsername(), "Mentee Cancelled Your Upcoming Office Hour", $body)
  			->setBcc(sfConfig::get('app_email_dev'));
  		$ret = $this->getMailer()->send($message);
  		if($ret){
  			$this->getLogger()->log("Event Cancel[$clientIp]: sent email to mentor {$mentor->getUsername()}", sfLogger::DEBUG);
  		}else{
  			$this->getLogger()->log("Event Cancel[$clientIp]: failed sending email to mentor {$mentor->getUsername()}", sfLogger::DEBUG);
  		}
	  		
  		//notify signedup users
  		Doctrine_Core::getTable('EventNotify')->notify($eventId);
	  		
  		//mark current userregcounter to -1
  		$counter = $this->getUser()->getAttribute('userregcounter');
  		$this->getLogger()->log("Event Cancel[$clientIp]: current userregcounter is $counter", sfLogger::DEBUG);
  		if($counter > 0){
  			$this->getUser()->setAttribute('userregcounter', $counter-1);
  			$this->getLogger()->log("Event Cancel[$clientIp]: new userregcounter is {$this->getUser()->getAttribute('userregcounter')}", sfLogger::DEBUG);
  		}
  		$this->getLogger()->log("Event Cancel[$clientIp]: === END EVENT CANCEL ===", sfLogger::DEBUG);
  		
  		$this->getUser()->setFlash('cancel', 'Cancel successful.');
  		if($type=='my' && $this->getUser()->getAttribute('usertype')=='Mentee'){
  			$this->redirect('@mentee_manage_event');
  		}elseif($type=='my' && $this->getUser()->getAttribute('usertype')=='Mentor'){
  			$this->redirect('@mentor_manage_event');
  		}else{
  			$this->redirect("event/list?type=$type&page=$page&keyword=$keyword");
  		}
  }
	
	
  public function executeIndex(sfWebRequest $request)
  {
    $this->registrations = Doctrine_Core::getTable('Registration')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->registration = Doctrine_Core::getTable('Registration')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->registration);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new RegistrationForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new RegistrationForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($registration = Doctrine_Core::getTable('Registration')->find(array($request->getParameter('id'))), sprintf('Object registration does not exist (%s).', $request->getParameter('id')));
    $this->form = new RegistrationForm($registration);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($registration = Doctrine_Core::getTable('Registration')->find(array($request->getParameter('id'))), sprintf('Object registration does not exist (%s).', $request->getParameter('id')));
    $this->form = new RegistrationForm($registration);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($registration = Doctrine_Core::getTable('Registration')->find(array($request->getParameter('id'))), sprintf('Object registration does not exist (%s).', $request->getParameter('id')));
    $registration->delete();

    $this->redirect('register/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $registration = $form->save();

      $this->redirect('register/edit?id='.$registration->getId());
    }
  }
}
