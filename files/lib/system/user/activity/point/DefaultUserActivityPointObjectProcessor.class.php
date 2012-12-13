<?php
namespace wcf\system\user\activity\point;

/**
 * Does nothing.
 * 
 * @author	Tim Duesterhus
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.activity.point
 * @category	Community Framework
 */
class DefaultUserActivityPointObjectProcessor implements IUserActivityPointObjectProcessor {
	/**
	 * @see	wcf\system\user\activity\point\IUserActivityPointObject::countRequests();
	 */
	public function countRequests() {
		return 0;
	}
	
	/**
	 * @see	wcf\system\user\activity\point\IUserActivityPointObject::updateActivityPointEvents();
	 */
	public function updateActivityPointEvents($request) {
		return;
	}
}
