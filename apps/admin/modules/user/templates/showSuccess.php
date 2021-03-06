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
					<?php print link_to('Back to list', 'manage_users', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
				</div>
			</div>
			<table class="table table-bordered detail_table">
			  <tbody>
			  	<?php if($type == "Mentor"):?>
				  	<tr>
				      <th>Mentor ID</th>
				      <td><?php echo $yoca_user->getMentorId() ?></td>
				    </tr>
			    <?php endif?>
			    <tr>
			      <th>Username</th>
			      <td><?php echo $yoca_user->getUsername() ?></td>
			    </tr>
				<tr>
			      <th>User Type</th>
			      <td><?php echo $yoca_user->getType() ?></td>
			    </tr>
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
				      	<td><?php echo $yoca_user->getSchoolId()==25?$yoca_user->getSchool():Doctrine_Core::getTable('YocaUserSchool')->find($yoca_user->getSchoolId()) ?></td>
				    </tr>
				    <tr>
				      	<th>Major</th>
				      	<td><?php echo $yoca_user->getMajorId()==19?$yoca_user->getMajor():Doctrine_Core::getTable('YocaUserMajor')->find($yoca_user->getMajorId()) ?></td>
				    </tr>
				    <tr>
				    	<th>Work Experience</th>
				    	<td><?php $workExp = sfConfig::get('app_profile_mentee_work_experience'); echo $workExp[$yoca_user->getWork()] ?></td>
				    </tr>
				    <tr>
			      		<th>Employer</th>
			      		<td><?php echo $yoca_user->getEmployer() ?></td>
			    	</tr>
			    	<tr>
			      		<th>Industries Interested In</th>
			     		<td>
			     			<?php  
				      			$industry = explode(',', $yoca_user->getIndustryId());
				      			$indArr = array();
				      			foreach($industry as $i){
									if($i == 17){
										$indArr[] = Doctrine_Core::getTable('YocaIndustry')->find($i)." (".$yoca_user->getIndustry().")";	
									}else{
										$indArr[] = Doctrine_Core::getTable('YocaIndustry')->find($i);
									}
				      			}
				      			print implode(', ', $indArr);
				      		?>
			     		</td>
			    	</tr>
				    <tr>
				      	<th>Office Hour Preference</th>
				      	<td><?php $oh = sfConfig::get('app_profile_oh_preference'); echo $oh[$yoca_user->getOhPreference()] ?></td>
				    </tr>
				    <tr>
				      	<th>Description</th>
				      	<td><?php echo Doctrine_Core::getTable('YocaUserDescription')->find($yoca_user->getDescriptionId()) ?></td>
				    </tr>
				    <tr>
				      	<th>Expectation</th>
				      	<td>
				      		<?php  
				      			$expec = explode(',', $yoca_user->getExpectationId());
				      			$expArr = array();
				      			foreach($expec as $e){
									if($e == 9){
					      				$expArr[] = Doctrine_Core::getTable('YocaUserExpectation')->find($e)." (".$yoca_user->getExpectation().")";
									}else{
										$expArr[] = Doctrine_Core::getTable('YocaUserExpectation')->find($e);
									}
				      			}
				      			print implode(', ', $expArr);
				      		?>
				      	</td>
				    </tr>
				<?php elseif($type == 'Mentor'):?>
					<tr>
				      	<th>School</th>
				      	<td><?php echo $yoca_user->getSchool() ?></td>
				    </tr>
					<tr>
				    	<th>Work Experience</th>
						<td><?php $workExp = sfConfig::get('app_profile_mentor_work_experience'); echo $workExp[$yoca_user->getWork()] ?></td>
					</tr>
					<tr>
				      	<th>Employer</th>
				      	<td><?php echo $yoca_user->getEmployer() ?></td>
				    </tr>
					<tr>
				      	<th>Title</th>
				      	<td><?php echo $yoca_user->getMentorTitle() ?></td>
				    </tr>
				    <tr>
			      		<th>Industry</th>
			     		<td><?php echo Doctrine_Core::getTable('YocaIndustry')->find($yoca_user->getIndustryId()) ?></td>
			    	</tr>
					<tr>
			      		<th>Industry Sub-category</th>
			     		<td><?php echo $yoca_user->getSubIndustry() ?></td>
			    	</tr>
				    <tr>
				      	<th>Age</th>
				      	<td><?php $age = sfConfig::get('app_profile_age'); echo $age[$yoca_user->getAge()] ?></td>
				    </tr>
				    <tr>
				      	<th>Neighborhood</th>
				      	<td><?php echo Doctrine_Core::getTable('YocaNeighborhood')->find($yoca_user->getNeighborhood()) ?></td>
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
			    <tr>
			      <th>Status</th>
			      <td><?php echo $yoca_user->getIsActive()?'Confirmed':'<span class="label label-warning">Pending</span>' ?></td>
			    </tr>
			  </tbody>
			</table>
			<div class="row">
				<div class="span6">
					<?php print link_to('Back to list', 'manage_users', array('type'=>$type, 'page'=>$page, 'keyword'=>$keyword))?>
				</div>
				<div class="span6 text-right">
					<?php if($type != 'Admin'):?>
						<?php print $yoca_user->getIsActive()?link_to("Deactivate $type", 'set_user_active', array('id'=>$yoca_user->getId(), 'is_active'=>0, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-danger btn-wuxia')):link_to("Activate $type", 'set_user_active', array('id'=>$yoca_user->getId(), 'is_active'=>1, 'type'=>$type, 'page'=>$page, 'keyword'=>$keyword), array('confirm' => 'Are you sure?', 'class'=>'btn btn-success btn-wuxia'))?>
					<?php endif?>
				</div>
			</div>
		</div>
	</div>
</div>
