<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>
	
	<div class="content">
		<div class="page-container">
			<h2>Congratulations! You have just become a YOCA mentee</h2>
			<div class="row" style="margin-top:15px;margin-bottom:30px">
				<div class="span12">
					<h1 style="margin-bottom:30px"><small>Check out the mentorship events we are hosting in your neighborhood <span class="awe-circle-arrow-right"></span></small></h1>
					<a href="<?php print url_for('@manage_events?type=upcoming')?>" class="btn btn-large btn-primary btn-wuxia">View upcoming events</a>
				</div>
			</div>
			
		</div>
	</div>

</div>

