<?php
namespace wcf\data\user\avatar;

/**
 * Any displayable avatar type should implement this class.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category 	Community Framework
 */
interface IUserAvatar {
	/**
	 * Returns the url to this avatar.
	 * 
	 * @return	string
	 */
	public function getURL();
	
	/**
	 * Returns the html code to display this avatar.
	 * 
	 * @return	string
	 */
	public function __toString();
	
	/**
	 * Scales the avatar to a specific maximum height.
	 * 
	 * @param	integer		$maxHeight
	 */
	public function setMaxHeight($maxHeight);
	
	/**
	 * Scales the avatar to a specific maximum size.
	 * 
	 * @param	integer		$maxWidth
	 * @param	integer		$maxHeight
	 */
	public function setMaxSize($maxWidth, $maxHeight);
	
	/**
	 * Returns the width of this avatar.
	 *
	 * @return	integer
	 */
	public function getWidth();
	
	/**
	 * Returns the height of this avatar.
	 *
	 * @return	integer
	 */
	public function getHeight();
}
?>