<?php
namespace wcf\system\menu\user\profile\content;
use wcf\data\user\User;
use wcf\system\event\EventHandler;
use wcf\system\option\user\UserOptionHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles user profile information content.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user.profile.content
 * @category	Community Framework
 */
class AboutUserProfileMenuContent extends SingletonFactory implements IUserProfileMenuContent {
	/**
	 * user option handler object
	 * @var	wcf\system\option\user\UserOptionHandler
	 */
	public $optionHandler = null;
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		EventHandler::getInstance()->fireAction($this, 'shouldInit');
		
		$this->optionHandler = new UserOptionHandler(false, '', 'profile');
		$this->optionHandler->enableEditMode(false);
		$this->optionHandler->showEmptyOptions(false);
		
		EventHandler::getInstance()->fireAction($this, 'didInit');
	}
	
	/**
	 * @see	wcf\system\menu\user\profile\content\IUserProfileMenuContent::getContent()
	 */
	public function getContent($userID) {
		$user = new User($userID);
		$this->optionHandler->setUser($user);
		
		WCF::getTPL()->assign(array(
			'options' => $this->optionHandler->getOptionTree(),
			'userID' => $user->userID,
		));
		
		return WCF::getTPL()->fetch('userProfileAbout');
	}
}
