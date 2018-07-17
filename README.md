## Paddle.com API PHP wrapper library

This library provides convinient way of querying Paddle API from php code.

## Requirements

PHP 5.3 or later.

## Installation via Composer

https://getcomposer.org/

You can install the library via Composer. Add this to your composer.json:

```javascript
{
    "require": {
        "paddle/php-api": "1.*"
    }
}
```

Then install via:

```
composer.phar install
```

To use the library, either user Composer's autoload:

```php
require_once('vendor/autoload.php');
```

Or manually:

```php
require_once('/path/to/library/autoload.php');
```

## Manual Installation

Obtain the latest version of the Paddle library with:

```
git clone https://github.com/PaddleHQ/paddle-php
```

To use the library, add the following to your PHP script:

```php
require_once('/path/to/library/autoload.php');
```

## Getting Started

To interact with Paddle API you need to create an API object, and authorize using vendor_id / vendor_api_key:

```php
$api = new \Paddle\Api();
$api->authorize_vendor($vendor_id, $vendor_auth_code);
```

Optionally you can set HTTP timeout (default is 30 seconds):

```php
$api->set_timeout(60);
```

Both authorization and timeout can be set as well while creating new API object:

```php
$api = new \Paddle\Api($vendor_id, $vendor_auth_code, 60);
```

Example usage of generate_license() method:

```php
// define $vendor_id and $vendor_auth_code first
$api = new \Paddle\Api($vendor_id, $vendor_auth_code, 60);
$product_id = 100;
$license_code = $api->generate_license($product_id);
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
string generate_product_pay_link (int $product_id, [array $optional_arguments = array()])
```

#### Parameters

* int $product_id - the id of the product
* array $optional_arguments - an associative array of optional parameters:
 * string 'title' - override product title 
 * string 'image_url' - override product image 
 * float 'price' - overrride product price 
 * string 'return_url' - url to redirect to after transaction is complete 
 * bool 'discountable' - whether coupon can be apply to checkout by user 
 * string 'coupon_code' - discount coupon code 
 * bool 'locker_visible' - whether product is visible in user's locker 
 * bool 'quantity_variable' - whether product quantity can be changed by user 
 * string 'paypal_cancel_url' - url to redirect to when paypal transaction was canceled 
 * int 'expires' - checkout expiration date, timestamp 
 * bool 'is_popup' - whether checkout is being displayed as popup 
 * string 'parent_url' - url to redirect to when close button on checkout popup is clicked 
 * array 'affiliates' - every element should contain affiliate_id as key, and affiliate_commission as value. 
 * Commission value should be float, so commission '0.1' equals 10%. 
 * array 'stylesheets' - every element should contain stylesheet type as key, and code as value 

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$product_id must be a positive integer' 
* code: 301 message: '$title must be a string' 
* code: 302 message: '$image_url must be a valid url' 
* code: 303 message: '$price must be a number' 
* code: 304 message: '$price must not be negative' 
* code: 305 message: '$return_url must be a valid url' 
* code: 306 message: '$paypal_cancel_url must be a valid url' 
* code: 307 message: '$expires must be a valid timestamp' 
* code: 308 message: '$expires must be in the future' 
* code: 309 message: '$parent_url must be a valid url' 
* code: 310 message: '$affiliates must be an array' 
* code: 311 message: 'provide $affiliates as key->value contained array with vendor_id->vendor_commission' 
* code: 312 message: '$stylesheets must be an array' 
* code: 313 message: 'provide $stylesheets as key->value contained array with stylesheet_type->stylesheet_code' 
* code: 314 message: '$webhook_url can only be set for custom product' 

---

### Generate pay link for custom (not existing in Paddle database) product

```php
string generate_custom_product_pay_link (string $title, float $price, string $image_url, string $webhook_url, array $optional_arguments)
```

#### Parameters

* string $title - title of custom product
* float $price - price of custom product
* string $image_url - image of custom product
* string $webhook_url - webhook_url of custom product
* array $optional_arguments - an associative array of optional parameters:
 * string 'return_url' - url to redirect to after transaction is complete 
 * bool 'locker_visible' - whether product is visible in user's locker 
 * bool 'quantity_variable' - whether product quantity can be changed by user 
 * string 'paypal_cancel_url' - url to redirect to when paypal transaction was canceled 
 * int 'expires' - checkout expiration date, timestamp 
 * bool 'is_popup' - whether checkout is being displayed as popup 
 * string 'parent_url' - url to redirect to when close button on checkout popup is clicked 
 * array 'affiliates' - every element should contain affiliate_id as key, and affiliate_commission as value. 
 * Commission value should be float, so commission '0.1' equals 10%. 
 * array 'stylesheets' - every element should contain stylesheet type as key, and code as value 

#### Validation exceptions of type \InvalidArgumentException

* code: 301 message: '$title must be a string' 
* code: 302 message: '$image_url must be a valid url' 
* code: 303 message: '$price must be a number' 
* code: 304 message: '$price must not be negative' 
* code: 305 message: '$return_url must be a valid url' 
* code: 306 message: '$paypal_cancel_url must be a valid url' 
* code: 307 message: '$expires must be a valid timestamp' 
* code: 308 message: '$expires must be in the future' 
* code: 309 message: '$parent_url must be a valid url' 
* code: 310 message: '$affiliates must be an array' 
* code: 311 message: 'provide $affiliates as key->value contained array with vendor_id->vendor_commission' 
* code: 312 message: '$stylesheets must be an array' 
* code: 313 message: 'provide $stylesheets as key->value contained array with stylesheet_type->stylesheet_code' 
* code: 315 message: '$webhook_url must be a valid url' 
* code: 316 message: '$discountable is not allowed for custom product' 
* code: 317 message: '$coupon_code is not allowed for custom product' 
* code: 318 message: '$product_id is not allowed for custom product' 

---

### Generate license code for framework product

```php
string generate_license (int $product_id)
```

#### Parameters

* int $product_id - the id of the product

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$product_id must be a positive integer' 

---

### Get paginated list of products including details of each product

```php
array get_products ([int $limit = 1], [int $offset = 0])
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
* float 'base_price' - base price of the product
* float 'sale_price' - sale price of the product
* array 'screenshots' - screenshots of the product
* string 'icon' - image of the product

#### Validation exceptions of type \InvalidArgumentException

* code: 319 message: '$limit must be a positive integer' 
* code: 320 message: '$offset must be a non negative integer' 

---

### Get an array of customers details

```php
array generate_customers_report ([int $product_id = null])
```

#### Parameters
* int $product_id - the id of product for which report will be created, if not provided report will contain all products data

#### Return Values

Returned array contains:

* string 'full_name' - full name of the customer
* string 'email' - email address of the customer

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$product_id must be a positive integer' 

---

### Get an array of license activations details

Activations are reportable the day after they occur - so any activations from today will not be included

```php
array generate_license_activations_report ([int $product_id = null], [int $start_timestamp = null], [int $end_timestamp = null])
```

#### Parameters
* int $product_id - the id of product for which report will be created, if not provided report will contain all products data
* int $start_timestamp - report start time
* int $end_timestamp - report end date

#### Return Values

Returned array contains:

* string 'license_code' - license code
* string 'activation_date' - activation date
* string 'customer_ip' - customer ip
* string 'customer_email' - customer email

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$product_id must be a positive integer' 
* code: 321 message: '$start_timestamp must be a timestamp' 
* code: 322 message: '$end_timestamp must be a timestamp' 

---

### Get an array of orders details

```php
array generate_orders_report ([int $product_id = null], [int $start_timestamp = null], [int $end_timestamp = null])
```

#### Parameters
* int $product_id - the id of product for which report will be created, if not provided report will contain all products data
* int $start_timestamp - report start time
* int $end_timestamp - report end date

#### Return Values

Returned array contains:

* int 'order_id' - id of the order
* string 'product_name' - name of the product
* float 'your_earnings' - your earnings
* string 'earnings_currency' - earnings currency
* string 'sale_date' - sale date

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$product_id must be a positive integer' 
* code: 321 message: '$start_timestamp must be a timestamp' 
* code: 322 message: '$end_timestamp must be a timestamp' 

---

### Get an array of sent licenses details

```php
array generate_sent_licenses_report ([int $product_id = null])
```

#### Parameters
* int $product_id - the id of product for which report will be created, if not provided report will contain all products data

#### Return Values

Returned array contains:

* string 'customer_name' - full name of the customer
* string 'customer_email' - email address of the customer
* string 'product_name' - name of the product
* string 'license_code' - license code

#### Validation exceptions of type \InvalidArgumentException

* code: 300 message: '$product_id must be a positive integer' 

---

### Generate credentials to be used to call other API methods

```php
array generate_auth_code (string $vendor_email, string $vendor_password)
```

#### Parameters
* string $vendor_email
* string $vendor_password

#### Return Values

Returned array contains:

* int 'vendor_id'
* string 'vendor_auth_code'

#### Validation exceptions of type \InvalidArgumentException

* code: 323 message: '$vendor_email must be valid' 

---

### Register external application and receive auth code, that application can use to call API methods

```php
string register_external_application (string $application_name, string $application_description, string $application_icon_url)
```

#### Validation exceptions of type \InvalidArgumentException

* code: 324 message: '$application_icon_url must be a valid url' 

---

### Get vendor public key

```php
string get_vendor_public_key ()
```

























