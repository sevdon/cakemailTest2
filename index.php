<?php
require_once __DIR__.'/autoload.php';
use CakeMailTest\TodoList\controllerApi as controllerApi;
use CakeMailTest\TodoList\ExceptionTodoList as exception;
try {
	$API = new controllerApi();
	$API->request();
} catch (exception $e) {
	echo $e->getMessage();
	exit;
}	
?>