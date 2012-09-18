<?php
namespace wcf\page;
use wcf\system\WCF;

class UserObjectWatchListPage extends SortablePage {
	/**
	 * @see wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * @see wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'lastChangeTime';
	
	/**
	 * @see wcf\page\SortablePage::$defaultSortOrder
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @see wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('title', 'username', 'time', 'lastChangeTime');
	
	/**
	 * @see wcf\page\MultipleLinkPage::$objectListClassName
	 */	
	public $objectListClassName = 'wcf\data\user\object\watch\ViewableUserObjectWatchList';
}