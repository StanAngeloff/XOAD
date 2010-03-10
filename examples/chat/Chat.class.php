<?php
class Chat
{
	public $maxChatLines = 150;

	public function addMessage($message, $nick = 'Guest', $color = 'black')
	{
		if (empty($message)) {

			return false;
		}

		$message = trim($message);

		$nick = trim($nick);

		if (
		empty($message) ||
		empty($nick) ||
		empty($color)
		) {

			return false;
		}

		$chatFile = dirname(__FILE__) . '/var/chat.txt';

		$chatLines = @file($chatFile);

		if (empty($chatLines)) {

			return false;
		}

		if (sizeof($chatLines) >= $this->maxChatLines) {

			array_shift($chatLines);
		}

		$message = '<div><span style="color: ' . htmlspecialchars($color) . '">' . htmlspecialchars($nick) . '</span>:&nbsp;' . htmlspecialchars($message) . '</div>';

		if (strcasecmp(strip_tags($chatLines[sizeof($chatLines) - 1]), strip_tags($message)) == 0) {

			return false;
		}

		$chatLines[] = "\n" . $message;

		$handle = fopen($chatFile, 'w');

		foreach ($chatLines as $line) {

			fputs($handle, $line);
		}

		fclose($handle);

		return true;
	}

	public function getContents()
	{
		$chatFile = dirname(__FILE__) . '/var/chat.txt';

		$chatLines = @file($chatFile);

		if (empty($chatLines)) {

			return false;
		}

		$smilies = array(
		':D'		=>	'sbiggrin',
		':?'		=>	'sconfused',
		'8)'		=>	'scool',
		':cry:'		=>	'scry',
		':P'		=>	'sdrool',
		':o'		=>	'shappy',
		':mad:'		=>	'smad',
		':('		=>	'ssad',
		':sleep:'	=>	'ssleepy',
		':)'		=>	'ssmile',
		';)'		=>	'swink',
		':wow:'		=>	'ssuprised',
		':p'		=>	'stongue'
		);

		foreach ($smilies as $key => $value) {

			$smilies[$key] = '<img src="images/' . htmlspecialchars($value) . '.gif" width="18" height="18" alt="' . htmlspecialchars($key) . '" style="vertical-align: middle;" />';
		}

		foreach ($chatLines as $key => $line) {

			/**
			 * Rewritten by Nathan Codding - Feb 6, 2001.
			 * - Goes through the given string, and replaces xxxx://yyyy with an HTML <a> tag linking
			 * 	to that URL
			 * - Goes through the given string, and replaces www.xxxx.yyyy[zzzz] with an HTML <a> tag linking
			 * 	to http://www.xxxx.yyyy[/zzzz]
			 * - Goes through the given string, and replaces xxxx@yyyy with an HTML mailto: tag linking
			 *		to that email address
			 * - Only matches these 2 patterns either after a space, or at the beginning of a line
			 *
			 * Notes: the email one might get annoying - it's easy to make it more restrictive, though.. maybe
			 * have it require something like xxxx@yyyy.zzzz or such. We'll see.
			 *
			 * This code is part of phpBB.
			 *
			 */

			$tempLine = ' ' . $line;

			$tempLine = preg_replace("#(^|[\n (?:\&nbsp\;)])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\" style=\"color: #04c;\">\\2</a>", $tempLine);

			$tempLine = preg_replace("#(^|[\n (?:\&nbsp\;)])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\" style=\"color: #04c;\">\\2</a>", $tempLine);

			$tempLine = preg_replace("#(^|[\n (?:\&nbsp\;)])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\" style=\"color: #04c;\">\\2@\\3</a>", $tempLine);

			$line = substr($tempLine, 1);

			$line = str_replace(array_keys($smilies), array_values($smilies), $line);

			$chatLines[$key] = $line;
		}

		return $chatLines;
	}

	public function xoadGetMeta()
	{
		XOAD_Client::mapMethods($this, array('addMessage', 'getContents'));
	}
}

?>