	
<?php 
	if($form->getObject()->isNew()){
		$url = 'user/create';
	}else{
		$usertype = $form->getOption('usertype');
		if(!is_null($usertype)){
			switch($usertype){
				case 'Member':
				case 'Mentor':
				case 'Mentee':
				case 'Admin':
					$url = 'user/update';
					break;
				case 'becomeMentor':
					$url = 'user/updateMentor';
					break;
				case 'becomeMentee':
					$url = 'user/updateMentee';
					break;
				default:
					$url = '';
					break;
			}
		}
	}
?>



<form action="<?php echo url_for($url) ?>" class="<?php print $form->getObject()->isNew()?'':'form-horizontal'?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if ($form->getObject()->isNew()): ?>
	<fieldset>
		<?php if(!is_null($sf_user->getFlash('error'))):?>
			<div class="alert alert-block alert-error">
				<p><strong>Error</strong></p>
				<p><?php print $sf_user->getFlash('error')?></p>
			</div>
		<?php endif?>
		<div style="padding-top:20px;text-align:center"><p>Already a YOCA member? <a href="<?php print url_for('login')?>">Log in</a></p></div>
		<div class="control-group">
			<?php print $form['username']->renderLabel()?>
			<div class="controls">
				<?php print $form['username']->render(array('class'=>'input-xlarge'))?>
			</div>
		</div>
		<div class="control-group">
			<?php print $form['password']->renderLabel()?>
			<div class="controls">
				<?php print $form['password']->render(array('class'=>'input-xlarge'))?>
			</div>
		</div>
		<div class="control-group">
			<?php print $form['re_password']->renderLabel()?>
			<div class="controls">
				<?php print $form['re_password']->render(array('class'=>'input-xlarge'))?>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-wuxia btn-large btn-primary" type="submit">Sign up</button>
		</div>
		<?php print $form['_csrf_token']->render()?>
	</fieldset>
<?php else:?>
	<fieldset>
		<input type="hidden" name="sf_method" value="put" />
		<?php 
			foreach($form as $field){
				if($field->getName()!='eula' && $field->getName()!='_csrf_token'){
					print $field->renderRow();
				}elseif($field->getName() == '_csrf_token'){
					print $field->render();
				}elseif($field->getName() == 'eula'){
					print "<div class='control-group ".($field->hasError()?"error'":"'").">";
					print $field->renderLabel();
					print "<div class='controls'><label class='checkbox'>";
					print "	We strongly recommend you NOT to ask for referrals until you have built a good relationship with the mentor, usually after a few meetings. Also, please dress in business casual to attend our Office Hour events.";
					print "	<br><br><input class='input-xlarge' type='checkbox' name='edit[eula]' id='edit_eula'".($field->getValue()=='on'?' checked ':'')."><label>I understand</label>";
  					print "</label>";
  					print $field->hasError()?("<span class='help-inline'>".$field->renderError()."</span>"):"";
  					print "</div></div>";
				} 
			}
		?>
      	<div class="form-actions">
      		<?php if($form->getOption('usertype')=='becomeMentee' || $form->getOption('usertype')=='becomeMentor'):?>
      			<input class="btn btn-wuxia btn-primary" type="submit" value="Submit Profile"/>
      		<?php else:?>
				<input class="btn btn-wuxia btn-primary" type="submit" value="Update Profile"/>
			<?php endif?>
		</div>
  	</fieldset>
<?php endif ?>
</form>
