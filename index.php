<?php
include 'src/Validator.php';
$foo = [
    'foo' => 'Panda',
    'bar' => ' '
];

$rules = [
    'foo' => 'regexp:/^[a-zA-Z\s]*$/',
    'bar' => 'regexp:/^[a-zA-Z\s]*$/'
];

$validator = new Validator($foo, $rules);
var_dump($validator->getErrors());