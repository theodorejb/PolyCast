# PHP Strict Cast

[![Build Status](https://travis-ci.org/theodorejb/php-strict-cast.svg)](https://travis-ci.org/theodorejb/php-strict-cast)

Adds `toFloat`, `toInt`, and `toString` functions for safe, strict casting. The functions currently return `null` if a value cannot be safely cast, but this will likely be changed to `false`.

Based on https://github.com/TazeTSchnitzel/php-src/compare/php:master...TazeTSchnitzel:safe_casts.

## Examples

```php
toFloat("0");     // 0.0
toFloat(0);       // 0.0
toFloat(0.0);     // 0.0
toFloat("10");    // 10.0
toFloat(10);      // 10.0
toFloat(10.0);    // 10.0
toFloat(1.5);     // 1.5
toFloat("1.5");   // 1.5
toFloat("75e-5"); // 0.00075
toFloat(" 100 "); // 100.0

toFloat(null);           // null
toFloat(true);           // null
toFloat(false);          // null
toFloat(new stdClass()); // null
toFloat($resource);      // null
toFloat([]);             // null
toFloat("10abc");        // null

toInt("0");  // 0
toInt(0);    // 0
toInt(0.0);  // 0
toInt("10"); // 10
toInt(10);   // 10
toInt(10.0); // 10
toInt(1.5);  // 1

toInt("75e-5");        // null
toInt("1.5");          // null
toInt(null);           // null
toInt(true);           // null
toInt(false);          // null
toInt(new stdClass()); // null
toInt($resource);      // null
toInt([]);             // null

toString("foobar"); // "foobar"
toString(123);      // "123"
toString(123.45);   // "123.45"
toString(INF);      // "INF"
toString(NAN);      // "NAN"

toString(null);      // null
toString(true);      // null
toString(false);     // null
toString([]);        // null
toString($resource); // null

class NotStringable {}
class Stringable {
    public function __toString() {
        return "foobar";
    }
}

toString(new stdClass());      // null
toString(new NotStringable()); // null
toString(new Stringable());    // "foobar"
```

## Author

Theodore Brown  
[@theodorejb](https://twitter.com/theodorejb)  
<http://designedbytheo.com>

## License

MIT
