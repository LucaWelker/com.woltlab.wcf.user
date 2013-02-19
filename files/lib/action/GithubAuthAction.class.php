<?php
namespace wcf\action;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\SystemException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\HTTPRequest;
use wcf\util\JSON;
use wcf\util\StringUtil;

/**
 * Handles github auth.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	action
 * @category	Community Framework
 */
class GithubAuthAction extends AbstractAction {
	/**
	 * @see	\wcf\action\AbstractAction::$neededModules
	 */
	public $neededModules = array('GITHUB_PUBLIC_KEY', 'GITHUB_PRIVATE_KEY');
	
	/**
	 * @see	\wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check whether we have the code
		if (!isset($_GET['code'])) throw new IllegalLinkException();
		
		try {
			// call api
			$request = new HTTPRequest('https://github.com/login/oauth/access_token', array(), array(
				'client_id' => GITHUB_PUBLIC_KEY,
				'client_secret' => GITHUB_PRIVATE_KEY,
				'code' => $_GET['code']
			));
			$request->execute();
			$reply = $request->getReply();
			
			$content = $reply['body'];
		}
		catch (SystemException $e) {
			throw new IllegalLinkException();
		}
		
		// extract data
		parse_str($content, $data);
		
		// check whether the token is okay
		if (isset($data['error'])) throw new IllegalLinkException();
		
		$user = $this->getUser($data['access_token']);
		
		if ($user->userID) {
			// login
			WCF::getSession()->changeUser($user);
			WCF::getSession()->update();
			HeaderUtil::redirect(LinkHandler::getInstance()->getLink());
		}
		else {
			if (WCF::getUser()->userID) {
				try {
					// fetch userdata
					$request = new HTTPRequest('https://api.github.com/user?access_token='.$data['access_token']);
					$request->execute();
					$reply = $request->getReply();
					$userData = JSON::decode(StringUtil::trim($reply['body']));
					
					WCF::getSession()->register('__githubUsername', $userData['login']);
				}
				catch (SystemException $e) { }
				
				WCF::getSession()->register('__githubToken', $data['access_token']);
				
				HeaderUtil::redirect(LinkHandler::getInstance()->getLink('AccountManagement').'#3rdParty');
			}
			else {
				try {
					// fetch userdata
					$request = new HTTPRequest('https://api.github.com/user?access_token='.$data['access_token']);
					$request->execute();
					$reply = $request->getReply();
					$userData = JSON::decode(StringUtil::trim($reply['body']));
					
					WCF::getSession()->register('__username', $userData['login']);
				}
				catch (\wcf\system\exception\SystemException $e) { }
				
				if (isset($userData) && isset($userData['email']) && $userData['email'] !== null) {
					WCF::getSession()->register('__email', $userData['email']);
				}
				else {
					try {
						$request = new HTTPRequest('https://api.github.com/user/emails?access_token='.$data['access_token']);
						$request->execute();
						$reply = $request->getReply();
						$emails = JSON::decode(StringUtil::trim($reply['body']));
						
						if (is_string($emails[0])) {
							$email = $emails[0];
						}
						else {
							$email = $emails[0]['email'];
							foreach ($emails as $tmp) {
								if ($tmp['primary']) $email = $tmp['email'];
								break;
							}
						}
						WCF::getSession()->register('__email', $email);
					}
					catch (SystemException $e) { }
				}
				
				// save token
				WCF::getSession()->register('__githubToken', $data['access_token']);
				// we assume that bots won't register on github first
				WCF::getSession()->register('recaptchaDone', true);
				
				WCF::getSession()->update();
				HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Register'));
			}
		}
		
		$this->executed();
		exit;
	}
	
	/**
	 * Fetches the User with the given access-token.
	 * 
	 * @param	string	$token	access-token
	 * @return	\wcf\data\user\User
	 */
	public function getUser($token) {
		$sql = "SELECT	userID
			FROM	wcf".WCF_N."_user_option_value
			WHERE	userOption".User::getUserOptionID('githubToken')." = ?";
		$stmt = WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array($token));
		$row = $stmt->fetchColumn();
		
		$user = new User($row['userID']);
		return $user;
	}
}
