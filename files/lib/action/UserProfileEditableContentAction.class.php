<?php
namespace wcf\action;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\User;
use wcf\system\exception\AJAXException;
use wcf\system\exception\ValidateActionException;
use wcf\util\ArrayUtil;
use wcf\util\JSON;
use wcf\util\StringUtil;

class UserProfileEditableContentAction extends AbstractSecureAction {
	public $actionName = '';
	public $objectTypeIDs = array();
	public $userID = 0;
	public $user = null;
	
	protected $cache = null;
	
	/**
	 * @see	wcf\action\AbstractAction::_construct()
	 */
	public function __construct() {
		try {
			parent::__construct();
		}
		catch (\Exception $e) {
			if ($e instanceof AJAXException) {
				throw $e;
			}
			else {
				throw new AJAXException($e->getMessage());
			}
		}
	}
	
	/**
	 * @see	wcf\action\IAction::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_POST['actionName'])) $this->actionName = StringUtil::trim($_POST['actionName']);
		if (isset($_POST['objectTypeIDs']) && is_array($_POST['objectTypeIDs'])) $this->objectTypeIDs = ArrayUtil::toIntegerArray($_POST['objectTypeIDs']);
		if (isset($_POST['userID'])) {
			$this->userID = intval($_POST['userID']);
			$this->user = new User($this->userID);
		}
	}
	
	/**
	 * @see	wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		$this->validate();
		
		// process request
		$returnValues = array();
		foreach ($this->objectTypeIDs as $objectTypeID) {
			$object = $this->cache[$objectTypeID]->getProcessor();
			$object->setUser($this->user);
			
			switch ($this->actionName) {
				case 'beginEdit':
					$returnValues[$objectTypeID] = $object->beginEdit();
				break;
				
				default:
					throw new ValidateActionException("method '".$this->actionName."' is not a valid action");
				break;
			}
		}
		
		// send JSON response
		header('Content-type: application/json');
		echo JSON::encode($returnValues);
		exit;
	}
	
	protected function validate() {
		// validate user
		if ($this->user === null || !$this->user->userID) {
			throw new ValidateActionException("invalid user id given");
		}
		
		// validate object type ids
		$this->initCache();
		foreach ($this->objectTypeIDs as $objectTypeID) {
			if (!isset($this->cache[$objectTypeID])) {
				throw new ValidateActionException("object type id '".$objectTypeID."' is invalid");
			}
		}
	}
	
	protected function initCache() {
		$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.profileEditableContent');
		
		foreach ($objectTypes as $objectType) {
			$this->cache[$objectType->objectTypeID] = $objectType;
		}
	}
}
