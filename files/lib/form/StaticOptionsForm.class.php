<?php
namespace wcf\form;
use wcf\data\user\UserAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\language\LanguageFactory;
use wcf\system\menu\user\UserMenu;
use wcf\system\style\StyleHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;
use wcf\util\ArrayUtil;

/**
 * Shows the static options form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	form
 * @category	Community Framework
 */
class StaticOptionsForm extends AbstractForm {
	/**
	 * list of available content languages
	 * @var	array<wcf\data\language\Language>
	 */
	public $availableContentLanguages = array();
	
	/**
	 * list of available languages
	 * @var	array<wcf\data\language\Language>
	 */
	public $availableLanguages = array();
	
	/**
	 * list of available styles
	 * @var	array<wcf\data\style\Style>
	 */
	public $availableStyles = array();
	
	/**
	 * list of content language ids
	 * @var	array<integer>
	 */
	public $contentLanguageIDs = array();
	
	/**
	 * language id
	 * @var	integer
	 */
	public $languageID = 0;
	
	/**
	 * style id
	 * @var	integer
	 */
	public $styleID = 0;
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->availableContentLanguages = LanguageFactory::getInstance()->getContentLanguages();
		$this->availableLanguages = LanguageFactory::getInstance()->getLanguages();
		$this->availableStyles = StyleHandler::getInstance()->getAvailableStyles();
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['contentLanguageIDs']) && is_array($_POST['contentLanguageIDs'])) $this->contentLanguageIDs = ArrayUtil::toIntegerArray($_POST['contentLanguageIDs']);
		if (isset($_POST['languageID'])) $this->languageID = intval($_POST['languageID']);
		if (isset($_POST['styleID'])) $this->styleID = intval($_POST['styleID']);
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// validate language id
		if (!isset($this->availableLanguages[$this->languageID])) {
			$this->languageID = LanguageFactory::getInstance()->getDefaultLanguageID();
		}
		
		// validate content language ids
		foreach ($this->contentLanguageIDs as $key => $languageID) {
			if (!isset($this->availableContentLanguages[$languageID])) {
				unset($this->contentLanguageIDs[$key]);
			}
		}
		if (empty($this->contentLanguageIDs) && isset($this->availableContentLanguages[$this->languageID])) {
			$this->contentLanguageIDs[] = $this->languageID;
		}
		
		// validate style id
		if (!isset($this->availableStyles[$this->styleID])) {
			$this->styleID = 0;
		}
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (empty($_POST)) {
			$this->contentLanguageIDs = WCF::getUser()->getLanguageIDs();
			$this->languageID = WCF::getUser()->languageID;
			$this->styleID = WCF::getUser()->styleID;
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'availableContentLanguages' => $this->availableContentLanguages,
			'availableLanguages' => $this->availableLanguages,
			'availableStyles' => $this->availableStyles,
			'contentLanguageIDs' => $this->contentLanguageIDs,
			'languageID' => $this->languageID,
			'styleID' => $this->styleID
		));
	}
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.settings.staticOptions');
		
		parent::show();
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$this->objectAction = new UserAction(array(WCF::getUser()), 'update', array(
			'data' => array(
				'languageID' => $this->languageID,
				'styleID' => $this->styleID
			),
			'languages' => $this->contentLanguageIDs
		));
		$this->objectAction->executeAction();
		
		// reset user language ids cache
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'languageIDs', 1);
		
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
}
