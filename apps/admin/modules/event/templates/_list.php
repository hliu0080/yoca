<div class="pull-left">
	<?php print $sf_user->hasFlash('notify')?$sf_user->getFlash('notify'):''?>
	<?php print $sf_user->hasFlash('register')?$sf_user->getFlash('register'):''?>
	<?php print $sf_user->hasFlash('cancel')?$sf_user->getFlash('cancel'):''?>
	<?php print $sf_user->hasFlash('delte')?$sf_user->getFlash('delete'):''?>
	<?php print $sf_user->hasFlash('confirm')?$sf_user->getFlash('confirm'):''?>
</div>

<?php if($type != 'my'):?>
<div class="pull-right">
	<form style="background: none !important" action="<?php print url_for('search_events')?>" method="post">
		<div class="controls">
			<input class="input-medium search-query" type="text" name="keyword" value="<?php print $keyword?>" placeholder="Mentor ID, Industry"/>
			<a href="<?php print url_for('@manage_events?type='.$type)?>" class="search_remove"><span class="awe-remove"></span></a>
			<input type="hidden" value="<?php print $type?>" name="type" />
			<input class="btn btn-flat" type="submit" value="Search" />
		</div>
	</form>
</div>
<?php endif?>


<table class="table table-striped table-bordered" id="<?php print strtolower($type).'_eventtable'?>">
  <thead>
    <tr>
      <th>Time</th>
      <th>Topic</th>
      <th>Industry</th>
      <th>Mentor</th>
      <th>Booked / Capacity</th>
      <th>Neighborhood</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  	<?php if($total):?>
    	<?php foreach ($events as $event): ?>
		<?php $reg = Doctrine_Core::getTable('Registration')->getMenteeEventRegs($event['id'], $sf_user->getAttribute('userid'), 1)?>
	    <tr>
	    	<?php if($type=='my' || $sf_user->getAttribute('usertype')=='Admin' || count($reg)>0):?>
		    	<td><?php print link_to(date("m/d/Y H:i", strtotime($event['datetime'])), 'show_event', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword, 'id'=>$event['id']))?></td>
		    <?php else:?>
		    	<td><?php print date("m/d/Y H:i", strtotime($event['datetime']))?></td>
		    <?php endif?>
	      	<td><?php echo $event['EventTopic']['id']==8?$event['topic']:$event['EventTopic']['name'] ?></td>
	      	<td><?php echo $event['YocaIndustry']['name'] ?></td>
	      	<td><?php echo $event['YocaUser']['mentor_id']?></td>
<!-- 	      	<td><?php //echo link_to($event['YocaUser']['mentor_id'], 'show_user', array('type'=>'Mentor', 'id'=>$event['mentorid'])) ?></td> -->
	      	<td><?php echo $event['booked'] . " / " .$event['capacity'] ?></td>
	      	<td><?php echo $event['YocaNeighborhood']['name'] ?></td>
	      	
	      	<!-- Status -->
	      	<td>
	      	<?php if($event['status'] == 0):?>
	      		<span class="label label-warning">Pending</span>
	      	<?php elseif($event['status'] == 1):?>
	      		<?php if($type == 'upcoming'):?>
	      			<?php if(strtotime($event->getDatetime()) < time()+60*60*24):?>
	      				Registration Closed
	      			<?php elseif($event['capacity'] > $event['booked']):?>
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
	      			<?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
	      				<!-- TODO: -->
	      				<?php print ("registered && not finished")?"Pending Survey":"Finished"?>
	      			<?php else:?>
		      			Closed
	      			<?php endif?>
	      		<?php elseif($type == 'my'):?>
	      			<?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
	      				<?php if(strtotime($event['datetime']) > time()):?>
		      				Registered
		      			<?php elseif('registration status pending survey'):?>
		      				Pending Survey
		      			<?php else:?>
							Completed
		      			<?php endif?>		
	      			<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
	      				Confirmed
	      			<?php endif?>
	      		<?php endif?>
	      	<?php elseif($event['status'] == 2):?>
	      		Cancelled
	      	<?php endif?>
	      	</td>

	      	<!-- Actions -->
	      	<td class="toolbar">
	      		<div class="btn-group">
			      	<?php if($sf_user->getAttribute('usertype') == 'Mentee'):?>
			      		<?php if($type == 'upcoming' || $type == 'my'):?>
				      		<?php if($event['status'] == 1):?>
				      			<?php if(strtotime($event->getDatetime()) > time()+60*60*24):?>
					      			<?php if(count($reg) > 0):?>
							      		<?php print link_to('View', 'show_event', array('id'=>$event['id'], 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('class'=>'btn btn-small'))?>
					      				<?php print link_to('Cancel', 'cancel_register', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
					      			<?php elseif($event['capacity']>$event['booked'] && $sf_user->getAttribute('userregcounter')<sfConfig::get('app_const_reg_cap')):?>
					      				<?php print link_to('Register', 'register_event', array('eventId'=>$event->getId(), 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
					      			<?php elseif($event['capacity']>$event['booked'] && $sf_user->getAttribute('userregcounter')>=sfConfig::get('app_const_reg_cap')):?>
					      				<a class='btn btn-small disabled popup' data-content='Sorry, you have reached the max of 2 events per month' disabled>Register</a>
					      			<?php elseif($event['capacity'] <= $event['booked']):?>
					      				<?php print link_to('Notify Me When Available', 'signup_event_notify', array('eventId'=>$event['id'], 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
					      			<?php endif?>
					      		<?php endif?>
				      		<?php endif?>
			      		<?php elseif($type == 'past'):?>
			      			<?php if($event['status'] == 1):?>
			      				<?php if(count($reg) > 0 && "pending survey"):?>
			      					Survey
			      				<?php endif?>
			      			<?php endif?>
			      		<?php endif?>
			      	<?php elseif($sf_user->getAttribute('usertype') == 'Mentor'):?>
			      		<?php if($type == 'my'):?>
			      			<?php print link_to('View', 'show_event', array('id'=>$event['id'], 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('class'=>'btn btn-small'))?>
			      			<?php if($event['status'] == 0):?>
			      				<?php print link_to('Delete', 'set_event_status', array('id'=>$event['id'], 'status'=>3, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			      			<?php elseif($event['status'] == 1):?>
			      				<?php print link_to('Cancel', 'set_event_status', array('id'=>$event['id'], 'status'=>2, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			      			<?php endif?>
			      		<?php endif?>
			      	<?php elseif($sf_user->getAttribute('usertype') == 'Admin'):?>
			      		<?php print link_to('View', 'show_event', array('id'=>$event['id'], 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('class'=>'btn btn-small'))?>
			      		<?php if($type == 'pending'):?>
			      			<?php if(strtotime($event['datetime']) > time()+60*60*24):?>
			      				<?php print link_to('Confirm', 'set_event_status', array('id'=>$event['id'], 'status'=>1, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			      			<?php else:?>
			      				<a class='btn btn-small disabled popup' data-content='Sorry, event happening within 24hr cannot be confirmed' disabled>Confirm</a>
			      			<?php endif?>
			      			<?php print link_to('Delete', 'set_event_status', array('id'=>$event['id'], 'status'=>3, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			      		<?php elseif($type == 'upcoming'):?>
			      			<?php if($event->getStatus() == 1):?>
			      				<?php print link_to('Cancel', 'set_event_status', array('id'=>$event['id'], 'status'=>2, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
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
	
	<?php if($type != 'my'):?>
	<div class="span6 text-right">
		<a class="btn btn-small btn-flat <?php print $page-1>0?'':'disabled'?>" href="<?php print $page-1>0?url_for('@manage_events?type='.$type.'&page='.($page-1).'&keyword='.$keyword):'#'?>"><span class="awe-caret-left"></span></a>
		<?php print $total?"$page of $pages":""?>
		<a class="btn btn-small btn-flat <?php print $page+1>$pages?'disabled':''?>" href="<?php print $page+1>$pages?'#':url_for('@manage_events?type='.$type.'&page='.($page+1).'&keyword='.$keyword)?>"><span class="awe-caret-right"></span></a>
	</div>
	<?php endif?>
</div>

<script>
$(document).ready(function(){
	$('.popup').popover({
		trigger: 'hover'
	});
});
</script>