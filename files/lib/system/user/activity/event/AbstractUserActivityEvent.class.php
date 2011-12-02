<?php
namespace wcf\system\user\activity\event;
use wcf\system\SingletonFactory;

/**
 * Default implementation for user activity events.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.activity.event
 * @category 	Community Framework
 */
abstract class AbstractUserActivityEvent extends SingletonFactory implements IUserActivityEvent {
	/**
	 * event data
	 * @var	array<wcf\data\user\activity\event\UserActivityEvent>
	 */
	public $eventData = array();
	
	/**
	 * @see	wcf\system\user\activity\event\IUserActivityEvent::setEventData()
	 */
	public function setEventData(array $eventData) {
		$this->eventData = $eventData;
	}
	
	/**
	 * @see	wcf\system\user\activity\event\IUserActivityEvent::getOutput()
	 */
	abstract public function getOutput($eventID);
}
