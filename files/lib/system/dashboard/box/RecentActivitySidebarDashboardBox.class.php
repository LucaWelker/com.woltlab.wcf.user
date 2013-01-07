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
	 * @see	wcf\system\dashboard\box\AbstractContentDashboardBox::render()
	 */
	protected function render() {
		WCF::getTPL()->assign(array(
			'recentActivityList' => $this->recentActivityList
		));
		
		return WCF::getTPL()->fetch('dashboardBoxRecentActivitySidebar');
	}
}
