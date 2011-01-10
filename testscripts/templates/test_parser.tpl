<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Test Parser</title>
</head>
<body>

Input form for parser testing <BR>
<form name="Testparser" action="test_parser.php{if isset($smarty.request.XDEBUG_PROFILE)}?XDEBUG_PROFILE{/if}" method="post">
<strong>Template input</strong>
<textarea name="template" rows="10" cols="60">{$template|escape}</textarea><br> 
{html_checkboxes name='debug' values=1 output=Debug}
<input name="Update" type="submit" value="Update">
</form >

