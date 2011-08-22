<?php
namespace wcf\system\user\notification\event;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;
use wcf\system\user\notification\type\IUserNotificationType;
use wcf\system\WCF;

class UserFriendRequestCreateUserNotificationEvent extends AbstractUserNotificationEvent {
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getMessage()
	 */
	public function getMessage(IUserNotificationType $notificationType) {
		return '';
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getShortOutput()
	 * @todo	use language variables
	 */
	public function getShortOutput() {
		return 'Eingehende Freundschaftsanfrage';
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getOutput()
	 * @todo	use language variables
	 */
	public function getOutput() {
		return '<strong>dtdesign</strong> möchte dich zu seiner Freundesliste hinzufügen.';
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getActions()
	 */
	public function getActions() {
		return array(
			array(
				'action' => 'accept',
				'label' => 'Akzeptieren',
				'objectID' => $this->userNotificationObject->requestID
			),
			array(
				'action' => 'reject',
				'label' => 'Ablehnen',
				'objectID' => $this->userNotificationObject->requestID
			),
			array(
				'action' => 'ignore',
				'label' => 'Ignorieren',
				'objectID' => $this->userNotificationObject->requestID
			)
		);
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getRenderedOutput()
	 */
	public function getRenderedOutput() {
		WCF::getTPL()->assign(array(
			'buttons' => $this->getActions(),
			'className' => 'wcf\\data\\user\\friend\\request\\UserFriendRequestAction',
			'message' => $this->getOutput(),
			'time' => $this->userNotificationObject->time,
			'username' => 'dtdesign'	// fetch with left join?
		));
		
		return WCF::getTPL()->fetch('userNotificationDetails');
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getTitle()
	 */
	public function getTitle() {
		return '';
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getDescription()
	 */
	public function getDescription() {
		return '';
	}
}
