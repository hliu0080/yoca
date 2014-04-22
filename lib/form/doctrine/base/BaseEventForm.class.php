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
      'mentorid'     => new sfWidgetFormInputText(),
      'industry'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('YocaIndustry'), 'add_empty' => true)),
      'topic_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EventTopic'), 'add_empty' => true)),
      'topic'        => new sfWidgetFormInputText(),
      'capacity'     => new sfWidgetFormInputText(),
      'booked'       => new sfWidgetFormInputText(),
      'datetime'     => new sfWidgetFormDateTime(),
      'neighborhood' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('YocaNeighborhood'), 'add_empty' => true)),
      'address_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EventAddress'), 'add_empty' => true)),
      'address'      => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'eventcol'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'mentorid'     => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'industry'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('YocaIndustry'), 'required' => false)),
      'topic_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EventTopic'), 'required' => false)),
      'topic'        => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'capacity'     => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'booked'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'datetime'     => new sfValidatorDateTime(array('required' => false)),
      'neighborhood' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('YocaNeighborhood'), 'required' => false)),
      'address_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EventAddress'), 'required' => false)),
      'address'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'status'       => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'updated_at'   => new sfValidatorDateTime(array('required' => false)),
      'eventcol'     => new sfValidatorString(array('max_length' => 45, 'required' => false)),
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
