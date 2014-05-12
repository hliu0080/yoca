<?php 
class logActions extends sfActions{
	public function preExecute(){
		//Populate the main_nav bar
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->sections = sfConfig::get("app_dashboard_$usertype");
	}
	
	public function executeIndex(){
		$this->getUser()->setAttribute('cur_page', 'view_logs');
		
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->forward404If($usertype!='admin');
		
		
	}
}