<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>My Events</h1>
		</div>
		<div class="page-container">
			<div>
				<?php include_partial('event/list', array('events'=>$events, 'type'=>'my', 'total'=>$total, 'page'=>$page, 'pages'=>$pages, 'keyword'=>$keyword))?>
			</div>
		</div>
	</div>
</div>