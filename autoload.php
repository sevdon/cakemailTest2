<?php
/*
 *  Register the autoloader for the CakeMailTest TodoList API classes.
 *
 * Based off the official PSR-4 autoloader example found here:
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 *
 */
require_once __DIR__.'/config.php';

function autoloadApi($class) {
	
	/**
	 namespace prefix
	 
	**/ 
    $prefix = 'CakeMailTest\\';

    // base directory   
    $base_dir = (defined('CAKEMAILTEST_TODOLIST_API_DIR')) ? CAKEMAILTEST_TODOLIST_API_DIR : __DIR__ . '/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    /* 
     * get the relative class name
     */
    $relative_class = substr($class, $len);

    /* replace the namespace prefix with the base directory, replace namespace
    *  separators with directory separators in the relative class name, append  with .php
    */
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
   // if (DEBBUGAGE_MODE) echo $file.'<br/>';

    // if the file exists, require it
    if (file_exists($file)) require $file;
	
}

spl_autoload_register('autoloadApi');
