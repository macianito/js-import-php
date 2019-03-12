;(function($, root, options) {


  var

    // Plugin name
    pluginName = "js-php",

    // Plugin version
    pluginVersion = "1.0.5",

    options = options || {},

    // App
    app = options.app || "app1.php", // app que s'executa

    // Reference to core PHP methods Es carrega a load_methods.php

    loadingObj = null,

    activeProcesses = 0,

    remoteUrl = URL_ABS_PATH + '/functions_server.php';

    jQuery(document).ready(function() {

      loadingObj = options.loadingObj || jQuery('#loader-fn'); // objecte que mostrara el loading

    });

    // URL APP - http://127.0.0.1/APPS_JS_VARIS/JS_PHP/index.html


    function $php(func, args) { // promise
      // aqui fem ajax per enviar a php i retornar valor
      //console.log(':: ', func, args);

      // convert to php types ???? XXX S'ha de mirar
      for(var i = 0; i < args.length; i++) {
        if(typeof args[i] == 'string') {
          //args[i] = "'" + args[i] + "'";
        }
      }

      var data = {
        php_function: func,
        args: args
        //app: app
      };

      return getRemotePromise(data);

    }

    function $php_callback(func, callback, args) { // asincrona
      // aqui fem ajax per enviar a php i es tracta valor pero en callback
      //console.log(func, callback, args);

      // convert to php types ???? XXX S'ha de mirar
      for(var i = 0; i < args.length; i++) {
        if(typeof args[i] == 'string') {
          //args[i] = "'" + args[i] + "'";
        }
      }

       var data = {
        php_function: func,
        args: args
      };

      return getRemote(data, callback);

    }


    !function setupFunctions() {

      for(var i in coreMethods) {

        for(var len = coreMethods[i].length, j = 0; j < len; j++) {

          var phpFunction = coreMethods[i][j];


          var newCreatedFunction = (function(nameFunction) { // root es l’objecte globalvar callback = arguments[0];

            return function () {

              //var callback = arguments[0];
              //delete arguments[0]; // extraiem primer element
              //return $php_callback(nameFunction, arguments);

              return $php(nameFunction, arguments); // call function

            };

          })(phpFunction);

          var _aux = coreMethods[i][j].split('.');

          if(_aux.length > 1) { // cas metodes d'objectes

            if(!root[PREFIX_FN + _aux[0]]) { // if object doesn't exists
              root[PREFIX_FN + _aux[0]] = {};
            }

            root[PREFIX_FN + _aux[0]][_aux[1]] = newCreatedFunction;
          } else {
            root[PREFIX_FN + coreMethods[i][j]] = newCreatedFunction;
          }
        }
      }

    }();


    function getRemotePromise(data, callback) {

      return new Promise(function(resolve, reject) {

        $.ajax({
          type: "POST",
          url: remoteUrl,
          data: data,
          beforeSend: function(jqXHR) {
            activeProcesses++;
            loadingObj && loadingObj.show();
          }
        }).done(function( data ) {

          //console.log('i');

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

            alert(objson.error);

            reject(objson.error);

          } else {

            reject('Unexpected error');

          }

        }).fail(function(jqXHR, textStatus) {

          alert("Request failed: " + textStatus);

        }).always(function() {

          console.log('activeProcesses:', activeProcesses);

          if(--activeProcesses == 0 && loadingObj) {
            loadingObj.hide();
          }

        }); // end ajax

      }); // end promise
    }


    function getRemote(data, callback) {

      return $.ajax({
        type: "POST",
        url: remoteUrl,
        data: data,
        beforeSend: function(jqXHR) {
          activeProcesses++;
          loadingObj && loadingObj.show();
        }
      }).done(function( data ) {

        try {

          var objson = jQuery.parseJSON(data);

        } catch(e) {

          objson = {};

          if(/error|warning/.test(data.toLowerCase())) {

            objson = {};

            objson.error = data; // altres errors no controlats
          }

        }

        if(objson.ok && callback) {

          callback(objson.ok);

        } else if(objson.error) {

          alert(objson.error);

          reject(objson.error);

        } else {

          alert('Unexpected error');

        }

      }).fail(function(jqXHR, textStatus) {

        alert("Request failed: " + textStatus);

      }).always(function() {

        if(--activeProcesses == 0 && loadingObj) {
          loadingObj.hide();
        }

      }); // end ajax

    }


})( jQuery, window, optionsApp);

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
