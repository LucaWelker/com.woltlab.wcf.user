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
	 */
	public function getShortOutput() {
		return WCF::getLanguage()->get('wcf.user.notification.follow.shortOutput');
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getOutput()
	 */
	public function getOutput() {
		return WCF::getLanguage()->getDynamicVariable('wcf.user.notification.follow.output', array('author' => $this->author));
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getRenderedOutput()
	 */
	public function getRenderedOutput() {
		WCF::getTPL()->assign(array(
			'author' => $this->author,
			'buttons' => $this->getActions(),
			'message' => $this->getOutput(),
			'time' => $this->userNotificationObject->time
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
