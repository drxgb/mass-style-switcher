<?php

use App\App;

require_once(__DIR__ . '/config.php');
spl_autoload_register(function (string $className)
{
	/** @var string */
	$file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file))
		require $file;
});

try
{
	/** @var App */
	$app = new App($config);
	$app->start();
}
catch (\Throwable $e)
{
	echo PHP_EOL;
	echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
	echo 'Em: ' . $e->getFile() . PHP_EOL;
	echo 'Linha: ' . $e->getLine() . PHP_EOL;
	echo $e->getTraceAsString() . PHP_EOL . PHP_EOL;
}
