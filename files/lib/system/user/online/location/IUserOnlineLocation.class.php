<?php
namespace wcf\system\user\online\location;
use wcf\data\session\Session;

/**
 * Any page location class should implement this interface.
 *
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.online.location
 * @category 	Community Framework
 */
interface IUserOnlineLocation {
	/**
	 * Caches the information of a page location.
	 * 
	 * @param	wcf\data\session\Session	$session
	 */
	public function cache(Session $session);
	
	/**
	 * Returns the information of a page location.
	 * 
	 * @param	wcf\data\session\Session	$session
	 * @return	string
	 */
	public function get(Session $session);
}
