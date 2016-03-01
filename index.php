<?php
include 'src/Validator.php';
$data = [
    'username' => new DateTime()];

$rules = [
    'username' => 'date'
];
//
$validator = new Validation\Validator($data, $rules);
var_dump($validator->errors());

var_dump($_POST);
?>

<form method="post">
    <input type="file" name="file">
    <input type="submit">
</form>
