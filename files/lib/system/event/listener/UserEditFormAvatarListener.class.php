<?php
namespace wcf\system\event\listener;
use wcf\data\user\avatar\Gravatar;
use wcf\data\user\avatar\UserAvatar;
use wcf\data\user\avatar\UserAvatarAction;
use wcf\system\event\IEventListener;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles the user avatars in user administration.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class UserEditFormAvatarListener implements IEventListener {
	/**
	 * instance of UserAddForm
	 * @var	wcf\acp\form\UserAddForm
	 */
	protected $eventObj = null;
	
	/**
	 * user object
	 * @var	wcf\data\user\User
	 */
	protected $user = null;
	
	/**
	 * user avatar object
	 * @var wcf\data\user\avatar\UserAvatar
	 */
	public $userAvatar = null;
	
	/**
	 * avatar type
	 * @var	string
	 */
	public $avatarType = 'none';
	
	/**
	 * true to disable this avatar
	 * @var boolean
	 */
	public $disableAvatar = 0;
	
	/**
	 * reason
	 * @var string
	 */
	public $disableAvatarReason = '';
	
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$this->eventObj = $eventObj;
		$this->user = $eventObj->user;
		$this->$eventName();
	}
	
	/**
	 * Handles the assignVariables event.
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign(array(
			'avatarType' => $this->avatarType,
			'disableAvatar' => $this->disableAvatar,
			'disableAvatarReason' => $this->disableAvatarReason,
			'userAvatar' => $this->userAvatar
		));
	}
	
	/**
	 * Handles the readData event.
	 */
	protected function readData() {
		if (empty($_POST)) {
			$this->disableAvatar = $this->user->disableAvatar;
			$this->disableAvatarReason = $this->user->disableAvatarReason;
			
			if ($this->user->avatarID) $this->avatarType = 'custom';
			else if (MODULE_GRAVATAR && $this->user->enableGravatar) $this->avatarType = 'gravatar';
		}
		
		// get avatar object
		if ($this->avatarType == 'custom') {
			$this->userAvatar = new UserAvatar($this->user->avatarID);
		}
	}
	
	/**
	 * Handles the readFormParameters event.
	 */
	protected function readFormParameters() {
		if (isset($_POST['avatarType'])) $this->avatarType = $_POST['avatarType'];
		if (!empty($_POST['disableAvatar'])) $this->disableAvatar = 1;
		if (isset($_POST['disableAvatarReason'])) $this->disableAvatarReason = StringUtil::trim($_POST['disableAvatarReason']);
	}
	
	/**
	 * Handles the validate event.
	 */
	protected function validate() {
		if ($this->avatarType != 'custom' && $this->avatarType != 'gravatar') $this->avatarType = 'none';
		
		try {
			switch ($this->avatarType) {
				case 'custom':
					if (!$this->user->avatarID) {
						throw new UserInputException('customAvatar');
					}
					break;
			
				case 'gravatar':
					if (!MODULE_GRAVATAR) {
						$this->avatarType = 'none';
						break;
					}
					
					// test gravatar
					if (!Gravatar::test($this->user->email)) {
						throw new UserInputException('gravatar', 'notFound');
					}
			}
		}
		catch (UserInputException $e) {
			$this->eventObj->errorType[$e->getField()] = $e->getType();
		}
	}
	
	/**
	 * Handles the save event.
	 */
	protected function save() {
		if ($this->avatarType != 'custom') {
			// delete custom avatar
			if ($this->user->avatarID) {
				$action = new UserAvatarAction(array($this->user->avatarID), 'delete');
				$action->executeAction();
			}
		}
		
		// update user
		switch ($this->avatarType) {
			case 'none':
				$data = array(
					'avatarID' => null,
					'enableGravatar' => 0
				);
				break;
		
			case 'custom':
				$data = array(
					'avatarID' => null,
					'enableGravatar' => 0
				);
				break;
		
			case 'gravatar':
				$data = array(
					'avatarID' => null,
					'enableGravatar' => 1
				);
				break;
		}
		
		$data['disableAvatar'] = $this->disableAvatar;
		$data['disableAvatarReason'] = $this->disableAvatarReason;
		$this->eventObj->additionalFields = array_merge($this->eventObj->additionalFields, $data);
	}
}
