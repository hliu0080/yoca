<?php

/**
 * EventNotifyTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EventNotifyTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EventNotifyTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventNotify');
    }
    
    public function isSignedUp($eventId, $menteeId, $status){
    	$notify = $this->createQuery('n')
    	->select('count(n.id)')
    	->where('n.event_id = ? and n.mentee_id = ?', array($eventId, $menteeId))
    	->setHydrationMode(Doctrine::HYDRATE_SINGLE_SCALAR)
    	->execute();
    	
    	return $notify>0? true:false;
    }
    
    /**
     * get notify records by eventId and status
     * @param unknown $eventId
     * @param unknown $status
     * @return Ambigous <Doctrine_Collection, mixed, PDOStatement, Doctrine_Adapter_Statement, Doctrine_Connection_Statement, unknown, number>
     */
    public function getNotify($eventId, $statusArray){
    	return $this->createQuery('n')
    	->where('n.event_id = ? and n.status in ?', array($eventId, $statusArray))
    	->execute();
    }
    
	/**
	 * Send emails to singedup users and mark status as 'notified'
	 * @param unknown $eventId
	 */
    public function notify($eventId){
    	$notifies = $this->getNotify($eventId, array('signedup'));
    	
    	if(count($notifies)){
	    	$menteeUsernames = array();
	    	foreach($notifies as $notify){
	    		$menteeUsernames[] = $notify->getMenteeUsername();
	    		$notify->setStatus('notified');
	    		$notify->save();
	    	}
	    	
	    	$event = Doctrine_Core::getTable('Event')->findEvent($eventId);
	    	$mentor = $event->getYocaUser();
	    	
	    	$body = "Good news! This office hour just became available again. Please login to http://member.yocausa.org and register before someone else does!\n\n";
	    	$body .= "Mentor ID: {$mentor->getMentorId()}\n";
	    	$body .= "Mentor: {$mentor->getLastName()}, {$mentor->getFirstName()}\n";
	    	$body .= "Event Topic: " .($event->getTopicId()==8?$event->getTopic():$event->getEventTopic()->getName())."\n";
	    	$body .= "Event Industry: ".$event->getYocaIndustry()->getName()."\n";
	    	$body .= "Event Capacity: ".$event->getCapacity()."\n";
	    	$body .= "Booked Up Till Now: ".$event->getBooked()."\n";
	    	$body .= "Event Time: ".$event->getDatetime()."\n";
	    	$body .= "Event Neighborhood: ".$event->getYocaNeighborhood()->getName()."\n\n";
	    	$body .= "Please do not reply to this automated email. Contact ".sfConfig::get('app_email_contact')." if you need any help. If you believe you received this email by mistake, please contact ".sfConfig::get('app_email_contact').".\n\n";
	    	$body .= "Yours,\n";
	    	$body .= "YOCA Team";
	    	
	    	$message = $this->getMailer()
	    		->compose(sfConfig::get('app_email_service'), $menteeUsernames, "The YOCA Office Hour You Wanted To Attend Is Available Now!", $body)
	    		->setBcc(sfConfig::get('app_email_dev'));
	    	$this->getMailer()->send();
    	}
    }
    
    /**
     * Set notify status to "cancelled" for an event
     * @param unknown $eventId
     */
    public function cancel($id){
    	$notifies = $this->getNotify($id, array('signedup', 'notified'));
    	foreach($notifies as $notify){
    		$notify->setStatus('cancelled');
    		$notify->save();
    	}
    }
}