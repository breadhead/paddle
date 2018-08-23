## Paddle.com API PHP wrapper library

This library provides convinient way of querying Paddle API from php code.

## Requirements

PHP 5.3 or later.

## Installation via Composer

```sh
composer req breadhead/paddle
```

## Getting Started

To interact with Paddle API you need to create an API object, and authorize using vendorId / vendorApiKey:

```php
$api = new \Breadhead\Paddle\Api();
$api->authorizeVendor($vendorId, $vendorAuthCode);
```

Optionally you can set HTTP timeout (default is 30 seconds):

```php
$api->setTimeout(60);
```

Both authorization and timeout can be set as well while creating new API object:

```php
$api = new \Breadhead\Paddle\Api($vendorId, $vendorAuthCode, 60);
```

Example usage of generateLicense() method:

```php
// define $vendorId and $vendorAuthCode first
$api = new \Breadhead\Paddle\Api($vendorId, $vendorAuthCode, 60);
$productId = 100;
$licenseCode = $api->generateLicense($productId);
```

## Common exceptions

All methods can thrown the following exceptions of type \Exception:

* 1XX - API response errors
 * code: 100 message: 'Unable to find requested license' 
 * code: 101 message: 'Bad method call' 
 * code: 102 message: 'Bad api key' 
 * code: 103 message: 'Timestamp is too old or not valid' 
 * code: 104 message: 'License code has already been utilized' 
 * code: 105 message: 'License code is not active' 
 * code: 106 message: 'Unable to find requested activation' 
 * code: 107 message: 'You don't have permission to access this resource' 
 * code: 108 message: 'Unable to find requested product' 
 * code: 109 message: 'Provided currency is not valid' 
 * code: 110 message: 'Unable to find requested purchase' 
 * code: 111 message: 'Invalid authentication token' 
 * code: 112 message: 'Invalid verification token' 
 * code: 113 message: 'Invalid padding on decrypted string' 
 * code: 114 message: 'Invalid or duplicated affiliate' 
 * code: 115 message: 'Invalid or missing affiliate commision' 
 * code: 116 message: 'One or more required arguments are missing' 
 * code: 117 message: 'Provided expiration time is incorrect' 

* 2XX - general errors
 * code: 200 message: 'CURL error' 
 * code: 201 message: 'Incorrect HTTP response code' 
 * code: 202 message: 'Incorrect API response'
 * code: 203 message: 'Timeout must be a positive integer'
 * code: 204 message: 'Vendor credentials not provided'

## Available methods

### Generate pay link for regular product

```php
string generateProductPayLink (int $productId, [array $optionalArguments = array()])
```

#### Parameters

* int $productId - the id of the product
* array $optionalArguments - an associative array of optional parameters:
 * string 'title' - override product title 
 * string 'imageUrl' - override product image 
 * float 'price' - overrride product price 
 * string 'returnUrl' - url to redirect to after transaction is complete 
 * bool 'discountable' - whether coupon can be apply to checkout by user 
 * string 'couponCode' - discount coupon code 
 * bool 'lockerVisible' - whether product is visible in user's locker 
 * bool 'quantityVariable' - whether product quantity can be changed by user 
 * string 'paypalCancelUrl' - url to redirect to when paypal transaction was canceled 
 * int 'expires' - checkout expiration date, timestamp 
 * bool 'isPopup' - whether checkout is being displayed as popup 
 * string 'parentUrl' - url to redirect to when close button on checkout popup is clicked 
 * array 'affiliates' - every element should contain affiliate_id as key, and affiliate_commission as value. 
 * Commission value should be float, so commission '0.1' equals 10%. 
 * array 'stylesheets' - every element should contain stylesheet type as key, and code as value 

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$productId must be a positive integer' 
* code: 301 message: '$title must be a string' 
* code: 302 message: '$imageUrl must be a valid url' 
* code: 303 message: '$price must be a number' 
* code: 304 message: '$price must not be negative' 
* code: 305 message: '$returnUrl must be a valid url' 
* code: 306 message: '$paypalCancelUrl must be a valid url' 
* code: 307 message: '$expires must be a valid timestamp' 
* code: 308 message: '$expires must be in the future' 
* code: 309 message: '$parentUrl must be a valid url' 
* code: 310 message: '$affiliates must be an array' 
* code: 311 message: 'provide $affiliates as key->value contained array with vendorId->vendorCommission' 
* code: 312 message: '$stylesheets must be an array' 
* code: 313 message: 'provide $stylesheets as key->value contained array with stylesheetType->stylesheetCode' 
* code: 314 message: '$webhookUrl can only be set for custom product' 

---

### Generate pay link for custom (not existing in Paddle database) product

```php
string generateCustomProductPayLink (string $title, float $price, string $imageUrl, string $webhookUrl, array $optionalArguments)
```

#### Parameters

* string $title - title of custom product
* float $price - price of custom product
* string $imageUrl - image of custom product
* string $webhookUrl - webhook_url of custom product
* array $optionalArguments - an associative array of optional parameters:
 * string 'returnUrl' - url to redirect to after transaction is complete 
 * bool 'lockerVisible' - whether product is visible in user's locker 
 * bool 'quantityVariable' - whether product quantity can be changed by user 
 * string 'paypalCancelUrl' - url to redirect to when paypal transaction was canceled 
 * int 'expires' - checkout expiration date, timestamp 
 * bool 'isPopup' - whether checkout is being displayed as popup 
 * string 'parentUrl' - url to redirect to when close button on checkout popup is clicked 
 * array 'affiliates' - every element should contain affiliate_id as key, and affiliate_commission as value. 
 * Commission value should be float, so commission '0.1' equals 10%. 
 * array 'stylesheets' - every element should contain stylesheet type as key, and code as value 

#### Validation exceptions of type \InvalidArgumentException

* code: 301 message: '$title must be a string' 
* code: 302 message: '$imageUrl must be a valid url' 
* code: 303 message: '$price must be a number' 
* code: 304 message: '$price must not be negative' 
* code: 305 message: '$returnUrl must be a valid url' 
* code: 306 message: '$paypalCancelUrl must be a valid url' 
* code: 307 message: '$expires must be a valid timestamp' 
* code: 308 message: '$expires must be in the future' 
* code: 309 message: '$parentUrl must be a valid url' 
* code: 310 message: '$affiliates must be an array' 
* code: 311 message: 'provide $affiliates as key->value contained array with vendorId->vendorCommission' 
* code: 312 message: '$stylesheets must be an array' 
* code: 313 message: 'provide $stylesheets as key->value contained array with stylesheetType->stylesheetCode' 
* code: 315 message: '$webhookUrl must be a valid url' 
* code: 316 message: '$discountable is not allowed for custom product' 
* code: 317 message: '$couponCode is not allowed for custom product' 
* code: 318 message: '$productId is not allowed for custom product' 

---

### Generate license code for framework product

```php
string generateLicense (int $productId)
```

#### Parameters

* int $productId - the id of the product

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$productId must be a positive integer' 

---

### Get paginated list of products including details of each product

```php
array getProducts ([int $limit = 1], [int $offset = 0])
```

#### Parameters

* int $limit - number of returned products
* int $offset - offset from first product

#### Return Values

Returned array contains:

* int 'total' - total number of products
* int 'count' - number of returned products
* array 'products' - returned products, each of whitch contains:
* int 'id' - id of the product
* string 'name' - name of the product
* string 'description' - description of the product
* float 'basePrice' - base price of the product
* float 'salePrice' - sale price of the product
* array 'screenshots' - screenshots of the product
* string 'icon' - image of the product

#### Validation exceptions of type \InvalidArgumentException

* code: 319 message: '$limit must be a positive integer' 
* code: 320 message: '$offset must be a non negative integer' 

---

### Get an array of customers details

```php
array generateCustomersReport ([int $productId = null])
```

#### Parameters
* int $productId - the id of product for which report will be created, if not provided report will contain all products data

#### Return Values

Returned array contains:

* string 'fullName' - full name of the customer
* string 'email' - email address of the customer

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$productId must be a positive integer' 

---

### Get an array of license activations details

Activations are reportable the day after they occur - so any activations from today will not be included

```php
array generateLicenseActivationsReport ([int $productId = null], [int $startTimestamp = null], [int $endTimestamp = null])
```

#### Parameters
* int $productId - the id of product for which report will be created, if not provided report will contain all products data
* int $startTimestamp - report start time
* int $endTimestamp - report end date

#### Return Values

Returned array contains:

* string 'licenseCode' - license code
* string 'activationDate' - activation date
* string 'customerIp' - customer ip
* string 'customerEmail' - customer email

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$productId must be a positive integer' 
* code: 321 message: '$startTimestamp must be a timestamp' 
* code: 322 message: '$endTimestamp must be a timestamp' 

---

### Get an array of orders details

```php
array generateOrdersReport ([int $productId = null], [int $startTimestamp = null], [int $endTimestamp = null])
```

#### Parameters
* int $productId - the id of product for which report will be created, if not provided report will contain all products data
* int $startTimestamp - report start time
* int $endTimestamp - report end date

#### Return Values

Returned array contains:

* int 'orderId' - id of the order
* string 'productName' - name of the product
* float 'yourEarnings' - your earnings
* string 'earningsCurrency' - earnings currency
* string 'saleDate' - sale date

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$productId must be a positive integer' 
* code: 321 message: '$startTimestamp must be a timestamp' 
* code: 322 message: '$endTimestamp must be a timestamp' 

---

### Get an array of sent licenses details

```php
array generateSentLicensesReport ([int $productId = null])
```

#### Parameters
* int $productId - the id of product for which report will be created, if not provided report will contain all products data

#### Return Values

Returned array contains:

* string 'customerName' - full name of the customer
* string 'customerEmail' - email address of the customer
* string 'productName' - name of the product
* string 'licenseCode' - license code

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$productId must be a positive integer' 

---

### Generate credentials to be used to call other API methods

```php
array generateAuthCode (string $vendorEmail, string $vendorPassword)
```

#### Parameters
* string $vendorEmail
* string $vendorPassword

#### Return Values

Returned array contains:

* int 'vendorId'
* string 'vendorAuthCode'

#### Validation exceptions of type \InvalidArgumentException

* code: 323 message: '$vendorEmail must be valid' 

---

### Register external application and receive auth code, that application can use to call API methods

```php
string registerExternalApplication (string $applicationName, string $applicationDescription, string $applicationIconUrl)
```

#### Validation exceptions of type \InvalidArgumentException

* code: 324 message: '$applicationIconUrl must be a valid url' 

---

### Get vendor public key

```php
string getVendorPublicKey ()
```
