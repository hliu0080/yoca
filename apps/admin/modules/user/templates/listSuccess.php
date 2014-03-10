<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>Manage Users</h1>
		</div>
		<div class="page-container">
			<?php if(count($users) > 0):?>
			<table>
			  <thead>
			    <tr>
			      <th>Id</th>
			      <th>Username</th>
			      <th>Type</th>
			      <th>Name</th>
			      <th>Education</th>
			      <th>School</th>
			      <th>Major</th>
			      <th>Work Experience</th>
			      <th>Employer</th>
			      <th>Industry</th>
			      <th>Status</th>
			      <th>Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($users as $user): ?>
			    <tr>
			    	<td><a href="<?php print url_for('/user/show/?id='.$user->get('id'))?>"><?php print $user->get('id')?></a></td>
			    	<td><?php print $user->get('username')?></td>
			    	<td><?php print $user->get('type')?></td>
			    	<td><?php print $user->get('lastname').", ".$user->get('firstname')?></td>
			    	<td><?php print $user->get('education')?></td>
			    	<td><?php print $user->get('school')?></td>
			    	<td><?php print $user->get('major')?></td>
			    	<td><?php print $user->get('work')?></td>
			    	<td><?php print $user->get('employer')?></td>
			    	<td><?php print $user->get('industry')?></td>
			    	<td><?php print $user->get('is_active')?'Confirmed':'Pending'?></td>
			    	<td><?php print $user->get('is_active')?'':link_to('Activate', 'user/activate?id='.$user->get('id'), array('confirm' => 'Are you sure?'))?></td>
			    </tr>
			    <?php endforeach; ?>
			  </tbody>
			</table>
			<?php else:?>
				<p>There's no user.</p>
			<?php endif?>
		</div>
	</div>

</div>