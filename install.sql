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

-- friends
DROP TABLE IF EXISTS wcf1_user_friend;
CREATE TABLE wcf1_user_friend (
	friendID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID INT(10) NOT NULL,
	friendUserID INT(10) NOT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (userID, friendUserID)
);

-- friend requests
DROP TABLE IF EXISTS wcf1_user_friend_request;
CREATE TABLE wcf1_user_friend_request (
	requestID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID INT(10) NOT NULL,
	friendUserID INT(10) NOT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	ignored TINYINT(1) NOT NULL DEFAULT 0,
	UNIQUE KEY (userID, friendUserID)
);


ALTER TABLE wcf1_user_friend ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_friend ADD FOREIGN KEY (friendUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wcf1_user_friend_request ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;
ALTER TABLE wcf1_user_friend_request ADD FOREIGN KEY (friendUserID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;