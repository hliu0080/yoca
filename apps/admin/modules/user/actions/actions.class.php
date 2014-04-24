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
  	
  	$this->type = $request->getParameter('type');
  	$this->keyword = $request->getParameter('keyword');
  	$this->page = $request->getParameter('page');
  	$this->start = ($this->page - 1) * sfConfig::get('app_const_record_num');
  	
  	$this->fetchUsers();
  	
  	$this->getUser()->setAttribute('cur_page', 'manage_users');
  }
  
  /**
   * Search users by ID, username or name
   * @param sfWebRequest $request
   */
  public function executeSearch(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
	$this->type = $request->getPostParameter('type');
	$this->keyword = $request->getPostParameter('keyword');
  	$this->page = 1;
  	$this->start = 0;
  	
	$this->fetchUsers();
  	
  	$this->getUser()->setAttribute('cur_page', 'manage_users');
  	$this->setTemplate('list');
  }
  
  protected function fetchUsers(){
  	$query = Doctrine_Core::getTable('YocaUser')
  	->createQuery('a')
  	->where("type = ?", $this->type);
  	if($this->keyword){
  		$query->addWhere('username like ? or mentor_id like ?', array('%'.$this->keyword.'%', '%'.$this->keyword.'%'));
  	}
  	 
  	$this->total = $query->count();
  	$this->pages = ceil($this->total / sfConfig::get('app_const_record_num'));
  	$this->forward404if($this->total && $this->page>$this->pages);
  	 
  	$this->users = $query->limit(sfConfig::get('app_const_record_num'))->offset($this->start)->execute();
  }
  
  /**
   * Show user details
   * @param sfWebRequest $request
   */
  public function executeShow(sfWebRequest $request)
  {
//   	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$this->type = $request->getParameter('type');
  	$this->page = $request->getParameter('page');
  	$this->keyword = $request->getParameter('keyword');
  	
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
//   	$this->forward404Unless($this->yoca_user);
  }
  
  /**
   * Activate/de-activate mentor
   * @param sfWebRequest $request
   */
  public function executeSetActive(sfWebRequest $request){
  	$this->forward404Unless($this->getUser()->getAttribute('usertype')=='Admin');
  	
  	$is_active = $request->getParameter('is_active');
  	$this->forward404If(is_null($is_active));
  	 
  	$this->yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($request->getParameter('id')));
  	$this->forward404Unless($this->yoca_user);
  	 
  	$this->yoca_user->set('is_active', $is_active);
  	$this->yoca_user->save();
  	 
  	if($is_active){
	  	//Send confirmation email to mentor
	  	$body = "Mentor confirmed for {$this->yoca_user->getUsername()}\n\n";
	  	$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
	  	$body .= "Yours,\n";
	  	$body .= "YOCA Team";
	  	$mailer = sfContext::getInstance()->getMailer();
	  	$mailer->composeAndSend(sfConfig::get('app_email_service'), $this->yoca_user->getUsername(), 'Mentor Confirmed', $body);
  	}
  	 
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
    try{
    	$this->processForm($request, $this->form);
    }catch(Exception $e){
    	$this->getUser()->setFlash('error', $e->getMessage());
    }
    $this->setTemplate('new');
  }
  
  public function executeConfirm(sfWebRequest $request){
  	
  }
  
  /**
   * Change password
   * @param sfWebRequest $request
   */
  public function executeChangePass(sfWebRequest $request){
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  	$this->form = new ChangePasswordForm();
  }
  
  public function executeDoChangePass(sfWebRequest $request){
  	$this->forward404Unless($request->isMethod(sfRequest::POST));
  	$this->form = new ChangePasswordForm();
  	$this->processChangePassForm($request, $this->form);
  	
  	$this->setTemplate('changePass');
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
    $this->setTemplate('becomeMentee');
  }
  
  public function executeMenteeConfirm(sfWebRequest $request){
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
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
  	$yoca_user = Doctrine_Core::getTable('YocaUser')->find($this->getUser()->getAttribute('userid'));
  	$this->form = new YocaUserForm($yoca_user, array('usertype' => 'becomeMentor'));
  	$this->processForm($request, $this->form);
  	$this->setTemplate('becomeMentor');
  }
  
  public function executeMentorConfirm(sfWebRequest $request){
  	$this->getUser()->setAttribute('cur_page', 'mentorship_program');
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
    }else{
    	if($form->isNew()){
	    	throw new Exception(implode(' ', $form->getErrorSchema()->getErrors()));
    	}
    }
  }
  
  protected function processChangePassForm(sfWebRequest $request, sfForm $form){
  	$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
  	if ($form->isValid())
  	{
  		$yoca_user = Doctrine_Core::getTable('YocaUser')->find(array($this->getUser()->getAttribute('userid')));
  		if($this->getUser()->verify($this->form->getValue('current_password'), $yoca_user->getPassword())){
  			if($this->form->getValue('new_password') == $this->form->getValue('current_password')){
  				$this->getUser()->setFlash('error', 'Cannot set new password to be the same as current password');
  			}else{
  				// Do change password here
  				$passHash = sfContext::getInstance()->getUser()->generateHash($this->form->getValue('new_password'));
  				$yoca_user->setPassword($passHash);
  				$yoca_user->save();
  				
				$this->getUser()->setFlash('msg', 'Password changed successfully');
		  		$this->redirect('user/changePass');
  			}
  		}else{
  			$this->getUser()->setFlash('error', 'Current password does not match our record');
  		}
  	}
  }
}
