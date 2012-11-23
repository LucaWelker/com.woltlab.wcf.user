<?php
namespace wcf\data\user\profile\menu\item;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\UserInputException;
use wcf\system\menu\user\profile\UserProfileMenu;

/**
 * Executes user profile menu item-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.profile.menu.item
 * @category	Community Framework
 */
class UserProfileMenuItemAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$allowGuestAccess
	 */
	protected $allowGuestAccess = array('getContent');
	
	/**
	 * menu item
	 * @var	wcf\data\user\profile\menu\item\UserProfileMenuItem
	 */
	protected $menuItem = null;
	
	/**
	 * Validates menu item.
	 */
	public function validateGetContent() {
		if (isset($this->parameters['data']['menuItem'])) {
			$this->menuItem = UserProfileMenu::getInstance()->getMenuItem($this->parameters['data']['menuItem']);
		}
		
		if ($this->menuItem === null) {
			throw new UserInputException('menuItem');
		}
	}
	
	/**
	 * Returns content for given menu item.
	 */
	public function getContent() {
		$contentManager = $this->menuItem->getContentManager();
		
		return array(
			'containerID' => $this->parameters['data']['containerID'],
			'template' => $contentManager->getContent($this->parameters['data']['userID'])
		);
	}
}
