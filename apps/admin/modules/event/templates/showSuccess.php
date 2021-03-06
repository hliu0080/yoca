<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1><?php print ucfirst($type)?> Event</h1>
		</div>
		<div class="page-container">
			<div class="row">
				<div class="span12">
					<?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
						<?php print link_to('Back to list', $type=='my'?'mentee_manage_event':'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
						<?php print link_to('Back to list', $type=='my'?'mentor_manage_event':'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
					<?php else:?>
						<?php print link_to('Back to list', 'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
					<?php endif?>
				</div>
			</div>
			
		
			<table class="table table-bordered detail_table">
			  <tbody>
			    <tr>
			    	<th>Topic</th>
			    	<td><?php echo $event->getEventTopic()->getId()==8?$event->getTopic():$event->getEventTopic()->getName() ?></td>
			    </tr>
			    <tr>
			      <th>Industry</th>
			      <td><?php echo $event->getYocaIndustry()->getName() ?></td>
			    </tr>
				<tr>
			      <th>Industry Sub-category</th>
			      <td><?php echo $event->getYocaUser()->getSubIndustry() ?></td>
			    </tr>
			    <tr>
			      <th>Mentor</th>
			      <td><?php echo $event->getYocaUser()->getMentorId() ?></td>
			    </tr>
				<tr>
			      <th>Title</th>
			      <td><?php echo $event->getYocaUser()->getMentorTitle() ?></td>
			    </tr>
			    <tr>
			      <th>Company</th>
			      <td><?php echo $event->getYocaUser()->getEmployer() ?></td>
			    </tr>
			    <tr>
			      <th>Capacity</th>
			      <td><?php echo $event->getCapacity() ?></td>
			    </tr>
			    <tr>
			      <th>Booked</th>
			      <td><?php echo $event->getBooked() ?></td>
			    </tr>
			    <tr>
			      <th>Time</th>
			      <td><?php echo date("m/d/Y H:i", strtotime($event->getDatetime())) ?></td>
			    </tr>
			    <tr>
			      <th>Neighborhood</th>
			      <td><?php echo $event->getYocaNeighborhood()->getName() ?></td>
			    </tr>
			    <tr>
			      <th>Address</th>
			      <td><?php echo $event->getEventAddress()->getId()==18?$event->getAddress():$event->getEventAddress()->getName() ?></td>
			    </tr>
			    <tr>
			      <th>Created At</th>
			      <td><?php echo $event->getCreatedAt() ?></td>
			    </tr>
			    <tr>
			      <th>Updated At</th>
			      <td><?php echo $event->getUpdatedAt() ?></td>
			    </tr>
			    <tr>
			      <th>Status</th>
			      <!-- TODO: -->
			      <!-- 0 - pending, 1 - confirmed, 2 - cancelled, 3 - deleted -->
			      <td>
			      	<?php $reg = Doctrine_Core::getTable('Registration')->getMenteeEventRegs($event->getId(), $sf_user->getAttribute('userid'), 1)?>
			      	<?php if($event->getStatus() == 0):?>
			      		Pending
			      	<?php elseif($event->getStatus() == 1):?>
			      		<?php if($type == 'upcoming' || $type == 'my'):?>
			      			<?php if(strtotime($event->getDatetime()) < time()):?>
			      				Closed
			      			<?php elseif(strtotime($event->getDatetime()) < time()+60*60*24):?>
			      				Registration Closed
			      			<?php elseif($event->getCapacity() > $event->getBooked()):?>
			      				Available
		      					<?php if(count($reg)>0):?>
		      						/ Registered
		      					<?php endif?>
			      			<?php else:?>
			      				Full
		      					<?php if(count($reg)>0):?>
		      						/ Registered
		      					<?php endif?>
			      			<?php endif?>
			      		<?php elseif($type == 'past'):?>
				      		Closed
			      		<?php endif?>
			      	<?php elseif($event->getStatus() == 2):?>
			      		Cancelled
			      	<?php endif?>
			      </td>
			    </tr>
			  </tbody>
			</table>
			
			<?php if(isset($registrations) && !is_null($registrations) && count($registrations)>0):?>
			<table class="table table-bordered">
				<thead>
					<tr><th>Mentee</th><th>Email</th><th>School</th><th>Major</th><th>Work Experience</th><th>Registered At</th></tr>
				</thead>
				<tbody>
					<?php foreach($registrations as $registration):?>
						<?php if($registration->getStatus() == 1):?>
						<tr>
							<td><?php echo $registration->getYocaUser()->getLastname().", ".$registration->getYocaUser()->getFirstname()?></td>
							<td><?php echo $registration->getYocaUser()->getUsername()?></td>
							<td><?php echo $registration->getYocaUser()->getSchoolId()==25?$registration->getYocaUser()->getSchool():$registration->getYocaUser()->getYocaUserSchool()->getName()?></td>
							<td><?php echo $registration->getYocaUser()->getMajorId()==19?$registration->getYocaUser()->getMajor():$registration->getYocaUser()->getYocaUserMajor()->getName()?></td>
							<td><?php $workExp = sfConfig::get('app_profile_mentee_work_experience'); print $workExp[$registration->getYocaUser()->getWork()]?></td>
							<td><?php echo $registration->getCreatedAt()?></td>
						</tr>
						<?php endif?>
					<?php endforeach?>
				</tbody>
			</table>
			<?php endif?>

			<div class="row">
				<div class="span6">
					<?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
						<?php print link_to('Back to list', $type=='my'?'mentee_manage_event':'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
						<?php print link_to('Back to list', $type=='my'?'mentor_manage_event':'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
					<?php else:?>
						<?php print link_to('Back to list', 'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
					<?php endif?>
				</div>
				<div class="span6 text-right">
					<?php if($sf_user->getAttribute('usertype') == 'Admin'):?>
						<?php if($type == 'pending'):?>
<!-- TODO: edit event -->						
<!-- 							<a href="<?php //echo url_for('event/edit?id='.$event->getId())?>" class='btn btn-wuxia'>Edit Event</a> -->
			      			<?php if(strtotime($event->getDatetime()) > time()+60*60*24):?>
			      				<?php echo link_to('Confirm Event', 'set_event_status', array('id'=>$event->getId(), 'status'=>1, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia btn-success')) ?>
			      			<?php endif?>
			      			<?php echo link_to('Delete Event', 'set_event_status', array('id'=>$event->getId(), 'status'=>3, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia btn-danger')) ?>
			      		<?php elseif($type == 'upcoming'):?>
			      			<?php if($event->getStatus() == 1):?>
			      				<?php echo link_to('Cancel', 'set_event_status', array('id'=>$event->getId(), 'status'=>2, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia btn-danger')) ?>
			      			<?php endif?>
			      		<?php endif?>
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentee'):?>
						<?php if($type == 'upcoming' || $type == 'my'):?>
							<?php $reg = Doctrine_Core::getTable('Registration')->getMenteeEventRegs($event->getId(), $sf_user->getAttribute('userid'), 1)?>
							<?php if(strtotime($event->getDatetime())>time()+60*60*24 && $event->getStatus()==1):?>
					      		<?php if(count($reg) > 0):?>
					      			<?php print link_to('Cancel', 'cancel_register', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia btn-danger'))?>
					      		<?php elseif($event->getCapacity()>$event->getBooked() && $sf_user->getAttribute('userregcounter')<sfConfig::get('app_const_reg_cap')):?>
					      			<?php print link_to('Register', 'register_event', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia btn-success'))?>
					      		<?php elseif($event->getCapacity()>$event->getBooked() && $sf_user->getAttribute('userregcounter')>=sfConfig::get('app_const_reg_cap')):?>
					      			<a class='btn btn-wuxia disabled popup' data-content='Sorry, you have reached the max of 2 events per month' disabled>Register</a>
					      		<?php elseif($event->getCapacity() <= $event->getBooked()):?>
					      			<?php print link_to('Notify Me When Available', 'signup_event_notify', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia'))?>
					      		<?php endif?>
					      	<?php endif?>
						<?php elseif($type == 'past'):?>
						
						<?php endif?>
						
						
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
					
					<?php endif?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	$('.popup').popover({
		trigger: 'hover'
	});
});
</script>