<?php

/**
 * Папка с файлами приложения
 */
$application = 'application';

/**
 * Папка с модулями Коханы
 */
$modules = 'modules';

/**
 * Папка с ядром Коханы
 */
$system = 'system';

/**
 * Папка с темами оформления/картинками/иконками
 */
$theme = 'theme';

/**
 * Папка со стронними библиотеками ,плагинами
 */
$libs = 'libs';
 
/**
 * Папка со загрузками и картинками для сайта
 */
$media = 'media';

/**
 * Устанавливаем расширение по умолчанию
 */
define('EXT', '.php');

/**
 * Уровень ошибок
 * @link http://www.php.net/manual/errorfunc.configuration#ini.error-reporting
 *
 * Типы уровней: E_ALL | E_STRICT  ||  E_ALL ^ E_NOTICE || E_ALL & ~E_DEPRECATED
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Пути к папкам 
 *
 * @link http://kohanaframework.org/guide/using.configuration
 */
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

if (!is_dir($application) AND is_dir(DOCROOT.$application))
    $application = DOCROOT.$application;
 
if (!is_dir($system) AND is_dir(DOCROOT.$system))
    $system = DOCROOT.$system;
     
if (!is_dir($modules) AND is_dir(DOCROOT.$modules))
    $modules = DOCROOT.$modules;
     
if (!is_dir($theme) AND is_dir(DOCROOT.$themes))
    $themes = DOCROOT.$theme;
     
if (!is_dir($libs) AND is_dir(DOCROOT.$libs))
    $libs = DOCROOT.$libs;
 
if (!is_dir($media) AND is_dir(DOCROOT.$media))
    $media = DOCROOT.$media;
 
// Константы пути
define('APPPATH',   realpath($application).DIRECTORY_SEPARATOR);
define('SYSPATH',   realpath($system).DIRECTORY_SEPARATOR);
define('MODPATH',   realpath($modules).DIRECTORY_SEPARATOR);
define('THEMEPATH', realpath($theme).DIRECTORY_SEPARATOR);
define('LIBSPATH',  realpath($libs).DIRECTORY_SEPARATOR);
define('MEDIAPATH', realpath($media).DIRECTORY_SEPARATOR);
 
// Очистка конфигурационных переменных
unset($application, $modules, $system, $theme, $libs, $media);

/**
 * Проверка правильности установка. Оставлю пока для проверки развертывания на боевом хостинге
 */
//if (file_exists('install'.EXT))
//{
//	// Load the installation check
//	return include 'install'.EXT;
//}

/**
 * Константа: Старт отсчета времени
 */
if ( ! defined('KOHANA_START_TIME'))
{
	define('KOHANA_START_TIME', microtime(TRUE));
}

/**
 * Константа: Старт отсчета выделяемой памяти
 */
if ( ! defined('KOHANA_START_MEMORY'))
{
	define('KOHANA_START_MEMORY', memory_get_usage());
}

// Bootstrap the application
require APPPATH.'bootstrap'.EXT;

if (PHP_SAPI == 'cli') // Try and load minion
{
	class_exists('Minion_Task') OR die('Please enable the Minion module for CLI support.');
	set_exception_handler(array('Minion_Exception', 'handler'));

	Minion_Task::factory(Minion_CLI::options())->execute();
}
else
{
	/**
	 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
	 * If no source is specified, the URI will be automatically detected.
	 */
	echo Request::factory(TRUE, array(), FALSE)
		->execute()
		->send_headers(TRUE)
		->body();
}
