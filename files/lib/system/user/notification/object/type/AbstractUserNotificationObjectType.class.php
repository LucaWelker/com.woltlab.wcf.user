<?php
namespace wcf\system\user\notification\object\type;
use wcf\data\object\type\AbstractObjectTypeProcessor;

/**
 * Provides a default implementation of IUserNotificationObjectType.
 *
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.notification.object.type
 * @category	Community Framework
 */
class AbstractUserNotificationObjectType extends AbstractObjectTypeProcessor implements IUserNotificationObjectType {
	/**
	 * class name of the object decorator
	 * @var	string
	 */
	protected static $decoratorClassName = '';
	
	/**
	 * object class name
	 * @var	string
	 */
	protected static $objectClassName = '';
	
	/**
	 * class name for DatabaseObjectList
	 * @var	string
	 */
	protected static $objectListClassName = '';
	
	/**
	 * @see	wcf\system\user\notification\object\type\IUserNotificationObjectType::getObjectsByIDs()
	 */
	public function getObjectsByIDs(array $objectIDs) {
		$indexName = call_user_func(array(static::$objectClassName, 'getDatabaseTableIndexName'));
		$tableAlias = call_user_func(array(static::$objectClassName, 'getDatabaseTableAlias'));
		
		$objectList = new static::$objectListClassName();
		$objectList->getConditionBuilder()->add($tableAlias.'.'.$indexName.' IN (?)', array($objectIDs));
		$objectList->sqLimit = 0;
		$objectList->readObjects();
	
		$objects = array();
		foreach ($objectList as $object) {
			$objects[$object->$indexName] = new static::$decoratorClassName($object);
		}
	
		foreach ($objectIDs as $objectID) {
			// append empty objects for unknown ids
			if (!isset($objects[$objectID])) {
				$objects[$objectID] = new static::$decoratorClassName(new static::$objectClassName(null, array($indexName => $objectID)));
			}
		}
	
		return $objects;
	}
}
