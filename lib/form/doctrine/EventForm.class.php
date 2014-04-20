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
  		'industry' => new sfWidgetFormDoctrineChoice(array('model' => 'YocaIndustry', 'table_method'=>'getIndustryForMentor', 'add_empty' => true)),
  		'topic' => new sfWidgetFormDoctrineChoice(array('model' => 'EventTopic', 'add_empty' => true)),
  		'other_topic' => new sfWidgetFormInputText(), 
  		'capacity' => new sfWidgetFormInputText(),
  		'datetime' => new sfWidgetFormDateTime(array(
  				'date' => array('years' => array('2014' => '2014'), 'can_be_empty' => false, 'format'=>'%month% / %day% / %year%'),
  				'time' => array('minutes' => array('00', '30'), 'can_be_empty' => false, 'format_without_seconds'=>'%hour% : %minute%'),
  				'with_time' => true
  		)),
  		'neighborhood' => new sfWidgetFormDoctrineChoice(array('model' => 'YocaNeighborhood', 'add_empty' => true)),
  		'address' => new sfWidgetFormDoctrineChoice(array('model' => 'EventAddress', 'add_empty' => true)),
  		'other_address' => new sfWidgetFormInputText(),
  	);
  	$validators = array(
  		'industry' => new sfValidatorDoctrineChoice(array('model' => 'YocaIndustry')),
  		'topic' => new sfValidatorDoctrineChoice(array('model' => 'EventTopic')),
  		'other_topic' => new sfValidatorString(array('max_length' => 45, 'required'=>false)),
  		'capacity' => new sfValidatorInteger(),
  		'datetime' => new sfValidatorDate(array(
  				'min' => mktime(0, 0, 0, date('m'), date('d'), date('y')),
  		)),
  		'neighborhood' => new sfValidatorDoctrineChoice(array('model' => 'YocaNeighborhood')),
  		'address' => new sfValidatorDoctrineChoice(array('model' => 'EventAddress')),
  		'other_address' => new sfValidatorString(array('max_length' => 255, 'required'=>false)),
  	);
  	$labels = array(
  		'industry' => '* Industry',
  		'topic' => '* Topic',
  		'other_topic' => 'Other topic if not listed above',
  		'capacity' => '* Capacity',
  		'datetime' => '* Date & Time',
  		'neighborhood' => '* Neighborhood',
  		'address' => '* Address',
  		'other_address' => 'Other address if not listed above',
  	);
  	
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
  	$this->widgetSchema->setHelp('datetime', 'Format: Month / Day / Year, Hour (24hr) : Minute');
  	
  	$formatter = new sfWidgetFormSchemaFormatterCustom($this->getWidgetSchema());
  	$this->widgetSchema->addFormFormatter('custom', $formatter);
  	$this->widgetSchema->setFormFormatterName('custom');
  	 
  	foreach ($this->getWidgetSchema()->getFields() as $field)
  	{
  		$field->setAttribute('class', 'input-xlarge');
  	}
  }
  
}
