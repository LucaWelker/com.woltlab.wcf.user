<?php
namespace wcf\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBox;
use wcf\data\user\activity\event\ViewableUserActivityEventList;
use wcf\page\IPage;
use wcf\system\WCF;

/**
 * Dashboard box for recent activity in the sidebar.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard.box
 * @category	Community Framework
 */
class RecentActivitySidebarDashboardBox extends AbstractSidebarDashboardBox {
	/**
	 * recent activity list
	 * @var	wcf\data\user\activity\event\ViewableUserActivityEventList
	 */
	public $eventList = null;
	
	/**
	 * @see	wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);
		
		// TODO: add setting
		// TODO: use caching here?
		$this->eventList = new ViewableUserActivityEventList();
		$this->eventList->sqlLimit = 5;
		$this->eventList->readObjects();
	}
	
	/**
	 * @see	wcf\system\dashboard\box\AbstractContentDashboardBox::render()
	 */
	protected function render() {
		if (count($this->eventList)) {
			WCF::getTPL()->assign(array(
				'eventList' => $this->eventList
			));
			
			return WCF::getTPL()->fetch('dashboardBoxRecentActivitySidebar');
		}
	}
}
