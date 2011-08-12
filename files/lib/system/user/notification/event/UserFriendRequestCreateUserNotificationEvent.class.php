<?php
namespace wcf\system\user\notification\event;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;
use wcf\system\user\notification\type\IUserNotificationType;
use wcf\system\WCF;

class UserFriendRequestCreateUserNotificationEvent extends AbstractUserNotificationEvent {
	public function getMessage(IUserNotificationType $notificationType) {
		return '';
	}
	
	/**
	 * @todo	use language variables
	 */
	public function getShortOutput() {
		return 'Eingehende Freundschaftsanfrage';
	}
	
	public function getMediumOutput() {
		return '';
	}
	
	/**
	 * @todo	use language variables
	 */
	public function getOutput() {
		$buttons = array(
			array(
				'action' => 'accept',
				'label' => 'Akzeptieren',
			),
			array(
				'action' => 'reject',
				'label' => 'Ablehnen'
			),
			array(
				'action' => 'ignore',
				'label' => 'Ignorieren'
			)
		);
		
		// TODO: Avatar is still hard-coded, fetch via UserStorageHandler?
		WCF::getTPL()->assign(array(
			'buttons' => $buttons,
			'className' => 'wcf\\data\\user\\friend\\request\\UserFriendRequestAction',
			'message' => '<strong>dtdesign</strong> möchte dich zu seiner Freundesliste hinzufügen.',
			'objectID' => $this->userNotificationObject->requestID,
			'time' => $this->userNotificationObject->time,
			'username' => 'dtdesign'	// fetch with left join?
		));
		
		return WCF::getTPL()->fetch('userNotificationDetails');
	}
	
	public function getTitle() {
		return '';
	}
	
	public function getDescription() {
		return '';
	}
}
