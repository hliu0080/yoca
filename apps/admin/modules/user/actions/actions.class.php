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
	
	/**
	 * Index page of Manage Users
	 * @param sfWebRequest $request
	 */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->getUser()->setAttribute('cur_page', 'home');
  }

  /**
   * List users 
   * @param sfWebRequest $request
   */
  public function executeList(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$limit = sfConfig::get('app_records_num');
  	$this->type = $request->getParameter('type');
  	$this->keyword = $request->getParameter('keyword');
  	$this->page = $request->getParameter('page');
  	
  	$start = ($this->page - 1) * $limit;
  	
  	$query = Doctrine_Core::getTable('YocaUser')
  	->createQuery('a')
	->where("type = ?", $this->type);
  	if($this->keyword){
  		$query->addWhere('username like ? or id like ? or firstname like ? or lastname like ?', array('%'.$this->keyword.'%', '%'.$this->keyword.'%', '%'.$this->keyword.'%', '%'.$this->keyword.'%'));
  	}
  	
  	$this->total = $query->count();
  	
  	$this->pages = ceil($this->total / $limit);
  	$this->forward404if($this->total && $this->page>$this->pages);
  	
  	$this->users = $query->limit($limit)->offset($start)->execute();
  	
  	$this->getUser()->setAttribute('cur_page', 'manage_users');
  }
  
  /**
   * Search users by ID, username or name
   * @param sfWebRequest $request
   */
  public function executeSearch(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$limit = sfConfig::get('app_records_num');
	$this->type = $request->getPostParameter('type');
	$this->keyword = $request->getPostParameter('keyword');
  	$this->page = 1;
  	
  	$query = Doctrine_Core::getTable('YocaUser')
  	->createQuery('a')
  	->where("type = ?", $this->type);
  	if($this->keyword){
  		$query->addWhere('username like ? or id like ? or firstname like ? or lastname like ?', array('%'.$this->keyword.'%', '%'.$this->keyword.'%', '%'.$this->keyword.'%', '%'.$this->keyword.'%'));
  	}
  	 
  	$this->total = $query->count();
  	 
  	$this->pages = ceil($this->total / $limit);
  	$this->forward404if($this->total && $this->page>$this->pages);
  	 
  	$this->users = $query->limit($limit)->execute();
  	 
  	$this->getUser()->setAttribute('cur_page', 'manage_users');
  	$this->setTemplate('list');
  }
  
  /**
   * Show user details
   * @param sfWebRequest $request
   */
  public function executeShow(sfWebRequest $request)
  {
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$this->type = $request->getParameter('type');
  	$this->page = $request->getParameter('page');
  	$this->keyword = $request->getParameter('keyword');
  	
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
  	$this->forward404Unless($this->yoca_user);
  }
  
  /**
   * Admin activate mentor
   * @param sfWebRequest $request
   */
  public function executeActivate(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
  	$this->forward404Unless($this->yoca_user);
  	
  	$this->yoca_user->set('is_active', 1);
  	$this->yoca_user->save();
  	
  	//Send confirmation email to mentor
  	$body = "Mentor confirmed for {$this->yoca_user->getUsername()}";
  	$mailer = sfContext::getInstance()->getMailer();
  	$mailer->composeAndSend(sfConfig::get('app_mail_service'), $this->yoca_user->getUsername(), 'Mentor Confirmed', $body);
  	
  	$type = $request->getParameter('type');
  	$page = $request->getParameter('page');
  	$keyword = $request->getParameter('keyword');
  	$this->redirect("user/list?type=$type&page=$page&keyword=$keyword");
  }
  
  /**
   * Admin activate mentor
   * @param sfWebRequest $request
   */
  public function executeDeactivate(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
  	$this->forward404Unless($this->yoca_user);
  	 
  	$this->yoca_user->set('is_active', 0);
  	$this->yoca_user->save();
  	 
  	//Send confirmation email to mentor
//   	$body = "Event confirmed for {$this->yoca_user->getUsername()}";
//   	$mailer = sfContext::getInstance()->getMailer();
//   	$mailer->composeAndSend(sfConfig::get('app_mail_service'), $this->yoca_user->getUsername(), 'Event Confirmed', $body);
  	
  	$type = $request->getParameter('type');
  	$page = $request->getParameter('page');
  	$keyword = $request->getParameter('keyword');
  	$this->redirect("user/list?type=$type&page=$page&keyword=$keyword");
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
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Member');
  	
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentee'));
  	
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  }
  
  public function executeUpdateMentee(sfWebRequest $request){
  	$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Member');
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
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Member');
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentor'));
  	
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
  }
  
  public function executeUpdateMentor(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Member');
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
