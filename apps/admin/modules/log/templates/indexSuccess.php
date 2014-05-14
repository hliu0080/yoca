<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>View Logs</h1>
		</div>
		<div class="page-container">
			<?php print $sf_data->getRaw('content')?>
		</div>
	</div>

</div>
