<?php

class Calculator
{
	public $result;

	public function Calculator() {
		$this->result = 0;
	}

	public function Add($a) {
		$this->result += $a;
		
		return true;
	}
	
	public function Sub($a) {
		$this->result -= $a;
		
		return true;
	}
	
	public function Mul($a) {
		$this->result *= $a;
		
		return true;
	}
	
	public function Div($a) {
		if ($a == 0)
			return false;
		
		$this->result /= $a;
		
		return true;
	}

	public function Clear() {
		$this->result = 0;
		
		return true;
	}
}

define('XOAD_AUTOHANDLE', true);

require_once('../../xoad.php');

?>
<?= XOAD_Utilities::header('../..'); ?>

<input type="text" id="operationValue" value="1" style="font: normal 0.8em tahoma, verdana, arial, serif; width: 10em;" />
&nbsp;&nbsp;&nbsp;
<span id="operationResult" style="font: normal 0.8em tahoma, verdana, arial, serif;"></span>

<br />

<button onclick="add()" style="font: normal 1em tahoma, verdana, arial, serif; width: 2em;">+</button>
<button onclick="sub()" style="font: normal 1em tahoma, verdana, arial, serif; width: 2em;">-</button>
<button onclick="mul()" style="font: normal 1em tahoma, verdana, arial, serif; width: 2em;">*</button>
<button onclick="div()" style="font: normal 1em tahoma, verdana, arial, serif; width: 2em;">/</button>

<br />

<button onclick="clearResult()" style="font: normal 0.8em tahoma, verdana, arial, serif; width: 10em;">Clear</button>

<script type="text/javascript">
<!--
var calc = <?= XOAD_Client::register(new Calculator()) ?>;

var operationValue = document.getElementById('operationValue');

var operationResult = document.getElementById('operationResult');

function getValue() { return parseInt(operationValue.value); }

function update() { operationResult.innerHTML = 'Result: <strong>' + calc.result + '</strong>'; }

function add() { if (calc.add(getValue())) update(); }
function sub() { if (calc.sub(getValue())) update(); }
function mul() { if (calc.mul(getValue())) update(); }
function div() { if (calc.div(getValue())) update(); }

function clearResult() { if (calc.clear()) update(); }

update();
-->
</script>