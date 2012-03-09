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
ALTER TABLE wcf1_user ADD signature TEXT;
ALTER TABLE wcf1_user ADD signatureCache TEXT;
ALTER TABLE wcf1_user ADD signatureEnableBBCodes TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE wcf1_user ADD signatureEnableHtml TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD signatureEnableSmilies TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE wcf1_user ADD INDEX activationCode (activationCode);
ALTER TABLE wcf1_user ADD INDEX registrationData (registrationIpAddress, registrationDate);

-- avatar table
DROP TABLE IF EXISTS wcf1_user_avatar;
CREATE TABLE wcf1_user_avatar (
	avatarID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	avatarCategoryID INT(10),
	avatarName VARCHAR(255) NOT NULL DEFAULT '',
	avatarExtension VARCHAR(7) NOT NULL DEFAULT '',
	width SMALLINT(5) NOT NULL DEFAULT 0,
	height SMALLINT(5) NOT NULL DEFAULT 0,
	groupID INT(10),
	neededPoints INT(10) NOT NULL DEFAULT 0,
	userID INT(10)
);

-- avatar categories
DROP TABLE IF EXISTS wcf1_user_avatar_category;
CREATE TABLE wcf1_user_avatar_category (
	avatarCategoryID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL DEFAULT '',
	showOrder MEDIUMINT(5) NOT NULL DEFAULT 0,
	groupID INT(10),
	neededPoints INT(10) NOT NULL DEFAULT 0
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
	menuItemLink VARCHAR(255) NOT NULL DEFAULT '',
	showOrder INT(10) NOT NULL DEFAULT 0,
	permissions TEXT,
	options TEXT,
	UNIQUE KEY menuItem (menuItem, packageID)
);

-- notifications
DROP TABLE IF EXISTS wcf1_user_notification;
CREATE TABLE wcf1_user_notification (
	notificationID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	eventID INT(10) NOT NULL,
	objectID INT(10) NOT NULL DEFAULT 0,
	authorID INT(10),
	time INT(10) NOT NULL DEFAULT 0,
	additionalData TEXT
);

-- notification recipients
DROP TABLE IF EXISTS wcf1_user_notification_to_user;
CREATE TABLE wcf1_user_notification_to_user (
	notificationID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	confirmed TINYINT(1) NOT NULL DEFAULT 0,
	confirmationTime INT(10) NOT NULL DEFAULT 0,
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
	languageCategory VARCHAR(255) NOT NULL,
	defaultNotificationTypeID INT(10) NULL,
	requiresConfirmation TINYINT(1) NOT NULL DEFAULT 0,
	acceptURL VARCHAR(255) NOT NULL DEFAULT '',
	declineURL VARCHAR(255) NOT NULL DEFAULT '',
	permissions TEXT,
	options TEXT,
	UNIQUE KEY packageID (packageID, eventName)
);

-- user configuration for events
DROP TABLE IF EXISTS wcf1_user_notification_event_to_user;
CREATE TABLE wcf1_user_notification_event_to_user (
	userID INT(10) NOT NULL,
	eventID INT(10) NOT NULL,
	UNIQUE KEY (userID, eventID)
);

-- user configuration for notification types
DROP TABLE IF EXISTS wcf1_user_notification_event_notification_type;
CREATE TABLE wcf1_user_notification_event_notification_type (
	userID INT(10) NOT NULL,
	eventID INT(10) NOT NULL,
	notificationTypeID INT(10) NOT NULL,
	UNIQUE KEY (userID, eventID, notificationTypeID)
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
	neededPoints INT(10) NOT NULL DEFAULT 0,
	rankTitle VARCHAR(255) NOT NULL DEFAULT '',
	rankImage VARCHAR(255) NOT NULL DEFAULT '',
	repeatImage TINYINT(3) NOT NULL DEFAULT 1,
	gender TINYINT(1) NOT NULL DEFAULT 0
);

-- default ranks
INSERT INTO wcf1_user_rank (groupID, neededPoints, rankTitle, rankImage, repeatImage) VALUES
	(4, 0, 'wcf.user.rank.administrator', 'icon/userRankAdminS.png', 3),
	(5, 0, 'wcf.user.rank.moderator', 'icon/userRankAdminS.png', 1),
	(6, 0, 'wcf.user.rank.superModerator', 'icon/userRankAdminS.png', 2),
	(3, 0, 'wcf.user.rank.user0', '', 1),
	(3, 300, 'wcf.user.rank.user1', 'icon/userRank1S.png', 1),
	(3, 900, 'wcf.user.rank.user2', 'icon/userRank2S.png', 1),
	(3, 3000, 'wcf.user.rank.user3', 'icon/userRank3S.png', 1),
	(3, 9000, 'wcf.user.rank.user4', 'icon/userRank4S.png', 1),
	(3, 15000, 'wcf.user.rank.user5', 'icon/userRank5S.png', 1);

-- recent activity
DROP TABLE IF EXISTS wcf1_user_activity_event;
CREATE TABLE wcf1_user_activity_event (
	eventID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	time INT(10) NOT NULL,
	additionalData TEXT,
	KEY (packageID, userID)
);

ALTER TABLE wcf1_user ADD FOREIGN KEY (avatarID) REFERENCES wcf1_user_avatar (avatarID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_avatar ADD FOREIGN KEY (avatarCategoryID) REFERENCES wcf1_user_avatar_category (avatarCategoryID) ON DELETE SET NULL;
ALTER TABLE wcf1_user_avatar ADD FOREIGN KEY (groupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;
ALTER TABLE wcf1_user_avatar ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_avatar_category ADD FOREIGN KEY (groupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;

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
ALTER TABLE wcf1_user_notification_event ADD FOREIGN KEY (defaultNotificationTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_notification_event_to_user ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification_event_to_user ADD FOREIGN KEY (eventID) REFERENCES wcf1_user_notification_event (eventID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_notification_event_notification_type ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification_event_notification_type ADD FOREIGN KEY (eventID) REFERENCES wcf1_user_notification_event (eventID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_notification_event_notification_type ADD FOREIGN KEY (notificationTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_profile_menu_item ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_rank ADD FOREIGN KEY (groupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_activity_event ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_activity_event ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_activity_event ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
