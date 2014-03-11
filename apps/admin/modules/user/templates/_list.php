<table class="datatable table table-striped table-bordered" id="<?php print strtolower($type).'_usertable'?>">
  <thead>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Name</th>
      <?php if($type == 'Mentor'):?>
	  	<th>Education</th>
	  	<th>Work Experience</th>
      	<th>Employer</th>
      	<th>Status</th>
      	<th>Industry</th>
      <?php elseif($type == 'Mentee'):?>
      	<th>Education</th>
      	<th>School</th>
      	<th>Major</th>
      <?php elseif($type == 'Member'):?>
      	<th>Education</th>
      	<th>Status</th>
      <?php elseif($type == 'Admin'):?>
      	<th>Type</th>
      <?php endif?>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
    	<td><a href="<?php print url_for('/user/show/?id='.$user->get('id').'&type='.$type)?>"><?php print $user->get('id')?></a></td>
    	<td><?php print $user->get('username')?></td>
    	<td><?php if(strlen($user->get('lastname'))>0 && strlen($user->get('firstname'))>0) print $user->get('lastname').", ".$user->get('firstname')?></td>
    	<?php if($type == 'Mentor'):?>
	    	<td><?php print $user->get('education')?></td>
	    	<td><?php print sfConfig::get('app_profile_mentor_work_experience')[$user->get('work')]?></td>
	    	<td><?php print $user->get('employer')?></td>
	    	<td><?php print $user->get('is_active')?'Confirmed':'<span class="label label-warning">Pending</span>'?></td>
	    	<td><?php print $user->get('industry')?></td>
    	<?php elseif($type == 'Mentee'):?>
    		<td><?php print $user->get('education')?></td>
	    	<td><?php print $user->get('school')?></td>
	    	<td><?php print $user->get('major')?></td>
    	<?php elseif($type == 'Member'):?>
    		<td><?php print $user->get('education')?></td>
    		<td><?php print $user->get('is_active')?'Confirmed':'Pending'?></td>
	    <?php elseif($type == 'Admin'):?>
	    	<td><?php print $user->get('type')?></td>
    	<?php endif?>
    		<td class="toolbar">
				<div class="btn-group">
		    		<?php print link_to('View', 'user/show?id='.$user->get('id').'&type='.$type, array('class'=>'btn btn-small'))?>
	    		<?php print $user->get('is_active')?'':link_to('Activate', 'user/activate?id='.$user->get('id'), array('confirm' => 'Are you sure?', 'class'=>'btn btn-small'))?>
			</div>
		</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="row">
	<div class="span6">
		<div class="dataTables_info"><?php print "Page $page, total $pages"?></div>
	</div>
	<div class="span6">
		<div class="dataTables_paginate pagination">
			<ul>
				<li class="<?php print $prev>0?'':'disabled'?>"><a href="<?php print $prev>0?url_for('@manage_users?type='.$type.'&page='.$prev):'#'?>"><span class="awe-caret-left"></span>Prev</a></li>
				<?php for($i=1; $i<=intval($pages); $i++):?>
					<li class="<?php print $i==$page?'active':''?>"><a href="<?php print url_for('@manage_users?type='.$type.'&page='.$i)?>"><?php print $i?></a></li>
				<?php endfor?>
				<li class="<?php print $next>$pages?'disabled':''?>"><a href="<?php print $next>$pages?'':url_for('@manage_users?type='.$type.'&page='.$next)?>"><span class="awe-caret-right"></span>Next</a></li>
			</ul>
		</div>
	</div>
</div>