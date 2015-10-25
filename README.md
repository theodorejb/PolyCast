# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast) [![Packagist Version](https://img.shields.io/packagist/v/theodorejb/polycast.svg)](https://packagist.org/packages/theodorejb/polycast) [![License](https://img.shields.io/packagist/l/theodorejb/polycast.svg)](LICENSE.md)

Provides `safe_int`, `safe_float`, and `safe_string` functions.
The functions return true if a value can be cast to the designated type without
data loss, and false if it cannot.

Three complementary functions are also included: `to_int`, `to_float`, and
`to_string`. These functions cast and return a value if the corresponding
*safe_* function returns true, and throw a `CastException` if it returns false.

This library was originally based on the [Safe Casting Functions RFC](https://wiki.php.net/rfc/safe_cast)
proposed (but ultimately declined) for PHP 7.

## Acceptable casts

### `safe_int`

* Integers
* Floats without a remainder between `PHP_INT_MIN` and `PHP_INT_MAX`
* Strings with an optional positive/negative sign, without leading zeros, and
containing the digits 0-9 with a value between `PHP_INT_MIN` and `PHP_INT_MAX`.

### `safe_float`

* Floats
* Integers
* Strings with an optional positive/negative sign matching the format described
at http://php.net/manual/en/language.types.float.php.

### `safe_string`

* Strings
* Integers
* Floats
* Objects with a `__toString` method

The *safe_* functions will always return false if passed `null`, `true` or
`false`, an array, resource, or object (with the exception of objects with a
`__toString` method passed to `safe_string`).

## Install via Composer

`composer require theodorejb/polycast`

## Usage examples

### Input validation

```php
use function theodorejb\polycast\{ safe_int, safe_float, safe_string };

if (!safe_string($_POST['name'])) {
    echo 'Name must be a string';
} elseif (!safe_int($_POST['quantity'])) {
    echo 'Quantity must be an integer';
} elseif (!safe_float($_POST['price'])) {
    echo 'Price must be a number';
} else {
    addProduct($_POST['name'], (int)$_POST['quantity'], (float)$_POST['price']);
}

function addProduct(string $name, int $quantity, float $price)
{
    // ... a database query would go here
}
```

### Safe type conversion

```php
use theodorejb\polycast;

try {
    $totalRevenue = 0.0;
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
