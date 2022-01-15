<?php

require_once  __DIR__ . "/../init.php";

/**
 * Create account with iban
 */
$response = $client->recipient_accounts->create(
    [
        "accountHolderName" => "James Dean",
        "currency" => "EUR",
        "type" => "iban",
        "details" => [
            "legalType" => "PRIVATE",
            "IBAN" => "NL36RABO5749342085",
            "email" => "james.dean@example.com",
        ]
    ]
);

echo "<ore>";
print_r($response);
