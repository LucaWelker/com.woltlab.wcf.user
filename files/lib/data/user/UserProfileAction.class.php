<?php
namespace wcf\data\user;
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
}