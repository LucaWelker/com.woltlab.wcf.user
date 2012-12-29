<?php
namespace wcf\system\dashboard\box;
use wcf\system\cache\CacheHandler;
use wcf\system\WCF;

/**
 * Stats dashboard box.
 * 
 * @author	Marcel Weerk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard.box
 * @category	Community Framework
 */
class StatsSidebarDashboardBox extends AbstractSidebarDashboardBox {
	/**
	 * @see	wcf\system\dashboard\box\AbstractContentDashboardBox::render()
	 */
	protected function render() {
		CacheHandler::getInstance()->addResource('userStats', WBB_DIR.'cache/cache.userStats.php', 'wcf\system\cache\builder\UserStatsCacheBuilder', 600);
		WCF::getTPL()->assign(array(
			'dashboardStats' => CacheHandler::getInstance()->get('userStats')
		));
		
		return WCF::getTPL()->fetch('dashboardBoxStatsSidebar');
	}
}
