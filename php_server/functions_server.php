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

include_once 'settings.php';



try {

  if(isset($_POST['php_function'])) {

     $function = $_POST['php_function'];

     $args = isset($_POST['args']) ? $_POST['args'] : []; // function can have, or not have arguments

     if(is_class_method($function)) { // a class method has been called

       array_walk($args, 'prepare_args'); // prepare and sanitize passed arguments

       $object_method = explode('.', $function); // get

       // Class and method checking

       if(!isset($exported_fns[$object_method[0]])) {

         throw new Exception("Use of class {$object_method[0]} not allowed");

       } elseif(!class_exists($object_method[0])) {

         throw new Exception("Unknown class {$object_method[0]}");

       } elseif(!method_exists($object_method[0], $object_method[1])) {

         throw new Exception("Method {$object_method[1]} doesn\'t exist");

       } else {

         // instantiate the class
         $controller = new $object_method[0]();

       }

       ob_start();

         // call the class method
         $result = call_user_func_array(array($controller, $object_method[1]), $args);

       $rbuffer = ob_get_clean(); // store the echoed content or the returned value


     } else { // case function


       // Function cheking

       if(!in_array($function, $exported_fns['fns'])) {

         throw new Exception("Function {$function} doesn't exist");

       } elseif(in_array($function, $not_allowed_fns)) {

         throw new Exception("Function {$function} is not allowed");

       }

       ob_start();

         // call the function and save the result
         $result = call_user_func_array($function, $args);

       $rbuffer = ob_get_clean(); // get the buffer's content because the function may echo content or thrown warnings, notices.

     }

     $result = $rbuffer ?: $result; // store the echoed content or the returned value

     $last_error = error_get_last();

     if($result && !$last_error) { // php echoed error strings are catched with $last_error

       die(json_encode (array('ok' => $result)));

     }

     // catch and handle unexpected errors

     if($last_error) {

       $result = $last_error['message'];

     } elseif($result === null) {

       $result = "Perhaps the function " . $function . " doesn't return any value";

     }

     throw new Exception(strip_tags($result));

  } else {

    throw new Exception('Missing php_function parameter');

  }

} catch(Exception $e) {

  die(json_encode (array('error' => $e->getMessage())));

}
