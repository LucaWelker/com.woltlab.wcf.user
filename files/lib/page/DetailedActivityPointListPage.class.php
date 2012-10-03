<?php
namespace wcf\page;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\WCF;

/**
 * Shows the user profile page.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category 	Community Framework
 */
class DetailedActivityPointListPage extends UserPage {
	/**
	 * was the page requested via ajax
	 * @var boolean
	 */
	public $ajax = false;
	
	/**
	 * user activity point object types
	 * @var array<wcf\data\object\type\ObjectType>
	 */
	public $activityPointObjectTypes = array();
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['ajax'])) $this->ajax = true;
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// add breadcrumbs
		WCF::getBreadcrumbs()->add(new Breadcrumb($this->user->username, LinkHandler::getInstance()->getLink('User', array('object' => $this->user))));
		
		$this->activityPointObjectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.activityPointEvent');
		
		$sql = "SELECT	activityPoints
			FROM	wcf".WCF_N."_user_activity_points
			WHERE		userID = ?
				AND	objectTypeID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($this->activityPointObjectTypes as $objectType) {
			$statement->execute(array($this->user->userID, $objectType->objectTypeID));
			$row = $statement->fetchArray();
			
			$objectType->activityPoints = $row['activityPoints'];
		}
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'ajax' => $this->ajax,
			'activityPointObjectTypes' => $this->activityPointObjectTypes
		));
	}
}
