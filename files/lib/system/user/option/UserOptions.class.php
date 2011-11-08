<?php
namespace wcf\system\user\option;
use wcf\data\user\User;
use wcf\system\cache\CacheHandler;
use wcf\system\exception\SystemException;
use wcf\system\SingletonFactory;
use wcf\system\WCF;
use wcf\util\ClassUtil;
use wcf\util\FileUtil;

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
	 * Applies an option filter and caches the result.
	 * 
	 * @param	string		$optionFilter
	 */
	public function applyFilter($optionFilter) {
		// use raw data for empty filter (default)
		if (empty($optionFilter)) {
			$this->optionFilter = '';
			return;
		}
		
		$this->optionFilter = sha1($optionFilter);
		
		// apply filter and cache result
		if (!isset($this->filteredOptions[$hash])) {
			$this->filteredOptions[$hash] = array();
			
			foreach ($this->optionToCategories as $categoryName => $options) {
				$this->filteredOptions[$hash][$categoryName] = array();
				
				foreach ($options as $key => $optionName) {
					if (in_array($optionName, $optionFilter)) {
						$this->filteredOptions[$hash][$categoryName][$key] = $optionName;
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
				if (!empty($superCategory['categoryIconM'])) {
					// get relative path
					$path = '';
					if (empty($superCategory['packageDir'])) {
						$path = RELATIVE_WCF_DIR;
					}
					else {						
						$path = FileUtil::getRealPath(RELATIVE_WCF_DIR.$superCategory['packageDir']);
					}
					
					$superCategory['categoryIconM'] = $path . $superCategory['categoryIconM'];
				}
				
				$superCategory['options'] = $this->getCategoryOptions($superCategoryName, $user);
				
				if (count($superCategory['options']) > 0) {
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
			$options &= $this->optionToCategories;
		}
		else {
			$options &= $this->filteredOptions[$this->optionFilter];
		}
		
		if (isset($options[$categoryName])) {
			foreach ($options[$categoryName] as $optionName) {
				$option = $this->getOptionValue($optionName, $user);
				
				// add option to list
				if ($option) {
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
	 * @return	array
	 */
	public function getOptionValue($optionName, User $user) {
		if (!isset($this->options[$optionName])) return false;
		
		$visible = ($this->options[$optionName]['visible'] == 0
			|| ($this->options[$optionName]['visible'] == 1 && ($user->userID == WCF::getUser()->userID || WCF::getSession()->getPermission('admin.general.canViewPrivateUserOptions')))
			|| ($this->options[$optionName]['visible'] == 2 && $user->userID == WCF::getUser()->userID)
			|| ($this->options[$optionName]['visible'] == 3 && WCF::getSession()->getPermission('admin.general.canViewPrivateUserOptions')));
		if (!isset($this->options[$optionName]) || !$visible || $this->options[$optionName]['disabled']) return false;

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
			else $optionValue = $outputObj->getMediumOutput($user, $option, $optionValue);
		}
		else {
			$optionValue = StringUtil::encodeHTML($optionValue);
		}
		
		if (empty($optionValue) && empty($option->outputData)) return false;
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