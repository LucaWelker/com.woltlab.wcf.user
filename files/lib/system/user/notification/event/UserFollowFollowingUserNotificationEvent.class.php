<?php
namespace wcf\system\user\notification\event;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;
use wcf\system\user\notification\type\IUserNotificationType;
use wcf\system\WCF;

class UserFollowFollowingUserNotificationEvent extends AbstractUserNotificationEvent {
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
		return 'Neuer Follower';
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getOutput()
	 * @todo	use language variables
	 */
	public function getOutput() {
		return '<strong>dtdesign</strong> folgt dir nun.';
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getActions()
	 */
	public function getActions() {
		return array(
			array(
				'action' => 'confirm',
				'label' => 'OK',
				'objectID' => $this->notification->notificationID
			),
		);
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getRenderedOutput()
	 */
	public function getRenderedOutput() {
		WCF::getTPL()->assign(array(
			'buttons' => $this->getActions(),
			'className' => 'wcf\\data\\user\\follow\\UserFollowAction',
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
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getAuthorID()
	 */
	public function getAuthorID() {
		return WCF::getUser()->userID;
	}
}
