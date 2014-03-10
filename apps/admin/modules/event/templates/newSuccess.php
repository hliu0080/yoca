<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<h1>New Event</h1>
	<a href="<?php print url_for('event')?>">Back to Mentorship Program</a>	

	<?php include_partial('form', array('form' => $form)) ?>
</div>
