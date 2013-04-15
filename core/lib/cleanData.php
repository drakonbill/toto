<?php
class cleanData{
	function GET($varName){
		return addslashes(@$_GET[$varName]);
	}
	
	function POST($varName){
		return addslashes(@$_POST[$varName]);
	}
	
	function URL($varName){
		global $_URL;
		return addslashes(@$_URL[$varName]);
	}
}
?>