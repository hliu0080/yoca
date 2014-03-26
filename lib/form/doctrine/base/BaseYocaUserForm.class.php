<?php

/**
 * YocaUser form base class.
 *
 * @method YocaUser getObject() Returns the current form's model object
 *
 * @package    yoca
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseYocaUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'username'       => new sfWidgetFormInputText(),
      'password'       => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'firstname'      => new sfWidgetFormInputText(),
      'lastname'       => new sfWidgetFormInputText(),
      'english_name'   => new sfWidgetFormInputText(),
      'phone'          => new sfWidgetFormInputText(),
      'wechat'         => new sfWidgetFormInputText(),
      'education'      => new sfWidgetFormInputText(),
      'school_id'      => new sfWidgetFormInputText(),
      'school'         => new sfWidgetFormInputText(),
      'major_id'       => new sfWidgetFormInputText(),
      'major'          => new sfWidgetFormInputText(),
      'work'           => new sfWidgetFormInputText(),
      'employer'       => new sfWidgetFormInputText(),
      'oh_preference'  => new sfWidgetFormInputText(),
      'industry_id'    => new sfWidgetFormInputText(),
      'industry'       => new sfWidgetFormInputText(),
      'description'    => new sfWidgetFormInputText(),
      'expectation_id' => new sfWidgetFormInputText(),
      'expectation'    => new sfWidgetFormInputText(),
      'age'            => new sfWidgetFormInputText(),
      'neighborhood'   => new sfWidgetFormInputText(),
      'organization'   => new sfWidgetFormInputText(),
      'is_active'      => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'username'       => new sfValidatorString(array('max_length' => 255)),
      'password'       => new sfValidatorString(array('max_length' => 255)),
      'type'           => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'firstname'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'lastname'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'english_name'   => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'phone'          => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'wechat'         => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'education'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'school_id'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'school'         => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'major_id'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'major'          => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'work'           => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'employer'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'oh_preference'  => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'industry_id'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'industry'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'description'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'expectation_id' => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'expectation'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'age'            => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'neighborhood'   => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'organization'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_active'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(array('required' => false)),
      'updated_at'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('yoca_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'YocaUser';
  }

}
