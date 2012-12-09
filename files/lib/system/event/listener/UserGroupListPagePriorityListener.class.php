<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;

/**
 * Adds the priority column to the list of valid sort fields.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserGroupListPagePriorityListener implements IEventListener {
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$eventObj->validSortFields[] = 'priority';
	}
}
