<?php
namespace wcf\system\user\option;
use wcf\data\option\Option;
use wcf\data\user\User;
use wcf\system\cache\CacheHandler;
use wcf\system\exception\SystemException;
use wcf\system\SingletonFactory;
use wcf\system\WCF;
use wcf\util\ClassUtil;
use wcf\util\FileUtil;
use wcf\util\StringUtil;

/**
 * Shows a list of user options.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category 	Community Framework
 */
class UserOptions extends SingletonFactory {
	/**
	 * list of categories
	 * @var	array
	 */
	protected $categories = array();
	
	/**
	 * list of options
	 * @var	array
	 */
	protected $options = array();
	
	/**
	 * category structure
	 * @var	array
	 */
	protected $categoryStructure = array();
	
	/**
	 * raw option relations
	 * @var	array<array>
	 */
	protected $optionToCategories = array();
	
	/**
	 * list of output objects
	 * @var	array<wcf\system\option\user\IUserOptionOutput>
	 */
	protected $outputObjects = array();
	
	/**
	 * output type
	 * @var	string
	 */
	protected $outputType = 'normal';
	
	/**
	 * option filter
	 * @var	string
	 */
	protected $optionFilter = '';
	
	/**
	 * list of options with applied filter
	 * @var	array<array>
	 */
	protected $filteredOptions = array();
	
	/**
	 * Sets output type.
	 * 
	 * @param	string		$outputType
	 */
	public function setOutputType($outputType) {
		$this->outputType = $outputType;
	}
	
	/**
	 * Applies an option _or_ category filter and caches the result.
	 * 
	 * @param	array		$categoryFilter
	 * @param	array		$optionFilter
	 */
	public function applyFilter(array $categoryFilter, array $optionFilter = array()) {
		// use raw data for empty filter (default)
		if (empty($categoryFilter) && empty($categoryFilter)) {
			$this->optionFilter = '';
			return;
		}
		
		$this->optionFilter = sha1(serialize($categoryFilter).serialize($optionFilter));
		
		// apply filter and cache result
		if (!isset($this->filteredOptions[$this->optionFilter])) {
			$this->filteredOptions[$this->optionFilter] = array();
			
			foreach ($this->optionToCategories as $categoryName => $options) {
				// filter by category
				if (empty($optionFilter)) {
					if (in_array($categoryName, $categoryFilter)) {
						$this->filteredOptions[$this->optionFilter][$categoryName] = $options;
					}
				}
				else {
					// filter by option
					$this->filteredOptions[$this->optionFilter][$categoryName] = array();
					
					foreach ($options as $key => $optionName) {
						if (in_array($optionName, $optionFilter)) {
							$this->filteredOptions[$this->optionFilter][$categoryName][$key] = $optionName;
						}
					}
				}
			}
		}
	}
	
	/**
	 * Creates a new UserOptions object.
	 */
	protected function init() {
		$cacheName = 'user-option-'.PACKAGE_ID;
		CacheHandler::getInstance()->addResource(
			$cacheName,
			WCF_DIR.'cache/cache.'.$cacheName.'.php',
			'wcf\system\cache\builder\OptionCacheBuilder'
		);
		
		$this->categories = CacheHandler::getInstance()->get($cacheName, 'categories');
		$this->options = CacheHandler::getInstance()->get($cacheName, 'options');
		$this->categoryStructure = CacheHandler::getInstance()->get($cacheName, 'categoryStructure');
		$this->optionToCategories = CacheHandler::getInstance()->get($cacheName, 'optionToCategories');
	}
	
	/**
	 * Returns the tree of options.
	 * 
	 * @param	wcf\data\user\User	$user
	 * @param	string			$parentCategoryName
	 * @return	array
	 */
	public function getOptionTree(User $user, $parentCategoryName = '') {
		$options = array();
		
		if (isset($this->categoryStructure[$parentCategoryName])) {
			// get super categories
			foreach ($this->categoryStructure[$parentCategoryName] as $superCategoryName) {
				$superCategory = $this->categories[$superCategoryName];
				
				// add icon path
				if (!empty($superCategory->categoryIconM)) {
					// get relative path
					$path = '';
					if (empty($superCategory->packageDir)) {
						$path = RELATIVE_WCF_DIR;
					}
					else {						
						$path = FileUtil::getRealPath(RELATIVE_WCF_DIR.$superCategory->packageDir);
					}
					
					$superCategory->setIconPath($path . $superCategory->categoryIconM, 'M');
				}
				
				$superCategory->setOptions($this->getCategoryOptions($user, $superCategoryName));
				
				if (count($superCategory->options) > 0) {
					$options[$superCategoryName] = $superCategory;
				}
			}
		}
	
		return $options;
	}
	
	/**
	 * Returns a list with the options of a specific option category.
	 * 
	 * @param	wcf\data\user\User	$user
	 * @param	string			$categoryName
	 * @return	array
	 */
	public function getCategoryOptions(User $user, $categoryName = '') {
		$children = array();
		
		// get sub categories
		if (isset($this->categoryStructure[$categoryName])) {
			foreach ($this->categoryStructure[$categoryName] as $subCategoryName) {
				$children = array_merge($children, $this->getCategoryOptions($subCategoryName, $user));
			}
		}
		
		// get options
		if (empty($this->optionFilter)) {
			$options =& $this->optionToCategories;
		}
		else {
			$options =& $this->filteredOptions[$this->optionFilter];
		}
		
		if (isset($options[$categoryName])) {
			foreach ($options[$categoryName] as $optionName) {
				$option = $this->getOptionValue($optionName, $user);
				
				// add option to list
				if ($option !== null) {
					$children[] = $option;
				}
			}
		}
		
		return $children;
	}
	
	/**
	 * Returns the data of a user option.
	 * 
	 * @param	string				$optionName
	 * @return	wcf\data\user\option\UserOption
	 */
	public function getOption($optionName) {
		if (isset($this->options[$optionName])) return $this->options[$optionName];
		return null;
	}
	
	
	/**
	 * Returns the formatted value of a user option.
	 * 
	 * @param	string			$optionName
	 * @param	wcf\data\user\User	$user
	 * @return	wcf\data\option\Option
	 */
	public function getOptionValue($optionName, User $user) {
		if (!isset($this->options[$optionName])) return null;
		
		$bitmask = $this->options[$optionName]->visible;
		// check if option is hidden
		if ($bitmask & Option::VISIBILITY_NONE) {
			$visible = false;
		}
		// proceed if option is visible for all
		else if ($bitmask & Option::VISIBILITY_OTHER) {
			$visible = true;
		}
		else {
			$isAdmin = $isOwner = $visible = false;
			// check admin permissions
			if ($bitmask & Option::VISIBILITY_ADMINISTRATOR) {
				if (WCF::getSession()->getPermission('admin.general.canViewPrivateUserOptions')) {
					$isAdmin = true;
				}
			}
			
			// check owner state
			if ($bitmask & Option::VISIBILITY_OWNER) {
				if ($user->userID == WCF::getUser()->userID) {
					$isOwner = true;
				}
			}
			
			if ($isAdmin) {
				$visible = true;
			}
			else if ($isOwner) {
				$visible = true;
			}
		}
		
		if (!isset($this->options[$optionName]) || !$visible || $this->options[$optionName]->disabled) return null;

		// get option data
		$option = $this->options[$optionName];
		
		// get option value
		$optionValue = $user->{'userOption'.$option->optionID};
		
		// use output class
		if ($option->outputClass) {
			$outputObj = $this->getOutputObject($option->outputClass);
			
			if ($outputObj instanceof IUserOptionOutputContactInformation) {
				$option->outputData = $outputObj->getOutputData($user, $option, $optionValue);
			}
			
			if ($this->outputType == 'normal') $option->optionValue = $outputObj->getOutput($user, $option, $optionValue);
			else if ($this->outputType == 'short') $option->optionValue = $outputObj->getShortOutput($user, $option, $optionValue);
			else $option->optionValue = $outputObj->getMediumOutput($user, $option, $optionValue);
		}
		else {
			$option->optionValue = StringUtil::encodeHTML($optionValue);
		}
		
		if (empty($option->optionValue) && empty($option->outputData)) return null;
		return $option;
	}
		
	/**
	 * Returns an object of the requested option output type.
	 * 
	 * @param	string			$type
	 * @return	UserOptionOutput
	 */
	public function getOutputObject($className) {
		if (!isset($this->outputObjects[$className])) {
			// create instance
			if (!class_exists($className)) {
				throw new SystemException("unable to find class '".$className."'");
			}
			
			// validate interface
			if (!ClassUtil::isInstanceOf($className, 'wcf\system\user\option\IUserOptionOutput')) {
				throw new SystemException("'".$className."' should implement wcf\system\user\option\IUserOptionOutput");
			}
			
			$this->outputObjects[$className] = new $className();
		}
		
		return $this->outputObjects[$className];
	}
}
?>
