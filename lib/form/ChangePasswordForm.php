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
				'current_password' => new sfValidatorString(array('required' => true, 'trim' => true)),
				'new_password' => new sfValidatorString(array('required' => true, 'trim' => true)),
				'new_password_again' => new sfValidatorString(array('required' => true, 'trim' => true))
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
				'new_password_again' => '* New Password Again',
		));
		
		$formatter = new sfWidgetFormSchemaFormatterCustom($this->getWidgetSchema());
		$this->widgetSchema->addFormFormatter('custom', $formatter);
		$this->widgetSchema->setFormFormatterName('custom');
		
		foreach ($this->getWidgetSchema()->getFields() as $field){
			$field->setAttribute('class', 'input-xlarge');
		}
	}
}