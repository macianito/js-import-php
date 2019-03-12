<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/javascript');

include 'settings.php';

?>


var loadSettings = function(event) {
  loadJS('<?php echo URL_ABS_PATH; ?>settings_js.php', loadMethods, false, document.body);
};

var loadMethods = function(event) {
  loadJS(URL_ABS_PATH + 'load_methods.php', loadServer, false);
};

var loadServer = function(event) {

  var init = typeof initApp != 'undefined' ? initApp : function() {};

  loadJS(URL_ABS_PATH + 'js/functions_server.js', init  , false, document.body);

};



/* $(function() {

  loadSettings();

}); */

(function() {

  loadSettings();

})();



function loadJS(url, callback, async, location){
    //url is URL of external file, implementationCode is the code
    //to be called from the file, location is the location to
    //insert the <script> element

    var scriptTag = document.createElement('script');
    scriptTag.src = url;

    scriptTag.async = async || false;

    scriptTag.onload = callback;
    scriptTag.onreadystatechange = callback;

    location = location || document.head;

    //location.insertBefore(scriptTag, location.childNodes[0]);

    location.appendChild(scriptTag);

};








