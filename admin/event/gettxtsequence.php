<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."includes/function.php");
(isset($_POST["selseq"])) ? $selseq = trim($_POST["selseq"]) : $selseq = "";
(isset($_POST["txtseq1"])) ? $txtseq[1] = trim($_POST["txtseq1"]) : $txtseq[1] = "";
(isset($_POST["txtseq2"])) ? $txtseq[2] = trim($_POST["txtseq2"]) : $txtseq[2] = "";
(isset($_POST["txtseq3"])) ? $txtseq[3] = trim($_POST["txtseq3"]) : $txtseq[3] = "";
(isset($_POST["txtseq4"])) ? $txtseq[4] = trim($_POST["txtseq4"]) : $txtseq[4] = "";
(isset($_POST["txtseq5"])) ? $txtseq[5] = trim($_POST["txtseq5"]) : $txtseq[5] = "";
(isset($_POST["txtseq6"])) ? $txtseq[6] = trim($_POST["txtseq6"]) : $txtseq[6] = "";

echo "<div id='txtseq'>";
for($a=1;$a <= $selseq;$a++){
	echo
		"<label for=\"txtseq".$a."\">Teks Sequence Ke ".$a."</label>&nbsp;&nbsp;&nbsp;".
			"<input name=\"txtseq".$a."\" type=\"text\" maxlength=\"150\" value=\"".$txtseq[$a]."\" class=\"regular-text\" /><br/>";
}
echo "</div>";

?>