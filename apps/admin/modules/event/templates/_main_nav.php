	<!-- Main navigation bar -->
	<header class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">

<!-- 				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="awe-user"></span></button> -->
<!-- 				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="awe-chevron-down"></span></button> -->

				<a class="brand" href="/"><img class="logo" src="/img/yoca/square-logo-rounded.png" /></a>

				<div class="nav-collapse">
				
					<!-- Main navigation -->
					<nav class="navigation">
						<ul class="nav active-arrows" role="navigation">
							<li <?php print $sf_user->getAttribute('cur_page')=='home'?'class="active"':''?>>
								<a href="<?php print url_for('homepage')?>">
									<span class="awe-home"></span>
									Home
								</a>
							</li>
							<?php foreach($sections as $s):?>
								<?php if(preg_replace('/\s+/', "_", strtolower($s)) == 'mentorship_program' || preg_replace('/\s+/', "_", strtolower($s)) == 'manage_events'):?>
									<li <?php print $sf_user->getAttribute('cur_page')==preg_replace('/\s+/', "_", strtolower($s))?'class="active dropdown"':'class="dropdown"'?> >
										<a class="dropdown-toggle" data-toggle="dropdown" href="<?php print url_for(preg_replace('/\s+/', "_", strtolower($s)))?>">
											<div><?php print $s?></div>
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu pull-right">
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
												<li><a href="<?php print url_for('@mentor_manage_event?type=my')?>">My Events</a></li>
											<?php elseif($sf_user->getAttribute('usertype') == 'Mentee'):?>
												<li><a href="<?php print url_for('@manage_events?type=upcoming')?>">Upcoming Events</a></li>
												<li><a href="<?php print url_for('@manage_events?type=past')?>">Past Events</a></li>
												<li><a href="<?php print url_for('@mentee_manage_event?type=my')?>">My Events</a></li>
											<?php endif?>
										</ul>
									</li>
								<?php elseif(preg_replace('/\s+/', "_", strtolower($s)) == 'manage_users'):?>
									<li <?php print $sf_user->getAttribute('cur_page')==preg_replace('/\s+/', "_", strtolower($s))?'class="active dropdown"':'class="dropdown"'?>>
										<a class="dropdown-toggle" data-toggle="dropdown" href="">
											<div><?php print $s?></div>
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu pull-right">
											<li><a href="<?php print url_for('@manage_users?type=Member')?>">Manage Members</a></li>
											<li><a href="<?php print url_for('@manage_users?type=Mentee')?>">Manage Mentees</a></li>
											<li><a href="<?php print url_for('@manage_users?type=Mentor')?>">Manage Mentors</a></li>
											<li><a href="<?php print url_for('@manage_users?type=Admin')?>">Manage Admins</a></li>
										</ul>
									</li>
								<?php else:?>
									<li><a href="<?php print url_for(preg_replace('/\s+/', "_", strtolower($s)))?>"><?php print $s?></a></li>
								<?php endif?>
							<?php endforeach;?>
						</ul>
					</nav>
					
					<!-- User navigation -->
					<nav class="user">
						<div class="user-info pull-right">
<!-- 							<img src="http://placekitten.com/35/35" alt="User avatar"> -->
							<div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
									<div><strong><?php print $sf_user->getAttribute('username')?></strong><?php print $sf_user->getAttribute('usertype')?></div>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href='<?php print url_for('auth/logout')?>'><span class="awe-signout"></span> Log out</a></li>
									<li class="divider"></li>
									<li><a href='<?php print url_for('my_account')?>'><span class=""></span> Change Password</a></li>
								</ul>
							</div>
						</div>
					</nav>

				</div>
			</div>
		</div>
	</header>
	<!-- /Main navigation bar -->  