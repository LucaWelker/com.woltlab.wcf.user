<?php
namespace wcf\system\user\activity\event;
use wcf\system\SingletonFactory;

/**
 * Default implementation for user activity events.
 * 
 * @todo	This class is almost pointless right now, maybe remove it?
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
	 * @see	wcf\system\user\activity\event\IUserActivityEvent::prepare()
	 */
	/*abstract public function prepare(array $events);*/
}
