<?php
require __DIR__ . '/../vendor/autoload.php';

// SDK do MercadoPago
use MercadoPago\MercadoPagoConfig;
//Adicione as credenciais
MercadoPagoConfig::setAccessToken("PROD_ACCESS_TOKEN");

$client = new PreferenceClient();
$preference = $client->create([
  "items"=> array(
    array(
      "title" => "Meu produto",
      "quantity" => 1,
      "unit_price" => 25
    )
  )
]);

if (class_exists('MercadoPago\Preference')) {
    echo "Classe Preference encontrada!";
} else {
    echo "Classe Preference NÃO encontrada!";
}