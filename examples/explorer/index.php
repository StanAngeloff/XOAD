<?php

require_once('Explorer.class.php');

define('XOAD_AUTOHANDLE', true);

require_once('../../xoad.php');

?>
<html>
	<head>
		<title>Simple XOAD Explorer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?= XOAD_Utilities::header('../..') ?>

		<style type="text/css">@import url(style/explorer.css);</style>
	</head>
	<body>
		<script type="text/javascript">
		<!--
		<?= XOAD_Client::register('Explorer', 'index.php') ?>;

		function js_escape(text)
		{
			if (text == null) {

				return null;
			}

			return text.replace(/\'/gi, '\\\'').replace(/\"/gi, '\\\"');
		}

		function exploreFolder(path)
		{
			var explorerObject = new Explorer();

			explorerObject.setTimeout(10000);

			if (typeof(path) == 'undefined') {

				path = '.';
			}

			var content = document.getElementById('content');

			var location = path.replace(/^\./gi, 'Home').replace(/[\/\\]/ig, ' &raquo ').replace(/ /ig, '&nbsp;');

			// Uncomment to see a loading message:
			// content.innerHTML = '<div class="loading">Loading "' + location + '"...</div>';

			content.style.display = 'block';

			var links = content.getElementsByTagName('a');

			if (links.length > 0) {

				for (var iterator = 0; iterator < links.length; iterator ++) {

					links[iterator].onclick = function() { return false; };

					links[iterator].disabled = 'disabled';

					links[iterator].className += ' folder-disabled';
				}
			}

			explorerObject.explorefolder(path, function(result) {

				if (result) {

					var code = '<div class="location"><strong>Location</strong>: ' + location + '</div>';

					for (var iterator = 0; iterator < explorerObject.folders.length; iterator ++) {

						var name = explorerObject.folders[iterator];

						var parent = '.';

						if (name == '..') {

							for (var parentIterator = path.length - 1; parentIterator >= 0; parentIterator --) {

								if (
								(path.charAt(parentIterator) == '/') ||
								(path.charAt(parentIterator) == '\\')) {

									break;
								}
							}

							parent = path.substring(0, parentIterator);

						} else {

							parent = path + '/' + name;
						}

						code += '<a class="folder-name" ';
						code += 'href="javascript: void(0);" ';
						code += 'onclick="exploreFolder(\'' + js_escape(parent) + '\');" ';
						code += '><strong>' + name + '</strong></a>';
					}

					for (var iterator = 0; iterator < explorerObject.files.length; iterator ++) {

						var name = explorerObject.files[iterator];

						code += '<span class="file-name"';
						code += '>' + name + '</span>';
					}

					content.innerHTML = code;

				} else {

					alert('Failed loading "' + location + '".');
				}
			});
		}

		function handleError(error) {}

		xoad.setErrorHandler(handleError);

		window.onload = function() {

			exploreFolder();
		}
		
		-->
		</script>
		<div id="content"></div>
	</body>
</html>