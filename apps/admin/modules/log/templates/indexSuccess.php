<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>View Logs</h1>
		</div>
		<div class="page-container">
			<?php 
				$file_handle = fopen(dirname(__FILE__).'/../../../../../log/admin_dev.log', "r");
				$count = 0;
				while (!feof($file_handle) && $count<10) {
					$line = fgets($file_handle);
					echo "<pre>$line</pre>";
				}
				fclose($file_handle);
			
			?>
		
		</div>
	</div>

</div>
