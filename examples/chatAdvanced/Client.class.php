<?php

define('XOAD_CHAT_USERS_TABLE_NAME', 'xoad_chat_users');

class ChatClient
{
	public $nick;

	public $color;

	public function ChatClient()
	{
		$this->color = '#000';
	}

	public function getConnection()
	{
		$connection = mysql_connect('server', 'username', 'password');

		mysql_select_db('database', $connection);

		return $connection;
	}

	public function closeConnection($connection)
	{
		mysql_close($connection);
	}

	public function getUsers($nick)
	{
		$connection = $this->getConnection();

		$now = time();

		$sqlQuery = '
			UPDATE
				`' . XOAD_CHAT_USERS_TABLE_NAME . '`
			SET
				`time` = ' . $now . '
			WHERE
				`nick` = \'' . $this->escapeString($nick, $connection) . '\'
		';

		mysql_query($sqlQuery, $connection);

		$sqlQuery = '
			SELECT
				`nick`
			FROM
				`' . XOAD_CHAT_USERS_TABLE_NAME . '`
			WHERE
				`time` < ' . ($now - 30) . '
			ORDER BY
				`time` ASC,
				`nick` ASC
		';

		$sqlResult = mysql_query($sqlQuery);

		$oldUsers = array();

		while (($row = mysql_fetch_assoc($sqlResult)) != false) {

			$oldUsers[] = $row['nick'];
		}

		mysql_free_result($sqlResult);

		$sqlQuery = '
			DELETE FROM
				`' . XOAD_CHAT_USERS_TABLE_NAME . '`
			WHERE
				`time` < ' . ($now - 30) . '
		';

		mysql_query($sqlQuery);

		$sqlQuery = '
			SELECT
				`nick`
			FROM
				`' . XOAD_CHAT_USERS_TABLE_NAME . '`
			ORDER BY
				`nick` ASC
		';

		$sqlResult = mysql_query($sqlQuery, $connection);

		$users = array();

		while (($row = mysql_fetch_assoc($sqlResult)) != false) {

			$users[] = $row['nick'];
		}

		mysql_free_result($sqlResult);

		$storage =& XOAD_Events_Storage::getStorage();

		foreach ($oldUsers as $nick) {

			$storage->postEvent('onUserLeave', 'ChatClient', null, $nick);
		}

		$this->closeConnection($connection);

		return $users;
	}

	public function renameUser($oldNick, $newNick)
	{
		$connection = $this->getConnection();

		$now = time();

		$sqlQuery = '
			UPDATE
				`' . XOAD_CHAT_USERS_TABLE_NAME . '`
			SET
				`time` = ' . $now . ',
				`nick` = \'' . $this->escapeString($newNick, $connection) . '\'
			WHERE
				`nick` = \'' . $this->escapeString($oldNick, $connection) . '\'
		';

		mysql_query($sqlQuery, $connection);

		$this->closeConnection($connection);
	}

	public function addUser($nick)
	{
		$connection = $this->getConnection();

		$now = time();

		$sqlQuery = '
			INSERT INTO
				`' . XOAD_CHAT_USERS_TABLE_NAME . '`
			(
				`nick`,
				`time`
			)
			VALUES
			(
				\'' . $this->escapeString($nick, $connection) . '\',
				' . $now . '
			)
		';

		mysql_query($sqlQuery, $connection);

		$this->closeConnection($connection);
	}

	public function escapeString($unescapedString, $connection)
	{
		if (function_exists('mysql_real_escape_string')) {

			return mysql_real_escape_string($unescapedString, $connection);
		}

		return mysql_escape_string($unescapedString);
	}

	public function xoadGetMeta()
	{
		XOAD_Client::privateMethods($this, array('getConnection', 'closeConnection', 'escapeString'));

		XOAD_Client::mapMethods($this, array('getUsers', 'renameUser', 'addUser'));
	}
}

?>