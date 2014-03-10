<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>View Event</h1>
		</div>
		<div class="page-container">
			<?php if($sf_user->getAttribute('usertype') != 'Admin'):?>
				<a href="<?php print url_for('@mentor_manage_event')?>">Back to My Events</a>
			<?php else:?>	
				<a href="<?php print url_for('event/list?type=pending')?>">Back to Manage Events</a>
			<?php endif?>
		
			<table>
			  <tbody>
			    <tr>
			      <th>Id:</th>
			      <td><?php echo $event->getId() ?></td>
			    </tr>
			    <tr>
			      <th>Industry:</th>
			      <td><?php echo $event->getYocaIndustry()->getName() ?></td>
			    </tr>
			    <tr>
			      <th>Mentorid:</th>
			      <td><?php echo $event->getMentorid() ?></td>
			    </tr>
			    <tr>
			      <th>Capacity:</th>
			      <td><?php echo $event->getCapacity() ?></td>
			    </tr>
			    <tr>
			      <th>Booked:</th>
			      <td><?php echo $event->getBooked() ?></td>
			    </tr>
			    <tr>
			      <th>Datetime:</th>
			      <td><?php echo date("m/d/Y H:i", strtotime($event->getDatetime())) ?></td>
			    </tr>
			    <tr>
			      <th>Neighborhood:</th>
			      <td><?php echo $event->getNeighborhood() ?></td>
			    </tr>
			    <tr>
			      <th>Address:</th>
			      <td><?php echo $event->getAddress() ?></td>
			    </tr>
			    <tr>
			      <th>Status:</th>
			      <td><?php echo $event->getStatus()==0?'Pending':'Confirmed' ?></td>
			    </tr>
			  </tbody>
			</table>
		
			<hr />
		
			<a href="<?php echo url_for('event/edit?id='.$event->getId()) ?>">Edit</a>
			<?php if($event->getStatus()==0):?>
			<?php echo link_to('Delete', 'event/delete?id='.$event->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
			<?php endif?>
		</div>
	</div>

</div>