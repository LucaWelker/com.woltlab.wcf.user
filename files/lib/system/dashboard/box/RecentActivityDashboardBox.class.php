<?php
namespace wcf\system\dashboard\box;
use wcf\data\user\activity\event\ViewableUserActivityEventList;
use wcf\system\dashboard\box\AbstractDashboardBoxSidebar;
use wcf\system\WCF;

/**
 * Dashboard box for recent activity.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard.box
 * @category 	Community Framework
 */
class RecentActivityDashboardBox extends AbstractDashboardBoxSidebar {
	/**
	 * recent activity list
	 * @var wcf\data\user\activity\event\ViewableUserActivityEventList
	 */
	public $recentActivityList = null;
	
	/**
	 * @see	wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);
		
		// TODO: add setting
		// TODO: use caching here?
		$this->recentActivityList = new ViewableUserActivityEventList();
		$this->recentActivityList->sqlLimit = 5;
		$this->recentActivityList->readObjects();
	}
	
	/**
	 * @see	wcf\system\dashboard\box\AbstractDashboardBoxContent::render()
	 */
	protected function render() {
		WCF::getTPL()->assign(array(
			'recentActivityList' => $this->recentActivityList
		));
		
		return WCF::getTPL()->fetch('dashboardBoxRecentActivity');
	}
}
