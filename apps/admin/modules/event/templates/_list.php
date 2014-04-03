<div class="pull-left">
	<?php print $sf_user->hasFlash('notify')?$sf_user->getFlash('notify'):''?>
	<?php print $sf_user->hasFlash('register')?$sf_user->getFlash('register'):''?>
	<?php print $sf_user->hasFlash('cancel')?$sf_user->getFlash('cancel'):''?>
</div>


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
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  	<?php if($total):?>
    	<?php foreach ($events as $event): ?>
	    <tr>
		    <td><?php print link_to($event->getId(), 'show_event', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword, 'id'=>$event->getId()))?></td>
	      	<td><?php echo $event->getYocaIndustry()->getName() ?></td>
	      	<td><?php echo $event->getMentorid() ?></td>
	      	<td><?php echo $event->getCapacity() ?></td>
	      	<td><?php echo $event->getBooked() ?></td>
	      	<td><?php echo date("m/d/Y H:i", strtotime($event->getDatetime())) ?></td>
	      	<td><?php echo $event->getNeighborhood() ?></td>
	      	<td><?php echo $event->getAddress() ?></td>
	      	
	      	<!-- Status -->
	      	<td>
      					<?php $reg = Doctrine_Core::getTable('Registration')->getMenteeRegs($event->getId(), $sf_user->getAttribute('userid'), 1)?>
	      	<?php if($event->getStatus() == 0):?>
	      		Pending
	      	<?php elseif($event->getStatus() == 1):?>
	      		<?php if($type == 'upcoming'):?>
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

	      	<!-- Actions -->
	      	<td class="toolbar">
	      		<div class="btn-group">
		      		<?php print link_to('View', 'show_event', array('id'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('class'=>'btn btn-small'))?>
			      	<?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
			      		<?php if($type == 'upcoming'):?>
				      		<?php if($event->getStatus() == 1):?>
				      			<?php if(strtotime($event->getDatetime()) > time()+60*60*24):?>
					      			<?php if(count($reg) > 0):?>
					      				<?php print link_to('Cancel', 'cancel_register', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
					      			<?php elseif($event->getCapacity() > $event->getBooked()):?>
					      				<?php print link_to('Register', 'register_event', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
					      			<?php elseif($event->getCapacity() <= $event->getBooked()):?>
					      				<?php print link_to('Notify Me When Available', 'signup_event_notify', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
					      			<?php endif?>
					      		<?php endif?>
				      		<?php endif?>
			      		<?php elseif($type == 'past'):?>
			      			<?php if($event->getStatus() == 1):?>
			      				<?php if(count($reg) > 0 && "pending survey"):?>
			      					Survey
			      				<?php endif?>
			      			<?php endif?>
			      		<?php endif?>
			      	<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
			      	
			      	<?php elseif($sf_user->getAttribute('usertype') == 'Admin'):?>
			      		<?php if($type == 'pending'):?>
			      			<?php if(strtotime($event->getDatetime()) > time()+60*60*24):?>
			      				<?php print link_to('Confirm', 'set_event_status', array('id'=>$event->getId(), 'status'=>1, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			      			<?php endif?>
			      			<?php print link_to('Delete', 'set_event_status', array('id'=>$event->getId(), 'status'=>3, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			      		<?php elseif($type == 'upcoming'):?>
			      			<?php if($event->getStatus() == 1):?>
			      				<?php print link_to('Cancel', 'set_event_status', array('id'=>$event->getId(), 'status'=>2, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
							<?php endif?>
			      		<?php endif?>
			      	<?php endif?>
			      	
			      	

		      	</div>
	      	</td>
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