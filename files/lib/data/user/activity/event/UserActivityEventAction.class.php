<?php
namespace wcf\data\user\activity\event;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\WCF;

/**
 * Executes user activity event-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.activity.event
 * @category 	Community Framework
 */
class UserActivityEventAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\activity\event\UserActivityEventEditor';
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$allowGuestAccess
	 */
	public $allowGuestAccess = array('load');
	
	/**
	 * Validates parameters to load recent activity entries.
	 */
	public function validateLoad() {
		if (!isset($this->parameters['data']['userID']) || !intval($this->parameters['data']['userID'])) {
			throw new ValidateActionException("Missing parameter 'userID'");
		}
		
		if (!isset($this->parameters['data']['pageNo']) || !intval($this->parameters['data']['pageNo'])) {
			throw new ValidateActionException("Missing parameter 'pageNo'");
		}
	}
	
	/**
	 * Loads a list of recent activity entries.
	 * 
	 * @return	array
	 */
	public function load() {
		$returnValues = array(
			'hasMoreElements' => false,
			'template' => ''
		);
		
		$eventList = UserActivityEventHandler::getInstance()->getEvents(array($this->parameters['data']['userID']), 20, ($this->parameters['data']['pageNo'] * 20));
		switch (count($eventList)) {
			case 0:
				// offset is beyond valid values
				return $returnValues;
			break;
			
			case 20:
				$returnValues['hasMoreElements'] = true;
			break;
		}
		
		// parse template
		WCF::getTPL()->assign(array(
			'eventList' => $eventList,
			'userID' => $this->parameters['data']['userID']
		));
		$returnValues['template'] = WCF::getTPL()->fetch('recentActivities');
		
		return $returnValues;
	}
}
