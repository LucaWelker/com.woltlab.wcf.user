<?php
namespace wcf\page;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\User;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\WCF;

/**
 * Shows the detailed activity point summary.
 * 
 * @author	Tim Düsterhus
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category 	Community Framework
 */
class DetailedActivityPointListPage extends AbstractPage {
	/**
	 * user activity point object types
	 * @var array<wcf\data\object\type\ObjectType>
	 */
	public $activityPointObjectTypes = array();
	
	/**
	 * userID
	 * @var	integer
	 */
	public $userID = 0;
	
	/**
	 * user
	 * @var	wcf\data\user\User
	 */
	public $user = null;
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->userID = intval($_REQUEST['id']);
		$this->user = new User($this->userID);
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
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
			'activityPointObjectTypes' => $this->activityPointObjectTypes,
			'user' => $this->user
		));
	}
}
