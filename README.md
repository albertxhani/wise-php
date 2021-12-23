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
### Creating a recepient account
...





