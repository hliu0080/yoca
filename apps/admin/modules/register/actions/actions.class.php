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
	  	
	  	//check and bump up current userregcounter
	  	$counter = $this->getUser()->getAttribute('userregcounter');
	  	$this->forward404If($counter >= sfConfig::get('app_const_reg_cap'));
	  	$this->getUser()->setAttribute('userregcounter', $counter+1);
	  	
		//check if event full
		$event = Doctrine_Core::getTable('Event')->find($request->getParameter('eventId'));
		$this->forward404Unless($event && $event->getCapacity()>$event->getBooked());
		
		//insert registration record
		$reg = new Registration();
		$reg->setEventId($request->getParameter('eventId'));
		$reg->setMenteeId($this->getUser()->getAttribute('userid'));
		$reg->setStatus(1);
		$reg->setCreatedAt(date("Y-m-d H:i:s"));
		$reg->save();
		
		//update booked for this event
		$event->setBooked($event->getBooked()+1);
		$event->save();
		
		$mentee = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
		$mentor = Doctrine_Core::getTable('YocaUser')->find($event->getMentorId());
		
		//send email confirmation to mentee
		$mailer = sfContext::getInstance()->getMailer();
		
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
		
		$body .= "Follow-up survey:\n";
		$body .= "Please fill out this survey within 24 hours following the completion of the event. All of your responses will be kept strictly anonymous for our internal reviews.\n";
		$body .= "https://docs.google.com/forms/d/15ogqNH2-1KLwiSOtd7M56j4FeisR9kiMUFL2DNfqGv0/viewform​\n\n";
		
		$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
		$body .= "Yours,\n";
		$body .= "YOCA Team";
		$mailer->composeAndSend(sfConfig::get('app_email_service'), $mentee->getUsername(), "YOCA Office Hour Registration Confirmed", $body);
		
		//send email confirmation to mentor
// 		$body = "Mentee: {$mentee->getLastName()}, {$mentee->getFirstName()}\n";
// 		$body .= "School Attended/Attending: ".($mentee->getSchoolId()==25?$mentee->getSchool():Doctrine_Core::getTable('YocaUserSchool')->find($mentee->getSchoolId()))."\n";
// 		$body .= "Major: ".($mentee->getMajorId()==19?$mentee->getMajor():Doctrine_Core::getTable('YocaUserMajor')->find($mentee->getMajorId()))."\n";
// 		$body .= "Degree: {$mentee->getEducation()}\n";
// 		$workExp = sfConfig::get('app_profile_mentee_work_experience');
// 		$body .= "Work Experience: {$workExp[$yoca_user->getWork()]}\n";
// 		$body .= "Employer: {$mentee->getEmployer()}\n";
		
// 		$body .= "Mentor ID: {$mentor->getMentorId()}\n";
// 		$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
// 		$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
// 		$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
// 		$body .= "Event Capacity: ".$event->getCapacity()."\n";
// 		$body .= "Booked Up Till Now: ".$event->getBooked()."\n";
// 		$body .= "Event Time: ".$event->getDatetime()."\n";
// 		$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
// 		$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
// 		$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
// 		$body .= "Yours,\n";
// 		$body .= "YOCA Team";
		$mailer->composeAndSend(sfConfig::get('app_email_service'), $mentor->getUsername(), "Mentee Registered For Your Upcoming Office Hour", $body);
		
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
  		
  		$event = Doctrine_Core::getTable('Event')->find($eventId);
  		
  		if($event){
	  		//mark booked to booked-1 for event
			$event->setBooked($event->getBooked()-1);
			$event->save();
			
	  		//get regId from eventId and userid, mark registration status to 2
	  		$reg = Doctrine_Core::getTable('Registration')->getMenteeRegs($eventId, $this->getUser()->getAttribute('userid'), 1);
	  		$regIdArray = array();
	  		foreach($reg as $r){
	  			$regIdArray[] = $r['id'];
	   		}
	  		Doctrine_Core::getTable('Registration')->setRegStatus($regIdArray, 2);
	  		
	  		$mentee = Doctrine_Core::getTable('YocaUser')->find($reg[0]['mentee_id']);
			$mentor = Doctrine_Core::getTable('YocaUser')->find($event->getMentorId());

	  		//send comfirmation emails to mentee and mentor
	  		$mailer = sfContext::getInstance()->getMailer();
	  		
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
	  		$mailer->composeAndSend(sfConfig::get('app_email_service'), $mentee->getUsername(), "YOCA Office Hour Registration Cancelled", $body);
	  		
// 	  		$body = "Mentee: {$mentee->getLastName()}, {$mentee->getFirstName()}\n";
// 			$body .= "Mentor ID: {$mentor->getMentorId()}\n";
// 			$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
// 			$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
// 			$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
// 			$body .= "Event Capacity: ".$event->getCapacity()."\n";
// 			$body .= "Booked Up Till Now: ".$event->getBooked()."\n";
// 			$body .= "Event Time: ".$event->getDatetime()."\n";
// 			$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
// 			$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
// 	  		$body .= "Mentee Detail: {$mentee->getFirstName()} {$mentee->getLastName()}, {$mentee->getEducation()}, {$mentee->getSchool()}, {$mentee->getMajor()}, {$mentee->getWork()}, {$mentee->getEmployer()}, {$mentee->getExpectation()}\n\n";
// 	  		$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
// 	  		$body .= "Yours,\n";
// 	  		$body .= "YOCA Team";
	  		$mailer->composeAndSend(sfConfig::get('app_email_service'), $mentor->getUsername(), "Mentee Cancelled Your Upcoming Office Hour", $body);
	  		
	  		//notify signedup users
	  		Doctrine_Core::getTable('EventNotify')->notify($eventId);
	  		
	  		//mark current userregcounter to -1
	  		$counter = $this->getUser()->getAttribute('userregcounter');
	  		if($counter)
	  			$this->getUser()->setAttribute('userregcounter', $counter-1);
	  		
  		}else{
  			//TODO: error handling - event not found 
  		}
  		
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
