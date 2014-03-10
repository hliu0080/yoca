<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Mentorship Program</h1>
		</div>
		<div class="page-container">
			<h2>My Events</h2>
			<?php if(count($events) > 0):?>
			<table>
			  <thead>
			    <tr>
			      <th>Id</th>
			      <th>Industry</th>
			      <th>Capacity</th>
			      <th>Booked</th>
			      <th>Datetime</th>
			      <th>Neighborhood</th>
			      <th>Address</th>
			      <th>Status</th>
			      <th>Actions</th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($events as $event): ?>
			    <tr>
			      <td><?php echo $event->getId() ?></td>
			      <td><?php echo $event->getYocaIndustry()->getName() ?></td>
			      <td><?php echo $event->getCapacity() ?></td>
			      <td><?php echo $event->getBooked() ?></td>
			      <td><?php echo date("m/d/Y H:i", strtotime($event->getDatetime())) ?></td>
			      <td><?php echo $event->getNeighborhood() ?></td>
			      <td><?php echo $event->getAddress() ?></td>
			      <td><?php echo sfConfig::get('app_registration_status')[$event->getRegistration()[0]->getStatus()]?></td>
			      <td><?php if($event->getRegistration()[0]->getStatus()==1) echo link_to('Cancel', 'register/cancel?regId=')?><?php if($event->getRegistration()[0]->getStatus()==4) echo link_to('Survey', 'register/survey')?></td>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			<?php else:?>
				<p>You haven't registered for any events.</p>
				<p><a href="<?php print url_for('event/list/?type=upcoming')?>">View upcoming events</a></p>
			<?php endif?>
		</div>
	</div>
</div>