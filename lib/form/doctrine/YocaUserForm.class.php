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
  				'username' => new sfValidatorEmail(array('max_length' => 255)),
  				'password' => new sfValidatorString(array('max_length' => 255)),
  				're_password' => new sfValidatorString(array('max_length' => 255)),
  		);
  		
  		$labels = array(
  				'username' => '* Username / Email Address',
  				'password' => '* Password',
  				're_password' => '* Confirm Password'
  		);
  		
  		$this->validatorSchema->setPostValidator(
  				new sfValidatorAnd(array(
  						new sfValidatorSchemaCompare(
  								'password', sfValidatorSchemaCompare::EQUAL, 're_password', array(),
  								array('invalid' => 'Passwords do not match'))
  				))
  		);
  	}
  	
  	$usertype = $this->getOption('usertype');
  	if(!is_null($usertype)){
  		$widgets['firstname'] = new sfWidgetFormInputText();
  		$widgets['lastname'] = new sfWidgetFormInputText();
  		$widgets['english_name'] = new sfWidgetFormInputText();
  		$widgets['phone'] = new sfWidgetFormInputText();
  		$widgets['wechat'] = new sfWidgetFormInputText();
  		$widgets['education'] = new sfWidgetFormChoice(array(
  				'choices' => sfConfig::get('app_profile_education')
  		));
  		
  		$validators['firstname'] = new sfValidatorString(array('max_length' => 45));
  		$validators['lastname'] = new sfValidatorString(array('max_length' => 45));
  		$validators['english_name'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  		$validators['phone'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  		$validators['wechat'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  		$validators['education'] = new sfValidatorChoice(array(
  				'choices' => array_keys(sfConfig::get('app_profile_education'))
  		));
  		
  		$labels['firstname'] = '* First Name';
  		$labels['lastname'] = '* Last Name';
  		$labels['english_name'] = 'English Name';
  		
  		switch($usertype){
  			case 'Mentee':
  				$widgets['school_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserSchool'));
  				$widgets['school'] = new sfWidgetFormInputText();
  				$widgets['major_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserMajor'));
  				$widgets['major'] = new sfWidgetFormInputText();
  				$widgets['work'] = new sfWidgetFormChoice(array(
  						'choices' => sfConfig::get('app_profile_mentee_work_experience')
  				));
  				$widgets['employer'] = new sfWidgetFormInputText();
  				$widgets['description'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserDescription'));
  				$widgets['industry_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry', 'multiple' => true, 'expanded' => true));
  				$widgets['expectation_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserExpectation', 'multiple' => true, 'expanded' => true));
  				$widgets['oh_preference'] = new sfWidgetFormChoice(array(
  						'expanded' => true,
  						'choices' => sfConfig::get('app_profile_oh_preference')
  				));
  				
  				$validators['school_id'] = new sfValidatorString(array('max_length' => 45));
  				$validators['school'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  				$validators['major_id'] = new sfValidatorString(array('max_length' => 45));
  				$validators['major'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  				$validators['work'] = new sfValidatorChoice(array(
  						'choices' => array_keys(sfConfig::get('app_profile_mentee_work_experience'))
  				));
  				$validators['employer'] = new sfValidatorString(array('max_length' => 45));
  				$validators['description'] = new sfValidatorString(array('max_length' => 45));
  				$validators['industry_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry', 'multiple' => true, 'expanded' => true));
  				$validators['expectation_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaUserExpectation', 'multiple' => true, 'expanded' => true));
  				$validators['oh_preference'] = new sfValidatorChoice(array(
  						'choices' => array_keys(sfConfig::get('app_profile_oh_preference'))
  				));
  				
  				$labels['education'] = '* Education';
  				$labels['school_id'] = '* School';
  				$labels['school'] = 'Other School';
  				$labels['major_id'] = '* Major';
  				$labels['major'] = 'Other Major';
  				$labels['work'] = '* Work Experience';
  				$labels['employer'] = '* Current/Recent Employer';
  				$labels['description'] = '* I am a';
  				$labels['industry_id'] = '* Industries I am interested in';
  				$labels['expectation_id'] = '* I hope to get information on';
  				$labels['oh_preference'] = '* I would like YOCA to';
  				break;
  			case 'becomeMentee':
  				$widgets['school_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserSchool'));
  				$widgets['school'] = new sfWidgetFormInputText();
  				$widgets['major_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserMajor'));
  				$widgets['major'] = new sfWidgetFormInputText();
  				$widgets['work'] = new sfWidgetFormChoice(array(
  						'choices' => sfConfig::get('app_profile_mentee_work_experience')
  				));
  				$widgets['employer'] = new sfWidgetFormInputText();
  				$widgets['description'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserDescription'));
  				$widgets['oh_preference'] = new sfWidgetFormChoice(array(
  						'expanded' => true,
  						'choices' => sfConfig::get('app_profile_oh_preference')
  				));
  				$widgets['industry_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry', 'multiple' => true, 'expanded' => true));
  				$widgets['expectation_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaUserExpectation', 'multiple' => true, 'expanded' => true));
  				
  				$validators['school_id'] = new sfValidatorString(array('max_length' => 45));
  				$validators['school'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  				$validators['major_id'] = new sfValidatorString(array('max_length' => 45));
  				$validators['major'] = new sfValidatorString(array('max_length' => 45, 'required' => false));
  				$validators['work'] = new sfValidatorChoice(array(
  						'choices' => array_keys(sfConfig::get('app_profile_mentee_work_experience'))
  				));
  				$validators['employer'] = new sfValidatorString(array('max_length' => 45));
  				$validators['description'] = new sfValidatorString(array('max_length' => 45));
  				$validators['oh_preference'] = new sfValidatorChoice(array(
  						'choices' => array_keys(sfConfig::get('app_profile_oh_preference'))
  				));
  				$validators['industry_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry', 'multiple' => true));
  				$validators['expectation_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaUserExpectation', 'multiple' => true));
  				
  				$labels['education'] = '* Education';
  				$labels['school_id'] = '* School';
  				$labels['school'] = 'Other School';
  				$labels['major_id'] = '* Major';
  				$labels['major'] = 'Other Major';
  				$labels['work'] = '* Work Experience';
  				$labels['employer'] = '* Current/Recent Employer';
  				$labels['oh_preference'] = '* Office Hour Preference';
  				$labels['industry_id'] = '* Industries you are interested in';
  				$labels['description'] = '* Which of the following category best describes you?';
  				$labels['expectation_id'] = '* What do you hope to get out of this program?';
  				
  				$widgets['eula'] = new sfWidgetFormInputCheckbox();
  				$validators['eula'] = new sfValidatorString(array('max_length' => 45));
  				break;
  			case 'Mentor':
  			case 'becomeMentor':
  				$widgets['school'] = new sfWidgetFormInputText();
  				$widgets['work'] = new sfWidgetFormChoice(array(
  						'choices' => sfConfig::get('app_profile_mentor_work_experience')
  				));
  				$widgets['employer'] = new sfWidgetFormInputText();
  				$widgets['industry_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry'));
  				$widgets['industry'] = new sfWidgetFormInputText();
  				$widgets['age'] = new sfWidgetFormChoice(array(
  						'choices' => sfConfig::get('app_profile_age')
  				));
  				$widgets['neighborhood'] = new sfWidgetFormInputText();
  				$widgets['organization'] = new sfWidgetFormInputText();
  				
  				$validators['school'] = new sfValidatorString(array('max_length' => 45));
  				$validators['work'] = new sfValidatorChoice(array(
  						'choices' => array_keys(sfConfig::get('app_profile_mentor_work_experience'))
  				));
  				$validators['employer'] = new sfValidatorString(array('max_length' => 45));
  				$validators['industry_id'] = new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry'));
  				$validators['industry'] = new sfValidatorString(array('max_length' => 45));
  				$validators['school'] = new sfValidatorString(array('max_length' => 45));
  				$validators['age'] = new sfValidatorChoice(array(
  						'choices' => array_keys(sfConfig::get('app_profile_age'))
  				));
  				$validators['neighborhood'] = new sfValidatorString(array('max_length' => 45));
  				$validators['organization'] = new sfValidatorString(array('max_length' => 255, 'required' => false));
  				
  				$labels['education'] = '* Education';
  				$labels['school'] = '* School';
  				$labels['work'] = '* Work Experience';
  				$labels['employer'] = '* Current/Recent Employer';
  				$labels['industry_id'] = "* Industry I Work In";
  				$labels['industry'] = "Other Industry";
  				$labels['age'] = "* Age Group";
  				$labels['neighborhood'] = "* Neighborhood";
  				$labels['organization'] = "Other Organizations I Belong To";
  				break;
  			case 'Admin':
  				break;
  			default:
  				break;
  		}
  	}
  	$this->setWidgets($widgets);
  	$this->setValidators($validators);
  	$this->widgetSchema->setLabels($labels);
  	$this->widgetSchema->setNameFormat($this->isNew()?'signup[%s]':'edit[%s]');
  	
  	$formatter = new sfWidgetFormSchemaFormatterCustom($this->getWidgetSchema());
  	$this->widgetSchema->addFormFormatter('custom', $formatter);
  	$this->widgetSchema->setFormFormatterName('custom');
  	
  	foreach ($this->getWidgetSchema()->getFields() as $field)
  	{
  		$field->setAttribute('class', 'input-xlarge');
  	}
  }
  
  public function save($con = NULL){
  	if($this->isNew()){
	  	//Send confirmation email
	  	$body = "Member registeration successful for {$this->getObject()->getUsername()}";
	  	$mailer = sfContext::getInstance()->getMailer();
	  	$mailer->composeAndSend(sfConfig::get('app_mail_service'), $this->getObject()->getUsername(), 'Greetings from YOCA!', $body);
  	}elseif($this->getOption('usertype') == 'becomeMentee'){
  		//Send confirmation email if user type is member
  		$body = "Mentee registration successful for {$this->getObject()->getUsername()}";
  		$mailer = sfContext::getInstance()->getMailer();
  		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $this->getObject()->getUsername(), "Welcome to YOCA Mentorship Program", $body);
  		
  		//Update user type to mentee
  		$this->getObject()->setType('Mentee');
  		
  		//Update session user_type to mentee
  		sfContext::getInstance()->getUser()->setAttribute('usertype', 'Mentee');
  	}elseif($this->getOption('usertype') == 'becomeMentor'){
  		//Send confirmation email if user type is member
  		$body = "Mentor registration successful for {$this->getObject()->getUsername()}";
  		$mailer = sfContext::getInstance()->getMailer();
  		$mailer->composeAndSend(sfConfig::get('app_mail_service'), $this->getObject()->getUsername(), "Welcome to YOCA Mentorship Program", $body);
  		
  		//Update user type to mentee
  		$this->getObject()->setType('Mentor');
  		$this->getObject()->setIsActive(0);
  		
  		//Update session user_type to mentee
  		sfContext::getInstance()->getUser()->setAttribute('usertype', 'Mentor');
  	}
  	parent::save($con);
  }
}
