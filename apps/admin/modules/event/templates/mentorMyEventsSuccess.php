<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">

	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Mentorship Program</h1>
		</div>
		<div class="page-container">
			<h2>Create New Event</h2>
			<?php include_partial('form', array('form' => $form, 'type'=>$type, 'total'=>$total, 'page'=>$page, 'pages'=>$pages, 'keyword'=>$keyword)) ?>
			
			<hr />
			
			<h2>My Events</h2>
			<?php include_partial('event/list', array('events'=>$events, 'type'=>$type, 'total'=>$total, 'page'=>$page, 'pages'=>$pages, 'keyword'=>$keyword))?>
			
		</div>
	</div>
</div>