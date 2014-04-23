<header>
	<a href="http://www.yocausa.org" target="_blank"><img src="/img/yoca/square-logo-rounded.png" /></a>
</header>
	
<section>
	<?php include_partial('form', array('form' => $form)) ?>
</section>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#signup_username').focus();
});
</script>