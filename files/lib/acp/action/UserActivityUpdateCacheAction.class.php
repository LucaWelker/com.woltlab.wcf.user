<?php
namespace wcf\acp\action;
use wcf\action\AbstractAction;
use wcf\system\request\LinkHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\util\HeaderUtil;

/**
 * Clears the user activity point cache
 *
 * @author	Tim Düsterhus
 * @copyright	2012 Tim Düsterhus
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	acp.action
 * @category	Community Framework
 */
class UserActivityUpdateCacheAction extends AbstractAction {
	/**
	 * @see wcf\action\AbstractAction::$neededPermissions
	 */
	public $neededPermissions = array(); // TODO: Permissions
	
	/**
	 * @see wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		UserActivityPointHandler::getInstance()->updateCaches(array());
		
		$this->executed();
		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('UserActivityPointOption'));
		exit;
	}
}
