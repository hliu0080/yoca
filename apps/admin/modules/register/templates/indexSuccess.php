<h1>Registrations List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Event</th>
      <th>Mentee</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($registrations as $registration): ?>
    <tr>
      <td><a href="<?php echo url_for('register/show?id='.$registration->getId()) ?>"><?php echo $registration->getId() ?></a></td>
      <td><?php echo $registration->getEventId() ?></td>
      <td><?php echo $registration->getMenteeId() ?></td>
      <td><?php echo $registration->getStatus() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('register/new') ?>">New</a>
