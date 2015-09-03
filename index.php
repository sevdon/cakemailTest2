<?php
error_reporting(0);
require_once __DIR__.'/autoload.php';
use CakeMailTest\TodoList\controllerApi as controllerApi;
use CakeMailTest\TodoList\ExceptionTodoList as ExceptionTodoList;
ob_start();
try {
	$API = new controllerApi();
	$API->request();
} catch (ExceptionTodoList $e) {
	echo $e->getMessage();
	exit;
}	
$content = ob_get_contents();
ob_end_clean();
echo $content;
?>