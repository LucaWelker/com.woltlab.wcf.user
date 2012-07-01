<?php
namespace wcf\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use wcf\system\WCF;

/**
 * Default implementation for dashboard boxes displayed within content container.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard.box
 * @category 	Community Framework
 */
abstract class AbstractDashboardBoxContent implements IDashboardBox {
	/**
	 * dashboard box object
	 * @var	wcf\data\dashboard\box\DashboardBox
	 */
	public $box = null;
	
	/**
	 * IPage object
	 * @var	wcf\page\IPage
	 */
	public $page = null;
	
	/**
	 * template name
	 * @var	string
	 */
	public $templateName = 'dashboardBoxContent';
	
	/**
	 * @see	wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		$this->box = $box;
		$this->page = $page;
	}
	
	/**
	 * @see	wcf\system\dashboard\box\IDashboardBox::getTemplate()
	 */
	public function getTemplate() {
		WCF::getTPL()->assign(array(
			'box' => $this->box,
			'template' => $this->render()
		));
		
		return WCF::getTPL()->fetch($this->templateName);
	}
	
	/**
	 * Renders box view.
	 * 
	 * @return	string
	 */
	abstract protected function render();
}