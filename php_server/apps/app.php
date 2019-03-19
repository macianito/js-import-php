<?php

class Foo {


    public $prop1 = 'Foo Member Variable 1';
    public $prop2 = 'Foo Member Variable 2';

    public function index() { // Default method

    }

    public function get_Foo($arg1, $arg2 = 'gtg') {
        return 'Foo ' . $this->prop1 . ' ' . $this->prop2 . ' ' . $arg1 . ' ' . $arg2;
    }

    public function get_Params($params) {
       foreach($params as $param) {
         echo '<br>- ' . $param;
       }
    }

}

$Foo = new Foo();
