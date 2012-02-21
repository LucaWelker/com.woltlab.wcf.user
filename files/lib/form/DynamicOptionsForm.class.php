<?php
namespace wcf\form;
use wcf\data\user\UserAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\option\user\UserOptionHandler;
use wcf\system\WCF;

/**
 * Shows the dynamic options edit form.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category 	Community Framework
 */
class DynamicOptionsForm extends AbstractForm {
	/**
	 * user option handler
	 * @var	wcf\system\option\user\UserOptionHandler
	 */
	public $optionHandler = null;
	
	/**
	 * @see wcf\page\AbstractPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->optionHandler = new UserOptionHandler('user-option', 'wcf\system\cache\builder\OptionCacheBuilder', false, '', '', false);
		$this->optionHandler->setUser(WCF::getUser(), array('profile'));
	}
	
	/**
	 * @see wcf\form\AbstractForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->optionHandler->readUserInput($_POST);
	}
	
	/**
	 * @see wcf\form\AbstractForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		$this->optionHandler->validate();
	}
	
	/**
	 * @see wcf\form\AbstractForm::save()
	 */
	public function save() {
		parent::save();
		
		$saveOptions = $this->optionHandler->save();
		
		$this->objectAction = new UserAction(array(WCF::getUser()), 'update', array(
			'options' => $saveOptions
		));
		$this->objectAction->executeAction();
		
		$this->saved();
		
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see wcf\page\Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'optionTree' => $this->optionHandler->getOptionTree()
		));
	}
	
	/**
	 * @see wcf\page\Page::show()
	 */
	public function show() {
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
	
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.settings.dynamicOptions');
	
		parent::show();
	}
}