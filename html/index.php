<?php
// Change this to the URL of the GBrowse instance
define("GB_BASE_URL", "http://example.com/gb2/gbrowse/example/");

// Pull the sequence from GBrowse if all necessary parameters are specified.
if(!empty($_GET["ref"]) && !empty($_GET["start"]) && !empty($_GET["end"]))
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, GB_BASE_URL . "?plugin=FastaDumper;plugin_action=Go;name={$_GET["ref"]}:{$_GET["start"]}..{$_GET["end"]}&FastaDumper.format=text&FastaDumper.mRNA=1");
	$sequence = curl_exec($ch);
	curl_close($ch);

	$_GET["sequence"] = $sequence;
}

// Everything beyond this point is originally from primer3web_input.htm except
// for the form action and the textarea named SEQUENCE_TEMPLATE.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
          "http://www.w3.org/TR/html4/loose.dtd">
<html>


<head>
<title>Primer3 Input</title>
</head>

<body>

<form id="primer3web" action="process.php" method="post" enctype="multipart/form-data">

<table border="1" width="100%" bgcolor="#CCCCFF">
<tr>
  <td rowspan="2" valign="middle">
        <a name="top"><font size="+3">&nbsp;&nbsp;Primer3web</font></a>
        &nbsp;version 4.0.0&nbsp;&nbsp;-&nbsp;&nbsp;
        Pick primers from a DNA sequence.
    </td>
    <td align="center">
       <a href="#disclaimer">
       <strong>disclaimer</strong>
       </a>
    </td>
    <td align="center">
       <a href="http://primer3.sourceforge.net/">
       <strong>code</strong></a>
    </td>
</tr>

<tr>
    <td align="center">
         <a href="primer3web_help.htm#cautions">
         <strong>cautions</strong>
         </a>
         </td>
</tr>
</table>

<p>
Select the <a name="PRIMER_TASK_INPUT" href="primer3web_help.htm#PRIMER_TASK">
Task</a> for primer selection
<tt> </tt><select name="PRIMER_TASK">
<option selected="selected">generic</option>
<option>pick_sequencing_primers</option>
<option>pick_primer_list</option>
<option>check_primers</option>
</select>
</p>

<p>
Paste <a name="SEQUENCE_TEMPLATE_INPUT"> source sequence</a>
below (5'-&gt;3', string of ACGTNacgtn --
other letters treated as N -- numbers and blanks ignored).
FASTA format ok.  Please N-out undesirable sequence
(vector, ALUs, LINEs, etc.)  or use a
<a name="PRIMER_MISPRIMING_LIBRARY_INPUT" href="primer3web_help.htm#providedMisprimingLibs">
Mispriming Library (repeat library)</a>
<tt> </tt><select name="PRIMER_MISPRIMING_LIBRARY">
<option selected="selected">NONE</option>
<option>HUMAN</option>
<option>RODENT_AND_SIMPLE</option>
<option>RODENT</option>
<option>DROSOPHILA</option>
</select>
</p>

<p>
<textarea name="SEQUENCE_TEMPLATE" rows="6" cols="95"><?=$_GET["sequence"]?></textarea>
</p>

<table border="1">
<!-- tbody -->
<!-- tr bgcolor="#ccccff" -->
<tr>
 <td valign="top">
      <input name="MUST_XLATE_PRIMER_PICK_LEFT_PRIMER" value="1" checked="checked" type="checkbox">
      Pick&nbsp;left&nbsp;primer, or&nbsp;use&nbsp;left&nbsp;primer&nbsp;below
 </td><td valign="top">
      <input name="MUST_XLATE_PRIMER_PICK_INTERNAL_OLIGO" value="1" type="checkbox">
      Pick&nbsp;hybridization&nbsp;probe&nbsp;(internal oligo),&nbsp;or&nbsp;use&nbsp;oligo&nbsp;below
 </td><td valign="top">
      <input name="MUST_XLATE_PRIMER_PICK_RIGHT_PRIMER" value="1" checked="checked" type="checkbox">
      Pick&nbsp;right&nbsp;primer,&nbsp;or use&nbsp;right&nbsp;primer&nbsp;below
      (5'&nbsp;to&nbsp;3'&nbsp;on&nbsp;opposite&nbsp;strand)
 </td>
</tr>
<tr>
  <td> <input size="36" name="SEQUENCE_PRIMER" value="" type="text">
  </td><td> <input size="36" name="SEQUENCE_INTERNAL_OLIGO" value="" type="text">
  </td><td> <input size="36" name="SEQUENCE_PRIMER_REVCOMP" value="" type="text">
  </td>
</tr>
<!-- /tbody -->
</table>

<p>
        <input name="Pick Primers" value="Pick Primers" type="submit">
	<input name="Download Settings" value="Download Settings" type="submit">
        <input name="Reset Form" value="Reset Form" type="submit">
</p>

<table border="0" width="100%">

<!-- tbody -->
<tr> 
  <td><a name="SEQUENCE_ID_INPUT" href="primer3web_help.htm#SEQUENCE_ID">Sequence Id</a>
  </td><td><input name="SEQUENCE_ID" value="" type="text">
  </td><td> A string to identify your output.

</td></tr><tr> 
 <td><a name="SEQUENCE_TARGET_INPUT" href="primer3web_help.htm#SEQUENCE_TARGET">Targets</a>
 </td><td><input name="SEQUENCE_TARGET" value="" type="text">
 </td><td>E.g. 50,2 requires primers to surround the 2 bases at positions 50 and 51.
     Or mark the <a href="#SEQUENCE_TEMPLATE_INPUT">source sequence</a>
     with [ and ]: e.g. ...ATCT[CCCC]TCAT.. means
     that primers must flank the central CCCC.

</td></tr><tr> 
 <td><a name="SEQUENCE_OVERLAP_JUNCTION_LIST_INPUT" href="primer3web_help.htm#SEQUENCE_OVERLAP_JUNCTION_LIST">Overlap Junction List</a>
 </td><td><input name="SEQUENCE_OVERLAP_JUNCTION_LIST" value="" type="text">
 </td><td>E.g. 27 requires one primer to overlap the junction between
     positions 27 and 28.
     Or mark the <a href="#SEQUENCE_TEMPLATE_INPUT">source sequence</a>
     with -: e.g. ...ATCTAC-TGTCAT.. means
     that primers must overlap the junction between the C and T.

</td></tr><tr> 
 <td><a name="SEQUENCE_EXCLUDED_REGION_INPUT" href="primer3web_help.htm#SEQUENCE_EXCLUDED_REGION">Excluded Regions</a>
 </td><td><input name="SEQUENCE_EXCLUDED_REGION" value="" type="text">
 </td><td>E.g. 401,7 68,3 forbids selection of primers in the 7 bases starting at 401 and the 3 bases at 68.
     Or mark the <a href="#SEQUENCE_TEMPLATE_INPUT">source sequence</a>
     with &lt; and &gt;: e.g. ...ATCT&lt;CCCC&gt;TCAT.. forbids
     primers in the central CCCC.

</td></tr><tr> 
 <td><a name="SEQUENCE_PRIMER_PAIR_OK_REGION_LIST_INPUT" href="primer3web_help.htm#SEQUENCE_PRIMER_PAIR_OK_REGION_LIST">Pair OK Region List</a>
 </td><td><input name="SEQUENCE_PRIMER_PAIR_OK_REGION_LIST" value="" type="text">
 </td><td>See manual for help.
</td></tr>

<tr><td><a name="SEQUENCE_INCLUDED_REGION_INPUT" href="primer3web_help.htm#SEQUENCE_INCLUDED_REGION">Included Region</a>
</td><td><input name="SEQUENCE_INCLUDED_REGION" value="" type="text">
</td><td> E.g. 20,400: only pick primers in the 400 base region starting at position 20.
    Or use { and } in the 
    <a href="#SEQUENCE_TEMPLATE_INPUT">source sequence</a>
    to mark the beginning and end of the included region: e.g.
    in ATC{TTC...TCT}AT the included region is TTC...TCT.
</td></tr>

<tr><td><a name="SEQUENCE_START_CODON_POSITION_INPUT" href="primer3web_help.htm#SEQUENCE_START_CODON_POSITION">Start Codon Position</a>
</td><td><input name="SEQUENCE_START_CODON_POSITION" value="" type="text">
</td></tr>

<tr><td><a name="internal_oligo_generic_INPUT" href="primer3web_help.htm#SEQUENCE_INTERNAL_EXCLUDED_REGION">Internal Oligo Excluded Region</a>
</td><td><input name="SEQUENCE_INTERNAL_EXCLUDED_REGION" value="" type="text">
</td></tr>
</table>

<table>
<tr><td align=right>
<a name="SEQUENCE_FORCE_LEFT_START_INPUT" href="primer3web_help.htm#SEQUENCE_FORCE_LEFT_START">
Force Left Primer Start</a></td><td>
<input size="4" name="SEQUENCE_FORCE_LEFT_START" value="-1000000" type="text">
</td><td align=right>
<a name="SEQUENCE_FORCE_RIGHT_START_INPUT" href="primer3web_help.htm#SEQUENCE_FORCE_RIGHT_START">
Force Right Primer Start</a></td><td>
<input size="4" name="SEQUENCE_FORCE_RIGHT_START" value="-1000000" type="text">
</td></tr>

<tr><td align=right>
<a name="SEQUENCE_FORCE_LEFT_END_INPUT" href="primer3web_help.htm#SEQUENCE_FORCE_LEFT_END">
Force Left Primer End</a></td><td>
<input size="4" name="SEQUENCE_FORCE_LEFT_END" value="-1000000" type="text">
</td><td align=right>
<a name="SEQUENCE_FORCE_RIGHT_END_INPUT" href="primer3web_help.htm#SEQUENCE_FORCE_RIGHT_END">
Force Right Primer End</a></td><td>
<input size="4" name="SEQUENCE_FORCE_RIGHT_END" value="-1000000" type="text">
</td>
</tr>

</table>

<p><a name="SEQUENCE_QUALITY_INPUT" href="primer3web_help.htm#SEQUENCE_QUALITY">Sequence Quality </a></p>
<textarea rows="2" cols="95" name="SEQUENCE_QUALITY"></textarea>
<table border="0">	    
<!-- tbody -->
<tr>
 <td><a name="PRIMER_MIN_QUALITY_INPUT" href="primer3web_help.htm#PRIMER_MIN_QUALITY">Min Sequence Quality</a>
 </td><td><input size="4" name="PRIMER_MIN_QUALITY" value="0" type="text">
 </td><td><a name="PRIMER_MIN_END_QUALITY_INPUT" href="primer3web_help.htm#PRIMER_MIN_END_QUALITY">Min End Sequence Quality</a>
 </td><td><input size="4" name="PRIMER_MIN_END_QUALITY" value="0" type="text">
 </td><td><a name="PRIMER_QUALITY_RANGE_MIN_INPUT" href="primer3web_help.htm#PRIMER_QUALITY_RANGE_MIN">Sequence Quality Range Min</a>
 </td><td><input size="4" name="PRIMER_QUALITY_RANGE_MIN" value="0" type="text">
 </td><td><a name="PRIMER_QUALITY_RANGE_MAX_INPUT" href="primer3web_help.htm#PRIMER_QUALITY_RANGE_MAX">Sequence Quality Range Max</a>
 </td><td><input size="4" name="PRIMER_QUALITY_RANGE_MAX" value="100" type="text">
       </td></tr><!-- /tbody -->
</table>

<p>
     <input name="Pick Primers" value="Pick Primers" type="submit">
     <input name="Download Settings" value="Download Settings" type="submit">
     <input name="Reset Form" value="Reset Form" type="submit">
</p>
        

<h3> General Primer Picking Conditions </h3>

<p>Upload the settings from a file
<input type="file" name="Upload" onchange="document.forms['primer3web'].submit();">
</p>

<table border="0">
<!-- tbody -->
<tr>
 <td><a name="PRIMER_OPT_SIZE_INPUT" href="primer3web_help.htm#PRIMER_MIN_SIZE">Primer Size</a>
 </td><td>Min <input size="4" name="PRIMER_MIN_SIZE" value="18" type="text">
 </td><td>Opt <input size="4" name="PRIMER_OPT_SIZE" value="20" type="text">
 </td><td>Max <input size="4" name="PRIMER_MAX_SIZE" value="23" type="text">

</td></tr><tr>
 <td><a name="PRIMER_OPT_TM_INPUT" href="primer3web_help.htm#PRIMER_MIN_TM">Primer Tm</a>
 </td><td>Min <input size="4" name="PRIMER_MIN_TM" value="57.0" type="text">
 </td><td>Opt <input size="4" name="PRIMER_OPT_TM" value="59.0" type="text">
 </td><td>Max <input size="4" name="PRIMER_MAX_TM" value="62.0" type="text">
 </td><td><a name="PRIMER_PAIR_MAX_DIFF_TM_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_DIFF_TM">Max Tm Difference</a>
     <input size="4" name="PRIMER_PAIR_MAX_DIFF_TM" value="5.0" type="text">
 </td><td><a name="PRIMER_TM_FORMULA" href="primer3web_help.htm#PRIMER_TM_FORMULA">Table of thermodynamic parameters</a> 
 <select name="PRIMER_TM_FORMULA">
     <option value="0">Breslauer et al. 1986</option>
     <option selected="selected" value="1">SantaLucia 1998</option>
     </select>

</td></tr><tr>
  <td><a name="PRIMER_PRODUCT_TM_INPUT" href="primer3web_help.htm#PRIMER_PRODUCT_MIN_TM">Product Tm</a>
  </td><td>Min <input size="4" name="PRIMER_PRODUCT_MIN_TM" value="-1000000.0" type="text">
  </td><td>Opt <input size="4" name="PRIMER_PRODUCT_OPT_TM" value="0.0" type="text">
  </td><td>Max <input size="4" name="PRIMER_PRODUCT_MAX_TM" value="1000000.0" type="text">
  </td></tr>
<tr>
 <td><a name="PRIMER_GC_PERCENT_INPUT" href="primer3web_help.htm#PRIMER_MIN_GC">Primer GC%</a>
 </td><td>Min <input size="4" name="PRIMER_MIN_GC" value="30.0" type="text">
 </td><td>Opt <input size="4" name="PRIMER_OPT_GC_PERCENT" value="50.0" type="text">
 </td><td>Max <input size="4" name="PRIMER_MAX_GC" value="70.0" type="text">

</td>
</tr>
</table>

<p></p>

<table border="0">
<tr>
  <td>
    <a name="PRIMER_PRODUCT_SIZE_RANGE_INPUT" href="primer3web_help.htm#PRIMER_PRODUCT_SIZE_RANGE">Product Size Ranges</a>
    <input size="80" name="PRIMER_PRODUCT_SIZE_RANGE" value="150-250 100-300 301-400 401-500 501-600 601-700 701-850 851-1000" type="text">
</td></tr>

<!--
<tr>
<td><a href="cgi-bin/primer3/primer3_www_slow.cgi">
Click here to specify the min, opt, and max product sizes only if you
absolutely must. Using them is too slow (and too computationally
intensive for our server).</a>
</td></tr>
-->

</table>

<table border="0">
 <tr>
 <td align=right>
<a name="PRIMER_NUM_RETURN_INPUT" href="primer3web_help.htm#PRIMER_NUM_RETURN">Number To Return</a>
 </td><td><input size="4" name="PRIMER_NUM_RETURN" value="5" type="text">
</td><td align=right>
<a name="PRIMER_MAX_END_STABILITY_INPUT" href="primer3web_help.htm#PRIMER_MAX_END_STABILITY">Max 3' Stability</a>
 </td><td><input size="4" name="PRIMER_MAX_END_STABILITY" value="9.0" type="text">

</td></tr><tr><td align=right>
 <a name="PRIMER_MAX_LIBRARY_MISPRIMING_INPUT" href="primer3web_help.htm#PRIMER_MAX_LIBRARY_MISPRIMING">Max Library Mispriming</a>
 </td><td><input size="4" name="PRIMER_MAX_LIBRARY_MISPRIMING" value="12.00" type="text"></td>

<td align=right>
<a name="PRIMER_PAIR_MAX_LIBRARY_MISPRIMING_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_LIBRARY_MISPRIMING">
Pair Max Library Mispriming</a></td><td>
<input size="4" name="PRIMER_PAIR_MAX_LIBRARY_MISPRIMING" value="20.00" type="text"></td></tr>

</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
<tr><td>Thermodynamic Secondary Structure Alignments</td></tr>
<tr><td colspan="2"><input name="MUST_XLATE_PRIMER_THERMODYNAMIC_OLIGO_ALIGNMENT" value="1" checked="checked" type="checkbox">
    <a name="PRIMER_THERMODYNAMIC_OLIGO_ALIGNMENT_INPUT" href="primer3web_help.htm#PRIMER_THERMODYNAMIC_OLIGO_ALIGNMENT">Use Thermodynamic Oligo Alignment</a>
</td>
<td colspan="1"></td>
<td colspan="2"><input name="MUST_XLATE_PRIMER_THERMODYNAMIC_TEMPLATE_ALIGNMENT" value="1" type="checkbox">
    <a name="PRIMER_THERMODYNAMIC_TEMPLATE_ALIGNMENT_INPUT" href="primer3web_help.htm#PRIMER_THERMODYNAMIC_TEMPLATE_ALIGNMENT">Use Thermodynamic Template Alignment</a>
</td>
<td colspan="1"></td></tr>

<tr><td colspan="2"><a name="PRIMER_MAX_TEMPLATE_MISPRIMING_TH_INPUT" href="primer3web_help.htm#PRIMER_MAX_TEMPLATE_MISPRIMING_TH">
TH: Max Template Mispriming</a></td>
<td colspan="1"><input size="4" name="PRIMER_MAX_TEMPLATE_MISPRIMING_TH" value="40.00" type="text"></td>
<td colspan="2"><a name="PRIMER_PAIR_MAX_TEMPLATE_MISPRIMING_TH_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_TEMPLATE_MISPRIMING_TH">
TH: Pair Max Template Mispriming</a></td>
<td colspan="1"><input size="4" name="PRIMER_PAIR_MAX_TEMPLATE_MISPRIMING_TH" value="70.00" type="text">
</td></tr>

<tr>
 <td colspan="2"><a name="PRIMER_MAX_SELF_ANY_TH_INPUT" href="primer3web_help.htm#PRIMER_MAX_SELF_ANY_TH">TH: Max Self Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_SELF_ANY_TH" value="45.0" type="text">
 </td><td colspan="2"><a name="PRIMER_MAX_SELF_END_TH_INPUT" href="primer3web_help.htm#PRIMER_MAX_SELF_END_TH">TH: Max 3' Self Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_SELF_END_TH" value="35.0" type="text">
</td><td colspan="2"></td></tr>

 <tr><td colspan="2"><a name="PRIMER_PAIR_MAX_COMPL_ANY_TH_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_COMPL_ANY_TH">TH: Max Pair Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_PAIR_MAX_COMPL_ANY_TH" value="45.0" type="text">
 </td><td colspan="2"><a name="PRIMER_PAIR_MAX_COMPL_END_TH_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_COMPL_END_TH">TH: Max 3' Pair Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_PAIR_MAX_COMPL_END_TH" value="35.0" type="text">

</td><td colspan="2"></td></tr><tr>
 <td colspan="2"><a name="PRIMER_MAX_HAIRPIN_TH_INPUT" href="primer3web_help.htm#PRIMER_MAX_HAIRPIN_TH">TH: Max Primer Hairpin</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_HAIRPIN_TH" value="24.0" type="text">
 </td><td colspan="2"></td></tr>
</table>
</td></tr>
</table>

<p></p>

<table border="1">
<tr><td>

<table border="0">
<tr><td>Old Secondary Structure Alignments</td></tr>
<!-- Template mispriming (two input fields) -->
<tr><td colspan="2">
<a name="PRIMER_MAX_TEMPLATE_MISPRIMING_INPUT" href="primer3web_help.htm#PRIMER_MAX_TEMPLATE_MISPRIMING">Max Template Mispriming</a></td>
<td colspan="1"><input size="4" name="PRIMER_MAX_TEMPLATE_MISPRIMING" value="12.00" type="text"></td>
<td colspan="2"><a name="PRIMER_PAIR_MAX_TEMPLATE_MISPRIMING_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_TEMPLATE_MISPRIMING">Pair Max Template Mispriming</a></td>
<td colspan="1"><input size="4" name="PRIMER_PAIR_MAX_TEMPLATE_MISPRIMING" value="24.00" type="text"><td></tr>

<tr><td colspan="2"><a name="PRIMER_MAX_SELF_ANY_INPUT" href="primer3web_help.htm#PRIMER_MAX_SELF_ANY">Max Self Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_SELF_ANY" value="8.00" type="text">
 </td><td colspan="2"><a name="PRIMER_MAX_SELF_END_INPUT" href="primer3web_help.htm#PRIMER_MAX_SELF_END">Max 3' Self Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_SELF_END" value="3.00" type="text">
 </td><td colspan="2"></td></tr>

<tr><td colspan="2"><a name="PRIMER_PAIR_MAX_COMPL_ANY_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_COMPL_ANY">Max Pair Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_PAIR_MAX_COMPL_ANY" value="8.00" type="text">
 </td><td colspan="2"><a name="PRIMER_PAIR_MAX_COMPL_END_INPUT" href="primer3web_help.htm#PRIMER_PAIR_MAX_COMPL_END">Max 3' Pair Complementarity</a>
 </td><td colspan="1"><input size="4" name="PRIMER_PAIR_MAX_COMPL_END" value="3.00" type="text">
</td><td colspan="2"></td></tr>

</table>
</td></tr>
</table>

<p></p>

<table border="0"> 
<tr>
 <td colspan="2"><a name="PRIMER_MAX_NS_ACCEPTED_INPUT" href="primer3web_help.htm#PRIMER_MAX_NS_ACCEPTED">Max #N's accepted</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_NS_ACCEPTED" value="0" type="text">

 </td><td colspan="2"><a name="PRIMER_MAX_POLY_X_INPUT" href="primer3web_help.htm#PRIMER_MAX_POLY_X">Max Poly-X</a>
 </td><td colspan="1"><input size="4" name="PRIMER_MAX_POLY_X" value="4" type="text">
</td><td colspan="2"></td></tr>

  <tr>
  <td colspan="2">
  <a name="PRIMER_INSIDE_PENALTY_INPUT" href="primer3web_help.htm#PRIMER_INSIDE_PENALTY">Inside Target Penalty</a>
  </td>
  <td colspan="1">
  <input size="4" name="PRIMER_INSIDE_PENALTY" value="-1.0" type="text">
  </td>
  <td colspan="2">
  <a name="PRIMER_OUTSIDE_PENALTY_INPUT" href="primer3web_help.htm#PRIMER_OUTSIDE_PENALTY">Outside Target Penalty</a>
  </td>
  <td colspan="1">
  <input size="4" name="PRIMER_OUTSIDE_PENALTY" value="0" type="text">
  </td>

   <td colspan="2">
   <a href="primer3web_help.htm#PRIMER_INSIDE_PENALTY">
   Note: you can set Inside Target Penalty to allow primers inside a target.
   </a>

</td></tr><tr>
 <td colspan="2"><a name="PRIMER_FIRST_BASE_INDEX_INPUT" href="primer3web_help.htm#PRIMER_FIRST_BASE_INDEX">First Base Index</a>
 </td><td colspan="1"><input size="4" name="PRIMER_FIRST_BASE_INDEX" value="1" type="text">
 </td><td colspan="2"><a name="PRIMER_GC_CLAMP_INPUT" href="primer3web_help.htm#PRIMER_GC_CLAMP">CG Clamp</a>
 </td><td colspan="1"><input size="4" name="PRIMER_GC_CLAMP" value="0" type="text">

</td></tr><tr>
<td colspan="2"><a name="PRIMER_MAX_END_GC_INPUT" href="primer3web_help.htm#PRIMER_MAX_END_GC">Max GC in primer 3' end</a>
</td><td colspan="1"><input size="4" name="PRIMER_MAX_END_GC" value="5" type="text">

</td></tr><tr>
<td colspan="2"><a name="PRIMER_MIN_LEFT_THREE_PRIME_DISTANCE_INPUT" href="primer3web_help.htm#PRIMER_MIN_LEFT_THREE_PRIME_DISTANCE">3' End Distance Between Left Primers</a>
</td><td colspan="1"><input size="4" name="PRIMER_MIN_LEFT_THREE_PRIME_DISTANCE" value="3" type="text">
</td><td colspan="2"><a name="PRIMER_MIN_RIGHT_THREE_PRIME_DISTANCE_INPUT" href="primer3web_help.htm#PRIMER_MIN_RIGHT_THREE_PRIME_DISTANCE">3' End Distance Between Right Primers</a>
</td><td colspan="1"><input size="4" name="PRIMER_MIN_RIGHT_THREE_PRIME_DISTANCE" value="3" type="text">
</td><td colspan="2">

</td></tr><tr>
<td colspan="2"><a name="PRIMER_MIN_5_PRIME_OVERLAP_OF_JUNCTION_INPUT" href="primer3web_help.htm#PRIMER_MIN_5_PRIME_OVERLAP_OF_JUNCTION">5 Prime Junction Overlap</a>
</td><td colspan="1"><input size="4" name="PRIMER_MIN_5_PRIME_OVERLAP_OF_JUNCTION" value="7" type="text">
</td><td colspan="2"><a name="PRIMER_MIN_3_PRIME_OVERLAP_OF_JUNCTION_INPUT" href="primer3web_help.htm#PRIMER_MIN_3_PRIME_OVERLAP_OF_JUNCTION">3 Prime Junction Overlap</a>
</td><td colspan="1"><input size="4" name="PRIMER_MIN_3_PRIME_OVERLAP_OF_JUNCTION" value="4" type="text">
</td><td colspan="2">(Distance of the primer ends to one <a href="#SEQUENCE_OVERLAP_JUNCTION_LIST_INPUT">overlap position</a>.)

</td></tr><tr>
  <td colspan="2"><a name="PRIMER_SALT_MONOVALENT_INPUT" href="primer3web_help.htm#PRIMER_SALT_MONOVALENT">Concentration of Monovalent Cations</a>
  </td><td colspan="1"><input size="4" name="PRIMER_SALT_MONOVALENT" value="50.0" type="text">
  </td><td colspan="2"><a name="PRIMER_SALT_CORRECTIONS" href="primer3web_help.htm#PRIMER_SALT_CORRECTIONS">Salt Correction Formula</a></td>
  <td colspan="3"><select name="PRIMER_SALT_CORRECTIONS">
  <option value="0"> Schildkraut and Lifson 1965</option>
  <option selected="selected" value="1">SantaLucia 1998</option>
  <option value="2">Owczarzy et. 2004</option>
  </select>

</td></tr><tr>
<td colspan="2"><a name="PRIMER_SALT_DIVALENT_INPUT" href="primer3web_help.htm#PRIMER_SALT_DIVALENT">Concentration of Divalent Cations</a>
</td><td colspan="1"><input size="4" name="PRIMER_SALT_DIVALENT" value="1.5" type="text">
</td><td colspan="2"><a name="PRIMER_DNTP_CONC_INPUT" href="primer3web_help.htm#PRIMER_DNTP_CONC">Concentration of dNTPs</a>
</td><td colspan="1"><input size="4" name="PRIMER_DNTP_CONC" value="0.6" type="text">
</td><td colspan="2"></td></tr>

<tr><td colspan="2">
  <a name="PRIMER_DNA_CONC_INPUT" href="primer3web_help.htm#PRIMER_DNA_CONC">Annealing Oligo Concentration</a>
  </td><td colspan="1">
  <input size="4" name="PRIMER_DNA_CONC" value="50.0" type="text">
  </td><td colspan="5"> (Not the concentration of oligos in the reaction mix but of those annealing to template.)

</td></tr><tr>
 <td colspan="2"><a name="PRIMER_SEQUENCING_SPACING_INPUT" href="primer3web_help.htm#PRIMER_SEQUENCING_SPACING">Sequencing Spacing</a>
 </td><td colspan="1"><input size="4" name="PRIMER_SEQUENCING_SPACING" value="500" type="text">
 </td><td colspan="2"><a name="PRIMER_SEQUENCING_INTERVAL_INPUT" href="primer3web_help.htm#PRIMER_SEQUENCING_INTERVAL">Sequencing Interval</a>
 </td><td colspan="1"><input size="4" name="PRIMER_SEQUENCING_INTERVAL" value="250" type="text">

</td></tr><tr>
 <td colspan="2"><a name="PRIMER_SEQUENCING_LEAD_INPUT" href="primer3web_help.htm#PRIMER_SEQUENCING_LEAD">Sequencing Lead</a>
 </td><td colspan="1"><input size="4" name="PRIMER_SEQUENCING_LEAD" value="50" type="text">
 </td><td colspan="2"><a name="PRIMER_SEQUENCING_ACCURACY_INPUT" href="primer3web_help.htm#PRIMER_SEQUENCING_ACCURACY">Sequencing Accuracy</a>
 </td><td colspan="1"><input size="4" name="PRIMER_SEQUENCING_ACCURACY" value="20" type="text">

</td></tr>

</table>

<table border="0">
<tr>
 <td><input name="MUST_XLATE_PRIMER_LIBERAL_BASE" value="1" checked="checked" type="checkbox">
  <a name="PRIMER_LIBERAL_BASE_INPUT" href="primer3web_help.htm#PRIMER_LIBERAL_BASE">Liberal Base</a></td>
 <td><input name="MUST_XLATE_PRINT_INPUT" value="1" type="checkbox">
  <a name="SHOW_DEBUGGING_INPUT">Show Debuging Info</a></td>
 <td><input name="MUST_XLATE_PRIMER_LIB_AMBIGUITY_CODES_CONSENSUS" value="1" type="checkbox">
  <a name="PRIMER_LIB_AMBIGUITY_CODES_CONSENSUS" href="primer3web_help.htm#PRIMER_LIB_AMBIGUITY_CODES_CONSENSUS">Treat ambiguity codes in libraries as consensus</a></td>
</tr>
<tr>
 <td><input name="MUST_XLATE_PRIMER_LOWERCASE_MASKING" value="1" type="checkbox">
  <a name="PRIMER_LOWERCASE_MASKING" href="primer3web_help.htm#PRIMER_LOWERCASE_MASKING">Lowercase masking</a></td>
 <td><input name="MUST_XLATE_PRIMER_PICK_ANYWAY" value="1" checked="checked" type="checkbox">
  <a name="PRIMER_PICK_ANYWAY" href="primer3web_help.htm#PRIMER_PICK_ANYWAY">Pick anyway</a></td>
 <td><input name="MUST_XLATE_PRIMER_EXPLAIN_FLAG" value="1" checked="checked" type="checkbox">
  <a name="PRIMER_EXPLAIN_FLAG" href="primer3web_help.htm#PRIMER_EXPLAIN_FLAG">Print Statistics</a></td>
</tr>
</table>


        <p>
        <input name="Pick Primers" value="Pick Primers" type="submit">
	<input name="Download Settings" value="Download Settings" type="submit">
        <input name="Reset Form" value="Reset Form" type="submit">
        </p>
       
<h3>Objective Function Penalty Weights for Primers</h3>

<table border="0">

  <!-- tbody -->
<tr>
    <td><a name="WT_SIZE_INPUT" href="primer3web_help.htm#PRIMER_WT_SIZE_LT">Size</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_WT_SIZE_LT" value="1.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_WT_SIZE_GT" value="1.0" type="text">
    
  </td></tr><tr>
    <td><a name="WT_TM_INPUT" href="primer3web_help.htm#PRIMER_WT_TM_LT">Tm</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_WT_TM_LT" value="1.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_WT_TM_GT" value="1.0" type="text">
    
  </td></tr><tr>
    <td><a name="WT_GC_PERCENT_INPUT" href="primer3web_help.htm#PRIMER_WT_GC_PERCENT_LT">GC%</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_WT_GC_PERCENT_LT" value="0.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_WT_GC_PERCENT_GT" value="0.0" type="text">
    </td></tr><!-- /tbody -->
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
        <tr><td><a name="PRIMER_WT_SELF_ANY_TH_INPUT" href="primer3web_help.htm#PRIMER_WT_SELF_ANY_TH">TH: Self Complementarity</a>
	</td><td><input size="4" name="PRIMER_WT_SELF_ANY_TH" value="0.0" type="text">
	</td></tr>

        <tr><td><a name="PRIMER_WT_SELF_END_TH_INPUT" href="primer3web_help.htm#PRIMER_WT_SELF_END_TH">TH: 3' End Self Complementarity</a>
	</td><td><input size="4" name="PRIMER_WT_SELF_END_TH" value="0.0" type="text">	
	</td></tr>

	<tr><td><a name="PRIMER_WT_HAIRPIN_TH_INPUT" href="primer3web_help.htm#PRIMER_WT_HAIRPIN_TH">TH: Hairpin</a>
	</td><td><input size="4" name="PRIMER_WT_HAIRPIN_TH" value="0.0" type="text">		
	</td></tr>

        <tr><td><a href="primer3web_help.htm#PRIMER_WT_TEMPLATE_MISPRIMING_TH">TH: Template Mispriming</a>
	</td><td><input size="4" name="PRIMER_WT_TEMPLATE_MISPRIMING_TH" value="0.0" type="text">
	</td></tr>
</table>
</td></tr>
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
        <tr><td><a name="PRIMER_WT_SELF_ANY_INPUT" href="primer3web_help.htm#PRIMER_WT_SELF_ANY">Self Complementarity</a>
	</td><td><input size="4" name="PRIMER_WT_SELF_ANY" value="0.0" type="text">
	</td></tr>

	<tr><td><a name="PRIMER_WT_SELF_END_INPUT" href="primer3web_help.htm#PRIMER_WT_SELF_END">3' End Self Complementarity</a>
	</td><td><input size="4" name="PRIMER_WT_SELF_END" value="0.0" type="text">
	</td></tr>

        <tr><td><a href="primer3web_help.htm#PRIMER_WT_TEMPLATE_MISPRIMING">Template Mispriming</a>
        </td><td><input size="4" name="PRIMER_WT_TEMPLATE_MISPRIMING" value="0.0" type="text">
	</td></tr>

</table>
</td></tr>
</table>

<p></p>

<table border="0">
	<!-- tbody -->
<tr>
	<td><a name="WT_NUM_NS_INPUT" href="primer3web_help.htm#PRIMER_WT_NUM_NS">#N's</a>
	</td><td><input size="4" name="PRIMER_WT_NUM_NS" value="0.0" type="text">
		
	</td></tr><tr>
	<td><a name="WT_REP_SIM_INPUT" href="primer3web_help.htm#PRIMER_WT_LIBRARY_MISPRIMING">Library Mispriming</a>
	</td><td><input size="4" name="PRIMER_WT_LIBRARY_MISPRIMING" value="0.0" type="text">
		
        </tr><tr>
	<td><a name="WT_SEQ_QUAL_INPUT" href="primer3web_help.htm#PRIMER_WT_SEQ_QUAL">Sequence Quality</a>
	</td><td><input size="4" name="PRIMER_WT_SEQ_QUAL" value="0.0" type="text">
		
	</td></tr><tr>
	<td><a name="WT_END_QUAL_INPUT" href="primer3web_help.htm#PRIMER_WT_END_QUAL">End Sequence Quality</a>
	</td><td><input size="4" name="PRIMER_WT_END_QUAL" value="0.0" type="text">
		
	</td></tr><tr>
	<td><a name="WT_POS_PENALTY_INPUT" href="primer3web_help.htm#PRIMER_WT_POS_PENALTY">Position Penalty</a>
	</td><td><input size="4" name="PRIMER_WT_POS_PENALTY" value="0.0" type="text">

	</td></tr><tr>
	<td><a name="WT_END_STABILITY_INPUT" href="primer3web_help.htm#PRIMER_WT_END_STABILITY">End Stability</a>
	</td><td><input size="4" name="PRIMER_WT_END_STABILITY" value="0.0" type="text"></td></tr>

<!-- /tbody -->
</table>

<h3>Objective Function Penalty Weights for Primer <em>Pairs</em></h3>

<table border="0">
  <!-- tbody -->
<tr>
    <td><a name="PAIR_WT_PRODUCT_SIZE_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_PRODUCT_SIZE_LT">Product Size</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_PAIR_WT_PRODUCT_SIZE_LT" value="0.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_PAIR_WT_PRODUCT_SIZE_GT" value="0.0" type="text">
    
  </td></tr><tr>
    <td><a name="PAIR_WT_PRODUCT_TM_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_PRODUCT_TM_LT">Product Tm</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_PAIR_WT_PRODUCT_TM_LT" value="0.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_PAIR_WT_PRODUCT_TM_GT" value="0.0" type="text">
    </td></tr><!-- /tbody -->
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
        <tr><td><a name="PRIMER_PAIR_WT_COMPL_ANY_TH_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_COMPL_ANY_TH">TH: Any Complementarity</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_COMPL_ANY_TH" value="0.0" type="text">
	</td></tr>

	<tr><td><a name="PRIMER_PAIR_WT_COMPL_END_TH_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_COMPL_END_TH">TH: 3' End Complementarity</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_COMPL_END_TH" value="0.0" type="text">
	</td></tr>

	<tr><td><a href="primer3web_help.htm#PRIMER_PAIR_WT_TEMPLATE_MISPRIMING_TH">TH: Template Mispriming Weight</a>
        </td><td><input size="4" name="PRIMER_PAIR_WT_TEMPLATE_MISPRIMING_TH" value="0.0" type="text">
	</td></tr>
</table>
</td></tr>
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
        <tr><td><a name="PRIMER_PAIR_WT_COMPL_ANY_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_COMPL_ANY">Any Complementarity</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_COMPL_ANY" value="0.0" type="text">	
	</td></tr>

	<tr><td><a name="PRIMER_PAIR_WT_COMPL_END_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_COMPL_END">3' End Complementarity</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_COMPL_END" value="0.0" type="text">
	</td></tr>

	<tr><td><a href="primer3web_help.htm#PRIMER_PAIR_WT_TEMPLATE_MISPRIMING">Template Mispriming Weight</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_TEMPLATE_MISPRIMING" value="0.0" type="text">
	</td></tr>
</table>
</td></tr>
</table>

<p></p>

<table border="0">

	<!-- tbody -->
<tr>
	<td><a name="PAIR_WT_DIFF_TM_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_DIFF_TM">Tm Difference</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_DIFF_TM" value="0.0" type="text">
		
	</td></tr><tr>
		
	<td><a name="PAIR_WT_LIBRARY_MISPRIMING_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_LIBRARY_MISPRIMING">Library Mispriming</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_LIBRARY_MISPRIMING" value="0.0" type="text">
		
	</td></tr><tr>
	<td><a name="PAIR_WT_PR_PENALTY_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_PR_PENALTY">Primer Penalty Weight</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_PR_PENALTY" value="1.0" type="text">
		
	</td></tr><tr>
	<td><a name="PAIR_WT_IO_PENALTY_INPUT" href="primer3web_help.htm#PRIMER_PAIR_WT_IO_PENALTY">Internal Oligo Penalty Weight</a>
	</td><td><input size="4" name="PRIMER_PAIR_WT_IO_PENALTY" value="0.0" type="text">

	</td></tr>


</table>

<h3>Internal Oligo (Hyb Oligo) General Conditions</h3>

<table border="0">
<!-- tbody -->
<tr>
 <td><a name="PRIMER_INTERNAL_OLIGO_SIZE_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_MIN_SIZE">Internal Oligo Size</a>
 </td><td>Min <input size="4" name="PRIMER_INTERNAL_MIN_SIZE" value="18" type="text">
 </td><td>Opt <input size="4" name="PRIMER_INTERNAL_OPT_SIZE" value="20" type="text">
 </td><td>Max <input size="4" name="PRIMER_INTERNAL_MAX_SIZE" value="27" type="text">

</td></tr><tr>
 <td><a name="PRIMER_INTERNAL_OLIGO_OPT_TM_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_MIN_TM">Internal Oligo Tm</a>
 </td><td>Min <input size="4" name="PRIMER_INTERNAL_MIN_TM" value="57.0" type="text">
 </td><td>Opt <input size="4" name="PRIMER_INTERNAL_OPT_TM" value="60.0" type="text">
 </td><td>Max <input size="4" name="PRIMER_INTERNAL_MAX_TM" value="63.0" type="text">

</td></tr><tr>
 <td><a name="PRIMER_INTERNAL_OLIGO_GC_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_MIN_GC">Internal Oligo GC%</a>
 </td><td>Min <input size="4" name="PRIMER_INTERNAL_MIN_GC" value="20.0" type="text">
 </td><td>Opt <input size="4" name="PRIMER_INTERNAL_OPT_GC_PERCENT" value="50.0" type="text">
 </td><td>Max <input size="4" name="PRIMER_INTERNAL_MAX_GC" value="80.0" type="text">
</td></tr><!-- /tbody -->
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
<tr>
   <td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_SELF_ANY_TH">TH: Internal Oligo Self Complementarity</a>
   </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_SELF_ANY_TH" value="47.00" type="text">
   </td><td  colspan="1"></td><td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_SELF_END_TH">TH: Internal Oligo Max 3' End Self Complementarity</a>
   </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_SELF_END_TH" value="47.00" type="text">
   </td><td colspan="2"></td>
</tr>
<tr>
<td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_HAIRPIN_TH">TH: Internal Oligo Hairpin</a>
   </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_HAIRPIN_TH" value="47.00" type="text">
   </td><td  colspan="1"></td>
</tr>
</table>
</td></tr>
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
<tr>
   <td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_SELF_ANY">Internal Oligo Self Complementarity</a>
   </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_SELF_ANY" value="12.00" type="text">
   </td><td  colspan="1"></td><td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_SELF_END">Internal Oligo Max 3' End Self Complementarity</a>
   </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_SELF_END" value="12.00" type="text">
   </td><td colspan="2"></td>
</tr>
</table>
</td></tr>
</table>

<p></p>

<table border="0">

<tr>
   <td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MIN_QUALITY">Internal Oligo Min Sequence Quality</a>
   </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MIN_QUALITY" value="0" type="text">
   </td><td colspan="2"></td>
</tr>

<tr>
 <td colspan="2"><a name="PRIMER_INTERNAL_OLIGO_NUM_NS_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_MAX_NS_ACCEPTED">Max #Ns</a>
 </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_NS_ACCEPTED" value="0" type="text">
 </td><td colspan="1"></td><td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_POLY_X">Internal Oligo Max Poly-X</a>
 </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_POLY_X" value="5" type="text">
 </td><td colspan="2"></td>
</tr>

<tr>
  <td  colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MISHYB_LIBRARY">Internal Oligo Mishyb Library</a>
  </td><td colspan="2"><select name="PRIMER_INTERNAL_MISHYB_LIBRARY">
      <option selected="selected">NONE</option>
      <option>HUMAN</option>
      <option>RODENT_AND_SIMPLE</option>
      <option>RODENT</option>
      <option>DROSOPHILA</option>
  </select>
  </td><td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_MAX_LIBRARY_MISHYB">Internal Oligo Max Library Mishyb</a>
  </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_MAX_LIBRARY_MISHYB" value="12.00" type="text">
  </td><td colspan="2"></td>
</tr>
  
<tr>
  <td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_SALT_MONOVALENT">Internal Oligo Conc of monovalent cations</a>
  </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_SALT_MONOVALENT" value="50.0" type="text">
  </td><td colspan="1"><td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_DNA_CONC">Internal Oligo DNA Concentration</a>
  </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_DNA_CONC" value="50.0" type="text">
  </td><td colspan="2"></td>
</tr>

<tr>
  <td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_SALT_DIVALENT">Internal Oligo conc of divalent cations</a>
  </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_SALT_DIVALENT" value="1.5" type="text">
  </td><td colspan="1"></td><td colspan="2"><a href="primer3web_help.htm#PRIMER_INTERNAL_DNTP_CONC">Internal Oligo [dNTP]</a>
  </td><td colspan="1"><input size="4" name="PRIMER_INTERNAL_DNTP_CONC" value="0.0" type="text">
  </td><td colspan="2"></td>
</tr>
</table>
        

<h3>Objective Function Penalty Weights for Internal Oligos (Hyb Oligos)</h3>

<table border="0">

  <!-- tbody -->
    <tr>
    <td><a name="IO_WT_SIZE_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_SIZE_LT">Internal Oligo Size</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SIZE_LT" value="1.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SIZE_GT" value="1.0" type="text">
    
  </td></tr><tr>
    <td><a name="IO_WT_TM_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_TM_LT">Internal Oligo Tm</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_TM_LT" value="1.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_TM_GT" value="1.0" type="text">
    
  </td></tr><tr>
    <td><a name="IO_WT_GC_PERCENT_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_GC_PERCENT_LT">Internal Oligo GC%</a>
    </td><td>Lt
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_GC_PERCENT_LT" value="0.0" type="text">
    </td><td>Gt
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_GC_PERCENT_GT" value="0.0" type="text">
    </td></tr><!-- /tbody -->
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
<tr>
    <td><a name="IO_WT_COMPL_ANY_INPUT_TH" href="primer3web_help.htm#PRIMER_INTERNAL_WT_SELF_ANY_TH">TH: Internal Oligo Self Complementarity</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SELF_ANY_TH" value="0.0" type="text">
        
    </td></tr>
<tr>
    <td><a name="IO_WT_COMPL_END_INPUT_TH" href="primer3web_help.htm#PRIMER_INTERNAL_WT_SELF_END_TH">TH: Internal Oligo 3' End Complementarity</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SELF_END_TH" value="0.0" type="text">
        
    </td></tr>
<tr>
    <td><a name="IO_WT_HAIRPIN_TH" href="primer3web_help.htm#PRIMER_INTERNAL_WT_HAIRPIN_TH">TH: Internal Oligo Hairpin</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_HAIRPIN_TH" value="0.0" type="text">
        
    </td></tr>
</table>
</td></tr>
</table>

<p></p>

<table border="1">
<tr><td>
<table border="0">
<tr>
    <td><a name="IO_WT_COMPL_ANY_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_SELF_ANY">Internal Oligo Self Complementarity</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SELF_ANY" value="0.0" type="text">
        
    </td></tr>
 <tr>
    <td><a name="IO_WT_COMPL_END_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_SELF_END">Internal Oligo 3' End Complementarity</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SELF_END" value="0.0" type="text">
        
    </td></tr>
</table>
</td></tr>
</table>

<p></p>

<table border="0">

	<!-- tbody -->
       <tr>
	<td><a name="IO_WT_NUM_NS_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_NUM_NS">Internal Oligo #N's</a>
	</td><td><input size="4" name="PRIMER_INTERNAL_WT_NUM_NS" value="0.0" type="text">
		
    </td></tr><tr>
    <td><a name="IO_WT_REP_SIM_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_LIBRARY_MISHYB">Internal Oligo Library Mishybing</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_LIBRARY_MISHYB" value="0.0" type="text">
        
    </td></tr><tr>
    <td><a name="IO_WT_SEQ_QUAL_INPUT" href="primer3web_help.htm#PRIMER_INTERNAL_WT_SEQ_QUAL">
            Internal Oligo Sequence Quality</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_SEQ_QUAL" value="0.0" type="text">
    </td></tr><tr>

    <td><a name="IO_WT_SEQ_END_QUAL" href="primer3web_help.htm#PRIMER_INTERNAL_WT_END_QUAL">
            Internal Oligo Sequence End Quality</a>
    </td><td><input size="4" name="PRIMER_INTERNAL_WT_END_QUAL" value="0.0" type="text">
		
</td></tr><!-- /tbody -->
</table>

        <input name="Pick Primers" value="Pick Primers" type="submit">
	<input name="Download Settings" value="Download Settings" type="submit">
        <input name="Reset Form" value="Reset Form" type="submit">
        <br>
        

</form>

<h2><a name="disclaimer">Copyright Notice and Disclaimer</a></h2>
<p>
Copyright (c) 1996,1997,1998,1999,2000,2001,2004,2006,2007,2008,2009,2010,2011,2012,2013<br>
Whitehead Institute for Biomedical Research, <a href="http://steverozen.net/">Steve Rozen</a>, Andreas Untergasser and Helen Skaletsky.<br>
All rights reserved.<br>
</p>
<pre>
    This file is part of the primer3web suite.

    The primer3 suite and libraries are free software;
    you can redistribute them and/or modify them under the terms
    of the GNU General Public License as published by the Free
    Software Foundation; either version 2 of the License, or (at
    your option) any later version.

    This software is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this software (file gpl-2.0.txt in the source
    distribution); if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
OWNERS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
</pre>


<h2>Citing Primer3</h2>
<p>
We request that use of this software be cited in
publications as
</p>
<p>
<a href="http://steverozen.net">Steve Rozen</a>
and Helen J. Skaletsky (2000)
<a href="http://steverozen.net/papers/rozen-and-skaletsky-2000-primer3.pdf">
Primer3 on the WWW for general users and for biologist programmers.
</a>
In: Krawetz S, Misener S (eds)
<i>Bioinformatics Methods and Protocols: Methods in Molecular Biology.</i>
Humana Press, Totowa, NJ, pp 365-386<br>
Source code available at <a href="http://sourceforge.net/projects/primer3/">
http://sourceforge.net/projects/primer3/.
</a>
</p>

<h2>Acknowledgments</h2>
<p>
Initial development of Primer3 was funded by Howard Hughes Medical
Institute and by the National Institutes of Health, National Human
Genome Research Institute under grants R01-HG00257 (to David C. Page)
and P50-HG00098 (to Eric S. Lander)
but ongoing development and maintenance are not currently funded.
</p>

<p>
Primer3 was a complete re-implementation
of an earlier program:
Primer 0.5 (<em>Steve Lincoln, Mark Daly, and Eric
S. Lander</em>).
<!-- This web service provided by YOUR ORGANZATION HERE -->
</p>
<hr>
<pre>(input.htm v 4.0.0)</pre>
</body>
</html>
