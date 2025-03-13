<?php
include "vendor/autoload.php";
$data = [
   'username' => 'fff',
];

$rules = [
   'username' => 'max:2',
];
$validator = new \Donneh\Validator($data, $rules);
var_dump($validator->errors());
