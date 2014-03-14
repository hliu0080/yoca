<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1><?php print $type?>s</h1>
		</div>
		<div class="page-container">
			<div>
				<?php include_partial('user/list', array('users'=>$users, 'type'=>$type, 'total'=>$total, 'page'=>$page, 'pages'=>$pages, 'keyword'=>$keyword))?>
			</div>
		</div>
	</div>

</div>