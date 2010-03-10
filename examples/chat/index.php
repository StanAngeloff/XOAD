<?php

require_once('Chat.class.php');

define('XOAD_AUTOHANDLE', true);

require_once('../../xoad.php');

?>
<html>
	<head>
		<title>Simple XOAD Chat</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?= XOAD_Utilities::header('../..') ?>

	</head>
	<body style="overflow: hidden; background-color: #fff; font: normal 0.8em tahoma, verdana, arial, serif;">
		<div
			id="chatBox"
			style="height: 320px; overflow: auto; background-color: #fcfcfc; border: 1px solid #ccc; margin: 0 0 3px 0; padding: 5px;"></div>
		<input
			type="text"
			accesskey="m"
			id="message"
			name="message"
			maxlength="100"
			style="font: normal 1em tahoma, verdana, arial, serif; width: 100%; margin: 0 0 3px 0;" />
		<table cellspacing="0" cellpadding="3" border="0">
			<tr>
				<td style="font: normal 0.8em tahoma, verdana, arial, serif;">Nick:</td>
				<td>
					<input
						type="text"
						accesskey="n"
						id="userNick"
						name="userNick"
						maxlength="100"
						value="Guest"
						style="font: normal 0.8em tahoma, verdana, arial, serif; width: 120px; margin: 0 0 3px 0;" />
				</td>
				<td style="font: normal 0.8em tahoma, verdana, arial, serif;">Color:</td>
				<td>
					<select
						accesskey="c"
						id="userColor"
						name="userColor"
						style="font: normal 0.8em tahoma, verdana, arial, serif; width: 120px; margin: 0 0 3px 0;">
							<option style="color: #F0F8FF; background-color: #F0F8FF;">AliceBlue</option>
							<option style="color: #FAEBD7; background-color: #FAEBD7;">AntiqueWhite</option>
							<option style="color: #00FFFF; background-color: #00FFFF;">Aqua</option>
							<option style="color: #7FFFD4; background-color: #7FFFD4;">Aquamarine</option>
							<option style="color: #F0FFFF; background-color: #F0FFFF;">Azure</option>
							<option style="color: #F5F5DC; background-color: #F5F5DC;">Beige</option>
							<option style="color: #FFE4C4; background-color: #FFE4C4;">Bisque</option>
							<option style="color: #000000; background-color: #000000;" selected="selected">Black</option>
							<option style="color: #FFEBCD; background-color: #FFEBCD;">BlanchedAlmond</option>
							<option style="color: #0000FF; background-color: #0000FF;">Blue</option>
							<option style="color: #8A2BE2; background-color: #8A2BE2;">BlueViolet</option>
							<option style="color: #A52A2A; background-color: #A52A2A;">Brown</option>
							<option style="color: #DEB887; background-color: #DEB887;">BurlyWood</option>
							<option style="color: #5F9EA0; background-color: #5F9EA0;">CadetBlue</option>
							<option style="color: #7FFF00; background-color: #7FFF00;">Chartreuse</option>
							<option style="color: #D2691E; background-color: #D2691E;">Chocolate</option>
							<option style="color: #FF7F50; background-color: #FF7F50;">Coral</option>
							<option style="color: #6495ED; background-color: #6495ED;">CornflowerBlue</option>
							<option style="color: #FFF8DC; background-color: #FFF8DC;">Cornsilk</option>
							<option style="color: #DC143C; background-color: #DC143C;">Crimson</option>
							<option style="color: #00FFFF; background-color: #00FFFF;">Cyan</option>
							<option style="color: #00008B; background-color: #00008B;">DarkBlue</option>
							<option style="color: #008B8B; background-color: #008B8B;">DarkCyan</option>
							<option style="color: #B8860B; background-color: #B8860B;">DarkGoldenRod</option>
							<option style="color: #A9A9A9; background-color: #A9A9A9;">DarkGray</option>
							<option style="color: #006400; background-color: #006400;">DarkGreen</option>
							<option style="color: #BDB76B; background-color: #BDB76B;">DarkKhaki</option>
							<option style="color: #8B008B; background-color: #8B008B;">DarkMagenta</option>
							<option style="color: #556B2F; background-color: #556B2F;">DarkOliveGreen</option>
							<option style="color: #FF8C00; background-color: #FF8C00;">Darkorange</option>
							<option style="color: #9932CC; background-color: #9932CC;">DarkOrchid</option>
							<option style="color: #8B0000; background-color: #8B0000;">DarkRed</option>
							<option style="color: #E9967A; background-color: #E9967A;">DarkSalmon</option>
							<option style="color: #8FBC8F; background-color: #8FBC8F;">DarkSeaGreen</option>
							<option style="color: #483D8B; background-color: #483D8B;">DarkSlateBlue</option>
							<option style="color: #2F4F4F; background-color: #2F4F4F;">DarkSlateGray</option>
							<option style="color: #00CED1; background-color: #00CED1;">DarkTurquoise</option>
							<option style="color: #9400D3; background-color: #9400D3;">DarkViolet</option>
							<option style="color: #FF1493; background-color: #FF1493;">DeepPink</option>
							<option style="color: #00BFFF; background-color: #00BFFF;">DeepSkyBlue</option>
							<option style="color: #696969; background-color: #696969;">DimGray</option>
							<option style="color: #1E90FF; background-color: #1E90FF;">DodgerBlue</option>
							<option style="color: #D19275; background-color: #D19275;">Feldspar</option>
							<option style="color: #B22222; background-color: #B22222;">FireBrick</option>
							<option style="color: #FFFAF0; background-color: #FFFAF0;">FloralWhite</option>
							<option style="color: #228B22; background-color: #228B22;">ForestGreen</option>
							<option style="color: #FF00FF; background-color: #FF00FF;">Fuchsia</option>
							<option style="color: #DCDCDC; background-color: #DCDCDC;">Gainsboro</option>
							<option style="color: #F8F8FF; background-color: #F8F8FF;">GhostWhite</option>
							<option style="color: #FFD700; background-color: #FFD700;">Gold</option>
							<option style="color: #DAA520; background-color: #DAA520;">GoldenRod</option>
							<option style="color: #808080; background-color: #808080;">Gray</option>
							<option style="color: #008000; background-color: #008000;">Green</option>
							<option style="color: #ADFF2F; background-color: #ADFF2F;">GreenYellow</option>
							<option style="color: #F0FFF0; background-color: #F0FFF0;">HoneyDew</option>
							<option style="color: #FF69B4; background-color: #FF69B4;">HotPink</option>
							<option style="color: #CD5C5C; background-color: #CD5C5C;">IndianRed</option>
							<option style="color: #4B0082; background-color: #4B0082;">Indigo</option>
							<option style="color: #FFFFF0; background-color: #FFFFF0;">Ivory</option>
							<option style="color: #F0E68C; background-color: #F0E68C;">Khaki</option>
							<option style="color: #E6E6FA; background-color: #E6E6FA;">Lavender</option>
							<option style="color: #FFF0F5; background-color: #FFF0F5;">LavenderBlush</option>
							<option style="color: #7CFC00; background-color: #7CFC00;">LawnGreen</option>
							<option style="color: #FFFACD; background-color: #FFFACD;">LemonChiffon</option>
							<option style="color: #ADD8E6; background-color: #ADD8E6;">LightBlue</option>
							<option style="color: #F08080; background-color: #F08080;">LightCoral</option>
							<option style="color: #E0FFFF; background-color: #E0FFFF;">LightCyan</option>
							<option style="color: #FAFAD2; background-color: #FAFAD2;">LightGoldenRodYellow</option>
							<option style="color: #D3D3D3; background-color: #D3D3D3;">LightGrey</option>
							<option style="color: #90EE90; background-color: #90EE90;">LightGreen</option>
							<option style="color: #FFB6C1; background-color: #FFB6C1;">LightPink</option>
							<option style="color: #FFA07A; background-color: #FFA07A;">LightSalmon</option>
							<option style="color: #20B2AA; background-color: #20B2AA;">LightSeaGreen</option>
							<option style="color: #87CEFA; background-color: #87CEFA;">LightSkyBlue</option>
							<option style="color: #8470FF; background-color: #8470FF;">LightSlateBlue</option>
							<option style="color: #778899; background-color: #778899;">LightSlateGray</option>
							<option style="color: #B0C4DE; background-color: #B0C4DE;">LightSteelBlue</option>
							<option style="color: #FFFFE0; background-color: #FFFFE0;">LightYellow</option>
							<option style="color: #00FF00; background-color: #00FF00;">Lime</option>
							<option style="color: #32CD32; background-color: #32CD32;">LimeGreen</option>
							<option style="color: #FAF0E6; background-color: #FAF0E6;">Linen</option>
							<option style="color: #FF00FF; background-color: #FF00FF;">Magenta</option>
							<option style="color: #800000; background-color: #800000;">Maroon</option>
							<option style="color: #66CDAA; background-color: #66CDAA;">MediumAquaMarine</option>
							<option style="color: #0000CD; background-color: #0000CD;">MediumBlue</option>
							<option style="color: #BA55D3; background-color: #BA55D3;">MediumOrchid</option>
							<option style="color: #9370D8; background-color: #9370D8;">MediumPurple</option>
							<option style="color: #3CB371; background-color: #3CB371;">MediumSeaGreen</option>
							<option style="color: #7B68EE; background-color: #7B68EE;">MediumSlateBlue</option>
							<option style="color: #00FA9A; background-color: #00FA9A;">MediumSpringGreen</option>
							<option style="color: #48D1CC; background-color: #48D1CC;">MediumTurquoise</option>
							<option style="color: #C71585; background-color: #C71585;">MediumVioletRed</option>
							<option style="color: #191970; background-color: #191970;">MidnightBlue</option>
							<option style="color: #F5FFFA; background-color: #F5FFFA;">MintCream</option>
							<option style="color: #FFE4E1; background-color: #FFE4E1;">MistyRose</option>
							<option style="color: #FFE4B5; background-color: #FFE4B5;">Moccasin</option>
							<option style="color: #FFDEAD; background-color: #FFDEAD;">NavajoWhite</option>
							<option style="color: #000080; background-color: #000080;">Navy</option>
							<option style="color: #FDF5E6; background-color: #FDF5E6;">OldLace</option>
							<option style="color: #808000; background-color: #808000;">Olive</option>
							<option style="color: #6B8E23; background-color: #6B8E23;">OliveDrab</option>
							<option style="color: #FFA500; background-color: #FFA500;">Orange</option>
							<option style="color: #FF4500; background-color: #FF4500;">OrangeRed</option>
							<option style="color: #DA70D6; background-color: #DA70D6;">Orchid</option>
							<option style="color: #EEE8AA; background-color: #EEE8AA;">PaleGoldenRod</option>
							<option style="color: #98FB98; background-color: #98FB98;">PaleGreen</option>
							<option style="color: #AFEEEE; background-color: #AFEEEE;">PaleTurquoise</option>
							<option style="color: #D87093; background-color: #D87093;">PaleVioletRed</option>
							<option style="color: #FFEFD5; background-color: #FFEFD5;">PapayaWhip</option>
							<option style="color: #FFDAB9; background-color: #FFDAB9;">PeachPuff</option>
							<option style="color: #CD853F; background-color: #CD853F;">Peru</option>
							<option style="color: #FFC0CB; background-color: #FFC0CB;">Pink</option>
							<option style="color: #DDA0DD; background-color: #DDA0DD;">Plum</option>
							<option style="color: #B0E0E6; background-color: #B0E0E6;">PowderBlue</option>
							<option style="color: #800080; background-color: #800080;">Purple</option>
							<option style="color: #FF0000; background-color: #FF0000;">Red</option>
							<option style="color: #BC8F8F; background-color: #BC8F8F;">RosyBrown</option>
							<option style="color: #4169E1; background-color: #4169E1;">RoyalBlue</option>
							<option style="color: #8B4513; background-color: #8B4513;">SaddleBrown</option>
							<option style="color: #FA8072; background-color: #FA8072;">Salmon</option>
							<option style="color: #F4A460; background-color: #F4A460;">SandyBrown</option>
							<option style="color: #2E8B57; background-color: #2E8B57;">SeaGreen</option>
							<option style="color: #FFF5EE; background-color: #FFF5EE;">SeaShell</option>
							<option style="color: #A0522D; background-color: #A0522D;">Sienna</option>
							<option style="color: #C0C0C0; background-color: #C0C0C0;">Silver</option>
							<option style="color: #87CEEB; background-color: #87CEEB;">SkyBlue</option>
							<option style="color: #6A5ACD; background-color: #6A5ACD;">SlateBlue</option>
							<option style="color: #708090; background-color: #708090;">SlateGray</option>
							<option style="color: #FFFAFA; background-color: #FFFAFA;">Snow</option>
							<option style="color: #00FF7F; background-color: #00FF7F;">SpringGreen</option>
							<option style="color: #4682B4; background-color: #4682B4;">SteelBlue</option>
							<option style="color: #D2B48C; background-color: #D2B48C;">Tan</option>
							<option style="color: #008080; background-color: #008080;">Teal</option>
							<option style="color: #D8BFD8; background-color: #D8BFD8;">Thistle</option>
							<option style="color: #FF6347; background-color: #FF6347;">Tomato</option>
							<option style="color: #40E0D0; background-color: #40E0D0;">Turquoise</option>
							<option style="color: #EE82EE; background-color: #EE82EE;">Violet</option>
							<option style="color: #D02090; background-color: #D02090;">VioletRed</option>
							<option style="color: #F5DEB3; background-color: #F5DEB3;">Wheat</option>
							<option style="color: #FFFFFF; background-color: #FFFFFF;">White</option>
							<option style="color: #F5F5F5; background-color: #F5F5F5;">WhiteSmoke</option>
							<option style="color: #FFFF00; background-color: #FFFF00;">Yellow</option>
							<option style="color: #9ACD32; background-color: #9ACD32;">YellowGreen</option>
					</select>
				</td>
			</tr>
		</table>

		<script type="text/javascript">

		<?= XOAD_Client::register('Chat', 'index.php') ?>;

		var chatCache = null;

		var chatRefresh = false;

		window.onload = function() {

			var userNick = document.getElementById('userNick');

			var userTime = new Date();

			userNick.value += userTime.getHours();
			userNick.value += userTime.getMinutes();
			userNick.value += userTime.getSeconds();

			var message = document.getElementById('message');

			message.onkeydown = function(e) {

				if (typeof(e) == 'undefined') {

					e = event;
				}

				var key = (e.keyCode ? e.keyCode : e.which);

				if (key == 13) {

					var userNick = document.getElementById('userNick');

					var userColor = document.getElementById('userColor');

					var body = this.value.replace(/^\s+/i, '').replace(/\s+$/i, '');

					if (body.length < 1) {

						return false;
					}

					var nick = userNick.value.replace(/^\s+/i, '').replace(/\s+$/i, '');

					if (nick.length < 1) {

						nick = 'Guest';
					}

					var color = userColor.options[userColor.selectedIndex].text;

					message.value = '';

					message.focus();

					var chatServer = new Chat();

					chatServer.addMessage(body, nick, color, function(result) {

						refresh();
					});
				}

				return true;
			}

			message.focus();

			refresh();

			setInterval('refresh()', 1000);
		}

		function refresh()
		{
			if (chatRefresh) {

				return false;
			}

			chatRefresh = true;

			var chatServer = new Chat();

			chatServer.getContents(function(result) {

				if (result != false) {

					var newContent = false;

					if (chatCache == null) {

						newContent = true;

					} else {

						if (result.length != chatCache.length) {

							newContent = true;

						} else {

							for (var iterator = 0; iterator < result.length; iterator ++) {

								if (result[iterator] != chatCache[iterator]) {

									newContent = true;

									break;
								}
							}
						}
					}
				}

				if (newContent) {

					chatCache = result;

					var chatBox = document.getElementById('chatBox');

					var content = '';

					for (var iterator = 0; iterator < result.length; iterator ++) {

						content += result[iterator];
					}

					chatBox.innerHTML = content;

					chatBox.scrollTop = iterator * 100;
				}

				chatRefresh = false;
			});
		}

		xoad.setErrorHandler(function(error) {

			alert('Error:\n' + error.message);

			chatRefresh = false;
		});

		</script>
	</body>
</html>