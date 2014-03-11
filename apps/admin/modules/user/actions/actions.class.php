<?php

/**
 * user actions.
 *
 * @package    yoca
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
  /**
   * Dashboard
   * @param sfWebRequest $request
   */

	public function preExecute(){
		//Populate the main_nav bar
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->sections = sfConfig::get("app_dashboard_$usertype");
	}
	
  public function executeIndex(sfWebRequest $request)
  {
  	$this->getUser()->setAttribute('cur_page', 'home');
  }

  public function executeList(sfWebRequest $request){
  	$this->forward404Unless($this->type = $request->getParameter('type'));
  	$this->start = $request->getParameter('start');
  	$this->limit = $request->getParameter('limit');

  	$this->total = Doctrine_Core::getTable('YocaUser')
  	->createQuery('a')
  	->addWhere("type = ?", $this->type)
  	->count();

  	$this->pages = ceil($this->total / $this->limit);
  	
  	$this->page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
  			'options' => array(
  					'default'   => 1,
  					'min_range' => 1,
  			),
  	)));
  	
  	$this->users = Doctrine_Core::getTable('YocaUser')
  	->createQuery('a')
  	->addWhere("type = ?", $this->type)
  	->limit($this->limit)
  	->execute();
  	
  	$this->getUser()->setAttribute('cur_page', 'manage_users');
  }
  
  public function executeShow(sfWebRequest $request)
  {
  	$this->type = $request->getParameter('type');
  	
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
  	$this->forward404Unless($this->yoca_user);
  }
  
  public function executeActivate(sfWebRequest $request){
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
  	$this->forward404Unless($this->yoca_user);
  	
  	$this->yoca_user->set('is_active', 1);
  	$this->yoca_user->save();
  	
  	//Send confirmation email to mentor
  	$body = "Mentor confirmed for {$this->yoca_user->getUsername()}";
  	$mailer = sfContext::getInstance()->getMailer();
  	$mailer->composeAndSend(sfConfig::get('app_mail_service'), $this->yoca_user->getUsername(), 'Event Confirmed', $body);
  	
  	$this->redirect('user/list');
  }

  /**
   * Signup form
   * @param sfWebRequest $request
   */
  public function executeNew(sfWebRequest $request)
  {
  	$user = $this->getUser();
  	if ($user->isAuthenticated()) {
  		return $this->redirect('@homepage');
  	}
    $this->form = new YocaUserForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new YocaUserForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }
  
  public function executeConfirm(sfWebRequest $request){
  	
  }
  
  /**
   * Password reset
   * @param sfWebRequest $request
   */
  public function executeResetPass(sfWebRequest $request){
  	
  }
  

  /**
   * Edit user profile
   * @param sfWebRequest $request
   */
  public function executeEdit(sfWebRequest $request)
  {
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	if(!is_null($yoca_user->get('industry_id'))){
  		$industryIds = explode(',', $yoca_user->get('industry_id'));
  		$yoca_user->set('industry_id', $industryIds);
  	}
  	if(!is_null($yoca_user->get('expectation_id'))){
  		$expectationIds = explode(',', $yoca_user->get('expectation_id'));
  		$yoca_user->set('expectation_id', $expectationIds);
  	}
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => $this->getUser()->getAttribute('usertype')));
  	
  	$this->getUser()->setAttribute('cur_page', 'my_profile');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
	$this->form = new YocaUserForm($yoca_user, array('usertype' => $this->getUser()->getAttribute('usertype')));
    
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }
  
  /**
   * Become a mentee signup form
   * @param sfWebRequest $request
   */
  public function executeBecomeMentee(sfWebRequest $request){
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentee'));
  	
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  }
  
  public function executeUpdateMentee(sfWebRequest $request){
  	$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
    $this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentee'));
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }
  
  public function executeMenteeConfirm(sfWebRequest $request){
  	
  }
  
  /**
   * Become a mentor signup form
   * @param sfWebRequest $request
   */
  public function executeBecomeMentor(sfWebRequest $request){
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentor'));
  	
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  }
  
  public function executeUpdateMentor(sfWebRequest $request){
  	$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentor'));
  	$this->processForm($request, $this->form);
  	$this->setTemplate('edit');
  }
  
  public function executeMentorConfirm(sfWebRequest $request){
  	 
  }
  

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $yoca_user = $form->save();
	  
      if($form->isNew()){
      	$this->redirect('user/confirm');
      }
      
      $usertype = $form->getOption('usertype');
      if(!is_null($usertype)){
      	switch($usertype){
      		case 'becomeMentee':
      			$this->redirect('user/menteeConfirm');
      			break;
      		case 'becomeMentor';
      			$this->redirect('user/mentorConfirm');
      			break;
      		case 'Mentee':
      		case 'Mentor':
      		case 'Admin':
      			$this->redirect('user/edit');
      			break;
      		
      			
      	}
      }
    }
  }
}