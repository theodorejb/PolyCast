# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast) [![Packagist Version](https://img.shields.io/packagist/v/theodorejb/polycast.svg)](https://packagist.org/packages/theodorejb/polycast) [![License](https://img.shields.io/packagist/l/theodorejb/polycast.svg)](LICENSE.md)

Adds `to_int`, `to_float`, and `to_string` functions for safe, strict casting.
The functions return `null` if a value cannot be safely cast.

Based on https://github.com/php/php-src/pull/874.
An RFC proposing inclusion in PHP 7 was opened for discussion on 2014-10-20:
https://wiki.php.net/rfc/safe_cast.

## Installation

To install via [Composer](https://getcomposer.org/),
add the following to the composer.json file in your project root:

```json
{
    "require": {
        "theodorejb/polycast": "~0.4"
    }
}
```

Then run `composer install` and require `vendor/autoload.php`
in your application's bootstrap file.

## Examples

Value      | `to_int()` | `to_float()` | `to_string()`
---------- | ---------- | ------------ | -------------
`null`     | `null`     | `null`       | `null`
`true`     | `null`     | `null`       | `null`
`false`    | `null`     | `null`       | `null`
`array`    | `null`     | `null`       | `null`
resource   | `null`     | `null`       | `null`
`stdClass` | `null`     | `null`       | `null`
""         | `null`     | `null`       | `null`
"10"       | 10         | 10.0         | "10"
"-10"      | -10        | -10.0        | "-10"
10.0       | 10         | 10.0         | "10"
"10.0"     | `null`     | 10.0         | "10.0"
1.5        | `null`     | 1.5          | "1.5"
"1.5"      | `null`     | 1.5          | "1.5"
"31e+7"    | `null`     | 310000000.0  | "31e+7"
"75e-5"    | `null`     | 0.00075      | "75e-5"
`INF`      | `null`     | `INF`        | "INF"
`NAN`      | `null`     | `NAN`        | "NAN"
"   10   " | `null`     | `null`       | "   10   "
"10abc"    | `null`     | `null`       | "10abc"
"abc10"    | `null`     | `null`       | "abc10"
"+10"      | `null`     | `null`       | "+10"
"010"      | `null`     | `null`       | "010"

### Support for `__toString()`

```php
class NotStringable {}
class Stringable {
    public function __toString() {
        return "foobar";
    }
}

to_string(new NotStringable()); // null
to_string(new Stringable());    // "foobar"
```

## Author

Theodore Brown  
<http://theodorejb.me>

## License

MIT
