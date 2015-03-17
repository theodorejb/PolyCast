# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast) [![Packagist Version](https://img.shields.io/packagist/v/theodorejb/polycast.svg)](https://packagist.org/packages/theodorejb/polycast) [![License](https://img.shields.io/packagist/l/theodorejb/polycast.svg)](LICENSE.md)

Provides `int_castable`, `float_castable`, and `string_castable` functions.
The functions return true if a value can be cast to the designated type without
data loss, and false if it cannot.

Three complementary functions are also included: `to_int`, `to_float`, and
`to_string`. These functions cast and return a value if the corresponding
*_castable* function returns true, and throw a `CastException` if it returns false.

This library was originally based on the [Safe Casting Functions RFC](https://wiki.php.net/rfc/safe_cast)
proposed (but ultimately declined) for PHP 7.

## Acceptable casts

### `int_castable`

* Integers
* Floats without a remainder between `PHP_INT_MIN` and `PHP_INT_MAX`
* Strings with an optional positive/negative sign, without leading zeros, and
containing the digits 0-9 with a value between `PHP_INT_MIN` and `PHP_INT_MAX`.

### `float_castable`

* Floats
* Integers
* Strings with an optional positive/negative sign, without leading zeros, and
matching the format described at http://php.net/manual/en/language.types.float.php.

### `string_castable`

* Strings
* Integers
* Floats
* Objects with a `__toString` method

The *_castable* functions will always return false if passed `null`, `true` or
`false`, an array, resource, or object (with the exception of objects with a
`__toString` method passed to `string_castable`).

## Installation

To install via [Composer](https://getcomposer.org/),
add the following to the composer.json file in your project root:

```json
{
    "require": {
        "theodorejb/polycast": "~0.8"
    }
}
```

Then run `composer install` and require `vendor/autoload.php`
in your application's bootstrap file.

## Usage examples

### Input validation

```php
use function theodorejb\polycast\{ int_castable, float_castable };

function validatePriceBreakReq(array $data)
{
    if (!isset($data['quantity'], $data['price'])) {
        throw new Exception('quantity and price are required');
    } elseif (!int_castable($data['quantity'])) {
        throw new Exception('quantity must be an integer');
    } elseif (!float_castable($data['price'])) {
        throw new Exception('price must be a number');
    }
}

function addPriceBreak(int $itemId, int $quantity, float $price)
{
    // insert price break into database
}

// route handler
$app->post('/items/:id/pricebreaks/', function ($id) use($app) {
    $data = $app->request->getBody();
    validatePriceBreakReq($data);
    addPriceBreak((int)$id, (int)$data['quantity'], (float)$data['price']);
});
```

### Safe type conversion

```php
use theodorejb\polycast;

try {
    $totalRevenue = 0;
    $totalTransactions = 0;

    foreach ($csvRows as $row) {
        $totalRevenue += polycast\to_float($row['monthly_revenue']);
        $totalTransactions += polycast\to_int($row['monthly_transactions']);
    }

    // do something with totals
} catch (polycast\CastException $e) {
    echo "Error: " . $e->getMessage();
    var_dump($e->getTrace());
}
```

## Author

Theodore Brown  
<http://theodorejb.me>

## License

MIT
