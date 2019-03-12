<?php
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: text/javascript');

include_once 'settings.php';

$constants = get_defined_constants(true);

//print_r($constants);

echo "// javascript configuration constants \n\r";

foreach($constants['user'] as $k=>$constant) {

  if(is_numeric($constant) || is_bool($constant)) {
    echo "var " . $k . " = " . $constant . ";\n\r";
  } else {
    echo "var " . $k . " = '" . $constant . "';\n\r";
  }
}

?>

var optionsApp = { // opcions de l'aplicacion
   // app: 'path_app'
};
