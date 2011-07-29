<?php
namespace app\system\user\notification\event;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;
use wcf\system\user\notification\type\IUserNotificationType;

class UserFriendRequestAcceptUserNotificationEvent extends AbstractUserNotificationEvent {
	public function getMessage(IUserNotificationType $notificationType) {
		return '';
	}

	public function getShortOutput() {
		return '';
	}

	public function getMediumOutput() {
		return '';
	}

	public function getOutput() {
		return '';
	}

	public function getTitle() {
		return '';
	}

	public function getDescription() {
		return '';
	}
}
