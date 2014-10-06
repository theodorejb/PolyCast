<?php

/**
 * Returns the value as a float, or null if it cannot be safely cast
 * @param mixed $val
 * @return float
 */
function toFloat($val)
{
    if (is_bool($val)) {
        return null;
    }

    if (is_float($val)) {
        return $val;
    }

    if (is_string($val)) {
        $val = trim($val, " \t\n\r\v\f");
    }

    $float = filter_var($val, FILTER_VALIDATE_FLOAT);

    if ($float === false) {
        return null;
    }

    return $float;
}

/**
 * Returns the value as an int, or null if it cannot be safely cast
 * @param mixed $val
 * @param int $base Has no effect unless $val is a string
 * @return int
 */
function toInt($val, $base = 10)
{
    // don't allow bool, object, resource, or array
    if (!in_array(gettype($val), ["integer", "double", "string"], true)) {
        return null;
    }

    if (is_int($val)) {
        return $val;
    }

    if (is_float($val) && !is_infinite($val) && !is_nan($val) && $val <= PHP_INT_MAX) {
        return (int) $val; // valid floats should cast
    }

    if (is_string($val)) {
        $base = filter_var($base, FILTER_VALIDATE_INT);
        if ($base === false || $base < 0 || $base > 36) {
            throw new InvalidArgumentException("base must be a valid integer between 0 and 36");
        }

        // lowercase and trim whitespace
        $val = strtolower(trim($val, " \t\n\r\v\f"));

        if ($base === 0) {
            // determine the actual base (see http://php.net/manual/en/function.intval.php)
            if (substr($val, 0, 2) === "0x") {
                $base = 16;
            } elseif (substr($val, 0, 1) === "0") {
                $base = 8;
            } else {
                $base = 10;
            }
        }

        if ($base === 16 && substr($val, 0, 2) === "0x") {
            $val = substr_replace($val, "", 0, 2); // remove the 0x
        }

        // for any given base, the string should only contain the
        // characters up to that position in the array (e.g. base
        // 10 allows characters 0-9, base 16 allows 0-9 and a-f)

        $chars = [
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
            "k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
            "u", "v", "w", "x", "y", "z"
        ];

        $allowedChars = implode("", array_slice($chars, 0, $base));

        if (!preg_match("/^[$allowedChars]+$/", $val)) {
            return null;
        }

        return intval($val, $base);
    }

    return null;
}
