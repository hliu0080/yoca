<?php 
class logActions extends sfActions{
	public function preExecute(){
		//Populate the main_nav bar
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->sections = sfConfig::get("app_dashboard_$usertype");
		$this->getUser()->setAttribute('cur_page', 'view_logs');
	}
	
	public function executeActivities(){
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->forward404If($usertype!='admin');
		
		$file_handle = fopen(dirname(__FILE__).'/../../../../../log/admin_prod.log', "r");
		$count = 0;
		
		$content = '';
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
			
			$p = strpos($line, "=");
			if(strcasecmp(substr($line, $p, 11), '===== START') == 0){
				$content .= "<pre>$line";
			}elseif(strcasecmp(substr($line, $p, 9), "===== END") == 0){
				$content .= "$line</pre>";
			}else{
				$content .= $line;
			}
		}
		fclose($file_handle);
		
		$this->content = $content;
	}
	
	public function executeReminders(){
		$usertype = strtolower($this->getUser()->getAttribute('usertype'));
		$this->forward404If($usertype!='admin');
	
		$file_handle = fopen(dirname(__FILE__).'/../../../../../log/EventReminder.log', "r");
		$count = 0;
	
		$content = '';
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
				
			$p = strpos($line, "=");
			if(strcasecmp(substr($line, $p, 11), '===== START') == 0){
				$content .= "<pre>$line";
			}elseif(strcasecmp(substr($line, $p, 9), "===== END") == 0){
				$content .= "$line</pre>";
			}else{
				$content .= $line;
			}
		}
		fclose($file_handle);
	
		$this->content = $content;
	}
}