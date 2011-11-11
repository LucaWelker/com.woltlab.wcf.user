<?php
namespace wcf\system\menu\user\profile\content;
use wcf\data\user\User;
use wcf\system\event\EventHandler;
use wcf\system\user\option\UserOptions;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles user profile overview content.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user.profile.content
 * @category 	Community Framework
 */
class OverviewUserProfileMenuContent extends SingletonFactory implements IUserProfileMenuContent {
	public $categoryFilter = array(
		'profile.aboutMe',
		'profile.personal',
		'profile.contact'
	);
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		EventHandler::getInstance()->fireAction($this, 'init');
		
		// build cached selection
		UserOptions::getInstance()->applyFilter($this->categoryFilter);
	}
	
	/**
	 * @see	wcf\system\menu\user\profile\content\IUserProfileMenuContent::getContent()
	 */
	public function getContent($userID) {
		// filter by category
		UserOptions::getInstance()->applyFilter($this->categoryFilter);
		
		// get options
		$user = new User($userID);
		$options = array();
		foreach ($this->categoryFilter as $categoryName) {
			$userOptions = UserOptions::getInstance()->getCategoryOptions($user, $categoryName);
			if (!empty($userOptions)) {
				$options[$categoryName] = $userOptions;
			}
		}
		
		WCF::getTPL()->assign(array(
			'options' => $options
		));
		
		return WCF::getTPL()->fetch('userProfileOverview');
	}
}
