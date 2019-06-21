<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

$response = $plus->getTransactionResult();

// Comprueba que el pago se haya efectuado correctamente
$plus->acknowledgeTransaction();


    
// Redirecciona al cliente a Webpay para recibir el Voucher
echo RedirectorHelper::redirectBackNormal($response->urlRedirection);

?>