<?php

class myUser extends sfBasicSecurityUser
{
	public function authenticate($username, $password){
		$user = YocaUserTable::getInstance()->findOneBy('username', $username);
		
		if($user){
			if($this->_verify($password, $user->get('password'))){
				$this->setAuthenticated(true);
				$this->setAttribute('username', $username);
				$this->setAttribute('userid', $user->get('id'));
				$this->setAttribute('usertype', $user->get('type'));
			} else{
				throw new Exception('Invalid password');
			}	
		}else{
			throw new Exception('User does not exist');
		}
	}
	
	public function generateHash($password) {
		if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
			$salt = '$2a$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
			return crypt($password, $salt);
		}
	}
	
	protected function _verify($password, $hashedPassword){
		return crypt($password, $hashedPassword) === $hashedPassword;
	}
}
