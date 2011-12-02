<?php
namespace wcf\data\user\activity\event;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes user activity event-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.activity.event
 * @category 	Community Framework
 */
class UserActivityEventAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\activity\event\UserActivityEventEditor';
}
