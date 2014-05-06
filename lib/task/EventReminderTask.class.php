<?php


class EventReminderTask extends sfBaseTask{
	public function configure(){
		$this->namespace = 'event';
		$this->name = 'reminder';
		$this->briefDescription = 'Send out event email reminder 24 hours ahead of time';
	}
	
	public function execute($arguments = array(), $options = array()){
	}
}