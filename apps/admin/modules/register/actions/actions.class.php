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
		//check if event full
		$event = Doctrine_Core::getTable('Event')->find($request->getParameter('eventId'));
		$this->forward404Unless($event && $event->getCapacity()!=$event->getBooked());
		
		//insert registration record
		$reg = new Registration();
		$reg->setEventId($request->getParameter('eventId'));
		$reg->setMenteeId($this->getUser()->getAttribute('userid'));
		$reg->setStatus(1);
		$reg->save();
		
		$mentee = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
		$mentor = Doctrine_Core::getTable('YocaUser')->find($event->getMentorId());
		
		//send email confirmation to mentee
		$body = "Event ID: {$request->getParameter('eventId')}\n";
		$body .= "Username: {$mentee->getUsername()}\n";
		$body .= "Event Detail: {$event->getIndustry()}, {$event->getMentorId()}, {$event->getCapacity()}, {$event->getBooked()}, {$event->getDatetime()}, {$event->getNeighborhood()}, {$event->getAddress()}\n";
		$mailer = sfContext::getInstance()->getMailer();
		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $mentee->getUsername(), "Office Hour Registration Confirmed", $body);
		
		//send email confirmation to mentor
		$body = "Event ID: {$request->getParameter('eventId')}\n";
		$body .= "Username: {$mentor->getUsername()}\n";
		$body .= "Event Detail: {$event->getIndustry()}, {$event->getMentorId()}, {$event->getCapacity()}, {$event->getBooked()}, {$event->getDatetime()}, {$event->getNeighborhood()}, {$event->getAddress()}\n";
		$body .= "Mentee Detail: {$mentee->getFirstName()} {$mentee->getLastName()}, {$mentee->getEducation()}, {$mentee->getSchool()}, {$mentee->getMajor()}, {$mentee->getWork()}, {$mentee->getEmployer()}, {$mentee->getExpectation()}\n";
		$mailer = sfContext::getInstance()->getMailer();
		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $mentor->getUsername(), "Mentee registered for your upcoming office hour", $body);
		
		//udpate booked for this event
		$event->setBooked($event->getBooked()+1);
		$event->save();
		
		$this->redirect('event/list?type=upcoming');
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
