<?php
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
