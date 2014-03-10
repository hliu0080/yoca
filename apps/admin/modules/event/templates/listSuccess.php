<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>
	
	<div class="content">
		<div class="page-header">
			<h1><?php print ucfirst($sf_request->getParameter('type'))?> Events</h1>
		</div>
		<div class="page-container">
			<?php if(count($events) > 0):?>
			<table>
			  <thead>
			    <tr>
			      <th>Event ID</th>
			      <th>Industry</th>
			      <th>Mentor ID</th>
			      <th>Capacity</th>
			      <th>Booked</th>
			      <th>Datetime</th>
			      <th>Neighborhood</th>
			      <th>Address</th>
			      <th>Status</th>
			      <?php if(($sf_user->getAttribute('usertype')=='Admin' && $sf_request->getParameter('type') == 'pending') || ($sf_user->getAttribute('usertype')=='Mentee')):?>
			      	<th>Actions</th>
			      <?php endif?>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($events as $event): ?>
			    <tr>
			    	<?php if($sf_user->getAttribute('usertype') == 'Admin' && $sf_request->getParameter('type') == 'pending'):?>
			    	  <td><a href="<?php print url_for('/event/show/?id='.$event->getId())?>"><?php print $event->getId()?></a>
			    	<?php else:?>
				      <td><?php echo $event->getId() ?></td>
			    	<?php endif?>
			      <td><?php echo $event->getYocaIndustry()->getName() ?></td>
			      <td><?php echo $event->getMentorid() ?></td>
			      <td><?php echo $event->getCapacity() ?></td>
			      <td><?php echo $event->getBooked() ?></td>
			      <td><?php echo date("m/d/Y H:i", strtotime($event->getDatetime())) ?></td>
			      <td><?php echo $event->getNeighborhood() ?></td>
			      <td><?php echo $event->getAddress() ?></td>
			      <?php if($sf_request->getParameter('type') == 'upcoming'):?>
			      	  <td><?php echo $event->getCapacity()==$event->getBooked()?'Full':'Available' ?></td>
				      <?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
				      	<?php if(count(RegistrationTable::getMenteeRegistrations($event->getId(), $sf_user->getAttribute('userid'))) > 0):?>
				      		<td><?php print link_to('Cancel', 'register/cancel?eventId='.$event->getId())?></td>
				      	<?php elseif($event->getCapacity()>$event->getBooked()):?>
				      		<td><?php print link_to('Register', 'register/register?eventId='.$event->getId())?></td>
				      	<?php else:?>
				      		<td><?php print link_to('Notify me when available', 'register/notify?eventId='.$event->getId())?></td>
				      	<?php endif?>
			      	  <?php else:?>
			      	  <td></td>
			      	  <?php endif?>
			      <?php elseif($sf_request->getParameter('type') == 'past'):?>
			      	  <td>Closed</td>
			      	  <td></td>
			      <?php elseif($sf_request->getParameter('type') == 'pending'):?>
			      	  <td>Pending</td>
			      	  <?php if($sf_user->getAttribute('usertype') == 'Admin'):?>
			      	  <td><?php echo link_to('Confirm', 'event/activate?id='.$event->getId(), array('confirm' => 'Are you sure?'))?></td>
			      	  <?php else:?>
			      	  <td></td>
			      	  <?php endif?>
			      <?php endif?>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			<?php else:?>
				<p>There's no <?php print sfContext::getInstance()->getRequest()->getParameter('type')?> events</p>
			<?php endif?>
		</div>
	</div>


</div>