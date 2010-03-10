<?php
class Exam
{
	public $questions;
	public $examQuestions = array();

	public function __construct() {
		$this->examQuestions[] = array(
		'What function would you use to tell the browser the content type of the data being output?',
		'imagejpeg()',
		'imageoutput()',
		'flush()',
		'header()',
		'imageload()',
		3);
		
		$this->examQuestions[] = array(
		'What parameters does the func_get_args() function take?',
		'0: It doesn\'t take any parameters',
		'1: name of function to check',
		'2: name of function to check, boolean "count optional arguments"',
		0);
		
		$this->examQuestions[] = array(
		'What does the array_shift() function do?',
		'Add an element to an array',
		'Removes an element from an array',
		'Shifts all elements towards the back of the array',
		'Switches array keys and values',
		'Clears the array',
		1);
		
		$this->examQuestions[] = array(
		'What function would you use to delete a file?',
		'unlink()',
		'delete()',
		'fdelete()',
		'file_delete()',
		0);
		
		$this->examQuestions[] = array(
		'What is the difference between exec() and pcntl_exec()?',
		'Nothing, they are the same',
		'pcntl_exec() forks a new process',
		'pcntl_exec() can only be called from a child process',
		'None of the above',
		3);
		
		$this->examQuestions[] = array(
		'If $string is "Hello, world!", how long would the output of sha1($string) be?',
		'It varies',
		'16 characters',
		'20 characters',
		'24 characters',
		'32 characters',
		'40 characters',
		5);
		
		$this->examQuestions[] = array(
		'If the input of chr() is $a and the output is $b, what function would take input $b and produce output $a?',
		'chr()',
		'rch()',
		'ord()',
		'strrev()',
		'chr(chr())',
		'chrrev()',
		2);
		
		$this->examQuestions[] = array(
		'If $a is "Hello, world!", is this statement true or false: md5($a) === md5($a)',
		'True',
		'False',
		0);
		
		$this->examQuestions[] = array(
		'Which function returns true when magic quotes are turned on?',
		'get_magic_quotes()',
		'magic_quotes_get()',
		'magic_quotes()',
		'get_magic_quotes_gpc()',
		'get_quotes()',
		3);
		
		$this->examQuestions[] = array(
		'The functions get_required_files() and get_included_files() are identical, true or false?',
		'True',
		'False',
		0);
		
		$this->examQuestions[] = array(
		'If $arr was an array of ten string elements with specific keys, what would array_values(ksort($arr)) do?',
		'Create a new array of just the values, then sort by the keys',
		'Create a new array of just the values, then ignore the sort as there are no keys',
		'Sort the array by key, then return a new array with just the values',
		'Trigger a warning',
		'None of the above',
		3);
		
		$this->examQuestions[] = array(
		'What is the return value of array_unique()?',
		'Boolean',
		'Integer',
		'Array',
		'It varies',
		'None of the above',
		2);
	}
	
	public function loadQuestions()
	{
		if ( ! isset($this->questions)) {

			$this->questions = array();

			foreach ($this->examQuestions as $question) {

				$this->questions[] = $question[0];
			}

			return true;
		}

		return false;
	}

	public function cleanAnswers()
	{
		@session_start();

		$_SESSION['examData'] = array();
	}

	public function getAnswers($id)
	{
		if ( ! array_key_exists($id, $this->examQuestions)) {

			return null;
		}

		$answers = array();

		for ($iterator = 1; $iterator < sizeof($this->examQuestions[$id]) - 1; $iterator ++) {

			$answers[] = $this->examQuestions[$id][$iterator];
		}

		@session_start();

		$result = array();

		if (array_key_exists($id, $_SESSION['examData'])) {

			$result['answer'] =& $_SESSION['examData'][$id];

		} else {

			$result['answer'] = -1;
		}

		$result['data'] =& $answers;

		return $result;
	}

	public function submitAnswer($question, $id)
	{
		@session_start();

		if ( ! array_key_exists('examData', $_SESSION)) {

			$_SESSION['examData'] = array();
		}

		if ( ! array_key_exists($question, $_SESSION['examData'])) {

			$_SESSION['examData'][$question] = $id;

			return true;
		}

		return false;
	}

	public function fetchResults()
	{
		@session_start();

		if (array_key_exists('examData', $_SESSION)) {

			if (sizeof($_SESSION['examData']) == sizeof($this->examQuestions)) {

				$result = array();

				$result['data'] =& $this->examQuestions;

				$result['answers'] =& $_SESSION['examData'];

				return $result;
			}
		}

		return null;
	}

	public function xoadGetMeta()
	{
		XOAD_Client::privateMethods($this, array('loadQuestions', 'cleanAnswers'));

		XOAD_Client::mapMethods($this, array('getAnswers', 'submitAnswer', 'fetchResults'));
	}
}

?>