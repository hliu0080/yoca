<?php

/**
 * YocaUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    yoca
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class YocaUser extends BaseYocaUser
{
	public function save(Doctrine_Connection $con = null){
		if ($this->isNew())
		{
			$passHash = sfContext::getInstance()->getUser()->generateHash($this->getPassword());
			
			$this->setPassword($passHash);
			$this->setCreatedAt(date("Y-m-d H:i:s"));
		}else{
			if(!is_null($this->getEnglishName()) && strlen($this->getEnglishName())==0){
				$this->setEnglishName(null);
			}
			if(!is_null($this->getPhone()) && strlen($this->getPhone())==0){
				$this->setPhone(null);
			}
			if(!is_null($this->getWechat()) && strlen($this->getWechat())==0){
				$this->setWechat(null);
			}
			if(!is_null($this->getSchoolId())){
				$this->setSchoolId(is_array($this->getSchoolId())?implode(',', $this->getSchoolId()):$this->getSchoolId());
			}
			if(!is_null($this->getMajorId())){
				$this->setMajorId(is_array($this->getMajorId())?implode(',', $this->getMajorId()):$this->getMajorId());
			}
			if(!is_null($this->getIndustryId())){
				$this->setIndustryId(is_array($this->getIndustryId())?implode(',', $this->getIndustryId()):$this->getIndustryId());
			}
			if(!is_null($this->getExpectationId())){
				$this->setExpectationId($this->getExpectationId()?implode(',', $this->getExpectationId()):$this->getExpectationId());
			}
		}
		
		return parent::save($con);
	}
}
