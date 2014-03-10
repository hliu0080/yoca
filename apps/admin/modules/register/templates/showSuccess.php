<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $registration->getId() ?></td>
    </tr>
    <tr>
      <th>Event:</th>
      <td><?php echo $registration->getEventId() ?></td>
    </tr>
    <tr>
      <th>Mentee:</th>
      <td><?php echo $registration->getMenteeId() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $registration->getStatus() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('register/edit?id='.$registration->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('register/index') ?>">List</a>
