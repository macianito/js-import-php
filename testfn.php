<?php

function testfn($num1, $num2) {
  return $num1 + $num2;
}

class Foo {

    public static function method()
    {

       return 'Static method';

    }


    public function obj_method($obj)
    {

       print_r($obj);

    }

}

function no_return() {
  $t = 6;
}
