<?php

set_error_handler("warning_handler", E_WARNING);
// dns_get_record(...)
//restore_error_handler();

function warning_handler($errno, $errstr) {
  throw new Exception("\n\r::errno: " . $errno . "\n\r" . $errstr . ' ');
}

set_error_handler("error_handler", E_ERROR);

function error_handler($errno, $errstr) {
  throw new Exception("\n\r::errno: " . $errno . "\n\r" . $errstr . ' ');
}

set_error_handler("notice_handler", E_NOTICE);

function notice_handler($errno, $errstr) {
  throw new Exception("\n\r::errno: " . $errno . "\n\r" . $errstr . ' ');
}