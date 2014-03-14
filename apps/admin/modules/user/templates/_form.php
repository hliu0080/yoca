	
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
      	<?php echo $form ?>
      	<div class="form-actions">
      		<?php if($form->getOption('usertype')=='becomeMentee' || $form->getOption('usertype')=='becomeMentor'):?>
      			<input class="btn btn-wuxia btn-primary" type="submit" value="Submit"/>
      		<?php else:?>
				<input class="btn btn-wuxia btn-primary" type="submit" value="Update"/>
			<?php endif?>
		</div>
  	</fieldset>
<?php endif ?>
</form>
