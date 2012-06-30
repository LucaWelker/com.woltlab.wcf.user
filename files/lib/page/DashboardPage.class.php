<?php
namespace wcf\page;
use wcf\system\dashboard\DashboardHandler;

/**
 * Shows the dashboard page.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class DashboardPage extends AbstractPage {
	/**
	 * @see	wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		DashboardHandler::getInstance()->loadBoxes('com.woltlab.wcf.user.DashboardPage', $this);
	}
}
