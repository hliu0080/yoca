<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>View <?php print $type?></h1>
		</div>
		
		
		<div class="page-container">
			<div class="row">
				<div class="span12">
					<a href="<?php print url_for("@manage_users?type=$type&page=$page&keyword=$keyword")?>"> Back to List</a>
				</div>
			</div>
			<table class="table table-bordered detail_table">
			  <tbody>
			    <tr>
			      <th>ID</th>
			      <td><?php echo $yoca_user->getId() ?></td>
			    </tr>
			    <tr>
			      <th>Username</th>
			      <td><?php echo $yoca_user->getUsername() ?></td>
			    </tr>
				<tr>
			      <th>User Type</th>
			      <td><?php echo $yoca_user->getType() ?></td>
			    </tr>
			    <?php if($type == "Mentor" || $type == "Mentee"):?>
			    	<tr>
				      <th>First Name</th>
				      <td><?php echo $yoca_user->getFirstname() ?></td>
				    </tr>
				    <tr>
				      <th>Last Name</th>
				      <td><?php echo $yoca_user->getLastname() ?></td>
				    </tr>
				    <tr>
				      <th>English Name</th>
				      <td><?php echo $yoca_user->getEnglishName() ?></td>
				    </tr>
				    <tr>
				      <th>Phone Number</th>
				      <td><?php echo $yoca_user->getPhone() ?></td>
				    </tr>
				    <tr>
				      <th>WeChat ID</th>
				      <td><?php echo $yoca_user->getWechat() ?></td>
				    </tr>
				    <tr>
				      <th>Education</th>
				      <td><?php echo $yoca_user->getEducation() ?></td>
				    </tr>
					<?php if($type == "Mentee"):?>
					    <tr>
					      	<th>School</th>
					      	<td><?php echo $yoca_user->getSchool() ?></td>
					    </tr>
					    <tr>
					      	<th>Major</th>
					      	<td><?php echo $yoca_user->getMajor() ?></td>
					    </tr>
					    <tr>
					    	<th>Work Experience</th>
					    	<td><?php echo sfConfig::get('app_profile_mentee_work_experience')[$yoca_user->getWork()] ?></td>
					    </tr>
					    <tr>
				      		<th>Employe</th>
				      		<td><?php echo $yoca_user->getEmployer() ?></td>
				    	</tr>
				    	<tr>
				      		<th>Industry</th>
				     		<td><?php echo $yoca_user->getIndustry() ?></td>
				    	</tr>
					    <tr>
					      	<th>Office Hour Preference</th>
					      	<td><?php echo sfConfig::get('app_profile_oh_preference')[$yoca_user->getOhPreference()] ?></td>
					    </tr>
					    <tr>
					      	<th>Description</th>
					      	<td><?php echo $yoca_user->getDescription() ?></td>
					    </tr>
					    <tr>
					      	<th>Expectation</th>
					      	<td><?php echo $yoca_user->getExpectation() ?></td>
					    </tr>
					<?php elseif($type == 'Mentor'):?>
						<tr>
					    	<th>Work Experience</th>
							<td><?php echo sfConfig::get('app_profile_mentor_work_experience')[$yoca_user->getWork()] ?></td>
						</tr>
						<tr>
					      	<th>Employer</th>
					      	<td><?php echo $yoca_user->getEmployer() ?></td>
					    </tr>
					    <tr>
				      		<th>Industry</th>
				     		<td><?php echo $yoca_user->getIndustry() ?></td>
				    	</tr>
					    <tr>
					      	<th>Age</th>
					      	<td><?php echo sfConfig::get('app_profile_age')[$yoca_user->getAge()] ?></td>
					    </tr>
					    <tr>
					      	<th>Neighborhood</th>
					      	<td><?php echo $yoca_user->getNeighborhood() ?></td>
					    </tr>
					    <tr>
					      	<th>Organization</th>
					      	<td><?php echo $yoca_user->getOrganization() ?></td>
					    </tr>
					<?php endif?>
					<tr>
						<th>Created At</th>
						<td><?php echo $yoca_user->getCreatedAt()?></td>
					</tr>
					<tr>
						<th>Updated At</th>
						<td><?php echo $yoca_user->getUpdatedAt()?></td>
					</tr>
			    <?php endif?>
			    <tr>
			      <th>Status</th>
			      <td><?php echo $yoca_user->getIsActive()?'Confirmed':'<span class="label label-warning">Pending</span>' ?></td>
			    </tr>
			  </tbody>
			</table>
			<div class="row">
				<div class="span6">
					<a href="<?php print url_for("@manage_users?type=$type&page=$page&keyword=$keyword")?>"> Back to List</a>
				</div>
				<div class="span6">
					<?php if($type == 'Mentor'):?>
						<?php print $yoca_user->getIsActive()?'':link_to('Activate Mentor', 'user/activate?id='.$yoca_user->getId(), array('confirm' => 'Are you sure?', 'class'=>'btn btn-success btn-wuxia'))?>
						<?php print !$yoca_user->getIsActive()?'':link_to('Deactivate Mentor', 'user/activate?id='.$yoca_user->getId(), array('confirm' => 'Are you sure?', 'class'=>'btn btn-danger btn-wuxia'))?>
					<?php endif?>
				</div>
			</div>
		</div>
	</div>
</div>
