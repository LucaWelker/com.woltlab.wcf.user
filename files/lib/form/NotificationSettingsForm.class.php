<?php
namespace wcf\form;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\WCF;

/**
 * Shows the notification settings form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category	Community Framework
 */
class NotificationSettingsForm extends AbstractForm {
	/**
	 * list of notification events
	 * @var	array<array>
	 */
	public $events = null;
	
	/**
	 * list of settings by event
	 * @var	array<array>
	 */
	public $settings = array();
	
	/**
	 * list of notification types
	 * @var	array<wcf\data\object\type\ObjectType>
	 */
	public $types = array();
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->events = UserNotificationHandler::getInstance()->getAvailableEvents();
		$this->types = UserNotificationHandler::getInstance()->getNotificationTypes();
		
		// filter events
		foreach ($this->events as $objectTypeID => $events) {
			foreach ($events as $eventName => $event) {
				if (!$event->isVisible()) {
					unset($this->events[$objectTypeID][$eventName]);
				}
			}
			
			if (empty($this->events[$objectTypeID])) {
				unset($this->events[$objectTypeID]);
			}
		}
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['settings'])) $this->settings = $_POST['settings'];
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// valid event ids
		$validEventIDs = array();
		foreach ($this->events as $events) {
			foreach ($events as $event) {
				$validEventIDs[] = $event->eventID;
			}
		}
		
		// valid type ids
		$validTypeIDs = array();
		foreach ($this->types as $type) {
			$validTypeIDs[] = $type->objectTypeID;
		}
		
		foreach ($this->settings as $eventID => &$settings) {
			// validate event id
			if (!in_array($eventID, $validEventIDs)) {
				throw new IllegalLinkException();
			}
			
			// validate type
			if ($settings['type'] && !in_array($settings['type'], $validTypeIDs)) {
				throw new IllegalLinkException();
			}
			
			// ensure 'enabled' exists
			if (!isset($settings['enabled'])) {
				$settings['enabled'] = 0;
			}
		}
		unset($settings);
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			// get user settings
			$eventIDs = array();
			foreach ($this->events as $events) {
				foreach ($events as $event) {
					$eventIDs[] = $event->eventID;
					$this->settings[$event->eventID] = array(
						'enabled' => false,
						'type' => 0
					);
				}
			}
			
			// get activation state
			$sql = "SELECT	eventID
				FROM	wcf".WCF_N."_user_notification_event_to_user
				WHERE	userID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array(WCF::getUser()->userID));
			while ($row = $statement->fetchArray()) {
				$this->settings[$row['eventID']]['enabled'] = true;
			}
			
			// get notification type
			$sql = "SELECT	eventID, notificationTypeID
				FROM	wcf".WCF_N."_user_notification_event_notification_type
				WHERE	userID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array(WCF::getUser()->userID));
			while ($row = $statement->fetchArray()) {
				$this->settings[$row['eventID']]['type'] = $row['notificationTypeID'];
			}
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'events' => $this->events,
			'settings' => $this->settings,
			'types' => $this->types
		));
	}
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.settings.notification');
		
		parent::show();
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$this->updateActivationStates();
		$this->updateNotificationTypes();
		
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * Updates preferences for notification types.
	 */
	protected function updateNotificationTypes() {
		$sql = "DELETE FROM	wcf".WCF_N."_user_notification_event_notification_type
			WHERE		eventID = ?
					AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		WCF::getDB()->beginTransaction();
		$notificationTypes = array();
		foreach ($this->settings as $eventID => $settings) {
			$statement->execute(array(
				$eventID,
				WCF::getUser()->userID
			));
			
			if ($settings['type']) {
				$notificationTypes[$eventID] = $settings['type'];
			}
		}
		
		if (!empty($notificationTypes)) {
			$sql = "INSERT INTO	wcf".WCF_N."_user_notification_event_notification_type
						(userID, eventID, notificationTypeID)
				VALUES		(?, ?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			foreach ($notificationTypes as $eventID => $type) {
				$statement->execute(array(
					WCF::getUser()->userID,
					$eventID,
					$type
				));
			}
		}
		WCF::getDB()->commitTransaction();
	}
	
	/**
	 * Updates preferences for notification events.
	 */
	protected function updateActivationStates() {
		$sql = "DELETE FROM	wcf".WCF_N."_user_notification_event_to_user
			WHERE		eventID = ?
					AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		WCF::getDB()->beginTransaction();
		$enableEventIDs = array();
		foreach ($this->settings as $eventID => $settings) {
			$statement->execute(array(
				$eventID,
				WCF::getUser()->userID
			));
			
			if ($settings['enabled']) {
				$enableEventIDs[] = $eventID;
			}
		}
		
		if (!empty($enableEventIDs)) {
			$sql = "INSERT INTO	wcf".WCF_N."_user_notification_event_to_user
						(eventID, userID)
				VALUES		(?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			foreach ($enableEventIDs as $eventID) {
				$statement->execute(array(
					$eventID,
					WCF::getUser()->userID
				));
			}
		}
		WCF::getDB()->commitTransaction();
	}
}
