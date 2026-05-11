# Wise PHP

This library is written to accommodate the Wise API in PHP projects.
With Wise you can automate payments, connect business tools, and manage finances.
You can also power cross-border and domestic payouts.
For more information, see https://wise.com/ and https://api-docs.wise.com/

## Requirements

- PHP 8.4 or later.
- guzzlehttp/guzzle ^7.0
- cURL

## Install

To install the package, run:
```
composer require transferwise/wise-php
```

## Development and tests

Install dependencies:
```bash
composer install
```

Run the test suite:
```bash
vendor/bin/phpunit
```

CI runs this suite against PHP 8.4 and 8.5 on each push and pull request.

## Sample Code

### Initialing Client
```php
$client = new \TransferWise\Client(
    [
         "token" => "WISE_TOKEN",
         "profile_id" => "WISE_PROFILE_ID",
         "env" => "sandbox" // optional
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

# Resources

### Recipient Account
Recipient is a person or institution who is the ultimate beneficiary of your payment.

Available Methods
```php
$client->recipient_accounts->create();
$client->recipient_accounts->all();
$client->recipient_accounts->retrieve();
$client->recipient_accounts->delete();
```

### Quote
The quote resource defines the basic information required for a Wise transfer - the currencies to send between, the amount to send and the profile who is sending the money.

Available Methods
```php
$client->quotes->create();
$client->quotes->temporary();
$client->quotes->update();
$client->quotes->retrieve();
```

### Transfer
A transfer is a payment order to recipient account based on a quote. Once created, a transfer needs to be funded within the next five working days. Otherwise, it will be automatically canceled.

Available Methods
```php
$client->transfers->create();
$client->transfers->requirements();
$client->transfers->fund();
$client->transfers->cancel();
$client->transfers->retrieve();
$client->transfers->issues();
$client->transfers->list();
```

### Validator
Validate different types of bank details with this resource like IBAN, Sort Code, account number etc

Available Methods
```php
$client->validators->sortcode();
$client->validators->accountNo();
$client->validators->iban();
$client->validators->ibanandbic();
$client->validators->aba();
$client->validators->abaAccountNo();
$client->validators->ifsc();
$client->validators->banks();
$client->validators->branch();

```

### Profile
Manage wise user profiles with this resource

Available Methods
```php
$client->profiles->create();
$client->profiles->update();
$client->profiles->all();
$client->profiles->retrieve();
$client->profiles->directors();
$client->profiles->addDirector();
$client->profiles->addIdentificationDocument();
```
