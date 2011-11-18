<?php
namespace wcf\system\user\profile\editable\content;
use wcf\data\user\User;

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
