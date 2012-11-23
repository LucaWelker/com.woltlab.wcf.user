<?php
namespace wcf\data\user\avatar;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\user\UserEditor;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\image\ImageHandler;
use wcf\system\upload\AvatarUploadFileValidationStrategy;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;
use wcf\util\FileUtil;

/**
 * Executes avatar-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category	Community Framework
 */
class UserAvatarAction extends AbstractDatabaseObjectAction {
	/**
	 * Validates the upload action.
	 */
	public function validateUpload() {
		// check upload permissions
		if (!WCF::getSession()->getPermission('user.profile.avatar.canUploadAvatar') || WCF::getUser()->disableAvatar) {
			throw new PermissionDeniedException();
		}
		
		if (count($this->parameters['__files']->getFiles()) != 1) {
			throw new UserInputException('files');
		}
		
		// check max filesize, allowed file extensions etc.
		$this->parameters['__files']->validateFiles(new AvatarUploadFileValidationStrategy(PHP_INT_MAX, explode("\n", WCF::getSession()->getPermission('user.profile.avatar.allowedFileExtensions'))));
	}
	
	/**
	 * Handles uploaded attachments.
	 */
	public function upload() {
		// save files
		$files = $this->parameters['__files']->getFiles();
		$file = $files[0];
		
		try {
			if (!$file->getValidationErrorType()) {
				// shrink avatar if necessary
				$fileLocation = $file->getLocation();
				$imageData = getimagesize($fileLocation);
				if ($imageData[0] > MAX_AVATAR_WIDTH || $imageData[1] > MAX_AVATAR_HEIGHT) {
					try {
						$adapter = ImageHandler::getInstance()->getAdapter();
						$adapter->loadFile($fileLocation);
						$fileLocation = FileUtil::getTemporaryFilename();
						$thumbnail = $adapter->createThumbnail(MAX_AVATAR_WIDTH, MAX_AVATAR_HEIGHT, false);
						$adapter->writeImage($thumbnail, $fileLocation);
						$imageData = getimagesize($fileLocation);
					}
					catch (SystemException $e) {
						throw new UserInputException('avatar', 'tooLarge');
					}
				}
				
				// check filesize (after shrink)
				if (@filesize($fileLocation) > WCF::getSession()->getPermission('user.profile.avatar.maxSize')) {
					throw new UserInputException('avatar', 'tooLarge');
				}
				
				$data = array(
					'avatarName' => $file->getFilename(),
					'avatarExtension' => $file->getFileExtension(),
					'width' => $imageData[0],
					'height' => $imageData[1],
					'userID' => WCF::getUser()->userID,
					'fileHash' => sha1_file($fileLocation)
				);
				
				// create avatar
				$avatar = UserAvatarEditor::create($data);
				
				// check avatar directory
				// and create subdirectory if necessary
				$dir = dirname($avatar->getLocation());
				if (!@file_exists($dir)) {
					FileUtil::makePath($dir, 0777);
				}
				
				// move uploaded file
				if (@copy($fileLocation, $avatar->getLocation())) {
					@unlink($fileLocation);
					
					// create thumbnails
					$action = new UserAvatarAction(array($avatar), 'generateThumbnails');
					$action->executeAction();
					
					// delete old avatar
					if (WCF::getUser()->avatarID) {
						$action = new UserAvatarAction(array(WCF::getUser()->avatarID), 'delete');
						$action->executeAction();
					}
					
					// update user
					$userEditor = new UserEditor(WCF::getUser());
					$userEditor->update(array(
						'avatarID' => $avatar->avatarID,
						'enableGravatar' => 0
					));
					
					// reset user storage
					UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'avatar', 1);
					
					// return result
					return array(
						'errorType' => '',
						'url' => $avatar->getURL(96)
					);
				}
				else {
					// moving failed; delete avatar
					$editor = new UserAvatarEditor($avatar);
					$editor->delete();
					throw new UserInputException('avatar', 'uploadFailed');
				}
			}
		}
		catch (UserInputException $e) {
			$file->setValidationErrorType($e->getType());
		}
		
		return array('errorType' => $file->getValidationErrorType());
	}
	
	/**
	 * Generates the thumbnails of the avatars in all needed sizes.
	 */
	public function generateThumbnails() {
		if (empty($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $avatar) {
			$adapter = ImageHandler::getInstance()->getAdapter();
			$adapter->loadFile($avatar->getLocation());
			
			foreach (UserAvatar::$avatarThumbnailSizes as $size) {
				if ($avatar->width <= $size && $avatar->height <= $size) break 2;
				
				$thumbnail = $adapter->createThumbnail($size, $size, false);
				$adapter->writeImage($thumbnail, $avatar->getLocation($size));
			}
		}
	}
}
