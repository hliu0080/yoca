<?php


class EventReminderTask extends sfBaseTask{
	public function configure(){
		$this->namespace = 'yoca';
		$this->name = 'event_reminder';
		$this->briefDescription = 'Send out event email reminder 24 hours ahead of time, send out event cancellation to mentor if no one booked';
		
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
			new sfCommandOption('hours', null, sfCommandOption::PARAMETER_REQUIRED, 'How many hours before the event', '24'),
			new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
		));
	}
	
	public function execute($arguments = array(), $options = array()){
		$starttime = date('U');
		
		$this->log ( "======= Start =======" );
		
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase(isset($options['connection']) ? $options['connection'] : null)->getConnection();
		
		$events = Doctrine_Core::getTable('Event')->findUpcomingEvents($options['hours']);
		
		//for each event, if nobody no books, send cancellation to mentor; otherwise, send reminder to both mentor and mentees
		foreach($events as $event){
			if($event['booked'] === 0){
				//send cancellation to mentor
				
				//mark event as status 2
			}else{
				//send reminder to mentor
				$mentor = Doctrine_Core::getTable('YocaUser')->find($event['mentorid']);
				var_dump($mentor->getUsername());
				
				//send reminder to mentee
				
			}
		}
		
		$this->log ( "======= End =======" );
		
		$endtime = date('U');
		$seconds = $endtime - $starttime;
		$hours = floor($seconds / (60 * 60));
		$divisor_for_minutes = $seconds % (60 * 60);
		$minutes = floor($divisor_for_minutes / 60);
		$divisor_for_seconds = $divisor_for_minutes % 60;
		$seconds = ceil($divisor_for_seconds);
		$this->log("\nTime taken: ". $hours."hr ".$minutes."min ".$seconds."s");
	}
}