<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
	    <?php include_http_metas() ?>
	    <?php include_metas() ?>
	    <?php include_title() ?>
	    <link rel="shortcut icon" href="/favicon.ico" />

		<!-- CSS styles -->
		<link rel='stylesheet' type='text/css' href='/css/wuxia-red.css'>
		<?php include_stylesheets() ?>
		
		<!-- Fav and touch icons -->
		<link rel="shortcut icon" href="/img/icons/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/icons/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/icons/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="/img/icons/apple-touch-icon-57-precomposed.png">
		
	    <!-- JS Libs -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/js/libs/jquery.js"><\/script>')</script>
	    <?php include_javascripts() ?>

		<script>
			$(document).ready(function(){
				
				// Tooltips
				$('[title]').tooltip({
					placement: 'top'
				});
				
			});
		</script>
	</head>
	<body class='login'>

		<!--  content -->
		<?php echo $sf_content ?>
		<!--  content -->
		
		<!-- Scripts -->
		<script src="/js/bootstrap/bootstrap-tooltip.js"></script>
		
		<footer class="container footer">
			<nav>
				<ul>
					<li>&copy; 2013 Young Overseas Chinese Association, a 501c3 nonprofit organization.</li>
					<li><a href="mailto:<?php echo sfConfig::get('app_email_support')?>">Support</a></li>
				</ul>
			</nav>
		</footer>
	</body>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  		ga('create', 'UA-42868336-1', 'yocausa.org');
  		ga('require', 'displayfeatures');
  		ga('send', 'pageview');
	</script>
</html>