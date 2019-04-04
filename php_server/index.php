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


header('Access-Control-Allow-Origin: *');
header('Content-Type: text/javascript');

include_once 'settings.php';

include_once 'settings_js.php';

include_once 'load_methods.php';

include_once 'js/functions_server.js';

?>

var init = typeof initApp != 'undefined' ? initApp : function() {};

init();
