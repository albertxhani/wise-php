<?php

require_once  __DIR__ . "/../init.php";

$test_id = 223537602;

try {

    /**
     * Retrieve account
     */
    $account = $client->recipient_accounts->retrieve($test_id);

    echo "<pre>";
    print_r($account);

    /**
     * Delete account
     */
    $response = $client->recipient_accounts->delete($test_id);

    print_r($response);

} catch (\TransferWise\Exception\AccessException $e) {
    echo "account must be deleted already";
}


