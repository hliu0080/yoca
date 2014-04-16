<?php 
class sfWidgetFormSchemaFormatterCustom extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat					= '<div class="control-group">%label%<div class="controls">%field%<span class="help-inline">%help%</span></div>%hidden_fields%</div>',
    $errorRowFormat  			= '<div class="control-group error">%label%<div class="controls">%field%<span class="help-inline">%help%</span><span class="help-inline">%error%</span></div>%hidden_fields%</div>',
    $errorListFormatInARow		= "  %errors%  ",
    $errorRowFormatInARow		= "  %error%\n";
//     $helpFormat					= '<p class="help-block">%help%</p>',
//     $decoratorFormat			= '<div><br>%content%</div>';
  
  /**
   * Overwrite label format
   * @see sfWidgetFormSchemaFormatter::generateLabel()
   */
  public function generateLabel($name, $attributes = array())
  {
	if(!array_key_exists('class', $attributes)){
		$attributes['class'] = 'control-label';
	}  	
  	return parent::generateLabel($name, $attributes);
  }
  
  /**
   * Overwrite error row format
   * @see sfWidgetFormSchemaFormatter::formatRow()
   */
  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null){
  	if($errors){
  		return strtr($this->getErrorRowFormat(), array(
      '%label%'         => $label,
      '%field%'         => $field,
      '%error%'         => $this->formatErrorsForRow($errors),
      '%help%'          => $this->formatHelp($help),
      '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
    ));
  	}else{
  		return parent::formatRow($label, $field, $errors, $help, $hiddenFields);
  	}
  }
}