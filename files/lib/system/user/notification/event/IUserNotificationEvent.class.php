<?php
namespace wcf\system\user\notification\event;
use wcf\data\user\notification\UserNotification;
use wcf\data\user\UserProfile;
use wcf\data\IDatabaseObjectProcessor;
use wcf\system\user\notification\type\IUserNotificationType;
use wcf\system\user\notification\object\IUserNotificationObject;

/**
 * This interface should be implemented by every event which is fired by the notification system.
 *
 * @author	Marcel Werk, Oliver Kliebisch
 * @copyright	2001-2012 WoltLab GmbH, Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.notification.event
 * @category	Community Framework
 */
interface IUserNotificationEvent extends IDatabaseObjectProcessor {
	/**
	 * Returns a list of actions for this notification event.
	 * @return	array<array>
	 */
	public function getActions();

	/**
	 * Returns a short title used for the notification overlay, e.g. "new follower".
	 *
	 * @return	string
	 */
	public function getTitle();
	
	/**
	 * Returns the notification event message, e.g. "dtdesign is now following you".
	 * 
	 * @return	string
	 */
	public function getMessage();
	
	/**
	 * Returns rendered HTML used within the notification overlay.
	 * 
	 * @return	string
	 */	
	public function getRenderedOutput();
	
	/**
	 * Returns the full title for this notification, e.g. for use with e-mails.
	 * 
	 * @return	string
	 */
	public function getEmailTitle();
	
	/**
	 * Returns the message for this notification event.
	 *
	 * @param	wcf\system\user\notification\type\IUserNotificationType	$notificationType
	 * @return	string
	 */
	public function getEmailMessage(IUserNotificationType $notificationType);
	
	/**
	 * Returns the author id for this notification event.
	 * 
	 * @return	integer
	 */
	public function getAuthorID();
	
	/**
	 * Returns the author for this notification event.
	 * 
	 * @return	wcf\data\user\UserProfile
	 */
	public function getAuthor();
	
	/**
	 * Returns true if this notification event is visible for the active user.
	 * 
	 * @return	boolean
	 */
	public function isVisible();

	/**
	 * Returns true if this event supports the given notification type.
	 *
	 * @param	wcf\system\user\notification\type\IUserNotificationType	$notificationType
	 * @return	boolean
	 */
	public function supportsNotificationType(IUserNotificationType $notificationType);
	
	/**
	 * Sets the object for the event.
	 *
	 * @param	wcf\data\user\notification\UserNotification			$notification
	 * @param	wcf\system\user\notification\object\IUserNotificationObject	$object
	 * @param	wcf\data\user\UserProfile					$author
	 * @param	array<mixed>							$additionalData
	 */
	public function setObject(UserNotification $notification, IUserNotificationObject $object, UserProfile $author, array $additionalData = array());
}
