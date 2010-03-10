<?php

class FormExample
{
	public function Import()
	{
		$formData = array();

		$formData['firstName'] = 'Bill';
		$formData['lastName'] = 'Gates';
		$formData['businessName'] = 'Microsoft';
		$formData['age'] = '49';
		$formData['gender'] = 'm';

		$formData['mail'] = 'One Microsoft Way';
		$formData['city'] = 'Redmond';
		$formData['state'] = 'WA';
		$formData['zip'] = '98052-6399';
		$formData['phone1'] = '(425) 882-8080';
		$formData['phone2'] = '(888) PC SAFETY';

		$formData['comments'] = "http://www.microsoft.com\nhttp://support.microsoft.com";
		$formData['browsers'] = array('ff', 'ns');
		$formData['email'] = 'billig@microsoft.com';
		$formData['newsletter'] = 'html';
		$formData['agree'] = true;

		XOAD_HTML::importForm('mainForm', $formData);
	}

	public function Dump($formData)
	{
		return var_export($formData, true);
	}

	public function xoadGetMeta()
	{
		XOAD_Client::mapMethods($this, array('Import', 'Dump'));
	}
}

require_once('../../xoad.php');

XOAD_Server::allowClasses('FormExample');

if (XOAD_Server::runServer()) {

	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	<head>
		<title>XOAD_HTML Form Import/Export</title>
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

			input,
			select,
			textarea
			{
				font: normal 1em tahoma, verdana, arial, serif;
			}

			p
			{
				padding: 1em;
				margin: 0;
			}

		</style>
	</head>
	<body>
		<h1>XOAD_HTML Form Import/Export</h1>
		
		<script type="text/javascript">
		<!--
		var formUtils = <?= XOAD_Client::register(new FormExample()) ?>;

		function dumpForm()
		{
			formUtils.OnDumpError = function(error) {

				alert('Error: \n\n' + error.message);

				return true;
			}

			formUtils.Dump(xoad.html.exportForm('mainForm'), function(dump) {

				alert('Server Response:\n\n' + dump);
			});
		}
		-->
		</script>
		
		<form id="mainForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post"
			onsubmit="dumpForm(); return false;">
			<fieldset style="float: left; clear: right; width: 30%;">
				<legend>Personal Information:&nbsp;</legend>
				<p>
					<label for="first-name">First Name:</label><br />
					<input type="text" name="firstName" id="first-name" />
					<br />
					<label for="last-name">Last Name:</label><br />
					<input type="text" name="lastName" id="last-name" />
					<br />
					<label for="business-name">Name of Business:</label><br />
					<input type="text" name="businessName" id="business-name" />
					<br />
					<label for="age">Age:</label><br />
					<select name="age" id="age">
						<option selected="selected">-</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
						<option value="32">32</option>
						<option value="33">33</option>
						<option value="34">34</option>
						<option value="35">35</option>
						<option value="36">36</option>
						<option value="37">37</option>
						<option value="38">38</option>
						<option value="39">39</option>
						<option value="40">40</option>
						<option value="41">41</option>
						<option value="42">42</option>
						<option value="43">43</option>
						<option value="44">44</option>
						<option value="45">45</option>
						<option value="46">46</option>
						<option value="47">47</option>
						<option value="48">48</option>
						<option value="49">49</option>
						<option value="50">50</option>
						<option value="51">51</option>
						<option value="52">52</option>
						<option value="53">53</option>
						<option value="54">54</option>
						<option value="55">55</option>
						<option value="56">56</option>
						<option value="57">57</option>
						<option value="58">58</option>
						<option value="59">59</option>
						<option value="60">60</option>
						<option value="61">61</option>
						<option value="62">62</option>
						<option value="63">63</option>
						<option value="64">64</option>
						<option value="65">65</option>
					</select>
					<br />
					<label for="gender">Gender:</label><br />
					<select name="gender" id="gender">
						<option selected="selected">-</option>
						<option value="m">Male</option>
						<option value="f">Female</option>
					</select>
				</p>
			</fieldset>
			<div style="float: left; width: 2%"></div>
			<fieldset style="float: left; clear: right; width: 30%;">
				<legend>Contact Information:&nbsp;</legend>
				<p>
					<label for="mail">Mailing Address:</label><br />
					<input type="text" name="mail" id="mail" />
					<br />
					<label for="city">City:</label><br />
					<input type="text" name="city" id="city" />
					<br />
					<label for="state">State:</label><br />
					<input type="text" name="state" id="state" />
					<br />
					<label for="zip">Zip:</label><br />
					<input type="text" name="zip" id="zip" />
					<br />
					<label for="phone1">Phone 1:</label><br />
					<input type="text" name="phone1" id="phone1" />
					<br />
					<label for="phone2">Phone 2:</label><br />
					<input type="text" name="phone2" id="phone2" />
				</p>
			</fieldset>
			<div style="float: left; width: 2%"></div>
			<fieldset style="float: left; clear: right; width: 30%;">
				<legend>Additional Information:&nbsp;</legend>
				<p>
					<label for="comments">Comments:</label><br />
					<textarea name="comments" id="comments" rows="4" cols="30"></textarea>
					<br />
					<label for="browsers">Browser(s):</label><br />
					<select name="browsers[]" id="browsers" multiple="multiple" size="5">
						<option value="ie">Internet Explorer</option>
						<option value="ff">Firefox / Mozilla</option>
						<option value="op">Opera</option>
						<option value="sf">Safari</option>
						<option value="ns">Netscape</option>
					</select>
					<br />
					<label for="email">Email:</label><br />
					<input type="text" name="email" id="email" />
					<br />
					<input type="radio" name="newsletter" id="n-html" value="html" /><label for="n-html">&nbsp;HTML Newsletter</label><br />
					<input type="radio" name="newsletter" id="n-text" value="text" /><label for="n-text">&nbsp;Plain Newsletter</label>
					<br /><br />
					<input type="checkbox" name="agree" id="agree" value="1" /><label for="agree">&nbsp;I Agree</label><br />
				</p>
			</fieldset>
			<div style="clear: both;"></div>
			<input type="button" value="Load from server (import)" onclick="formUtils.Import(xoad.asyncCall); return false;" />
			<input type="submit" value="Submit (export)" />
		</form>
	</body>
</html>