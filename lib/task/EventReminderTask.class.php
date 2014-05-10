<?php
/**
 * Send out email reminders before event time
 * and automatically cancel event if no books 
 * @author hliu
 *
 */
class EventReminderTask extends sfBaseTask{
	public function configure(){
		$this->namespace = 'yoca';
		$this->name = 'event_reminder';
		$this->briefDescription = 'Send out event email reminder 24 hours ahead of time, send out event cancellation to mentor if no one booked';
		
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
			new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
			new sfCommandOption('dryrun', null, sfCommandOption::PARAMETER_REQUIRED, 'Whether to change db value or not', true),
			new sfCommandOption('hours', null, sfCommandOption::PARAMETER_REQUIRED, 'How many hours before the event', '24')
		));
	}
	
	public function execute($arguments = array(), $options = array()){
		$starttime = date('U');
		
		$file_logger = new sfFileLogger($this->dispatcher, array(
			"file" => $this->configuration->getRootDir()."/log/EventReminder.log"
		));
		$this->dispatcher->connect("command.log", array($file_logger, "listenToLogEvent"));
		
		$this->log ( "======= Start =======" );
		
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase(isset($options['connection']) ? $options['connection'] : null)->getConnection();
		
		$events = Doctrine_Core::getTable('Event')->findUpcomingEvents($options['hours']);
		$this->log(count($events) . (count($events)>1?" events":" event")." found for next {$options['hours']} hours");
		
		//for each event, if no books, send cancellation to mentor; otherwise, send reminder to both mentor and mentees
		foreach($events as $event){
			$mentor = $event->getYocaUser();
			$this->log("Event id: ".$event->getId());
			$this->log("Time: ".$event->getDatetime());
			$this->log("Mentor: ".$mentor->getMentorId()." - ".$mentor->getUsername());
			$this->log("Booked: ".$event->getBooked());
			
			if($event->getBooked() == 0){
				//send cancellation to mentor
				$this->log("Send cancellation to ".$mentor->getUsername());
				if(!(bool)$options['dryrun']){
					$body = "Your following office hour has been cancelled due to lack of registration.\n\n";
					$body .= "Mentor ID: {$mentor->getMentorId()}\n";
					$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
					$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
					$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
					$body .= "Event Capacity: ".$event->getCapacity()."\n";
					$body .= "Booked: ".$event->getBooked()."\n";
					$body .= "Event Time: ".$event->getDatetime()."\n";
					$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
					$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
					$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
					$body .= "Yours,\n";
					$body .= "YOCA Team";
					
					$message = $this->getMailer()
					->compose(sfConfig::get('app_email_service'), $mentor->getUsername(), "YOCA Office Hour Cancelled", $body)
					->setBcc(sfConfig::get('app_email_dev'));
					$ret = $this->getMailer()->send($message);
					if(!$ret){
						$this->log("Failed sending email to mentor {$mentor->getUsername()}", sfLogger::DEBUG);
					}
				}
									
				//mark event as status 2
				$this->log("Set event status to 2");
				if(!(bool)$options['dryrun']){
					$event->setStatus(2);
					$event->save();
				}
			}else{
				//get mentee usernames
				$regs = Doctrine_Core::getTable('Registration')->getEventRegs($event->getId(), 1);
		  		$regIdArray = $usernameArray = array();
		  		foreach($regs as $reg){
		  			$regIdArray[] = $reg['id'];
		
			  		$username = Doctrine_Core::getTable('YocaUser')->find($reg['mentee_id']);
			  		$usernameArray[$username->getUsername()] = $username->getLastname().", ".$username->getFirstname();
		  		}
		  		
				//send reminder to mentor
				$this->log("Send reminder to ".$mentor->getUsername());
				if(!(bool)$options['dryrun']){
					$body = "This is a reminder for your office hour at {$event->getDatetime()}.\n\n";
					$body .= "Mentor ID: {$mentor->getMentorId()}\n";
					$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
					$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
					$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
					$body .= "Event Capacity: ".$event->getCapacity()."\n";
					$body .= "Booked: ".$event->getBooked()."\n";
					$body .= "Event Time: ".$event->getDatetime()."\n";
					$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
					$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
					$body .= "Mentee: \n";
					foreach($usernameArray as $key=>$value){
						$body .= "$value ($key)\n";
					}
					$body .= "\n";
					$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
					$body .= "Yours,\n";
					$body .= "YOCA Team";
					
					$message = $this->getMailer()
					->compose(sfConfig::get('app_email_service'), $mentor->getUsername(), "YOCA Office Hour Reminder", $body)
					->setBcc(sfConfig::get('app_email_dev'));
					$ret = $this->getMailer()->send($message);
					if(!$ret){
						$this->log("Failed sending email to mentor {$mentor->getUsername()}", sfLogger::DEBUG);
					}
				}
				
				//send reminder to mentee
				$this->log("Send reminder to ".implode(',', array_keys($usernameArray)));
				if(!(bool)$options['dryrun']){
					$body = "This is a reminder for your office hour at {$event->getDatetime()}.\n\n";
					$body .= "Mentor ID: {$mentor->getMentorId()}\n";
					$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
					$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
					$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
					$body .= "Event Capacity: ".$event->getCapacity()."\n";
					$body .= "Booked: ".$event->getBooked()."\n";
					$body .= "Event Time: ".$event->getDatetime()."\n";
					$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n";
					$body .= "Event Address: ".($event->getAddressId()==18?$event->getAddress():$event->getEventAddress()->getName())."\n\n";
					$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
					$body .= "Yours,\n";
					$body .= "YOCA Team";
						
					$message = $this->getMailer()
					->compose(sfConfig::get('app_email_service'), array_keys($usernameArray), "YOCA Office Hour Reminder", $body)
					->setBcc(sfConfig::get('app_email_dev'));
					$ret = $this->getMailer()->send($message);
					if(!$ret){
						$this->log("Failed sending email to mentees", sfLogger::DEBUG);
					}
				}
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
		$this->log("Time taken: ". $hours."hr ".$minutes."min ".$seconds."s");
	}
}