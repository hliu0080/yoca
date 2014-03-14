<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Edit Event</h1>
		</div>
		<div class="page-container">
			<div class="row">
				<div class="span12">
					<?php print $sf_user->getAttribute('usertype')=='Admin'?link_to('Back to list', 'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword)):link_to('Back to list', 'mentor_manage_event')?>
				</div>
			</div>
		
			<?php include_partial('form', array('form' => $form)) ?>
		</div>
	</div>

</div>
