<?php
namespace wcf\data\user\online;
use wcf\data\user\UserProfile;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\util\UserUtil;


class UserOnline extends UserProfile {
	protected $location = '';
	
	/**
	 * Returns the formatted username.
	 * 
	 * @return	string
	 */
	public function getFormattedUsername() {
		$username = StringUtil::encodeHTML($this->username);
		
		if ($this->userOnlineMarking && $this->userOnlineMarking != '%s') {
			$username = sprintf($this->userOnlineMarking, $username);
		}
		
		if ($this->canViewOnlineStatus == 3) {
			$username .= WCF::getLanguage()->get('wcf.user.usersOnline.invisible');
		}
		
		return $username;
	}
	
	public function setLocation($location) {
		$this->location = $location;
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	/**
	 * Returns the ip address.
	 * 
	 * @return	string
	 */
	public function getFormattedIPAddress() {
		if ($address = UserUtil::convertIPv6To4($this->ipAddress)) {
			return $address;
		}
		
		return $this->ipAddress;
	}
	
	/**
	 * Tries to retrieve browser name and version.
	 * 
	 * @return	string
	 */
	public function getBrowser() {
		// firefox
		if (preg_match('~firefox/([\d\.]+)~i', $this->userAgent, $match)) {
			return 'Firefox '.$match[1];
		}
		
		// ie
		if (preg_match('~msie ([\d\.]+)~i', $this->userAgent, $match)) {
			return 'Internet Explorer '.$match[1];
		}
		
		// chrome
		if (preg_match('~chrome/([\d\.]+)~i', $this->userAgent, $match)) {
			return 'Chrome '.$match[1];
		}
		
		// safari
		if (preg_match('~([\d\.]+) safari~i', $this->userAgent, $match)) {
			return 'Safari '.$match[1];
		}
		
		// opera
		if (preg_match('~opera.*version/([\d\.]+)~i', $this->userAgent, $match)) {
			return 'Opera '.$match[1];
		}
		
		return $this->userAgent;
	}
}