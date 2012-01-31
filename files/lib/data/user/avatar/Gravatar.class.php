<?php
namespace wcf\data\user\avatar;
use wcf\system\exception\SystemException;
use wcf\util\FileUtil;
use wcf\util\StringUtil;

/**
 * Represents a gravatar.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category 	Community Framework
 * @see		http://www.gravatar.com
 */
class Gravatar implements IUserAvatar {
	/**
	 * gravatar base url
	 * @var string
	 */
	const GRAVATAR_BASE = 'http://gravatar.com/avatar/%s?s=%d&r=g&d=%s';
	
	/**
	 * gravatar local cache location
	 * @var string
	 */
	const GRAVATAR_CACHE_LOCATION = 'images/avatars/gravatars/%s-%s.png';
	
	/**
	 * gravatar expire time (days)
	 * @var integer
	 */
	const GRAVATAR_CACHE_EXPIRE = 7;

	/**
	 * gravatar e-mail address
	 * @var	string
	 */
	public $gravatar = '';

	/**
	 * size of the gravatar
	 * @var integer
	 */
	public $size = 150;
	
	/**
	 * urls of this gravatar
	 * @var array<string>
	 */
	protected $url = array();
	
	/**
	 * Creates a new Gravatar object.
	 * 
	 * @param	string		$gravatar
	 */
	public function __construct($gravatar) {
		$this->gravatar = $gravatar;
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getURL()
	 */
	public function getURL($size = null) {
		if ($size === null) $size = $this->size;
		
		if (!isset($this->url[$size])) {
			// try to use cached gravatar
			$cachedFilename = sprintf(self::GRAVATAR_CACHE_LOCATION, md5(StringUtil::toLowerCase($this->gravatar)), $size);
			if (file_exists(WCF_DIR.$cachedFilename) && filemtime(WCF_DIR.$cachedFilename) > (TIME_NOW - (self::GRAVATAR_CACHE_EXPIRE * 86400))) {
				$this->url[$size] = RELATIVE_WCF_DIR.$cachedFilename;
			}
			else {
				$gravatarURL = sprintf(self::GRAVATAR_BASE, md5(StringUtil::toLowerCase($this->gravatar)), $size, 'mm');
				try {
					$tmpFile = FileUtil::downloadFileFromHttp($gravatarURL, 'gravatar');
					copy($tmpFile, WCF_DIR.$cachedFilename);
					@unlink($tmpFile);
					@chmod(WCF_DIR.$cachedFilename, 0777);
					$this->url[$size] = RELATIVE_WCF_DIR.$cachedFilename;
				}
				catch (SystemException $e) {
					$this->url[$size] = RELATIVE_WCF_DIR . 'images/avatars/avatar-default.png';
				}
			}
		}
		
		return $this->url[$size];
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getImageTag()
	 */
	public function getImageTag($size = null) {
		if ($size === null) $size = $this->size;
		
		return '<img src="'.$this->getURL($size).'" style="width: '.$size.'px; height: '.$size.'px" alt=".WCF::getLanguage()->get('wcf.user.avatar.alt')." />';
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getWidth()
	 */
	public function getWidth() {
		return $this->size;
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getHeight()
	 */
	public function getHeight() {
		return $this->size;
	}
}
