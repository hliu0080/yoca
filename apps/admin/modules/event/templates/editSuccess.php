<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Edit Event</h1>
		</div>
		<div class="page-container">
			<?php if($sf_user->getAttribute('usertype') != 'Admin'):?>
				<a href="<?php print url_for('@mentor_manage_event')?>">Back to My Events</a>
			<?php else:?>	
				<a href="<?php print url_for('event/list?type=pending')?>">Back to Manage Events</a>
			<?php endif?>
		
			<?php include_partial('form', array('form' => $form)) ?>
		</div>
	</div>

</div>
