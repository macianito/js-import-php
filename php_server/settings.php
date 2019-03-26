<?php
header('Access-Control-Allow-Origin: *');


/*--------------------------------------------------------------
  # Local Access only
--------------------------------------------------------------*/

if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
  $this->output->set_status_header(400, 'No Remote Access Allowed');
  exit; //just for good measure
}

/*--------------------------------------------------------------
  # Setup display errors
--------------------------------------------------------------*/

ini_set('display_errors', 1);
error_reporting(E_ALL);


/*--------------------------------------------------------------
  # Define APP constants
--------------------------------------------------------------*/

$path_relative = substr(realpath(__DIR__), strlen(realpath($_SERVER['DOCUMENT_ROOT'])));

define('PATH_SEP', '/'); // path separator

define('APP_RELATIVE_PATH', str_replace('\\', PATH_SEP, $path_relative));

define('APP_PATH', $_SERVER['DOCUMENT_ROOT'] . APP_RELATIVE_PATH . PATH_SEP); // APP PATH

define('URL_RELATIVE_PATH', APP_RELATIVE_PATH);

define('URL_ABS_PATH', 'http://' . $_SERVER['HTTP_HOST'] . URL_RELATIVE_PATH . PATH_SEP);

define('PREFIX_FN', '$'); // javascript functions prefix

/*--------------------------------------------------------------
  # Include libraries
--------------------------------------------------------------*/

include_once 'includes/helpers.php';

/*--------------------------------------------------------------
  # Load APP
--------------------------------------------------------------*/

if(isset($_REQUEST['app'])) {

  $app = $_REQUEST['app'];

  if(substr($app, -4) != '.php') {
    $app .= '.php';
  }

  if(substr($app, 0, 1) != '/') { // no absolute path
    $app = 'apps/' . $app;
  }

} else { // default behaviour

  $app = 'apps/app.php';

}


include_once $app;

// End Load App


if(!defined('FILES_PATH')) {
  define('FILES_PATH', $_SERVER['DOCUMENT_ROOT'] . APP_RELATIVE_PATH . '/../folder-test/'); // FILES PATH
}

/*--------------------------------------------------------------
  # Load Core Methods if they have not been previously loaded
--------------------------------------------------------------*/

if(!isset($core_methods)) {

  // Reference to core PHP methods -- loaded methods
  $defined_functions = get_defined_functions();

  $core_methods = array(
    //'internal' => array('file_get_contents', 'file_put_contents', 'unlink', 'scandir'), // functions example
    'internal' => $defined_functions['internal'],
    'user'     => $defined_functions['user'],
    'other'    => array(),
    //'foo'    => array('Foo.index', 'Foo.get_Foo'), // object methods example
    'Foo'      => get_methods_from_class('Foo') // object methods example
  );

}

if(!isset($not_allowed_methods)) {
  $not_allowed_methods = array( // this methods are not allowed
  //'str_replace',
  );
}


