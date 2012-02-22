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
	protected $output = '';
	
	/**
	 * event text (short version)
	 * @var	string
	 */
	protected $shortOutput = '';
	
	/**
	 * event icon
	 * @var string
	 */
	protected $icon = '';
	
	/**
	 * user profile
	 * @var wcf\data\user\UserProfile
	 */
	protected $userProfile = null;
	
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
	 * @param	string		$output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}
	
	/**
	 * Returns event text.
	 * 
	 * @return	string
	 */
	public function getOutput() {
		return $this->output;
	}
	
	/**
	 * Sets event text.
	 * 
	 * @param	string		$output
	 */
	public function setShortOutput($output) {
		$this->shortOutput = $output;
	}
	
	/**
	 * Returns event text.
	 * 
	 * @return	string
	 */
	public function getShortOutput() {
		return $this->shortOutput;
	}
	
	/**
	 * Sets event icon.
	 * 
	 * @param	string		$icon
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
	}
	
	/**
	 * Returns event icon.
	 * 
	 * @return	string
	 */
	public function getIcon() {
		return $this->icon;
	}
}
