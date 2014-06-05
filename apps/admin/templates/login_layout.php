<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/wuxia-red.css">
    <?php include_stylesheets() ?>
    
    <!-- Fav and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/icons/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/icons/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/img/icons/apple-touch-icon-57-precomposed.png">
    
    <!-- JS Libs -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery.js"><\/script>')</script>
    <?php include_javascripts() ?>
  </head>
  <body class="login">
  
    <?php echo $sf_content ?>
    
    <!-- Main footer -->
	<footer class="container footer">
		<nav>
			<ul>
				<li>&copy; 2013 Young Overseas Chinese Association, a 501c3 nonprofit organization.</li>
				<li><a href="mailto:<?php echo sfConfig::get('app_email_support')?>">Support</a></li>
			</ul>
		</nav>
	</footer>
	<!-- /Main footer -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  		ga('create', 'UA-42868336-1', 'yocausa.org');
  		ga('require', 'displayfeatures');
  		ga('send', 'pageview');
	</script>
  </body>
</html>