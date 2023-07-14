<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once './Model/Payment.php';

$payment = new Payment();
$data = file_get_contents("php://input");

$response = $payment->accept_payment($data);
echo $response;
