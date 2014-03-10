<?php

/**
 * Event form.
 *
 * @package    yoca
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventForm extends BaseEventForm
{
  public function configure()
  {
  	$widgets = array(
  		'industry' => new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry')),
  		'capacity' => new sfWidgetFormInputText(),
  		'datetime' => new sfWidgetFormDateTime(array(
  				'date' => array('years' => array('2014' => '2014'), 'can_be_empty' => false),
  				'time' => array('minutes' => array('00', '30'), 'can_be_empty' => false)
  		)),
  		'neighborhood' => new sfWidgetFormInputText(),
  		'address' => new sfWidgetFormInputText(),
  	);
  	$validators = array(
  		'industry' => new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry')),
  		'capacity' => new sfValidatorInteger(),
  		'datetime' => new sfValidatorDate(array(
  				'min' => mktime(0, 0, 0, date('m'), date('d'), date('y')),
  		)),
  		'neighborhood' => new sfValidatorString(array('max_length' => 45)),
  		'address' => new sfValidatorString(array('max_length' => 255)),
  	);
  	$labels = array();
  	
  	$this->setWidgets($widgets);
  	$this->setValidators($validators);
  	$this->setDefaults(array(
  		'industry' => $this->getOption('industry'),
  		'capacity' => 5,
  		'neighborhood' => $this->getOption('neighborhood'),
  		'datetime' => date('m/d/Y'),
  	));
  	$this->widgetSchema->setLabels($labels);
  	$this->widgetSchema->setNameFormat('newEvent[%s]');
  }
}
