<?php
use wcf\data\user\UserEditor;
use wcf\data\user\UserProfileAction;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\WCF;

/**
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
// set dashboard default values 
DashboardHandler::setDefaultValues('com.woltlab.wcf.user.DashboardPage', array('recentActivity' => 1));

// update administrator user rank and user online marking
$editor = new UserEditor(WCF::getUser());
$action = new UserProfileAction(array($editor), 'updateUserRank');
$action->executeAction();
$action = new UserProfileAction(array($editor), 'updateUserOnlineMarking');
$action->executeAction();
