<?php
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);


//substr(str_replace('\\', '/', realpath(dirname(__DIR__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))))

$path_relative = substr(realpath(__DIR__), strlen(realpath($_SERVER['DOCUMENT_ROOT'])));


define('APP_RELATIVE_PATH', str_replace('\\', '/', $path_relative));

define('APP_PATH', $_SERVER['DOCUMENT_ROOT'] . APP_RELATIVE_PATH . '/'); // APP PATH

define('URL_RELATIVE_PATH', APP_RELATIVE_PATH);

define('URL_ABS_PATH', 'http://' . $_SERVER['HTTP_HOST'] . URL_RELATIVE_PATH . '/');

define('PATH_SEP', '/'); // separador de elements ruta - depen sistema operatiu

define('PREFIX_FN', '$'); // separador de elements ruta - depen sistema operatiu


// Load App

$app = isset($_POST['app']) ? $_POST['app'] : 'app1.php';
include_once 'apps/' . $app;

if(!defined('FILES_PATH')) {
  define('FILES_PATH', $_SERVER['DOCUMENT_ROOT'] . APP_RELATIVE_PATH . '/../folder-test/'); // FILES PATH
}



// Reference to core PHP methods -- metodes admesos

$defined_functions = get_defined_functions();

$core_methods = array(
  //'internal' => array('file_get_contents', 'file_put_contents', 'unlink', 'scandir'),
  'internal' => $defined_functions['internal'],
  'user'     => $defined_functions['user'],
  'other'    => array(),
  //'foo'    => array('Foo.index', 'Foo.get_Foo'), // cas metodes d'objectes
  'Foo'      => get_methods_from_class('Foo') // cas metodes d'objectes
);

$not_allowed_methods = array(
  //'str_replace',
);


//die(print_r($coreMethods));

function get_methods_from_class($class) {

  $class_methods = get_class_methods($class);

  foreach ($class_methods as $k=>$method_name) {

    $reflect = new ReflectionMethod($class, $method_name);

    if ($reflect->isPublic()) {

      $class_methods[$k] = $class . '.' . $method_name;

    } else {

      unset($class_methods[$k]);

    }

  }

  return $class_methods;

}

