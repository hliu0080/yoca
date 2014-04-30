<?php
class ChangePasswordForm extends sfForm{
	public function configure()
	{
		$this->setWidgets(array(
				'current_password' => new sfWidgetFormInputPassword(),
				'new_password' => new sfWidgetFormInputPassword(),
				'new_password_again' => new sfWidgetFormInputPassword()
		));
	
		$this->widgetSchema->setNameFormat('changePass[%s]');
	
		$this->setValidators(array(
				'current_password' => new sfValidatorString(),
				'new_password' => new sfValidatorAnd(array(
						new sfValidatorString(array('min_length' => 8), array('min_length' => 'At least 8 characters.')),
						new sfValidatorRegex(array('pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/'), array('invalid' => 'Must contain required characters.')),
				)),
				'new_password_again' => new sfValidatorAnd(array(
						new sfValidatorString(array('min_length' => 8), array('min_length' => 'At least 8 characters.')),
						new sfValidatorRegex(array('pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/'), array('invalid' => 'Must contain required characters.')),
				))
		));
		
		$this->validatorSchema->setPostValidator(
				new sfValidatorSchemaCompare(
						'new_password',
						sfValidatorSchemaCompare::EQUAL,
						'new_password_again',
						array(),
						array('invalid' => 'New passwords must match.')
				)
		);
		
		$this->widgetSchema->setLabels(array(
				'current_password' => '* Current Password',
				'new_password' => '* New Password',
				'new_password_again' => '* Confirm New Password',
		));
		
		$this->widgetSchema->setHelp('new_password', 'Password must be at least 8 characters and contain uppercase letters, lowercase letters, numbers and special characters (i.e. @, #, $, %, ^, &, +, =)');
		$this->widgetSchema->setHelp('new_password_again', 'Password must be at least 8 characters and contain uppercase letters, lowercase letters, numbers and special characters (i.e. @, #, $, %, ^, &, +, =)');
		
		$formatter = new sfWidgetFormSchemaFormatterCustom($this->getWidgetSchema());
		$this->widgetSchema->addFormFormatter('custom', $formatter);
		$this->widgetSchema->setFormFormatterName('custom');
		
		foreach ($this->getWidgetSchema()->getFields() as $field){
			$field->setAttribute('class', 'input-xlarge');
		}
	}
}