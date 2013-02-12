<?php
namespace wcf\system\event\listener;
use wcf\data\user\UserList;
use wcf\system\event\IEventListener;
use wcf\system\request\LinkHandler;
use wcf\system\Regex;
use wcf\util\StringUtil;

/**
 * Parses @user mentions.
 * 
 * @author	Tim Duesterhus
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category 	Community Framework
 */
class PreParserAtUserListener implements IEventListener {
	/**
	 * @see wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!$eventObj->text) return;
		
		static $userRegex = null;
		if ($userRegex === null) {
			$userRegex = new Regex('(?<=^|\s)@([^,\s]*)');
		}
		
		$userRegex->match($eventObj->text, true);
		$matches = $userRegex->getMatches();
		
		if (!empty($matches[1])) {
			// remove duplicates
			$matches[1] = array_unique($matches[1]);
			
			// fetch users
			$userList = new UserList();
			$userList->getConditionBuilder()->add('user_table.username IN (?)', array($matches[1]));
			$userList->readObjects();
			foreach ($userList as $user) {
				$link = LinkHandler::getInstance()->getLink('User', array(
					'object' => $user
				));
				
				$eventObj->text = StringUtil::replace('@'.$user->username, "[url='".$link."']@".$user->username.'[/url]', $eventObj->text);
			}
		}
	}
}
