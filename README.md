# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast)

Adds `to_int`, `to_float`, and `to_string` functions for safe, strict casting. The functions return `false` if a value cannot be safely cast.

Based on https://github.com/TazeTSchnitzel/php-src/compare/php:master...TazeTSchnitzel:safe_casts.

## Installation

To install via [Composer](https://getcomposer.org/), add the following to the composer.json file in your project root:

```json
{
    "require": {
        "theodorejb/polycast": "~0.2"
    }
}
```

Then run `composer install` and require `vendor/autoload.php` in your application's bootstrap file.

## Examples

```php
to_int("0");     // 0
to_int(0);       // 0
to_int(0.0);     // 0
to_int("10");    // 10
to_int(10);      // 10
to_int(10.0);    // 10
to_int(1.5);     // 1
to_int(" 100 "); // 100

to_int("10abc");        // false
to_int("31e+7");        // false
to_int("1.5");          // false
to_int(null);           // false
to_int(true);           // false
to_int(false);          // false
to_int(INF);            // false
to_int(NAN);            // false
to_int(new stdClass()); // false
to_int($resource);      // false
to_int([]);             // false

to_float("0");     // 0.0
to_float(0);       // 0.0
to_float(0.0);     // 0.0
to_float("10");    // 10.0
to_float(10);      // 10.0
to_float(10.0);    // 10.0
to_float(1.5);     // 1.5
to_float("1.5");   // 1.5
to_float(INF);     // INF
to_float(NAN);     // NAN
to_float("75e-5"); // 0.00075
to_float(" 100 "); // 100.0

to_float(null);           // false
to_float(true);           // false
to_float(false);          // false
to_float(new stdClass()); // false
to_float($resource);      // false
to_float([]);             // false
to_float("10abc");        // false

to_string("foobar"); // "foobar"
to_string(123);      // "123"
to_string(123.45);   // "123.45"
to_string(INF);      // "INF"
to_string(NAN);      // "NAN"

to_string(null);      // false
to_string(true);      // false
to_string(false);     // false
to_string([]);        // false
to_string($resource); // false

class NotStringable {}
class Stringable {
    public function __toString() {
        return "foobar";
    }
}

to_string(new stdClass());      // false
to_string(new NotStringable()); // false
to_string(new Stringable());    // "foobar"
```

## Author

Theodore Brown  
[@theodorejb](https://twitter.com/theodorejb)  
<http://designedbytheo.com>

## License

MIT
