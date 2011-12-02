<?php
namespace wcf\data\user\activity\event;
use wcf\data\user\UserProfile;
use wcf\data\DatabaseObjectDecorator;

/**
 * Provides methods for viewable user activity events.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.activity.event
 * @category 	Community Framework
 */
class ViewableUserActivityEvent extends DatabaseObjectDecorator {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	public static $baseClass = 'wcf\data\user\activity\event\UserActivityEvent';
	
	/**
	 * event text
	 * @var	string
	 */
	public $text = '';
	
	/**
	 * user profile
	 * @var wcf\data\user\UserProfile
	 */
	public $userProfile = null;
	
	/**
	 * Sets user profile.
	 * 
	 * @param	wcf\data\user\UserProfile	$userProfile
	 */
	public function setUserProfile(UserProfile $userProfile) {
		$this->userProfile = $userProfile;
	}
	
	/**
	 * Returns user profile.
	 * 
	 * @return	wcf\data\user\UserProfile
	 */
	public function getUserProfile() {
		return $this->userProfile;
	}
	
	/**
	 * Sets event text.
	 * 
	 * @param	string		$text
	 */
	public function setText($text) {
		$this->text = $text;
	}
	
	/**
	 * Returns event text.
	 * 
	 * @return	string
	 */
	public function getText() {
		return $this->text;
	}
}
