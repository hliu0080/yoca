<form id="login-form" action="<?php echo url_for('auth/login') ?>" method="POST">
	<fieldset>
		<?php if(!is_null($sf_user->getFlash('error'))):?>
			<div class="alert alert-block alert-error">
				<p><strong>Error</strong></p>
				<p><?php print $sf_user->getFlash('error')?></p>
			</div>
		<?php endif?>
		<div class="control-group">
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><span class="awe-user"></span></span>
					<?php print $form['username']->render(array('class'=>'form-control', 'placeholder'=>'Username / Email Address')) ?>
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><span class="awe-lock"></span></span>
					<?php print $form['password']->render(array('class'=>'form-control', 'placeholder'=>'Password')) ?>
				</div>
<!-- TODO: -->				
<!-- 				<label class="checkbox"> -->
<!-- 					<input id="optionsCheckbox" type="checkbox" value="option1"> Remember me -->
<!-- 				</label> -->
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-wuxia btn-large btn-primary" type="submit">Log in</button>
		</div>
		<div class="signup">
			<a href="<?php echo url_for('user/new') ?>">Sign up Now!</a>
		</div>
<!-- TODO: -->
<!-- 		<div class="passreset"> -->
<!-- 			<a href="#"><small>Forget Passsword?</small></a> -->
<!-- 		</div> -->
	</fieldset>
	<?php print $form['_csrf_token']->render()?>
</form>
