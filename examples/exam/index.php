<?php

require_once('Exam.class.php');

define('XOAD_AUTOHANDLE', true);

require_once('../../xoad.php');

$exam = new Exam();

$exam->loadQuestions();

$exam->cleanAnswers();

?>
<?= '<?' ?>xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	<head>
		<title>XOAD Exam System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?= XOAD_Utilities::header('../..') ?>

		<style type="text/css" media="screen">

			body
			{
				background-color: #fff;
				color: #000;
				font: normal 0.8em tahoma, verdana, arial, serif;
				margin: 0;
				padding: 1em;
			}

			a
			{
				color: #04c;
				text-decoration: underline;
			}

			a:hover
			{
				color: #08f;
				text-decoration: underline;
			}

			a.active,
			a.active:hover
			{
				color: #c00;
				cursor: default;
				text-decoration: none;
			}

			a.answered,
			a.answered:hover
			{
				color: #4a4;
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
				top: 1em;
				width: 6em;
			}

			#answers ol
			{
				margin: 0;
				padding: 0 0 1em 2em;
			}

			#answers ol li
			{
				margin: 0;
				padding: 0 0 0.25em 0;
			}

			#answers ol .correct
			{
				color: #4a4;
				font-weight: bold;
			}

			#answers ol .incorrect
			{
				color: #c00;
				font-weight: bold;
				text-decoration: line-through;
			}

			h1
			{
				border-bottom: 0.1em solid #ccc;
				font-size: 1.2em;
				padding: 0;
				margin: 0 0 1em 0;
			}

			h2
			{
				font-size: 0.9em;
				margin: 0 0 0.5em 0;
				padding: 0;
			}

			.answer
			{
				font-weight: bold;
			}

		</style>
	</head>
	<body>
		<h1 id="question">Welcome</h1>
		<div id="answers"></div>
		<div id="pager"></div>
		<div id="loading">Loading...</div>
		<script type="text/javascript">
		<!--
		var exam = <?= XOAD_Client::register($exam) ?>;

		var pageState = 0;

		var timeout = 10000;

		var answeredQuestion = 0;

		var currentQuestion = null;

		function showLoading()
		{
			document.body.style.cursor = 'wait';

			document.getElementById('loading').style.display = 'block';
		};

		function hideLoading()
		{
			document.body.style.cursor = 'default';

			document.getElementById('loading').style.display = 'none';
		};

		function openQuestion(id)
		{
			if (
			(pageState > 0) ||
			(currentQuestion == id) ||
			(typeof(exam.questions[id]) == 'undefined')) {

				return false;
			}

			pageState ++;

			showLoading();

			exam.onGetAnswersError = function(error) {

				hideLoading();

				pageState --;

				alert('The was an error fetching the question from the server:\n\n' + error.message);

				return true;
			};

			exam.setTimeout(timeout);

			exam.getAnswers(id, function(result) {

				if (
				(result == null) ||
				(result == false)) {

					return false;
				}

				if (currentQuestion != null) {

					var currentPagerLink = document.getElementById('question-' + currentQuestion);

					currentPagerLink.className = currentPagerLink.className.replace(' active', '');
				}

				var pagerLink = document.getElementById('question-' + id);

				pagerLink.className += ' active';

				var question = document.getElementById('question');

				question.innerHTML = (id + 1) + '. ' + exam.questions[id];

				var answers = document.getElementById('answers');

				for (var iterator = answers.childNodes.length - 1; iterator >= 0; iterator --) {

					answers.removeChild(answers.childNodes[iterator]);
				}

				var list = document.createElement('ol');

				for (var iterator = 0; iterator < result.data.length; iterator ++) {

					var item = document.createElement('li');

					if (result.answer >= 0) {

						var text = document.createElement('span');

						text.innerHTML = result.data[iterator];

						if (iterator == result.answer) {

							text.className = 'answer';
						}

						item.appendChild(text);

					} else {

						var link = document.createElement('a');

						link.innerHTML = result.data[iterator];

						link.setAttribute('href', '#');

						link.setAttribute('id', 'answer-' + iterator);

						link.setAttribute('rel', iterator);

						link.onclick = function() {

							this.blur();

							submitAnswer(parseInt(this.getAttribute('rel')));

							return false;
						};

						item.appendChild(link);
					}

					list.appendChild(item);
				}

				answers.appendChild(list);

				currentQuestion = id;

				hideLoading();

				pageState --;
			});

			return true;
		};

		function submitAnswer(id)
		{
			if (
			(pageState > 0) ||
			(currentQuestion == null) ||
			(typeof(exam.questions[currentQuestion]) == 'undefined')) {

				return false;
			}

			pageState ++;

			showLoading();

			exam.onSubmitAnswerError = function(error) {

				hideLoading();

				pageState --;

				alert('The was an error submitting the answer to the server:\n\n' + error.message);

				return true;
			};

			exam.setTimeout(timeout);

			exam.submitAnswer(currentQuestion, id, function(result) {

				if (
				(result == null) ||
				(result == false)) {

					return false;
				}

				var pagerLink = document.getElementById('question-' + currentQuestion);

				pagerLink.className += ' answered';

				hideLoading();

				pageState --;

				answeredQuestion ++;

				if (answeredQuestion < exam.questions.length) {

					if (currentQuestion < exam.questions.length - 1) {

						openQuestion(currentQuestion + 1);

					} else {

						openQuestion(0);
					}

				} else {

					showResult();
				}
			});

			return true;
		};

		function showResult()
		{
			if (pageState < 1) {

				pageState ++;
			}

			var question = document.getElementById('question');

			var answers = document.getElementById('answers');

			var pager = document.getElementById('pager');

			question.innerHTML = 'Results';

			for (var iterator = answers.childNodes.length - 1; iterator >= 0; iterator --) {

				answers.removeChild(answers.childNodes[iterator]);
			}

			for (var iterator = pager.childNodes.length - 1; iterator >= 0; iterator --) {

				pager.removeChild(pager.childNodes[iterator]);
			}

			showLoading();

			exam.onFetchResultsError = function(error) {

				hideLoading();

				if (confirm('The was an error fetching the results from the server:\n\n' + error.message + '\n\nTry again?')) {

					showResult();
				}

				return true;
			};

			exam.setTimeout(timeout);

			exam.fetchResults(function(result) {

				if (
				(result == null) ||
				(result == false)) {

					return false;
				}

				var correctAnswers = 0;

				for (var iterator = 0; iterator < result.data.length; iterator ++) {

					var title  = document.createElement('h2');

					title.innerHTML = (iterator + 1) + '. ' + result.data[iterator][0];

					answers.appendChild(title);

					var list = document.createElement('ol');

					var correctAnswer = result.data[iterator][[result.data[iterator].length - 1]];

					var givenAnswer = result.answers[iterator];

					var isCorrect = (correctAnswer == givenAnswer);

					correctAnswers += (isCorrect ? 1 : 0);

					for (var answersIterator = 1; answersIterator < result.data[iterator].length - 1; answersIterator ++) {

						var item = document.createElement('li');

						item.innerHTML = result.data[iterator][answersIterator];

						if (answersIterator - 1 == givenAnswer) {

							if ( ! isCorrect) {

								item.className = 'incorrect';

							} else {

								item.className = 'correct';
							}

						} else {

							if ( ! isCorrect) {

								if (answersIterator - 1 == correctAnswer) {

									item.className = 'correct';
								}
							}
						}

						list.appendChild(item);
					}

					answers.appendChild(list);
				}

				question.innerHTML += ' (' + correctAnswers + ' / ' + result.data.length + ')';

				hideLoading();
			});
		};

		function buildPager()
		{
			var pager = document.getElementById('pager');

			for (var iterator = 0; iterator < exam.questions.length; iterator ++) {

				var link = document.createElement('a');

				link.innerHTML = iterator + 1;

				link.setAttribute('id', 'question-' + iterator);

				link.setAttribute('href', '#');

				link.onclick = function() {

					this.blur();

					openQuestion(parseInt(this.innerHTML) - 1);

					return false;
				};

				pager.appendChild(link);

				if (iterator < exam.questions.length - 1) {

					pager.appendChild(document.createTextNode(' '));
				}
			}
		};

		buildPager();

		openQuestion(0);
		-->
		</script>
	</body>
</html>