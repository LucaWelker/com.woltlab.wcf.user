ALTER TABLE wcf1_user ADD activationCode INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD lastLostPasswordRequestTime INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD lostPasswordKey VARCHAR(40) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD lastUsernameChange INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD newEmail VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD oldUsername VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD quitStarted INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD reactivationCode INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD registrationIpAddress VARCHAR(39) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD avatarID INT(10);
ALTER TABLE wcf1_user ADD disableAvatar TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD disableAvatarReason TEXT;
ALTER TABLE wcf1_user ADD enableGravatar TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD signature TEXT;
ALTER TABLE wcf1_user ADD signatureEnableBBCodes TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE wcf1_user ADD signatureEnableHtml TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD signatureEnableSmilies TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE wcf1_user ADD disableSignature TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD disableSignatureReason TEXT;
ALTER TABLE wcf1_user ADD lastActivityTime INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD profileHits INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD rankID INT(10);
ALTER TABLE wcf1_user ADD userTitle VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD userOnlineGroupID INT(10);
ALTER TABLE wcf1_user ADD activityPoints INT(10) NOT NULL DEFAULT 0; -- hopefully 2'147'483'647 is enough
ALTER TABLE wcf1_user ADD notificationMailToken VARCHAR(20) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD authData VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE wcf1_user ADD INDEX activationCode (activationCode);
ALTER TABLE wcf1_user ADD INDEX registrationData (registrationIpAddress, registrationDate);
ALTER TABLE wcf1_user ADD INDEX activityPoints (activityPoints);

ALTER TABLE wcf1_user_group ADD priority MEDIUMINT(8) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user_group ADD userOnlineMarking VARCHAR(255) NOT NULL DEFAULT '%s';
ALTER TABLE wcf1_user_group ADD showOnTeamPage TINYINT(1) NOT NULL DEFAULT 0;

-- default priorities
UPDATE wcf1_user_group SET priority = 10 WHERE groupID = 3;
UPDATE wcf1_user_group SET priority = 1000 WHERE groupID = 4;
UPDATE wcf1_user_group SET priority = 50 WHERE groupID = 5;
UPDATE wcf1_user_group SET priority = 100 WHERE groupID = 6;

-- default 'showOnTeamPage' setting
UPDATE wcf1_user_group SET showOnTeamPage = 1 WHERE groupID IN (4, 5, 6);

-- dashboard
DROP TABLE IF EXISTS wcf1_dashboard_box;
CREATE TABLE wcf1_dashboard_box (
	boxID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	boxName VARCHAR(255) NOT NULL DEFAULT '',
	boxType VARCHAR(30) NOT NULL DEFAULT 'sidebar', -- can be 'content' or 'sidebar'
	className VARCHAR(255) NOT NULL DEFAULT ''
);

DROP TABLE IF EXISTS wcf1_dashboard_option;
CREATE TABLE wcf1_dashboard_option (
	objectTypeID INT(10) NOT NULL,
	boxID INT(10) NOT NULL,
	showOrder INT(10) NOT NULL,
	UNIQUE KEY dashboardOption (objectTypeID, boxID)
);

DROP TABLE IF EXISTS wcf1_tracked_visit;
CREATE TABLE wcf1_tracked_visit (
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	visitTime INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (objectTypeID, objectID, userID),
	KEY (userID, visitTime)
);

DROP TABLE IF EXISTS wcf1_tracked_visit_type;
CREATE TABLE wcf1_tracked_visit_type (
	objectTypeID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	visitTime INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (objectTypeID, userID),
	KEY (userID, visitTime)
);

-- avatar table
DROP TABLE IF EXISTS wcf1_user_avatar;
CREATE TABLE wcf1_user_avatar (
	avatarID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	avatarName VARCHAR(255) NOT NULL DEFAULT '',
	avatarExtension VARCHAR(7) NOT NULL DEFAULT '',
	width SMALLINT(5) NOT NULL DEFAULT 0,
	height SMALLINT(5) NOT NULL DEFAULT 0,
	userID INT(10),
	fileHash VARCHAR(40) NOT NULL DEFAULT ''
);

-- follower list
DROP TABLE IF EXISTS wcf1_user_follow;
CREATE TABLE wcf1_user_follow (
	followID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID INT(10) NOT NULL,
	followUserID INT(10) NOT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (userID, followUserID)
);

-- ignore list
DROP TABLE IF EXISTS wcf1_user_ignore;
CREATE TABLE wcf1_user_ignore (
	ignoreID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID INT(10) NOT NULL,
	ignoreUserID INT(10) NOT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (userID, ignoreUserID)
);

-- user menu
DROP TABLE IF EXISTS wcf1_user_menu_item;
CREATE TABLE wcf1_user_menu_item (
	menuItemID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	menuItem VARCHAR(255) NOT NULL DEFAULT '',
	parentMenuItem VARCHAR(255) NOT NULL DEFAULT '',
	menuItemController VARCHAR(255) NOT NULL DEFAULT '',
	menuItemLink VARCHAR(255) NOT NULL DEFAULT '',
	showOrder INT(10) NOT NULL DEFAULT 0,
	permissions TEXT,
	options TEXT,
	className VARCHAR(255) NOT NULL DEFAULT '',
	UNIQUE KEY menuItem (menuItem, packageID)
);

-- notifications
DROP TABLE IF EXISTS wcf1_user_notification;
CREATE TABLE wcf1_user_notification (
	notificationID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	eventID INT(10) NOT NULL,
	objectID INT(10) NOT NULL DEFAULT 0,
	eventHash VARCHAR(40) NOT NULL DEFAULT '',
	authorID INT(10),
	time INT(10) NOT NULL DEFAULT 0,
	additionalData TEXT,
	KEY (eventHash),
	UNIQUE KEY (packageID, eventID, objectID)
);

-- notification recipients
DROP TABLE IF EXISTS wcf1_user_notification_to_user;
CREATE TABLE wcf1_user_notification_to_user (
	notificationID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	mailNotified TINYINT(1) NOT NULL DEFAULT 0,
	UNIQUE KEY notificationID (notificationID, userID)
);

-- events that create notifications
DROP TABLE IF EXISTS wcf1_user_notification_event;
CREATE TABLE wcf1_user_notification_event (
	eventID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	eventName VARCHAR(255) NOT NULL DEFAULT '',
	objectTypeID INT(10) NOT NULL,
	className VARCHAR(255) NOT NULL DEFAULT '',
	permissions TEXT,
	options TEXT,
	preset TINYINT(1) NOT DEFAULT 0,
	UNIQUE KEY eventName (eventName, objectTypeID)
);

-- user configuration for events
DROP TABLE IF EXISTS wcf1_user_notification_event_to_user;
CREATE TABLE wcf1_user_notification_event_to_user (
	userID INT(10) NOT NULL,
	eventID INT(10) NOT NULL,
	mailNotificationType ENUM('none', 'instant', 'daily') NOT NULL DEFAULT 'none',
	UNIQUE KEY (eventID, userID)
);

-- user profile menu
DROP TABLE IF EXISTS wcf1_user_profile_menu_item;
CREATE TABLE wcf1_user_profile_menu_item (
	menuItemID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	menuItem VARCHAR(255) NOT NULL,
	showOrder INT(10) NOT NULL DEFAULT 0,
	permissions TEXT NULL,
	options TEXT NULL,
	className VARCHAR(255) NOT NULL,
	UNIQUE KEY (packageID, menuItem)
);

-- user ranks
DROP TABLE IF EXISTS wcf1_user_rank;
CREATE TABLE wcf1_user_rank (
	rankID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	groupID INT(10),
	requiredPoints INT(10) NOT NULL DEFAULT 0,
	rankTitle VARCHAR(255) NOT NULL DEFAULT '',
	cssClassName VARCHAR(255) NOT NULL DEFAULT '',
	rankImage VARCHAR(255) NOT NULL DEFAULT '',
	repeatImage TINYINT(3) NOT NULL DEFAULT 1,
	requiredGender TINYINT(1) NOT NULL DEFAULT 0
);

-- default ranks
INSERT INTO wcf1_user_rank (groupID, requiredPoints, rankTitle, cssClassName) VALUES
	(4, 0, 'wcf.user.rank.administrator', 'blue'),
	(5, 0, 'wcf.user.rank.moderator', 'blue'),
	(6, 0, 'wcf.user.rank.superModerator', 'blue'),
	(3, 0, 'wcf.user.rank.user0', ''),
	(3, 300, 'wcf.user.rank.user1', ''),
	(3, 900, 'wcf.user.rank.user2', ''),
	(3, 3000, 'wcf.user.rank.user3', ''),
	(3, 9000, 'wcf.user.rank.user4', ''),
	(3, 15000, 'wcf.user.rank.user5', '');

-- recent activity
DROP TABLE IF EXISTS wcf1_user_activity_event;
CREATE TABLE wcf1_user_activity_event (
	eventID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	languageID INT(10),
	userID INT(10) NOT NULL,
	time INT(10) NOT NULL,
	additionalData TEXT,
	
	KEY (time),
	KEY (userID, time),
	KEY (objectTypeID, objectID)
);

DROP TABLE IF EXISTS wcf1_user_activity_point;
CREATE TABLE wcf1_user_activity_point (
	userID INT(10) NOT NULL,
	objectTypeID INT(10) NOT NULL,
	activityPoints INT(10) NOT NULL DEFAULT 0,
	PRIMARY KEY (userID, objectTypeID),
	KEY (objectTypeID)
);

DROP TABLE IF EXISTS wcf1_user_activity_point_event;
CREATE TABLE wcf1_user_activity_point_event (
	eventID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	additionalData TEXT,
	UNIQUE KEY (objectTypeID, userID, objectID)
);

-- profile visitors
DROP TABLE IF EXISTS wcf1_user_profile_visitor;
CREATE TABLE wcf1_user_profile_visitor (
	visitorID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ownerID INT(10),
	userID INT(10),
	time INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (ownerID, userID),
	KEY (time)
);

-- watched objects
DROP TABLE IF EXISTS wcf1_user_object_watch;
CREATE TABLE wcf1_user_object_watch (
	watchID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	notification TINYINT(1) NOT NULL DEFAULT 0,
	
	UNIQUE KEY (objectTypeID, userID, objectID),
	KEY (objectTypeID, objectID)
);

ALTER TABLE wcf1_dashboard_box ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;

ALTER TABLE wcf1_dashboard_option ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_dashboard_option ADD FOREIGN KEY (boxID) REFERENCES wcf1_dashboard_box (boxID) ON DELETE CASCADE;

ALTER TABLE wcf1_tracked_visit ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_tracked_visit ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_tracked_visit_type ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_tracked_visit_type ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user ADD FOREIGN KEY (avatarID) REFERENCES wcf1_user_avatar (avatarID) ON DELETE SET NULL;
ALTER TABLE wcf1_user ADD FOREIGN KEY (rankID) REFERENCES wcf1_user_rank (rankID) ON DELETE SET NULL;
ALTER TABLE wcf1_user ADD FOREIGN KEY (userOnlineGroupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_avatar ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_follow ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_follow ADD FOREIGN KEY (followUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_ignore ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_ignore ADD FOREIGN KEY (ignoreUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_menu_item ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_notification ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification ADD FOREIGN KEY (eventID) REFERENCES wcf1_user_notification_event (eventID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification ADD FOREIGN KEY (authorID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_notification_to_user ADD FOREIGN KEY (notificationID) REFERENCES wcf1_user_notification (notificationID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification_to_user ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_notification_event ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification_event ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_notification_event_to_user ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification_event_to_user ADD FOREIGN KEY (eventID) REFERENCES wcf1_user_notification_event (eventID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_profile_menu_item ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_rank ADD FOREIGN KEY (groupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_activity_event ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_activity_event ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_activity_event ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_activity_point ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_activity_point ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_activity_point_event ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_activity_point_event ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_profile_visitor ADD FOREIGN KEY (ownerID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_profile_visitor ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_object_watch ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_object_watch ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
