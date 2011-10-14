ALTER TABLE wcf1_user ADD activationCode INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD lastLostPasswordRequestTime INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD lostPasswordKey VARCHAR(40) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD lastUsernameChange INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD newEmail VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD oldUsername VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE wcf1_user ADD quitStarted INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD reactivationCode INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD registrationIpAddress VARCHAR(39) NOT NULL DEFAULT '';

ALTER TABLE wcf1_user ADD INDEX activationCode (activationCode);
ALTER TABLE wcf1_user ADD INDEX registrationData (registrationIpAddress, registrationDate);

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

-- profile posts
DROP TABLE IF EXISTS wcf1_user_profile_post;
CREATE TABLE wcf1_user_profile_post (
	profilePostID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	profileUserID INT(10) NOT NULL,
	userID INT(10),
	username VARCHAR(255) NOT NULL DEFAULT '',
	message MEDIUMTEXT NOT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	languageID INT(10)
);

DROP TABLE IF EXISTS wcf1_user_profile_post_comment;
CREATE TABLE wcf1_user_profile_post_comment (
	profilePostCommentID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	profilePostID INT(10) NOT NULL,
	userID INT(10),
	username VARCHAR(255) NOT NULL DEFAULT '',
	message MEDIUMTEXT NOT NULL,
	time INT(10) NOT NULL DEFAULT 0
);

ALTER TABLE wcf1_user_follow ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_follow ADD FOREIGN KEY (followUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_ignore ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_ignore ADD FOREIGN KEY (ignoreUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_rank ADD FOREIGN KEY (groupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_profile_post ADD FOREIGN KEY (profileUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_profile_post ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE wcf1_user_profile_post ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;

ALTER TABLE wcf1_user_profile_post_comment ADD FOREIGN KEY (profilePostID) REFERENCES wcf1_user_profile_post (profilePostID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_profile_post_comment ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
