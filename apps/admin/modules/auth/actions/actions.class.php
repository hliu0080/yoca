<?php 
class authActions extends sfActions
{
	public function executeLogin(sfWebRequest $request){
		$user = $this->getUser();
		if ($user->isAuthenticated()) {
			return $this->redirect('@homepage');
		}
		
		$this->form = new LoginForm();
		if($request->isMethod('post')){
			$this->form->bind($request->getParameter('login'));
			if($this->form->isValid()){
				try{
					$this->getUser()->authenticate($this->form->getValue('username'), $this->form->getValue('password'));
				}catch(Exception $e){
					$this->getUser()->setFlash('error', $e->getMessage());
				}

				$this->redirect('@homepage');
			}
		}
	}
	
	public function executeLogout(sfWebRequest $request){
		$this->getUser()->clearCredentials();
		$this->getUser()->setAuthenticated(false);
		$this->redirect('@login');
	}
}