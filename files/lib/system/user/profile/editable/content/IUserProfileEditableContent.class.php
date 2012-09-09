<?php
namespace wcf\system\user\profile\editable\content;
use wcf\data\user\User;

/**
 * Every user profile editable content has to implement this interface.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.profile.editable.content
 * @category 	Community Framework
 */
interface IUserProfileEditableContent {
	/**
	 * Sets target user object.
	 * 
	 * @param	wcf\data\user\User	$user
	 */
	public function setUser(User $user);
	
	/**
	 * Prepares editing by returning a template with editing-capability.
	 * 
	 * @return	string
	 */
	public function beginEdit();
	
	/**
	 * Saves changed content.
	 * 
	 * @param	array		$data
	 */
	public function save(array $data);
	
	/**
	 * Returns the default template view.
	 * 
	 * @return	string
	 */
	public function restore();
}
