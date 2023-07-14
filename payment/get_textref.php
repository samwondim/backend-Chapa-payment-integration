<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once './Model/User.php';
require_once './Model/Database.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);
$user->create();

$txt_ref = $user->getTextRef();

echo json_encode($user->getAsKeyValue());
