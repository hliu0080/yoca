<?php include_partial('main_nav', array('sections' => $sections))?>

<div class="container" role="main">
	
	<!-- TODO: -->
	<?php //include_partial('sec_nav', array('sections' => $sections))?>

	<div class="content">
		<div class="page-header">
			<h1>View User</h1>
		</div>
		<div class="page-container">
			<a href="<?php print url_for('user/list')?>">Back to Manage Users</a>	
		
			<table>
			  <tbody>
			    <tr>
			      <th>Id:</th>
			      <td><?php echo $yoca_user->getId() ?></td>
			    </tr>
			    <tr>
			      <th>Username:</th>
			      <td><?php echo $yoca_user->getUsername() ?></td>
			    </tr>
				<tr>
			      <th>User Type:</th>
			      <td><?php echo $yoca_user->getType() ?></td>
			    </tr>
			    <tr>
			      <th>First Name:</th>
			      <td><?php echo $yoca_user->getFirstname() ?></td>
			    </tr>
			    <tr>
			      <th>Last Name:</th>
			      <td><?php echo $yoca_user->getLastname() ?></td>
			    </tr>
			    <tr>
			      <th>English Name:</th>
			      <td><?php echo $yoca_user->getEnglishName() ?></td>
			    </tr>
			    <tr>
			      <th>Phone Number:</th>
			      <td><?php echo $yoca_user->getPhone() ?></td>
			    </tr>
			    <tr>
			      <th>WeChat ID:</th>
			      <td><?php echo $yoca_user->getWechat() ?></td>
			    </tr>
			    <tr>
			      <th>Education:</th>
			      <td><?php echo $yoca_user->getEducation() ?></td>
			    </tr>
			    <tr>
			      <th>School:</th>
			      <td><?php echo $yoca_user->getSchool() ?></td>
			    </tr>
			    <tr>
			      <th>Major:</th>
			      <td><?php echo $yoca_user->getMajor() ?></td>
			    </tr>
			    <tr>
			      <th>Work Experience:</th>
			      <td><?php echo $yoca_user->getWork() ?></td>
			    </tr>
			    <tr>
			      <th>Employer:</th>
			      <td><?php echo $yoca_user->getEmployer() ?></td>
			    </tr>
			    <tr>
			      <th>Office Hour Preference:</th>
			      <td><?php echo $yoca_user->getOhPreference() ?></td>
			    </tr>
			    <tr>
			      <th>Industry:</th>
			      <td><?php echo $yoca_user->getIndustry() ?></td>
			    </tr>
			    <tr>
			      <th>Description:</th>
			      <td><?php echo $yoca_user->getDescription() ?></td>
			    </tr>
			    <tr>
			      <th>Expectation:</th>
			      <td><?php echo $yoca_user->getExpectation() ?></td>
			    </tr>
			    <tr>
			      <th>Age:</th>
			      <td><?php echo $yoca_user->getAge() ?></td>
			    </tr>
			    <tr>
			      <th>Neighborhood:</th>
			      <td><?php echo $yoca_user->getNeighborhood() ?></td>
			    </tr>
			    <tr>
			      <th>Organization:</th>
			      <td><?php echo $yoca_user->getOrganization() ?></td>
			    </tr>
			    <tr>
			      <th>Status:</th>
			      <td><?php echo $yoca_user->getIsActive()?'Confirmed':'Pending' ?></td>
			    </tr>
			  </tbody>
			</table>
			
			<?php print $yoca_user->getIsActive()?'':"<hr />".link_to('Activate', 'user/activate?id='.$yoca_user->getId(), array('confirm' => 'Are you sure?'))?>
		</div>
	</div>

	
</div>
