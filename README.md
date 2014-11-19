# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast) [![Packagist Version](https://img.shields.io/packagist/v/theodorejb/polycast.svg)](https://packagist.org/packages/theodorejb/polycast) [![License](https://img.shields.io/packagist/l/theodorejb/polycast.svg)](LICENSE.md)

Adds `to_int`, `to_float`, and `to_string` functions for safe, strict casting.
The functions throw a `CastException` if a value cannot be safely cast.

Also adds `try_int`, `try_float`, and `try_string` methods, which validate identically
but return `null` instead of throwing an exception if a value cannot be safely cast.

Based on https://github.com/php/php-src/pull/874.
An RFC proposing inclusion in PHP 7 was opened for discussion on 2014-10-20:
https://wiki.php.net/rfc/safe_cast.

## Installation

To install via [Composer](https://getcomposer.org/),
add the following to the composer.json file in your project root:

```json
{
    "require": {
        "theodorejb/polycast": "~0.6"
    }
}
```

Then run `composer install` and require `vendor/autoload.php`
in your application's bootstrap file.

## Examples

Value      | `to_int()` | `to_float()` | `to_string()`
---------- | ---------- | ------------ | -------------
`null`     | fail       | fail         | fail
`true`     | fail       | fail         | fail
`false`    | fail       | fail         | fail
`array`    | fail       | fail         | fail
resource   | fail       | fail         | fail
`stdClass` | fail       | fail         | fail
"10"       | 10         | 10.0         | "10"
"+10"      | 10         | 10.0         | "+10"
"-10"      | -10        | -10.0        | "-10"
10.0       | 10         | 10.0         | "10"
"10.0"     | fail       | 10.0         | "10.0"
1.5        | fail       | 1.5          | "1.5"
"1.5"      | fail       | 1.5          | "1.5"
"31e+7"    | fail       | 310000000.0  | "31e+7"
"75e-5"    | fail       | 0.00075      | "75e-5"
`INF`      | fail       | `INF`        | "INF"
`NAN`      | fail       | `NAN`        | "NAN"
""         | fail       | fail         | ""
"   10   " | fail       | fail         | "   10   "
"10abc"    | fail       | fail         | "10abc"
"abc10"    | fail       | fail         | "abc10"
"010"      | fail       | fail         | "010"

### Support for `__toString()`

```php
class NotStringable {}
class Stringable {
    public function __toString() {
        return "foobar";
    }
}

to_string(new NotStringable()); // fail
to_string(new Stringable());    // "foobar"
```

## Author

Theodore Brown  
<http://theodorejb.me>

## License

MIT
