;(function($, root, options) {


  var

    // Plugin name
    pluginName      = "js-php",

    // Plugin version
    pluginVersion   = "1.0.5",

    options         = options || {},

    app             = options.app || APP_PATH + 'apps/app',

    loadingObj      = null,

    activeProcesses = 0, // number of running processes

    remoteUrl       = URL_ABS_PATH + '/functions_server.php';

    $(document).ready(function() {

      loadingObj = (typeof options.loadingObj  !== 'undefined')
        ? $('#' + options.loadingObj)
        : ($('#loader-fn').length > 0)
          ? $('#loader-fn')
          : null;

    });


    function $php(func, args) {

      var params = $isDefined(args) ? args[args.length - 1] : {};

      if(params instanceof $Params) {
        delete args[args.length - 1];
      } else {
        params = {};
      }

      var data = {
        php_function: func,
        args: args,
        app : app
      };

      return getRemote(data, params);

    }


    // create javascript functions

    !function setupFunctions() {

      if(!$isDefined(exportedFns.fns)) {
        alert('\'fns\' key is missing in exported functions. You must provide this key');
        return false;
      }

      for(var i in exportedFns) {

        if(!Array.isArray(exportedFns[i])) {
           alert('\'' + i + ' element\' in exported functions must be an array');
           return false;
        }

        for(var len = exportedFns[i].length, j = 0; j < len; j++) {

          var phpFunction = exportedFns[i][j];


          var jsFunction = (function(nameFunction) {

            return function () {

              return $php(nameFunction, arguments); // call function

            };

          })(phpFunction);

          var _aux = exportedFns[i][j].split('.');

          if(_aux.length > 1) { // cas metodes d'objectes

            if(!root[PREFIX_FN + _aux[0]]) { // if object doesn't exists
              root[PREFIX_FN + _aux[0]] = {};
            }

            root[PREFIX_FN + _aux[0]][_aux[1]] = jsFunction;
          } else {
            root[PREFIX_FN + exportedFns[i][j]] = jsFunction;
          }
        }
      }

    }();

    // ajax call using promise

    function getRemote(request, params) {

      var request = JSON.parse(JSON.stringify(request)); // clone object

      return new Promise(function(resolve, reject) {

        $.ajax({
          type: "POST",
          url: remoteUrl,
          data: request,
          beforeSend: function(jqXHR) {

            activeProcesses++;

            if($isDefined(params.beforeExec)) {

              params.beforeSend();

            } else {

              loadingObj && loadingObj.show();

            }

          }
        }).done(function( data ) {

          try {

            var objson = $.parseJSON(data);

          } catch(e) { // catch unexpected errors

            objson = {};

            objson.error = data;

          }

          if(objson.ok) {

            resolve(objson.ok);


          } else if(objson.error) {

            if(THROW_ALERTS)
              alert(objson.error + ' - function: ' + request.php_function);

            reject(objson.error);


          } else {

            reject('Unexpected error');

          }

        }).fail(function(jqXHR, textStatus) { // failed request

          if(THROW_ALERTS)
            alert("Request failed: " + textStatus);

          reject("Request failed: " + textStatus);

        }).always(function() {

          activeProcesses--;

          if($isDefined(params.always)) {

            params.always();

          }
          
          if(activeProcesses == 0 && loadingObj) {
            loadingObj.hide();
          }



        }); // end ajax

      }); // end promise

    } // end getRemote


})( jQuery, window, optionsApp);

// Create Promise methods alias

Promise.prototype.exec = function(fn) {
    return this.then(fn);
};

Promise.prototype.error = function(fn) {
    return this.catch(fn);
};

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


function $Params(params) {

  this.load(params);

}

$Params.prototype.remove = function() {

  for(var i in this) {
    // obj.hasOwnProperty() is used to filter out properties from the object's prototype chain
    delete this[i];
  }

  return this;

};

$Params.prototype.load = function(params) {

  this.remove();

  for(var i in params) {
    // obj.hasOwnProperty() is used to filter out properties from the object's prototype chain
    this[i] = params[i];
  }

  return this;

};

$Params.prototype.add = function(params) {

  for(var i in params) {
    this[i] = params[i];
  }

  return this;

};

//var obj = new $Params({r: 'ff', t: 'gg'});
//console.log(obj.removeParams());

function $isDefined(ele) {

  return typeof ele !== 'undefined';

}



