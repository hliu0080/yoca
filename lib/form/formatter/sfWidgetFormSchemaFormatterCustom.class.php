<?php 
class sfWidgetFormSchemaFormatterCustom extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = '<div class="control-group">%label%<div class="controls">%field%</div>%error%%help% %hidden_fields%</div>',
    $errorRowFormat  = '<div>%errors%</div>',
    $helpFormat      = '<p class="help-block">%help%</p>',
    $decoratorFormat = '<div><br>%content%</div>';
  
  public function generateLabel($name, $attributes = array())
  {
	if(!array_key_exists('class', $attributes)){
		$attributes['class'] = 'control-label';
	}  	
  	return parent::generateLabel($name, $attributes);
  }
}