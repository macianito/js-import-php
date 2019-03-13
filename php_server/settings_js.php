<?php

include_once 'settings.php';

$constants = get_defined_constants(true);


echo "// javascript configuration constants \n\r";

foreach($constants['user'] as $k=>$constant) {

  if(is_numeric($constant) || is_bool($constant)) {
    echo "var " . $k . " = " . $constant . ";\n\r";
  } else {
    echo "var " . $k . " = '" . $constant . "';\n\r";
  }
}


echo "// set optionsApp if no defined \n\r";
?>
    
if(typeof optionsApp == 'undefined') {
  var optionsApp = {};
}
    
<?php

echo "// set app option \n\r";

if(isset($_GET['app'])) { // initial enviroment load script set app option ?>  
    
  optionsApp.app = '<?php echo $_GET['app']; ?>';
    
<?php }
