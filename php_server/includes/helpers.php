<?php

function sanitize_args(&$item) {
  if(gettype($item) != 'string') return;
  $item = "'" . $item . "'";
}

function is_class_method($function) {
  $object_method = explode('.', $function);
  return (sizeof($object_method) > 1) ;
}
