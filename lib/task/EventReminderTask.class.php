<?php


class EventReminderTask extends sfBaseTask{
	public function configure(){
		$this->namespace = 'yoca';
		$this->name = 'event_reminder';
		$this->briefDescription = 'Send out event email reminder 24 hours ahead of time';
		
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
			new sfCommandOption('hours', null, sfCommandOption::PARAMETER_REQUIRED, 'How many hours before the event', '24'),
			new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
		));
	}
	
	public function execute($arguments = array(), $options = array()){
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase(isset($options['connection']) ? $options['connection'] : null)->getConnection();
		
		$events = Doctrine_Core::getTable('Event')->findUpcomingEvents(24*5);
		
		var_dump($events);
		
	}
}