<?php

class Content
{
	public function getPage($id, $activeId)
	{
		if (($id < 1) || ($id > 4)) {

			return null;
		}

		if (($activeId >= 1) && ($activeId <= 4)) {

			$activeTab =& XOAD_HTML::getElementById('page-' . $activeId);

			$activeTab->className = '';
		}

		$pageTab =& XOAD_HTML::getElementById('page-' . $id);

		$pageTab->className = 'active';

		$page = @join(null, @file(XOAD_BASE . '/examples/html/content/page' . $id . '.html'));
		
		$content =& XOAD_HTML::getElementById('content');

		$content->innerHTML = $page;

		return true;
	}

	public function xoadGetMeta()
	{
		XOAD_Client::mapMethods($this, array('getPage'));
	}
}

require_once('../../xoad.php');

XOAD_Server::allowClasses('Content');

if (XOAD_Server::runServer()) {

	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	<head>
		<title>XOAD_HTML Example</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?= XOAD_Utilities::header('../..') ?>

		<style type="text/css" media="screen">

			body
			{
				background-color: #fff;
				color: #000;
				font: normal 0.8em tahoma, verdana, arial, serif;
				margin: 0;
				padding: 0;
			}

			h1
			{
				background-color: #369;
				border-bottom: 0.1em solid #036;
				color: #fff;
				font-size: 1.5em;
				margin: 0;
				padding: 0.25em 0.5em 0.25em 0.5em;
			}

			h2
			{
				font-size: 1.2em;
			}

			ul
			{
				list-style-type: none;
				margin: 0;
				padding: 1em;
			}

			ul li
			{
				background-color: #69c;
				border-top: 0.1em solid #9cf;
				border-right: 0.1em solid #369;
				border-bottom: 0.1em solid #369;
				border-left: 0.1em solid #9cf;
				clear: right;
				float: left;
				margin-right: 0.1em;
				padding: 0.25em 0 0.25em 0;
				text-align: center;
				width: 10em;
			}

			ul li.active
			{
				background-color: #f0f0f0;
				border-top: 0.1em solid #eee;
				border-right: 0.1em solid #ccc;
				border-bottom: 0.1em solid #ccc;
				border-left: 0.1em solid #eee;
			}

			ul li a
			{
				color: #ff0;
				display: block;
				text-decoration: none;
				width: 100%;
			}

			ul li a:hover
			{
				color: #fff;
				text-decoration: none;
			}

			ul li.active a,
			ul li.active a:hover
			{
				color: #000;
			}

			#content
			{
				padding: 1.25em;
				width: 35em;
			}

			#loading
			{
				background-color: #c00;
				border: 0.1em solid #800;
				color: #fff;
				display: none;
				margin: 0;
				right: 1em;
				padding: 0.25em 0.5em 0.25em 0.5em;
				position: absolute;
				bottom: 1em;
				width: 6em;
			}

		</style>
	</head>
	<body>
		<h1>XOAD_HTML Example</h1>
		<ul id="pages">
			<li id="page-1"><a href="#1" onclick="blur(); loadPage(1);">Page 1</a></li>
			<li id="page-2"><a href="#2" onclick="blur(); loadPage(2);">Page 2</a></li>
			<li id="page-3"><a href="#3" onclick="blur(); loadPage(3);">Page 3</a></li>
			<li id="page-4"><a href="#4" onclick="blur(); loadPage(4);">Page 4</a></li>
		</ul>
		<div style="clear: both;"></div>
		<div id="content"></div>
		<div id="loading">Loading...</div>

		<script type="text/javascript">
		<!--
		var content = <?= XOAD_Client::register(new Content()) ?>;

		var pageState = 0;

		var activeId = 0;

		function showLoading()
		{
			document.body.style.cursor = 'wait';

			document.getElementById('loading').style.display = 'block';
		};

		function hideLoading()
		{
			document.body.style.cursor = '';

			document.getElementById('loading').style.display = 'none';
		};

		function loadPage(id)
		{
			if (id == activeId) {

				return false;
			}

			if (pageState > 0) {

				return false;
			}

			pageState++;

			showLoading();

			content.onGetPageError = function() {

				hideLoading();

				pageState--;
			}

			content.setTimeout(10000);

			content.getPage(id, activeId, function() {

				activeId = id;

				hideLoading();

				pageState--;
			});
		};

		if (window.location.href.indexOf('#') >= 0) {

			var hrefId = parseInt(window.location.href.split('#')[1]);

			if ((hrefId >= 1) && (hrefId <= 4)) {

				loadPage(hrefId);

			} else {

				loadPage(1);
			}

		} else {

			loadPage(1);
		}
		-->
		</script>
	</body>
</html>