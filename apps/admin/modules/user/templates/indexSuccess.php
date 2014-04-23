<!-- Homepage -->

<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="hero-unit blow">
		<h1>Welcome to YOCA Member</h1>
	</div>

	<div class="content">
		<div class="page-container">
			<div class="nav-secondary inverse">
				<nav>
					<ul>
						<?php foreach($sections as $s):?>
							<li><a class="wuxify-me" href='<?php print url_for(preg_replace('/\s+/', "_", strtolower($s)))?>'><span class="awe-th"></span><?php print $s?></a></li>
						<?php endforeach?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>