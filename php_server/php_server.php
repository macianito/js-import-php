<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/javascript');

include_once 'settings.php';

include_once 'settings_js.php';

include_once 'load_methods.php';

include_once 'js/functions_server.js';

?>

var init = typeof initApp != 'undefined' ? initApp : function() {};

init();
