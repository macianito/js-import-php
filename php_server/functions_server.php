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

include_once 'includes/manage_errors.php';


// Respondre als warnings -- http://stackoverflow.com/questions/1241728/can-i-try-catch-a-warning


try {

  if(isset($_POST['php_function'])) {

     $function = $_POST['php_function'];
     $args = isset($_POST['args']) ? $_POST['args'] : []; // function can have, or not have arguments

     if(is_class_method($function)) { // http://stackoverflow.com/questions/980708/calling-method-of-object-of-object-with-call-user-func
       //$result = call_user_func_array(array($object_method[0], $object_method[1]), $args);

       array_walk($args, 'prepare_args'); // afegeix cometes a cada argument

       $controller = null;

       $object_method = explode('.', $function);


       // Class validation
       if(!isset($core_methods[$object_method[0]])) {

         throw new Exception("Class {$object_method[0]} doesn't exist");

       } elseif(!class_exists($object_method[0])) {

         throw new Exception("Unknown Class {$object_method[0]}");

       } elseif(!method_exists ($object_method[0], $object_method[1])) {

         throw new Exception("Method {$object_method[1]} doesn\'t exist");

       } else { // instantiate class

         $controller = new $object_method[0]();

       }


       // execute class method
       ob_start();

         $result = call_user_func_array(array($controller, $object_method[1]), $args);

       $rbuffer = ob_get_clean(); // per si fa echo la funcio o hi ha errors o warnings


     } else {


       // Function validation
       if(!in_array($function, $core_methods['internal']) &&
          !in_array($function, $core_methods['user']) &&
          !in_array($function, $core_methods['other'] )
        ) {

         throw new Exception("Function {$function} doesn't exist");

       } elseif(in_array($function, $not_allowed_methods)) {

         throw new Exception("Function {$function} not allowed");

       }

       // execute function
       ob_start();

         $result = call_user_func_array($function, $args); // @  suppress the call with the @ operator

       $rbuffer = ob_get_clean(); // per si fa echo la funcio o hi ha errors o warnings

     }

     $result = $rbuffer ?: $result; // si la funcio ha escopit (echoed) contingut. En cas que no llavors ha retornat alguna cosa

     $last_error = error_get_last();

     if($result && !$last_error) { // php echoed error strings are catched with $last_error

       die(json_encode (array('ok' => $result)));

     }

     if($last_error) {

       $result = $last_error['message'];

     } elseif($result === null) {

       $result = "Perhaps the function " . $function . " doesn't return any value";

     }

     die(json_encode (array('error' => strip_tags($result))));

  }

} catch(Exception $e) {

  die(json_encode (array('error' => $e->getMessage())));

}
