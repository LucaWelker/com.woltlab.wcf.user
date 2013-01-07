<?php
namespace wcf\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBox;
use wcf\data\user\activity\event\ViewableUserActivityEventList;
use wcf\page\IPage;
use wcf\system\WCF;

/**
 * Dashboard box for recent activity.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard.box
 * @category	Community Framework
 */
class RecentActivityDashboardBox extends AbstractContentDashboardBox {
	/**
	 * recent activity list
	 * @var	wcf\data\user\activity\event\ViewableUserActivityEventList
	 */
	public $eventList = null;
	
	/**
	 * true, if results were filtered by followed users
	 * @var boolean
	 */
	public $filteredByFollowedUsers = false;
	
	/**
	 * @see	wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);
		
		$this->eventList = new ViewableUserActivityEventList();
		if (count(WCF::getUserProfileHandler()->getFollowingUsers())) {
			$this->filteredByFollowedUsers = true;
			$this->eventList->getConditionBuilder()->add('user_activity_event.userID IN (?)', array(WCF::getUserProfileHandler()->getFollowingUsers()));
		}
		//$this->recentActivityList->sqlLimit = 5; // TODO: add setting
		$this->eventList->readObjects();
	}
	
	/**
	 * @see	wcf\system\dashboard\box\AbstractContentDashboardBox::render()
	 */
	protected function render() {
		if (count($this->eventList)) {
			WCF::getTPL()->assign(array(
				'eventList' => $this->eventList,
				'filteredByFollowedUsers' => $this->filteredByFollowedUsers
			));
			
			return WCF::getTPL()->fetch('dashboardBoxRecentActivity');
		}
	}
}
