<?php
//include "vendor/autoload.php";
//$data = [
//    'username' => 'fff',
//];
//
//$rules = [
//    'username' => 'max:2',
//];
//$validator = new \Donneh\Validator($data, $rules);
//var_dump($validator->errors());

function isOfDrivingAge($age) {
    return $age >= 18;
}

function notifyOfDrivingAge($name, $age) {
    return $message = isOfDrivingAge($age) ? 'May drive.' : 'May not drive.';
}

echo notifyOfDrivingAge('Yannick', 21);