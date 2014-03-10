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
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($events as $event): ?>
			    <tr>
			      <td><a href="<?php echo url_for('event/show?id='.$event->getId()) ?>"><?php echo $event->getId() ?></a></td>
			      <td><?php echo $event->getYocaIndustry()->getName() ?></td>
			      <td><?php echo $event->getCapacity() ?></td>
			      <td><?php echo $event->getBooked() ?></td>
			      <td><?php echo date("m/d/Y H:i", strtotime($event->getDatetime())) ?></td>
			      <td><?php echo $event->getNeighborhood() ?></td>
			      <td><?php echo $event->getAddress() ?></td>
			      <td><?php echo $event->getStatus()==0?'Pending':'Confirmed' ?></td>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			<?php else:?>
				<p>You haven't created any events.</p>
			<?php endif?>
			
			<hr />
			
			<h2>Create Event</h2>
			<?php include_partial('form', array('form' => $form)) ?>
		</div>
	</div>
</div>