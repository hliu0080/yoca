	<!-- Breadcrumbs -->
	<ul class="breadcrumb">
		<li><a href="#"><span class="awe-home"></span> Home</a></li>
		<?php foreach($sections as $s):?>
			<a href="<?php print url_for(preg_replace('/\s+/', "_", strtolower($s)))?>"><?php print $s?></a>
		<?php endforeach?>
		<li class="active">Dashboard</li>
	</ul>
	<!-- Breadcrumbs -->