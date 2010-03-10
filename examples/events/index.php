<?php

class TestEvents
{
	public $myVar = 0;

	public function Test() {}
}

require_once('../../xoad.php');

class ServerObserver extends XOAD_Observer
{
	public $serverEvents = array();
	
	public function updateObserver($event, $arg)
	{
		if ($arg == null) {

			$arg = "null";
		}

		$this->serverEvents[] = $event . ' => ' . str_replace("\n", "\n" . str_repeat(' ', strlen($event) + 4), var_export($arg, true));

		if ($event == 'dispatchLeave') {

			$arg['response']['output'] = "<strong>Server Events:</strong>\n\n" . join('<hr />', $this->serverEvents);
		}
	}
}

XOAD_Server::addObserver(new ServerObserver());

if (XOAD_Server::runServer()) {

	exit;
}

?>
<?= XOAD_Utilities::header('../..') ?>

<script type="text/javascript">
<!--
var obj = <?= XOAD_Client::register(new TestEvents()) ?>;

obj.test();

document.write('<pre>');
document.write(obj.fetchOutput());
document.write('</pre>');
-->
</script>