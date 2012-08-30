<?php
namespace wcf\data\user\online;
use wcf\data\user\UserProfile;
use wcf\system\WCF;
use wcf\util\StringUtil;

class UserOnline extends UserProfile {
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
}