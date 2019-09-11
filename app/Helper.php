<?php
	function dateFormat($date){
		return date(' Y-m-d ',strtotime($date));
	}
	function dateTimeFormat($date){
		return date(' d MM Y H:i ');
	}

	//// numbering format

	function zeroFill($number){
		return sprintf("%08d", $number);
		// return  $number;
	}

	function backofficeDomain(){
		return "http://localhost:3000";
	}

?>