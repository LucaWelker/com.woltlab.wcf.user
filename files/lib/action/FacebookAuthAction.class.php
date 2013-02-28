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
 * Handles facebook auth.
 * 
 * @author	Tim Duesterhus
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	action
 * @category	Community Framework
 */
class FacebookAuthAction extends AbstractAction {
	/**
	 * @see	wcf\action\AbstractAction::$neededModules
	 */
	public $neededModules = array('FACEBOOK_PUBLIC_KEY', 'FACEBOOK_PRIVATE_KEY');
	
	/**
	 * @see	wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		$callbackURL = LinkHandler::getInstance()->getLink('FacebookAuth'); // TODO: appendsession Y/N?
		if (isset($_GET['code']) && isset($_GET['state'])) {
			if ($_GET['state'] != WCF::getSession()->getVar('__facebookInit')) throw new IllegalLinkException();
			
			try {
				// call api
				$request = new HTTPRequest('https://graph.facebook.com/oauth/access_token?client_id='.FACEBOOK_PUBLIC_KEY.'&redirect_uri='.rawurlencode($callbackURL).'&client_secret='.FACEBOOK_PRIVATE_KEY.'&code='.rawurlencode($_GET['code']));
				$request->execute();
				$reply = $request->getReply();
				
				$content = $reply['body'];
			}
			catch (SystemException $e) {
				throw new IllegalLinkException();
			}
			
			// extract data
			parse_str($content, $data);
			
			try {
				// call api
				$request = new HTTPRequest('https://graph.facebook.com/me?access_token='.rawurlencode($data['access_token']));
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
					throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.facebook.connect.error.inuse'));
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
					WCF::getSession()->register('__facebookUsername', $data['name']);
					WCF::getSession()->register('__facebookData', $data);
					
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('AccountManagement').'#3rdParty');
				}
				else {
					WCF::getSession()->register('__username', $data['name']);
					if (isset($data['email'])) WCF::getSession()->register('__email', $data['email']);
						
					// save token
					WCF::getSession()->register('__facebookData', $data);
					
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
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.facebook.login.error.'.$_GET['error']));
		}
		
		$token = StringUtil::getRandomID();
		WCF::getSession()->register('__facebookInit', $token);
		HeaderUtil::redirect("https://www.facebook.com/dialog/oauth?client_id=".FACEBOOK_PUBLIC_KEY. "&redirect_uri=".rawurlencode($callbackURL)."&state=".$token."&scope=email,user_about_me,user_birthday,user_interests,user_location,user_website");
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
			WHERE	userOption".User::getUserOptionID('facebookUserID')." = ?";
		$stmt = WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array($userID));
		$row = $stmt->fetchArray();
		
		$user = new User($row['userID']);
		return $user;
	}
}
