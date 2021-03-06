<?php

/**
 * YocaUser form.
 *
 * @package    yoca
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class YocaUserForm extends BaseYocaUserForm
{
  public function configure()
  {
  	if($this->isNew()){
  		
  		$widgets = array(
  				'username' => new sfWidgetFormInputText(),
  				'password' => new sfWidgetFormInputPassword(),
  				're_password' => new sfWidgetFormInputPassword(),
  		);
  		
  		$validators = array(
  				'username' => new sfValidatorEmail(array('max_length' => 255, 'trim' => true), array('required' => 'Username required.', 'invalid' => 'Please setup username as your email address.')),
  				'password' => new sfValidatorAnd(
  						array(
  							new sfValidatorString(array('min_length' => 8), array('min_length' => 'Passwords must be min 8 characters.')),
  							new sfValidatorRegex(array('pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/'), array('invalid' => 'Passwords must contain required characters.'))
  						),
  						array(),
  						array('required' => 'Password required.')
  				),
  				're_password' => new sfValidatorString(array('required' => true), array('required' => 'Confirm password required.')),
  		);
  		
  		$labels = array(
  				'username' => '* Username / Email Address',
  				'password' => '* Password',
  				're_password' => '* Confirm Password'
  		);
  	}else{
  		$usertype = $this->getOption('usertype');
  		if(!is_null($usertype)){
  			$widgets['firstname'] = new sfWidgetFormInputText();
  			$widgets['lastname'] = new sfWidgetFormInputText();
  			$widgets['english_name'] = new sfWidgetFormInputText();
  			$widgets['phone'] = new sfWidgetFormInputText();
  			$widgets['wechat'] = new sfWidgetFormInputText();
  			$widgets['education'] = new sfWidgetFormChoice(array(
  					'choices' => array('' => "Choose Education") + sfConfig::get('app_profile_education')
  			));
  		
  			$validators['firstname'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  			$validators['lastname'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  			$validators['english_name'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  			$validators['phone'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  			$validators['wechat'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  			$validators['education'] = new sfValidatorChoice(array(
  					'choices' => array_keys(sfConfig::get('app_profile_education'))
  			));
  		
  			$labels['firstname'] = '* First Name';
  			$labels['lastname'] = '* Last Name';
  			$labels['english_name'] = 'English Name';
  			$labels['education'] = '* Education';
  		
  			switch($usertype){
  				case 'Mentee':
  				case 'becomeMentee':
  					$widgets['school_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserSchool', 'add_empty'=>true));
  					$widgets['school'] = new sfWidgetFormInputText();
  					$widgets['major_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserMajor', 'add_empty'=>true));
  					$widgets['major'] = new sfWidgetFormInputText();
  					$widgets['work'] = new sfWidgetFormChoice(array(
  							'choices' => array('' => "Choose Work Experience") + sfConfig::get('app_profile_mentee_work_experience')
  					));
  					$widgets['employer'] = new sfWidgetFormInputText();
  					$widgets['description_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserDescription', 'add_empty'=>true));
  					$widgets['oh_preference'] = new sfWidgetFormChoice(array(
  							'expanded' => true,
  							'renderer_options'=>array('formatter'=>array($this, 'RadioChoiceFormatter')),
  							'choices' => sfConfig::get('app_profile_oh_preference')
  					));
  					$widgets['industry_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry', 'table_method'=>'getIndustryForMentee', 'renderer_options'=>array('formatter'=>array($this, 'CheckboxChoiceFormatter')), 'multiple' => true, 'expanded' => true));
  					$widgets['industry'] = new sfWidgetFormInputText();
  					$widgets['expectation_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserExpectation', 'renderer_options'=>array('formatter'=>array($this, 'CheckboxChoiceFormatter')), 'multiple' => true, 'expanded' => true));
  					$widgets['expectation'] = new sfWidgetFormInputText();
  		
  					$validators['school_id'] = new sfValidatorString(array('max_length' => 45));
  					$validators['school'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  					$validators['major_id'] = new sfValidatorString(array('max_length' => 45));
  					$validators['major'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  					$validators['work'] = new sfValidatorChoice(array(
  							'choices' => array_keys(sfConfig::get('app_profile_mentee_work_experience'))
  					));
  					$validators['employer'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  					$validators['description_id'] = new sfValidatorString(array('max_length' => 45));
  					$validators['oh_preference'] = new sfValidatorChoice(array(
  							'choices' => array_keys(sfConfig::get('app_profile_oh_preference'))
  					));
  					$validators['industry_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry', 'multiple' => true));
  					$validators['industry'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  					$validators['expectation_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaUserExpectation', 'multiple' => true));
  					$validators['expectation'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  		
  					$labels['school_id'] = '* School';
  					$labels['school'] = 'Other School';
  					$labels['major_id'] = '* Major';
  					$labels['major'] = 'Other Major';
  					$labels['work'] = '* Work Experience';
  					$labels['employer'] = '* Current/Recent Employer';
  					$labels['oh_preference'] = '* Office Hour Preference';
  					$labels['industry_id'] = '* Industries you are interested in';
  					$labels['industry'] = 'Other industries if not listed above';
  					$labels['description_id'] = '* Which of the following category best describes you?';
  					$labels['expectation_id'] = '* What do you hope to get out of this program?';
  					$labels['expectation'] = 'Other expectations if not listed above';
  					break;
  				case 'Mentor':
  				case 'becomeMentor':
  					$widgets['school'] = new sfWidgetFormInputText();
  					$widgets['work'] = new sfWidgetFormChoice(array(
  							'choices' => array('' => "Choose Work Experience") + sfConfig::get('app_profile_mentor_work_experience')
  					));
  					$widgets['employer'] = new sfWidgetFormInputText();
  					$widgets['mentor_title'] = new sfWidgetFormInputText();
  					$widgets['industry_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry', 'table_method'=>'getIndustryForMentor', 'add_empty'=>true));
  					$widgets['industry'] = new sfWidgetFormInputText();
  					$widgets['sub_industry'] = new sfWidgetFormInputText();
  					$widgets['age'] = new sfWidgetFormChoice(array(
  							'choices' => array('' => "Choose Age Group") + sfConfig::get('app_profile_age')
  					));
  					$widgets['neighborhood'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaNeighborhood', 'add_empty'=>true));
  					$widgets['organization'] = new sfWidgetFormInputText();
  		
  					$validators['school'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  					$validators['work'] = new sfValidatorChoice(array(
  							'choices' => array_keys(sfConfig::get('app_profile_mentor_work_experience'))
  					));
  					$validators['employer'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  					$validators['mentor_title'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  					$validators['industry_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry'));
  					$validators['industry'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  					$validators['sub_industry'] = new sfValidatorString(array('max_length' => 45, 'required' => false, 'trim' => true));
  					$validators['school'] = new sfValidatorString(array('max_length' => 45, 'trim' => true));
  					$validators['age'] = new sfValidatorChoice(array(
  							'choices' => array_keys(sfConfig::get('app_profile_age'))
  					));
  					$validators['neighborhood'] = new sfValidatorDoctrineChoice(array('model' => 'YocaNeighborhood'));
  					$validators['organization'] = new sfValidatorString(array('max_length' => 255, 'required' => false, 'trim' => true));
  		
  					$labels['school'] = '* School';
  					$labels['work'] = '* Work Experience';
  					$labels['employer'] = '* Current/Recent Employer';
  					$labels['mentor_title'] = '* Title';
  					$labels['industry_id'] = "* Industry You work in";
  					$labels['industry'] = "Other industry if not listed above";
  					$labels['sub_industry'] = "Industry sub-category";
  					$labels['age'] = "* Age Group";
  					$labels['neighborhood'] = "* Neighborhood where you'll be hosting your office hours";
  					$labels['organization'] = "Other organizations you belong to";
  					break;
  				case 'Admin':
  					break;
  				default:
  					break;
  			}
  		}
  		
  		//add EULA for becomeMentee
  		if($usertype == 'becomeMentee'){
  			$labels['eula'] = 'Important Instruction';
  			$widgets['eula'] = new sfWidgetFormInputCheckbox();
  			$validators['eula'] = new sfValidatorChoice(array('required' => true, 'choices' => array('on')));
  		}
  	}
  	
  	$this->setWidgets($widgets);
  	$this->setValidators($validators);
  	
  	if($this->isNew()){
  		$this->validatorSchema->setPostValidator(
  			new sfValidatorSchemaCompare(
  					'password',
  					sfValidatorSchemaCompare::EQUAL,
  					're_password',
  					array(),
  					array('invalid' => 'Passwords must match.')
  			)
  		);
  		
  		$this->mergePostValidator(new sfValidatorDoctrineUnique(array(
  				'model' => 'YocaUser',
  				'column' => 'username',
  				'primary_key' => 'id'
  		), array('invalid' => 'Username already exists.')));
  		
  		$this->widgetSchema->setHelp('password', 'Min 8 characters - lowercase letters, CAPS, numbers and symbols');
  	}else{
	  	$this->widgetSchema->setHelp('eula', 'We strongly recommend you NOT to ask for referrals until you have built a good relationship with the mentor, usually after a few meetings. Also, please dress in business casual to attend our Office Hour events.');
	  	$this->widgetSchema->setHelp('sub_industry', 'e.g. Finance - Trading, Marketing - SEO, etc.');
	  	
	  	$formatter = new sfWidgetFormSchemaFormatterCustom($this->getWidgetSchema());
	  	$this->widgetSchema->addFormFormatter('custom', $formatter);
	  	$this->widgetSchema->setFormFormatterName('custom');
  	}
  	
  	$this->widgetSchema->setLabels($labels);
  	$this->widgetSchema->setNameFormat($this->isNew()?'signup[%s]':'edit[%s]');
  	
  	foreach ($this->getWidgetSchema()->getFields() as $field){
  		$field->setAttribute('class', 'input-xlarge');
  	}
  }
  
  public function save($con = NULL){
  	$clientIp = sfContext::getInstance()->getRequest()->getRemoteAddress();
  	
  	$values = $this->getValues();
  	if($this->isNew()){
	  	//Send confirmation email
	  	$body = "Member registeration successful for ".strtolower($values['username'])."\n\n";
	  	$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
	  	$body .= "Yours,\n";
	  	$body .= "YOCA Team";
	  	
	  	$mailer = sfContext::getInstance()->getMailer();
	  	$mailer->composeAndSend(sfConfig::get('app_email_service'), $values['username'], 'Greetings from YOCA!', $body);
  	}elseif($this->getOption('usertype') == 'becomeMentee'){
  		//Send confirmation email if user type is member
  		$body = "Mentee registration successful for {$this->getObject()->getUsername()}.\n\n";
  		$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
  		$body .= "Yours,\n";
  		$body .= "YOCA Team";
  		
  		$mailer = sfContext::getInstance()->getMailer();
  		$mailer->composeAndSend(sfConfig::get('app_email_service'), $this->getObject()->getUsername(), "Welcome to YOCA Mentorship Program", $body);
  		
  		//Update user type to mentee
  		$this->getObject()->setType('Mentee');
  		
  		//Update session user_type to mentee
  		sfContext::getInstance()->getUser()->setAttribute('usertype', 'Mentee');
  	}elseif($this->getOption('usertype') == 'becomeMentor'){
  		//Send confirmation email if user type is member
  		$body = "Mentor registration successful for {$this->getObject()->getUsername()}.\n\n";
  		$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
  		$body .= "Yours,\n";
  		$body .= "YOCA Team";
  		
  		$message = sfContext::getInstance()->getMailer()
  		->compose(sfConfig::get('app_email_service'), $this->getObject()->getUsername(), 'Welcome to YOCA Mentorship Program', $body)
  		->setBcc(sfConfig::get('app_email_contact'));
  		
  		sfContext::getInstance()->getLogger()->log("Mentor Register[$clientIp]: ===== START MENTOR REGISGER =====", sfLogger::DEBUG);
  		$ret = sfContext::getInstance()->getMailer()->send($message);
  		if($ret){
  			sfContext::getInstance()->getLogger()->log("Mentor Register[$clientIp]: sent email to mentor ".$this->getObject()->getUsername(), sfLogger::DEBUG);
  		}else{
  			sfContext::getInstance()->getLogger()->log("Mentor Register[$clientIp]: failed sending email to mentor ".$this->getObject()->getUsername(), sfLogger::DEBUG);
  		}

  		//Set mentor_id to be industry initial + number 
  		$industry = Doctrine_Core::getTable('YocaIndustry')->find($values['industry_id']);
  		$id = Doctrine_Core::getTable('YocaUser')->nextAvailableMentorId($industry);
  		$this->getObject()->setMentorId($id);
  		sfContext::getInstance()->getLogger()->log("Mentor Register[$clientIp]: mentor Id $id", sfLogger::DEBUG);
  		sfContext::getInstance()->getLogger()->log("Mentor Register[$clientIp]: ===== END MENTOR REGISTER =====", sfLogger::DEBUG);
  		
  		//Update user type to mentee
  		$this->getObject()->setType('Mentor');
  		$this->getObject()->setIsActive(0);
  		
  		//Update session user_type to mentee
  		sfContext::getInstance()->getUser()->setAttribute('usertype', 'Mentor');
  	}
  	parent::save($con);
  }
  
  public static function CheckboxChoiceFormatter($widget, $inputs) {
  	$result = "";
  	foreach ($inputs as $input) {
  		$result .= "<label class='checkbox'>". $input['input'] . " " . $input['label'] . "</label>";
  	}
  	return $result;
  }
  
  public static function RadioChoiceFormatter($widget, $inputs) {
  	$result = "";
  	foreach ($inputs as $input) {
  		$result .= "<label class='radio'>". $input['input'] . " " . $input['label'] . "</label>";
  	}
  	return $result;
  }
  
  
}
