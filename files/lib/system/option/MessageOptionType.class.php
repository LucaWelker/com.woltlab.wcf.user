<?php
namespace wcf\system\option;
use wcf\data\option\Option;
use wcf\data\smiley\SmileyCache;
use wcf\system\WCF;

/**
 * Option type implementation for message.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option
 * @category	Community Framework
 */
class MessageOptionType extends TextareaOptionType {
	/**
	 * @see	wcf\system\option\IOptionType::getFormElement()
	 */
	public function getFormElement(Option $option, $value) {
		WCF::getTPL()->assign(array(
			'option' => $option,
			'value' => $value,
			'defaultSmilies' => SmileyCache::getInstance()->getCategorySmilies()
		));
		return WCF::getTPL()->fetch('messageOptionType');
	}
}
