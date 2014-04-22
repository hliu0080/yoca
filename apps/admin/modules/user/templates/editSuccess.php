<!-- My Profile -->

<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class='content'>
		<div class="page-header">
			<h1>Edit Profile<?php echo $sf_user->getAttribute('usertype')=='Mentor'?(' - Mentor '.$form->getObject()->getMentorId()):''?></h1>
		</div>
		<div class="page-container">
			<?php include_partial('form', array('form' => $form)) ?>
		</div>
	</div>

</div>