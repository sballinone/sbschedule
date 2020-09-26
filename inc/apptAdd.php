<?php
session_start();

include "header.php";

if(file_exists("../lang/".$_SESSION['lang'].".php")) {
    include "../lang/".$_SESSION['lang'].".php";
} else {
    include "../lang/de.php";
}
?>

<div class='heading'><?=$output['apptAdd'];?>
	<div style='font-size: 8pt; float:right; cursor:pointer;' id='closeElementDetail' onclick='javascript:closeElementDetail()'><?=$output['close'];?>
	</div>
</div>
