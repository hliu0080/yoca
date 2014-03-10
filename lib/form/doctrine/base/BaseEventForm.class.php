<?php

/**
 * Event form base class.
 *
 * @method Event getObject() Returns the current form's model object
 *
 * @package    yoca
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'industry'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('YocaIndustry'), 'add_empty' => true)),
      'mentorid'     => new sfWidgetFormInputText(),
      'capacity'     => new sfWidgetFormInputText(),
      'booked'       => new sfWidgetFormInputText(),
      'datetime'     => new sfWidgetFormDateTime(),
      'neighborhood' => new sfWidgetFormInputText(),
      'address'      => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'udpated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'industry'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('YocaIndustry'), 'required' => false)),
      'mentorid'     => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'capacity'     => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'booked'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'datetime'     => new sfValidatorDateTime(array('required' => false)),
      'neighborhood' => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'address'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'status'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'udpated_at'   => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Event';
  }

}
