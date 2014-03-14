<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Mentorship Program</h1>
		</div>
		<div class="page-container">
			<ul>
				<?php if($sf_user->getAttribute('usertype') == 'Member'):?>
					<li><a href="<?php print url_for('@become_a_mentee')?>">Become a Mentee</a></li>
					<li><a href="<?php print url_for('@become_a_mentor')?>">Become a Mentor</a></li>
				<?php elseif($sf_user->getAttribute('usertype') == 'Admin'):?>
					<li><a href="<?php print url_for('@manage_events?type=upcoming')?>">Upcoming Events</a></li>
					<li><a href="<?php print url_for('@manage_events?type=past')?>">Past Events</a></li>
					<li><a href="<?php print url_for('@manage_events?type=pending')?>">Pending Events</a></li>
				<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
					<li><a href="<?php print url_for('@manage_events?type=upcoming')?>">Upcoming Events</a></li>
					<li><a href="<?php print url_for('@manage_events?type=past')?>">Past Events</a></li>
					<li><a href="<?php print url_for('event/mentorMyEvents')?>">My Events</a></li>
				<?php elseif($sf_user->getAttribute('usertype') == 'Mentee'):?>
					<li><a href="<?php print url_for('@manage_events?type=upcoming')?>">Upcoming Events</a></li>
					<li><a href="<?php print url_for('@manage_events?type=past')?>">Past Events</a></li>
					<li><a href="<?php print url_for('event/menteeMyEvents')?>">My Events</a></li>
				<?php endif?>	
				
			</ul>
		</div>
	</div>

</div>

