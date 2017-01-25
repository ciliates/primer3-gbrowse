<?php
// Change this to the URL of the GBrowse instance
define("GB_BASE_URL", "http://example.com/gb2/gbrowse/example/");

// Change this to the URL of primer3web_results.cgi
define("PRIMER3_RESULTS_URL", "http://example.com/cgi-bin/primer3/primer3web_results.cgi");

// PHP converts spaces in POST parameters to underscores. Convert these back.
$cleanParams = ["Pick_Primers"];
foreach($cleanParams as $param)
{
	if(isset($_POST[$param]))
	{
		$_POST[str_replace("_", " ", $param)] = $_POST[$param];
		unset($_POST[$param]);
	}
}

// If the input sequence is a region, then pull the sequence from GBrowse and
// use it as the input sequence.
$query = trim($_POST["SEQUENCE_TEMPLATE"]);
if(preg_match("/^([^:]+):([0-9,]+)..([0-9,]+)$/", $query))
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, GB_BASE_URL . "?plugin=FastaDumper;plugin_action=Go;name={$query}&FastaDumper.format=text&FastaDumper.mRNA=1");
	$sequence = curl_exec($ch);
	curl_close($ch);

	// Replace the original query with this result
	$_POST["SEQUENCE_TEMPLATE"] = $sequence;
}

// Perform the actual query
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PRIMER3_RESULTS_URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
curl_exec($ch);
curl_close($ch);





























