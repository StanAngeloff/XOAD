<?php

require_once('Client.class.php');

define('XOAD_AUTOHANDLE', true);

require_once('../../xoad.php');

if ( ! empty($_GET['css'])) {

	$styleFile = htmlspecialchars($_GET['css']);

} else {

	$styleFile = 'style/default.css';
}

?>
<?= '<?' ?>xml version="1.0" encoding="utf-8"<?= '?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	<head>
		<title>XOAD Advanced Chat</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?= XOAD_Utilities::header('../..') ?>

		<?= XOAD_Utilities::eventsHeader() ?>

		<script type="text/javascript">

		xoad.events.refreshInterval = 1000;

		var client = <?= XOAD_Client::register(new ChatClient(), array('class' => 'ChatClient')) ?>;

		var users = [];

		var emoticons = [
		[':angry:', 'angry'],
		[':biggrin:', 'biggrin'],
		[':D', 'biggrin'],
		[':-D', 'biggrin'],
		[':blink:', 'blink'],
		[':blush:', 'blush'],
		[':bored:', 'bored'],
		[':closedeyes:', 'closedeyes'],
		[':confused:', 'confused'],
		[':cool:', 'cool'],
		['8)', 'cool'],
		['B)', 'cool'],
		['8-)', 'cool'],
		['B-)', 'cool'],
		[':cry:', 'crying'],
		[':cursing:', 'cursing'],
		[':freak:', 'drool'],
		[':glare:', 'glare'],
		[':huh:', 'huh'],
		[':laugh:', 'laugh'],
		[':lol:', 'lol'],
		[':mad:', 'mad'],
		[':mellow:', 'mellow'],
		[':ohmy:', 'ohmy'],
		[':oh:', 'ohmy'],
		[':roll:', 'rolleyes'],
		[':rolleyes:', 'rolleyes'],
		[':sad:', 'sad'],
		[':(', 'sad'],
		[':-(', 'sad'],
		[';(', 'sad'],
		[';-(', 'sad'],
		[':scared:', 'scared'],
		[':sleep:', 'sleep'],
		[':smile:', 'smile'],
		[':)', 'smile'],
		[':-)', 'smile'],
		[':sneaky:', 'sneaky2'],
		[':down:', 'thumbdown'],
		[':thumbdown:', 'thumbdown'],
		[':thumbsdown:', 'thumbdown'],
		[':up:', 'thumbup'],
		[':thumbup:', 'thumbup'],
		[':thumbsup:', 'thumbup'],
		[':tongue:', 'tongue'],
		[':p', 'tongue'],
		[':-p', 'tongue'],
		[':P', 'tongue_smilie'],
		[':-P', 'tongue_smilie'],
		[':tt:', 'tt1'],
		[':love:', 'tt1'],
		[';p', 'tt2'],
		[';-p', 'tt2'],
		[';P', 'tt2'],
		[';-P', 'tt2'],
		[':unsure:', 'unsure'],
		[':wow:', 'w00t'],
		[':w00t:', 'w00t'],
		[':wink:', 'wink'],
		[';)', 'wink'],
		[';-)', 'wink'],
		[':wub:', 'wub']
		];

		var refreshStatus = 0;
		var contentSize = 0;

		function trim(text)
		{
			return text.replace(/^\s+/i, '').replace(/\s+$/i, '');
		}

		function htmlEncode(text)
		{
			return text.replace(/\&/ig, '&amp;').replace(/\</ig, '&lt;').replace(/\>/ig, '&gt;').replace(/\"/ig, '&quot;');
		}

		function htmlDecode(text)
		{
			return text.replace(/\&amp\;/ig, '&').replace(/\&lt\;/ig, '<').replace(/\&gt\;/ig, '>').replace(/\&quot\;/ig, '"');
		}

		function messageTo(obj)
		{
			var message = document.getElementById('message');

			message.value = '@' + htmlDecode(obj.innerHTML) + ': ' + message.value;

			message.focus();
		}

		function changeColor(obj)
		{
			var color = trim(obj.value);

			if (color.length < 1) {

				obj.style.color = 'black';

				client.color = 'black';

			} else {

				try {

					obj.style.color = color;

					client.color = color;

				} catch (e) {

					obj.style.color = 'black';

					client.color = 'black';
				}
			}
		}

		function refreshUsers()
		{
			if (refreshStatus > 0) {

				return false;
			}

			refreshStatus = 1;

			var nick = document.getElementById('nick');

			var usersList = document.getElementById('users-list');

			var listTop = usersList.scrollTop;

			var html = '';

			client.onGetUsersError = function(error) {

				refreshStatus = 0;

				return true;
			}

			client.getUsers(client.nick, function(result) {

				users = result;

				for (var iterator = 0; iterator < users.length; iterator ++) {

					html += '<div class="nick"><a href="#" onclick="messageTo(this); return false;">'
					+ htmlEncode(users[iterator])
					+ '</a></div>';
				}

				usersList.innerHTML = html;

				usersList.scrollTop = listTop;

				refreshStatus = 0;
			});

			return true;
		}

		function changeNick()
		{
			var message = document.getElementById('message');

			var nick = document.getElementById('nick');

			nick.value = trim(nick.value);

			if (nick.value.length < 1) {

				nick.value = 'Guest' + Math.round(Math.random() * 1000000);

				return changeNick();
			}

			if (nick.value.toLowerCase() == client.nick.toLowerCase()) {

				return true;
			}

			if (users != null) {

				var newNick = nick.value.toLowerCase();

				for (var iterator = 0; iterator < users.length; iterator ++) {

					if (users[iterator].toLowerCase() == newNick) {

						nick.value += Math.round(Math.random() * 1000000);

						return changeNick();
					}
				}
			}

			message.setAttribute('disabled', true);

			nick.setAttribute('disabled', true);

			client.onRenameUserError = function(error) {

				var message = document.getElementById('message');

				var nick = document.getElementById('nick');

				message.removeAttribute('disabled');

				nick.removeAttribute('disabled');

				nick.value = client.nick;

				return true;
			}

			client.renameUser(client.nick, nick.value, function() {

				client.postEvent('onNickChange', { oldNick : client.nick, newNick : nick.value });

				client.nick = nick.value;

				message.removeAttribute('disabled');

				nick.removeAttribute('disabled');
			});

			return true;
		}

		function sendMessage(e)
		{
			var key = (e.keyCode ? e.keyCode : e.which);

			if (key == 13) {

				var message = document.getElementById('message');

				message.value = trim(message.value);

				if (message.value.length > 0) {

					client.postEvent('onMessage', message.value);

					message.value = '';
				}

				message.focus();
			}
		}

		window.onload = function() {

			var message = document.getElementById('message');

			var nick = document.getElementById('nick');

			nick.value = 'Guest' + Math.round(Math.random() * 1000000);

			client.nick = nick.value;

			client.onAddUserError = function(error) {

				window.location.reload();

				return true;
			}

			client.addUser(client.nick, function() {

				message.removeAttribute('disabled');

				nick.removeAttribute('disabled');

				client.postEvent('onUserJoin', client.nick);

				window.setInterval('refreshUsers()', 5000);

				refreshUsers();

				message.focus();
			});
		}

		client.onMessage = function(sender, message)
		{
			var boxContent = document.getElementById('box-content');

			var chatContent = document.getElementById('chat-content');

			tempMessage = ' ' + htmlEncode(message);

			tempMessage = tempMessage.replace(/(^|[\n (?:\&nbsp\;)])([\w]+?\:\/\/[\w\#$%&~\/.\-;:=,?@\[\]+]*)/i, '$1<a href=\"$2\" target=\"_blank\">$2</a>');
			tempMessage = tempMessage.replace(/(^|[\n (?:\&nbsp\;)])((www|ftp)\.[\w\#$%&~\/.\-;:=,?@\[\]+]*)/i, '$1<a href=\"http://$2\" target=\"_blank\">$2</a>');
			tempMessage = tempMessage.replace(/(^|[\n (?:\&nbsp\;)])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i, '$1<a href=\"mailto:$2@$3\">$2@$3</a>');

			message = tempMessage.substr(1);

			for (var iterator = 0; iterator < emoticons.length; iterator ++) {

				if (message.indexOf(emoticons[iterator][0]) >= 0) {

					var lastMessage = null;

					while (lastMessage != message) {

						lastMessage = message;

						message = message.replace(emoticons[iterator][0],
						'<img src="smilies/' + emoticons[iterator][1] + '.gif" alt="' + emoticons[iterator][1] + '" border="0" />');
					}
				}
			}

			chatContent.innerHTML += '<div class="message'
			+ (contentSize % 2 == 1 ? ' message-alternative' : '')
			+ '">'
			+ '<span class="nick">&lt;'
			+ '<a href="#" onclick="messageTo(this); return false;" style="color: '
			+ htmlEncode(sender.color)
			+ ';">'
			+ htmlEncode(sender.nick)
			+ '</a>&gt;</span>&nbsp;'
			+ message
			+ '</div>';

			boxContent.scrollTop += boxContent.offsetHeight;

			contentSize ++;
		}

		client.onUserJoin = function(sender, nick)
		{
			var boxContent = document.getElementById('box-content');

			var chatContent = document.getElementById('chat-content');

			chatContent.innerHTML += '<div class="join-message'
			+ (contentSize % 2 == 1 ? ' join-message-alternative' : '')
			+ '">'
			+ '<span class="nick">'
			+ '<a href="#" onclick="messageTo(this); return false;" style="color: '
			+ htmlEncode(sender.color)
			+ ';">'
			+ htmlEncode(sender.nick)
			+ '</a></span> has joined the chat.'
			+ '</div>';

			boxContent.scrollTop += boxContent.offsetHeight;

			contentSize ++;
		}

		client.onUserLeave = function(sender, nick)
		{
			var boxContent = document.getElementById('box-content');

			var chatContent = document.getElementById('chat-content');

			chatContent.innerHTML += '<div class="leave-message'
			+ (contentSize % 2 == 1 ? ' leave-message-alternative' : '')
			+ '">'
			+ '<span class="nick">'
			+ htmlEncode(nick)
			+ '</span> has left the chat.'
			+ '</div>';

			boxContent.scrollTop += boxContent.offsetHeight;

			contentSize ++;
		}

		client.onNickChange = function(sender, arguments)
		{
			var boxContent = document.getElementById('box-content');

			var chatContent = document.getElementById('chat-content');

			chatContent.innerHTML += '<div class="nick-change-message'
			+ (contentSize % 2 == 1 ? ' nick-change-message-alternative' : '')
			+ '">'
			+ '<span class="nick">'
			+ '<a href="#" onclick="messageTo(this); return false;" style="color: '
			+ htmlEncode(sender.color)
			+ ';">'
			+ htmlEncode(arguments.oldNick)
			+ '</a></span> is now known as '
			+ '<span class="nick">'
			+ '<a href="#" onclick="messageTo(this); return false;" style="color: '
			+ htmlEncode(sender.color)
			+ ';">'
			+ htmlEncode(arguments.newNick)
			+ '</a></span>.'
			+ '</div>';

			boxContent.scrollTop += boxContent.offsetHeight;

			contentSize ++;
		}

		client.catchEvent('onMessage');
		client.catchEvent('onUserJoin');
		client.catchEvent('onUserLeave');
		client.catchEvent('onNickChange');

		</script>
		<style type="text/css" media="screen">
			@import url(<?= $styleFile ?>);
		</style>
	</head>
	<body>
		<table cellpadding="0" cellspacing="0" border="0" width="100%"
			summary="XOAD Advanced Chat User Interface">
			<caption>Welcome to XOAD Advanced Chat</caption>
			<tbody>
				<tr>
					<td class="cell-content" rowspan="2">
						<div id="box-content">
							<div id="chat-content" class="wrapper"></div>
						</div>
					</td>
					<td class="cell-nick">
						<div id="box-nick">
							<div class="wrapper">
								<input type="text" id="nick" name="nick" maxlength="20" onblur="return changeNick();" disabled="disabled" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="cell-users">
						<div id="box-users">
							<div id="users-list" class="wrapper"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="cell-message">
						<div id="box-message">
							<div class="wrapper">
								<input type="text" id="message" name="message" maxlength="200" onkeydown="sendMessage(event);" disabled="disabled" />
							</div>
						</div>
					</td>
					<td class="cell-actions">
						<div id="box-actions">
							<div class="wrapper">
								<input type="text" id="color" name="color" maxlength="20" onkeyup="changeColor(this);" value="black" onfocus="select()" />
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<code id="content"></code>
	</body>
</html>