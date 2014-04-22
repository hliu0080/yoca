<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class='content'>
		<div class="page-header">
			<h1>Change Password</h1>
		</div>
		<div class="page-container">
		
			<?php print $sf_user->hasFlash('msg')?$sf_user->getFlash('msg'):''?>
			<?php print $sf_user->hasFlash('error')?$sf_user->getFlash('error'):''?>
		
			<form action="<?php print url_for('user/doChangePass')?>" method="post" class="form-horizontal">
				<fieldset>
					<?php print $form?>
				</fieldset>
				<div class="form-actions">
	      			<input class="btn btn-wuxia btn-primary" type="submit" value="Update Password"/>
				</div>
			</form>
		</div>
	</div>

</div>