<?php
namespace wcf\action;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\SystemException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\HTTPRequest;
use wcf\util\JSON;
use wcf\util\StringUtil;

/**
 * Handles google auth.
 * 
 * @author	Tim Duesterhus
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	action
 * @category	Community Framework
 */
class GoogleAuthAction extends AbstractAction {
	/**
	 * @see	wcf\action\AbstractAction::$neededModules
	 */
	public $neededModules = array('GOOGLE_PUBLIC_KEY', 'GOOGLE_PRIVATE_KEY');
	
	/**
	 * @see	wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		$callbackURL = LinkHandler::getInstance()->getLink('GoogleAuth', array(
			'appendSession' => false
		));
		if (isset($_GET['code']) && isset($_GET['state'])) {
			if ($_GET['state'] != WCF::getSession()->getVar('__googleInit')) throw new IllegalLinkException();
			try {
				// call api
				$request = new HTTPRequest('https://accounts.google.com/o/oauth2/token', array(), array(
					'code' => $_GET['code'],
					'client_id' => GOOGLE_PUBLIC_KEY,
					'client_secret' => GOOGLE_PRIVATE_KEY,
					'redirect_uri' => $callbackURL,
					'grant_type' => 'authorization_code'
				));
				$request->execute();
				$reply = $request->getReply();
				
				$content = $reply['body'];
			}
			catch (SystemException $e) {
				throw new IllegalLinkException();
			}
			
			// extract data
			$data = JSON::decode($content);
			
			try {
				// call api
				$request = new HTTPRequest('https://www.googleapis.com/oauth2/v1/userinfo');
				$request->addHeader('Authorization', 'Bearer '.$data['access_token']);
				$request->execute();
				$reply = $request->getReply();
				
				$content = $reply['body'];
			}
			catch (SystemException $e) {
				throw new IllegalLinkException();
			}
			
			$data = JSON::decode($content);
			
			$user = $this->getUser($data['id']);
			
			if ($user->userID) {
				if (WCF::getUser()->userID) {
					throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.google.connect.error.inuse'));
				}
				else {
					// login
					WCF::getSession()->changeUser($user);
					WCF::getSession()->update();
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink());
				}
			}
			else {
				if (WCF::getUser()->userID) {
					WCF::getSession()->register('__googleUsername', $data['name']);
					WCF::getSession()->register('__googleData', $data);
					
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('AccountManagement').'#3rdParty');
				}
				else {
					WCF::getSession()->register('__username', $data['name']);
					if (isset($data['email'])) WCF::getSession()->register('__email', $data['email']);
						
					// save token
					WCF::getSession()->register('__googleData', $data);
					
					// we assume that bots won't register on facebook first
					WCF::getSession()->register('recaptchaDone', true);
					
					WCF::getSession()->update();
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Register'));
				}
			}
			
			$this->executed();
			exit;
		}
		if (isset($_GET['error'])) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.google.login.error.'.$_GET['error']));
		}
		
		$token = StringUtil::getRandomID();
		WCF::getSession()->register('__googleInit', $token);
		HeaderUtil::redirect("https://accounts.google.com/o/oauth2/auth?client_id=".rawurlencode(GOOGLE_PUBLIC_KEY). "&redirect_uri=".rawurlencode($callbackURL)."&state=".$token."&scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&response_type=code");
		$this->executed();
		exit;
	}
	
	/**
	 * Fetches the User with the given userID.
	 * 
	 * @param	integer			$userID
	 * @return	wcf\data\user\User
	 */
	public function getUser($userID) {
		$sql = "SELECT	userID
			FROM	wcf".WCF_N."_user_option_value
			WHERE	userOption".User::getUserOptionID('googleUserID')." = ?";
		$stmt = WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array($userID));
		$row = $stmt->fetchArray();
		
		$user = new User($row['userID']);
		return $user;
	}
}
