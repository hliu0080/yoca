<?php

/**
 * RegistrationTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class RegistrationTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object RegistrationTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Registration');
    }
    
    /**
     * Get mentee registration for this event
     * @param unknown $eventId
     * @param unknown $menteeId
     * @param unknown $status
     */
	public function getMenteeRegs($eventId, $menteeId, $status){
		return $this->createQuery('r')
		->where('r.mentee_id = ? and r.event_id = ? and r.status = ?', array($menteeId, $eventId, $status))
		->setHydrationMode(Doctrine::HYDRATE_ARRAY_SHALLOW)
		->execute();
	}
	
	/**
	 * Get mentee registrations for this month
	 * @param unknown $eventId
	 * @param unknown $menteeId
	 * @param unknown $status
	 * @return Ambigous <Doctrine_Collection, mixed, PDOStatement, Doctrine_Adapter_Statement, Doctrine_Connection_Statement, unknown, number>
	 */
	public function getMenteeMonthRegs($menteeId, $regStatus, $eventStatus){
		return $this->createQuery('r')
		->innerJoin('r.Event e')
		->where('r.mentee_id = ? and r.status = ? and e.status = ? and e.datetime > ? and e.datetime < ?', array($menteeId, $regStatus, $eventStatus, date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')))
		->setHydrationMode(Doctrine::HYDRATE_ARRAY_SHALLOW)
		->execute();
	}
	
	/**
	 * Get registrations for a event with specified status
	 * @param unknown $eventId
	 * @param unknown $status
	 * @return Ambigous <Doctrine_Collection, mixed, PDOStatement, Doctrine_Adapter_Statement, Doctrine_Connection_Statement, unknown, number>
	 */
	public function getRegsForEvent($eventId, $status){
		return $this->createQuery('r')
		->where('r.event_id = ? and r.status = ?', array($eventId, $status))
		->setHydrationMode(Doctrine::HYDRATE_ARRAY_SHALLOW)
		->execute();
	}
	
	/**
	 * Set status of a registration
	 * @param unknown $regIdArray
	 * @param unknown $status
	 */
	public function setRegStatus($regIdArray, $status){
		$this->createQuery('r')
		->update()
		->set('r.status', $status)
		->whereIn('r.id', $regIdArray)
		->execute();
	}
}