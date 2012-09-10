<?php
namespace wcf\data\user\ignore;
use wcf\data\user\ignore\UserIgnore;
use wcf\data\user\ignore\UserIgnoreEditor;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes ignored user-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.ignore
 * @category 	Community Framework
 */
class UserIgnoreAction extends AbstractDatabaseObjectAction {
	/**
	 * Does nothing.
	 */
	public function validateIgnore() {}
	
	/**
	 * Ignores an user.
	 * 
	 * @return	array
	 */
	public function ignore() {
		$ignore = UserIgnore::getIgnore($this->parameters['data']['ignoreUserID']);
		
		if (!$ignore->ignoreID) {
			UserIgnoreEditor::create(array(
				'ignoreUserID' => $this->parameters['data']['ignoreUserID'],
				'time' => TIME_NOW,
				'userID' => WCF::getUser()->userID,
			));
			
			UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'ignoredUserIDs', 1);
		}
		
		return array('isIgnoredUser' => 1);
	}
	
	/**
	 * Does nothing.
	 */
	public function validateUnignore() {}
	
	/**
	 * Unignores an user.
	 * 
	 * @return	array
	 */
	public function unignore() {
		$ignore = UserIgnore::getIgnore($this->parameters['data']['ignoreUserID']);
		
		if ($ignore->ignoreID) {
			$ignoreEditor = new UserIgnoreEditor($ignore);
			$ignoreEditor->delete();
			
			UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'ignoredUserIDs', 1);
		}
		
		return array('isIgnoredUser' => 0);
	}
	
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::validateDelete()
	 */
	public function validateDelete() {
		if (empty($this->objectIDs)) {
			throw new ValidateActionException("missing parameter 'objectID'");
		}
		
		// disguise as unignore
		$this->parameters['data']['ignoreUserID'] = array_shift($this->objectIDs);
		$this->validateUnignore();
	}
	
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::delete()
	 */
	public function delete() {
		// disguise as unignore
		$this->unignore();
	}
}
