<?php
namespace wcf\data\user;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\bbcode\MessageParser;
use wcf\system\exception\ValidateActionException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Executes user profile-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user
 * @category 	Community Framework
 */
class UserProfileAction extends UserAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$allowGuestAccess
	 */
	protected $allowGuestAccess = array('getUserProfile', 'getDetailedActivityPointList');
	
	/**
	 * Validates parameters for signature preview.
	 */
	public function validateGetMessagePreview() {
		if (!isset($this->parameters['data']['message'])) {
			throw new ValidateActionException("Missing parameter 'message'");
		}
		
		if (!isset($this->parameters['options'])) {
			throw new ValidateActionException("Missing parameter 'options'");
		}
	}
	
	/**
	 * Returns a rendered signature preview.
	 * 
	 * @return	array
	 */
	public function getMessagePreview() {
		// get options
		$enableBBCodes = (isset($this->parameters['options']['enableBBCodes'])) ? 1 : 0;
		$enableHtml = (isset($this->parameters['options']['enableHtml'])) ? 1 : 0;
		$enableSmilies = (isset($this->parameters['options']['enableSmilies'])) ? 1 : 0;
		
		// validate permissions for options
		if ($enableBBCodes && !WCF::getSession()->getPermission('user.community.signature.canUseBBCodes')) $enableBBCodes = 0;
		if ($enableHtml && !WCF::getSession()->getPermission('user.community.signature.canUseHtml')) $enableHtml = 0;
		if ($enableSmilies && !WCF::getSession()->getPermission('user.community.signature.canUseSmilies')) $enableSmilies = 0;
		
		// parse message
		$message = StringUtil::trim($this->parameters['data']['message']);
		$preview = MessageParser::getInstance()->parse($message, $enableSmilies, $enableHtml, $enableBBCodes, false);
		
		return array(
			'message' => $preview
		);
	}
	
	/**
	 * Validates user profile preview.
	 */
	public function validateGetUserProfile() {
		switch (count($this->objectIDs)) {
			case 0:
				throw new ValidateActionException("Missing user id");
			break;
			
			case 1:
				// we're fine
			break;
			
			default:
				// more than one user id is pointless
				throw new ValidateActionException("Invalid parameter for user id given");
			break;
		}
	}
	
	/**
	 * Returns user profile preview.
	 * 
	 * @return	array
	 */
	public function getUserProfile() {
		$userID = reset($this->objectIDs);
		
		$userProfileList = new UserProfileList();
		$userProfileList->getConditionBuilder()->add("user_table.userID = ?", array($userID));
		$userProfileList->readObjects();
		$userProfiles = $userProfileList->getObjects();
		
		WCF::getTPL()->assign(array(
			'user' => reset($userProfiles)
		));
		
		return array(
			'template' => WCF::getTPL()->fetch('userProfilePreview')
		);
	}
	
	/**
	 * Validates detailed activity point list
	 */
	public function validateGetDetailedActivityPointList() {
		switch (count($this->objectIDs)) {
			case 0:
				throw new ValidateActionException("Missing user id");
			break;
			
			case 1:
				// we're fine
			break;
			
			default:
				// more than one user id is pointless
				throw new ValidateActionException("Invalid parameter for user id given");
			break;
		}
	}
	
	/**
	 * Returns detailed activity point list.
	 * 
	 * @return	array
	 */
	public function getDetailedActivityPointList() {
		$userID = reset($this->objectIDs);
		
		$userProfileList = new UserProfileList();
		$userProfileList->getConditionBuilder()->add("user_table.userID = ?", array($userID));
		$userProfileList->readObjects();
		$userProfiles = $userProfileList->getObjects();
		$user = reset($userProfiles);
		
		$activityPointObjectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.activityPointEvent');
		
		$sql = "SELECT	activityPoints
			FROM	wcf".WCF_N."_user_activity_points
			WHERE		userID = ?
				AND	objectTypeID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($activityPointObjectTypes as $objectType) {
			$statement->execute(array($user->userID, $objectType->objectTypeID));
			$row = $statement->fetchArray();
			
			$objectType->activityPoints = $row['activityPoints'];
		}
		
		WCF::getTPL()->assign(array(
			'activityPointObjectTypes' => $activityPointObjectTypes,
			'user' => $user
		));
		
		return array(
			'template' => WCF::getTPL()->fetch('detailedActivityPointList')
		);
	}
}