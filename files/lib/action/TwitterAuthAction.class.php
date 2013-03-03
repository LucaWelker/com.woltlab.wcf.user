<?php
namespace wcf\action;
use wcf\data\user\User;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\SystemException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\HTTPRequest;
use wcf\util\StringUtil;

/**
 * Handles twitter auth.
 * 
 * @author	Tim Duesterhus
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	action
 * @category	Community Framework
 */
class TwitterAuthAction extends AbstractAction {
	/**
	 * @see	wcf\action\AbstractAction::$neededModules
	 */
	public $neededModules = array('TWITTER_PUBLIC_KEY', 'TWITTER_PRIVATE_KEY');
	
	/**
	 * @see	wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		// user accepted
		if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
			// fetch data created in the first step
			$initData = WCF::getSession()->getVar('__twitterInit');
			if (!$initData) throw new IllegalLinkException();
			
			// validate oauth_token
			if ($_GET['oauth_token'] !== $initData['oauth_token']) throw new IllegalLinkException();
			
			try {
				// fetch access_token
				$oauthHeader = array(
					'oauth_consumer_key' => TWITTER_PUBLIC_KEY,
					'oauth_nonce' => StringUtil::getRandomID(),
					'oauth_signature_method' => 'HMAC-SHA1',
					'oauth_timestamp' => TIME_NOW,
					'oauth_version' => '1.0',
					'oauth_token' => $initData['oauth_token']
				);
				$postData = array(
					'oauth_verifier' => $_GET['oauth_verifier']
				);
				
				$signature = $this->createSignature('https://api.twitter.com/oauth/access_token', array_merge($oauthHeader, $postData));
				$oauthHeader['oauth_signature'] = $signature;
				
				$request = new HTTPRequest('https://api.twitter.com/oauth/access_token', array(), $postData);
				$request->addHeader('Authorization', 'OAuth '.$this->buildOAuthHeader($oauthHeader));
				$request->execute();
				$reply = $request->getReply();
				$content = $reply['body'];
			}
			catch (SystemException $e) {
				throw new IllegalLinkException();
			}
			
			parse_str($content, $data);
			
			// check whether a user is connected to this twitter account
			$user = $this->getUser($data['user_id']);
			
			if ($user->userID) {
				// a user is already connected, but we are logged in, break
				if (WCF::getUser()->userID) {
					throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.twitter.connect.error.inuse'));
				}
				// perform login
				else {
					WCF::getSession()->changeUser($user);
					WCF::getSession()->update();
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink());
				}
			}
			else {
				// save data for connection
				if (WCF::getUser()->userID) {
					WCF::getSession()->register('__twitterUsername', $data['screen_name']);
					WCF::getSession()->register('__twitterData', $data);
					
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('AccountManagement').'#3rdParty');
				}
				// save data and redirect to registration
				else {
					WCF::getSession()->register('__username', $data['screen_name']);
					
					WCF::getSession()->register('__twitterData', $data);
					
					// we assume that bots won't register on twitter first
					WCF::getSession()->register('recaptchaDone', true);
					
					WCF::getSession()->update();
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Register'));
				}
			}
			
			$this->executed();
			exit;
		}
		
		// user declined
		if (isset($_GET['denied'])) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.twitter.login.error.denied'));
		}
		
		// start auth by fetching request_token
		try {
			$callbackURL = LinkHandler::getInstance()->getLink('TwitterAuth', array(
				'appendSession' => false
			));
			$oauthHeader = array(
				'oauth_callback' => $callbackURL,
				'oauth_consumer_key' => TWITTER_PUBLIC_KEY,
				'oauth_nonce' => StringUtil::getRandomID(),
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_timestamp' => TIME_NOW,
				'oauth_version' => '1.0'
			);
			$signature = $this->createSignature('https://api.twitter.com/oauth/request_token', $oauthHeader);
			$oauthHeader['oauth_signature'] = $signature;
			
			// call api
			$request = new HTTPRequest('https://api.twitter.com/oauth/request_token', array('method' => 'POST'));
			$request->addHeader('Authorization', 'OAuth '.$this->buildOAuthHeader($oauthHeader));
			$request->execute();
			$reply = $request->getReply();
			
			$content = $reply['body'];
		}
		catch (SystemException $e) {
			throw new IllegalLinkException();
		}
		
		parse_str($content, $data);
		if ($data['oauth_callback_confirmed'] != 'true') throw new IllegalLinkException();
		
		WCF::getSession()->register('__twitterInit', $data);
		// redirect to twitter
		HeaderUtil::redirect('https://api.twitter.com/oauth/authenticate?oauth_token='.rawurlencode($data['oauth_token']));
		
		$this->executed();
		exit;
	}
	
	/**
	 * Builds the OAuth authorization header.
	 * 
	 * @param	array $parameters
	 * @return	string
	 */
	public function buildOAuthHeader(array $parameters) {
		$header = '';
		foreach ($parameters as $key => $val) {
			if ($header !== '') $header .= ', ';
			$header .= rawurlencode($key).'="'.rawurlencode($val).'"';
		}
		
		return $header;
	}
	
	/**
	 * Creates an OAuth 1 signature.
	 * 
	 * @param	string $url
	 * @param	array $parameters
	 * @param	string $tokenSecret
	 * @return	string
	 */
	public function createSignature($url, array $parameters, $tokenSecret = '') {
		$tmp = array();
		foreach ($parameters as $key => $val) {
			$tmp[rawurlencode($key)] = rawurlencode($val);
		}
		$parameters = $tmp;
		
		uksort($parameters, 'strcmp');
		$parameterString = '';
		foreach ($parameters as $key => $val) {
			if ($parameterString !== '') $parameterString .= '&';
			$parameterString .= $key.'='.$val;
		}
		
		$base = "POST&".rawurlencode($url)."&".rawurlencode($parameterString);
		$key = rawurlencode(TWITTER_PRIVATE_KEY).'&'.rawurlencode($tokenSecret);
		
		return base64_encode(hash_hmac('sha1', $base, $key, true));
	}
	
	/**
	 * Fetches the User with the given userID
	 * 
	 * @param	integer			$userID
	 * @return	wcf\data\user\User
	 */
	public function getUser($userID) {
		$sql = "SELECT	userID
			FROM	wcf".WCF_N."_user_option_value
			WHERE	userOption".User::getUserOptionID('twitterUserID')." = ?";
		$stmt = WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array($userID));
		$row = $stmt->fetchArray();
		
		$user = new User($row['userID']);
		return $user;
	}
}
