<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Manage Users</h1>
		</div>
		<div class="page-container">
			<h3><?php print $type?>s</h3>
			<div class="dataTables_wrapper form-inline">
				<?php include_partial('user/list', array('users'=>$users, 'type'=>$type, 'total'=>$total, 'page'=>$page, 'pages'=>$pages, 'prev'=>$prev, 'next'=>$next, 'keyword'=>$keyword))?>
			</div>
		</div>
	</div>

</div>