<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles additional conditions in user search / user bulk processing.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserSearchFormAdditionalConditionsListener implements IEventListener {
	/**
	 * form object
	 * @var	wcf\acp\form\AbstractForm
	 */
	protected $eventObj = null;
	
	/**
	 * last activity start time
	 * @var string
	 */
	public $lastActivityTimeStart = '';
	
	/**
	 * last activity end time
	 * @var string
	 */
	public $lastActivityTimeEnd = '';
	
	/**
	 * enabled state
	 * @var boolean
	 */
	public $enabled = 0;
	
	/**
	 * disabled state
	 * @var boolean
	 */
	public $disabled = 0;
	
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$this->eventObj = $eventObj;
		
		$this->$eventName();
	}
	
	/**
	 * Handles the readFormParameters event.
	 */
	protected function readFormParameters() {
		if (isset($_POST['lastActivityTimeStart'])) $this->lastActivityTimeStart = $_POST['lastActivityTimeStart'];
		if (isset($_POST['lastActivityTimeEnd'])) $this->lastActivityTimeEnd = $_POST['lastActivityTimeEnd'];
		if (isset($_POST['enabled'])) $this->enabled = intval($_POST['enabled']);
		if (isset($_POST['disabled'])) $this->disabled = intval($_POST['disabled']);
	}
	
	/**
	 * Handles the assignVariables event.
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign(array(
			'lastActivityTimeStart' => $this->lastActivityTimeStart,
			'lastActivityTimeEnd' => $this->lastActivityTimeEnd,
			'enabled' => $this->enabled,
			'disabled' => $this->disabled
		));
	}
	
	/**
	 * Handles the buildConditions event.
	 */
	protected function buildConditions() {
		// last activity time
		if ($startDate = @strtotime($this->lastActivityTimeStart)) {
			$this->eventObj->conditions->add('user_table.lastActivityTime >= ?', array($startDate));
		}
		if ($endDate = @strtotime($this->lastActivityTimeEnd)) {
			$this->eventObj->conditions->add('user_table.lastActivityTime <= ?', array($endDate));
		}
		
		if ($this->enabled) {
			$this->eventObj->conditions->add('user_table.activationCode = ?', array(0));
		}
		if ($this->disabled) {
			$this->eventObj->conditions->add('user_table.activationCode <> ?', array(0));
		}
	}
}
