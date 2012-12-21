<?php 
namespace wcf\data\user;
use wcf\data\user\group\Team;

use wcf\data\user\group\UserGroup;

use wcf\system\WCF;

class TeamList extends UserProfileList {
	protected $teams = array();
	
	/**
	 * @see	wcf\data\DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_user_group user_group,
				wcf".WCF_N."_user_to_group user_to_group
			WHERE	user_to_group.groupID = user_group.groupID
				AND user_group.showOnTeamPage = 1";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$row = $statement->fetchArray();
		return $row['count'];
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjectIDs()
	 */
	public function readObjectIDs() {
		$this->objectIDs = array();
		$sql = "SELECT		user_to_group.userID AS objectID
			FROM		wcf".WCF_N."_user_group user_group,
					wcf".WCF_N."_user_to_group user_to_group
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = user_to_group.userID)
			WHERE		user_to_group.groupID = user_group.groupID
					AND user_group.showOnTeamPage = 1
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$statement = WCF::getDB()->prepareStatement($sql, $this->sqlLimit, $this->sqlOffset);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			$this->objectIDs[] = $row['objectID'];
		}
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		parent::readObjects();
	
		$sql = "SELECT		user_to_group.*
			FROM		wcf".WCF_N."_user_group user_group,
					wcf".WCF_N."_user_to_group user_to_group
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = user_to_group.userID)
			WHERE		user_to_group.groupID = user_group.groupID
					AND user_group.showOnTeamPage = 1
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$statement = WCF::getDB()->prepareStatement($sql, $this->sqlLimit, $this->sqlOffset);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			if (!isset($this->teams[$row['groupID']])) {
				$userGroup = UserGroup::getGroupByID($row['groupID']);
				$this->teams[$row['groupID']] = new Team($userGroup);
			}
			
			$this->teams[$row['groupID']]->addMember($this->objects[$row['userID']]);
		}
	}
	
	public function getTeams() {
		return $this->teams;
	}
}
