<?php
namespace wcf\system\user\notification\event;
use wcf\data\user\notification\UserNotification;
use wcf\data\user\UserProfile;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\user\notification\object\IUserNotificationObject;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Provides default a implementation for user notification events.
 * 
 * @author	Marcel Werk, Oliver Kliebisch
 * @copyright	2001-2012 WoltLab GmbH, Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.notification.event
 * @category	Community Framework
 */
abstract class AbstractUserNotificationEvent extends DatabaseObjectDecorator implements IUserNotificationEvent {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\notification\event\UserNotificationEvent';
	
	/**
	 * author object
	 * @var	wcf\data\user\UserProfile
	 */
	protected $author = null;
	
	/**
	 * user notification
	 * @var	wcf\data\user\notification\UserNotification
	 */
	protected $notification = null;
	
	/**
	 * user notification object
	 * @var	wcf\system\user\notification\object\IUserNotificationObject
	 */
	protected $userNotificationObject = null;
	
	/**
	 * additional data for this event
	 * @var	array<mixed>
	 */
	protected $additionalData = array();
	
	/**
	 * list of actions for this event
	 * @var	array<array>
	 */
	protected $actions = array();
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getActions()
	 */
	public function getActions() {
		return $this->actions;
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::setObject()
	 */
	public function setObject(UserNotification $notification, IUserNotificationObject $object, UserProfile $author, array $additionalData = array()) {
		$this->notification = $notification;
		$this->userNotificationObject = $object;
		$this->author = $author;
		$this->additionalData = $additionalData;
		
		$this->addDefaultAction();
	}
	
	/**
	 * Adds default event action to action list.
	 */
	protected function addDefaultAction() {
		$this->actions[] = array(
			'actionName' => 'markAsConfirmed',
			'className' => 'wcf\\data\\user\\notification\\UserNotificationAction',
			'label' => WCF::getLanguage()->get('wcf.user.notification.button.confirmed'),
			'objectID' => $this->notification->notificationID
		);
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getAuthorID()
	 */
	public function getAuthorID() {
		return $this->author->userID;
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getAuthor()
	 */
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::isVisible()
	 */
	public function isVisible() {
		return true;
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getEmailTitle()
	 */
	public function getEmailTitle() {
		return $this->getTitle();
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getEmailMessage()
	 */
	public function getEmailMessage() {
		return $this->getMessage();
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getEventHash()
	 */
	public function getEventHash() {
		return StringUtil::getHash($this->packageID . '-'. $this->eventID . '-' . $this->userNotificationObject->getObjectID());
	}
	
	/**
	 * @see	wcf\system\user\notification\event\IUserNotificationEvent::getRenderedOutput()
	 */
	public function getRenderedOutput() {
		WCF::getTPL()->assign(array(
			'author' => $this->author,
			'buttons' => $this->getActions(),
			'message' => $this->getMessage(),
			'time' => $this->userNotificationObject->time
		));
		
		return WCF::getTPL()->fetch('userNotificationDetails');
	}
}
