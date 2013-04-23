<?php
class cleanData{
	function GET($varName){
		return addslashes(trim(@$_GET[$varName]));
	}
	
	function POST($varName){
		return addslashes(trim(@$_POST[$varName]));
	}
	
	function URL($varName){
		global $_URL;
		return addslashes(trim(@$_URL[$varName]));
	}
}
?>