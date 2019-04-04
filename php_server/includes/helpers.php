<?php
/*
 * This file is part of JS PHP Import.
 *
 * (c) Ivan Macià Galan <ivanmaciagalan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    JS PHP Import
 * @author     Ivan Macià Galan <ivanmaciagalan@gmail.com>
 * @copyright  Copyright (c) 2019, Ivan Macià Galan. (http://mazius.org/)
 * @license    https://opensource.org/licenses/MIT MIT License
 * @version    1.0.0
 * @link       https://github.com/macianito/js-import-php GitHub - Mazius.org
 * @since      File available since Release 1.0.0
 *
 */


/*
 * prepare argument acording to its type
 *
 * */
function prepare_args(&$arg) {
  if(gettype($arg) == 'string') {
    $arg = "'" . $arg . "'";
  }
}

/*
 * Check if the argument passed is a call to class method
 *
 * */

function is_class_method($function) {

  $result = preg_match('/([A-Za-z0-9-_]*)\.([A-Za-z0-9-_]*)/', $function);

  return $result === 1;

  //$object_method = explode('.', $class_method);
  //return (sizeof($object_method) > 1) ;
}

/*
 * Get array of methods from a class
 *
 */

function get_methods_from_class($class) {


  if(!class_exists($class)) {
    //echo 'console.warn("unknown class ' . $class . '")';
    return array();
  }

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


/*
 * Check if the string being passed is a php string error
 *
 */

function is_php_msg_error($str) {

  if(!is_string($str)) {
    return false;
  }

  $result = preg_match('/(Warning|Notice|Fatal Error)(.*)\:/', $str);

  return $result === 1;

}


