<?php 

require_once  __DIR__ . "/../init.php";

/**
 * List all accounts
 */
$accounts = $client->recipient_accounts->all();

/**
 * Filter by currency 
 */
// $accounts = $client->recipient_accounts->all(["currency" => "GBP"]);

echo "<ore>";
print_r($accounts);

