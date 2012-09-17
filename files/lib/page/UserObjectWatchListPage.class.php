<?php
namespace wcf\page;

class UserObjectWatchListPage extends SortablePage {
	/**
	 * @see wcf\page\MultipleLinkPage::$objectListClassName
	 */	
	public $objectListClassName = 'wcf\data\user\object\watch\ViewableUserObjectWatchList';

}