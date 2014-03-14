<div class="pull-right">
	<form style="background: none !important" action="<?php print url_for('search_events')?>" method="post">
		<div class="controls">
			<input class="input-medium search-query" type="text" name="keyword" value="<?php print $keyword?>" placeholder="ID, Industry, Mentor ID"/>
			<a href="<?php print url_for('@manage_events?type='.$type)?>" class="search_remove"><span class="awe-remove"></span></a>
			<input type="hidden" value="<?php print $type?>" name="type" />
			<input class="btn btn-flat" type="submit" value="Search" />
		</div>
	</form>
</div>

<table class="table table-striped table-bordered" id="<?php print strtolower($type).'_eventtable'?>">
  <thead>
    <tr>
      <th>ID</th>
      <th>Industry</th>
      <th>Mentor ID</th>
      <th>Capacity</th>
      <th>Booked</th>
      <th>Time</th>
      <th>Neighborhood</th>
      <th>Address</th>
      <th>Status</th>
      <?php if(($sf_user->getAttribute('usertype')=='Admin' && $sf_request->getParameter('type') == 'pending') || ($sf_user->getAttribute('usertype')=='Mentee')):?>
      	<th>Actions</th>
      <?php endif?>
    </tr>
  </thead>
  <tbody>
  	<?php if($total):?>
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
  	<?php endif?>
  </tbody>
</table>
<div class="row">
	<div class="span6"><?php print "$total " .(($total>1)?'records':'record'). " found"?></div>
	<div class="span6 text-right">
		<a class="btn btn-small btn-flat <?php print $page-1>0?'':'disabled'?>" href="<?php print $page-1>0?url_for('@manage_events?type='.$type.'&page='.($page-1).'&keyword='.$keyword):'#'?>"><span class="awe-caret-left"></span></a>
		<?php print $total?"$page of $pages":""?>
		<a class="btn btn-small btn-flat <?php print $page+1>$pages?'disabled':''?>" href="<?php print $page+1>$pages?'#':url_for('@manage_events?type='.$type.'&page='.($page+1).'&keyword='.$keyword)?>"><span class="awe-caret-right"></span></a>
	</div>
</div>