# Wise php

This library is written to accomodate the wise API's use in php projects
With Wise you can automate payments, connect your business tools, and create ways to manage your finances.
You can also power your cross-border and domestic payouts. For more info have a look at https://wise.com/ and https://api-docs.wise.com/

# Requirements

- PHP 5.6.0 and later.
- cURL

# Sample Code

### Initialing Client
```php
$client = new \Wise\Client(
    [
         "token" => "WISE_TOKEN",
         "profile_id" => "WISE_PROFILE_ID"
    ]
);

```
### Creating a recipient account

```php
$client->recipient_accounts->create(
    [
        "accountHolderName" => "John Snow",
        "currency" => "EUR",
        "type" => "iban",
            "details" => [
                "legalType" => "PRIVATE",
                "IBAN" => "GBFSDFS546S5DF46S5"
        ]
    ]
);

```

### Validating Iban

```php
$client->validators->iban($iban);
```

### Creating Quote
```php
$client->quotes->create(
    [
        "sourceCurrency" => "EUR",
        "targetCurrency" => "GBP",
        "sourceAmount" => "100.00",
        "targetAmount" => null
    ]
);
```

### Creating transfer
```php
$client->transfers->create(
    [
        "targetAccount" => "account id",
        "quoteUuid" => "generated quote id",
        "customerTransactionId" => "transaction id",
        "details" => [
            "reference" => "Company X",
            "transferPurpose"=> "verification.transfers.purpose.pay.bills",
            "sourceOfFunds"=> "verification.source.of.funds.other"
        ]
    ]
);
```





