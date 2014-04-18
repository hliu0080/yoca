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
			      <th>ID</th>
			      <td><?php echo $event->getId() ?></td>
			    </tr>
			    <tr>
			      <th>Industry</th>
			      <td><?php echo $event->getYocaIndustry()->getName() ?></td>
			    </tr>
			    <tr>
			      <th>Mentor ID</th>
			      <td><?php echo $event->getMentorid() ?></td>
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
			      <td><?php echo Doctrine_Core::getTable('YocaNeighborhood')->find($event->getNeighborhood()) ?></td>
			    </tr>
			    <tr>
			      <th>Address</th>
			      <td><?php echo $event->getAddress() ?></td>
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
			      	<?php $reg = Doctrine_Core::getTable('Registration')->getMenteeRegs($event->getId(), $sf_user->getAttribute('userid'), 1)?>
			      	<?php if($event->getStatus() == 0):?>
			      		Pending
			      	<?php elseif($event->getStatus() == 1):?>
			      		<?php if($type == 'upcoming' || $type == 'my'):?>
			      			<?php if(strtotime($event->getDatetime()) < time()+60*60*24):?>
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
			      			<?php if($sf_user->getAttribute('usertype' == 'Mentee')):?>
			      				<!-- TODO: -->
			      				<?php print ("registered && not finished")?"Pending Survey":"Finished"?>
			      			<?php else:?>
				      			Closed
			      			<?php endif?>
			      		<?php endif?>
			      	<?php elseif($event->getStatus() == 2):?>
			      		Cancelled
			      	<?php endif?>
			      </td>
			    </tr>
			  </tbody>
			</table>

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
				<div class="span6">
					<?php if($sf_user->getAttribute('usertype') == 'Admin'):?>
						<?php if($type == 'pending'):?>
							<a href="<?php echo url_for('event/edit?id='.$event->getId())?>" class='btn btn-wuxia'>Edit</a>
			      			<?php if(strtotime($event->getDatetime()) > time()+60*60*24):?>
			      				<?php echo link_to('Confirm', 'set_event_status', array('id'=>$event->getId(), 'status'=>1, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia')) ?>
			      			<?php endif?>
			      			<?php echo link_to('Delete', 'set_event_status', array('id'=>$event->getId(), 'status'=>3, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia')) ?>
			      		<?php elseif($type == 'upcoming'):?>
			      			<?php if($event->getStatus() == 1):?>
			      				<?php echo link_to('Cancel', 'set_event_status', array('id'=>$event->getId(), 'status'=>2, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia')) ?>
			      			<?php endif?>
			      		<?php endif?>
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentee'):?>
						<?php if($type == 'upcoming' || $type == 'my'):?>
							<?php $reg = Doctrine_Core::getTable('Registration')->getMenteeRegs($event->getId(), $sf_user->getAttribute('userid'), 1)?>
							<?php if(strtotime($event->getDatetime())>time()+60*60*24 && $event->getStatus()==1):?>
					      		<?php if(count($reg) > 0):?>
					      			<?php print link_to('Cancel', 'cancel_register', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia'))?>
					      		<?php elseif($event->getCapacity()>$event->getBooked() && $sf_user->getAttribute('userregcounter')<sfConfig::get('app_const_reg_cap')):?>
					      			<?php print link_to('Register', 'register_event', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-wuxia'))?>
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