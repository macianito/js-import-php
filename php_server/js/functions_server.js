;(function($, root, options) {

  //console.log('options:', options);

  var

    // Plugin name
    pluginName = "js-php",

    // Plugin version
    pluginVersion = "1.0.5",

    options = options || {},

    app     = options.app,

    loadingObj = null,

    activeProcesses = 0,

    remoteUrl = URL_ABS_PATH + '/functions_server.php';

    jQuery(document).ready(function() {

      loadingObj = jQuery('#' + options.loadingObj) || jQuery('#loader-fn'); // loading object

    });


    function $php(func, args) { // promise

      //console.log(':: ', func, args);

      // convert to php types ???? XXX S'ha de mirar
      /* for(var i = 0; i < args.length; i++) {
        if(typeof args[i] == 'string') {
          //args[i] = "'" + args[i] + "'";
        }
      }*/

      var data = {
        php_function: func,
        args: args,
        app : app
      };

      return getRemotePromise(data);

    }



    !function setupFunctions() {

      for(var i in exportedFns) {

        for(var len = exportedFns[i].length, j = 0; j < len; j++) {

          var phpFunction = exportedFns[i][j];


          var newCreatedFunction = (function(nameFunction) {

            return function () {

              return $php(nameFunction, arguments); // call function

            };

          })(phpFunction);

          var _aux = exportedFns[i][j].split('.');

          if(_aux.length > 1) { // cas metodes d'objectes

            if(!root[PREFIX_FN + _aux[0]]) { // if object doesn't exists
              root[PREFIX_FN + _aux[0]] = {};
            }

            root[PREFIX_FN + _aux[0]][_aux[1]] = newCreatedFunction;
          } else {
            root[PREFIX_FN + exportedFns[i][j]] = newCreatedFunction;
          }
        }
      }

    }();


    function getRemotePromise(request) {

      var request = JSON.parse(JSON.stringify(request)); // clone object

      return new Promise(function(resolve, reject) {

        $.ajax({
          type: "POST",
          url: remoteUrl,
          data: request,
          beforeSend: function(jqXHR) {
            activeProcesses++;
            loadingObj && loadingObj.show();
          }
        }).done(function( data ) {

          try {

            var objson = jQuery.parseJSON(data); // errors no controlats

          } catch(e) {

            objson = {};

            if(/error|warning/.test(data.toLowerCase())) {

              objson.error = data; // altres errors no controlats
            }

          }

          if(objson.ok) {

            resolve(objson.ok);

          } else if(objson.error) {

            alert(objson.error + ' - function: ' + request.php_function);

            reject(objson.error);

          } else {

            reject('Unexpected error');

          }

        }).fail(function(jqXHR, textStatus) {

          alert("Request failed: " + textStatus);

        }).always(function() {

          console.log('activeProcesses:', activeProcesses);

          activeProcesses--;

          if(activeProcesses == 0 && loadingObj) {
            console.log('processes finished', loadingObj);
            loadingObj.hide();
          }

        }); // end ajax

      }); // end promise
    }


})( jQuery, window, optionsApp);

// Create Promise methods alias

Promise.prototype.exec = function(fn) {
    return this.then(fn);
};


// method as a prototype function
// escape HTML tags as HTML entities

String.prototype.escape = function() {
    var tagsToReplace = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;'
    };
    return this.replace(/[&<>]/g, function(tag) {
        return tagsToReplace[tag] || tag;
    });
};

//var a = "<abc>";
//var b = a.escape(); // "&lt;abc&gt;"
