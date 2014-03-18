<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>View Event</h1>
		</div>
		<div class="page-container">
			<div class="row">
				<div class="span12">
					<?php print $sf_user->getAttribute('usertype')=='Admin'?link_to('Back to list', 'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword)):link_to('Back to list', 'mentor_manage_event')?>
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
			      <td><?php echo $event->getNeighborhood() ?></td>
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
			      <td><?php echo $event->getStatus()?'Confirmed':'<span class="label label-warning">Pending</span>' ?></td>
			    </tr>
			  </tbody>
			</table>

			<div class="row">
				<div class="span6">
					<?php if($sf_user->getAttribute('usertype') != 'Admin'):?>
						<a href="<?php print url_for('@mentor_manage_event')?>">Back to list</a>
					<?php else:?>	
						<?php print $sf_user->getAttribute('usertype')=='Admin'?link_to('Back to list', 'manage_events', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword)):link_to('Back to list', 'mentor_manage_event')?>
					<?php endif?>
				</div>
				<div class="span6">
					<?php if($sf_user->getAttribute('usertype') == 'Admin'):?>
						<?php if($event->getStatus() == 0):?>	
							<a href="<?php echo url_for('event/edit?id='.$event->getId())?>" class='btn btn-success btn-wuxia'>Edit</a>
						<?php elseif($event->getStatus() == 1):?>
							<a href="" class='btn btn-danger btn-wuxia'>Cancel</a>
						<?php endif?>
						
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentee'):?>
					
					<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
					
					<?php endif?>
					
					<?php if($event->getStatus()==0):?>
						<?php echo link_to('Delete', 'event/delete?id='.$event->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?', 'class'=>'btn btn-danger btn-wuxia')) ?>
					<?php endif?>
				</div>
			</div>
		</div>
	</div>

</div>