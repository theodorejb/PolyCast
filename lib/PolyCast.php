<?php

/**
 * Returns the value as an int, or false if it cannot be safely cast
 * @param mixed $val
 * @return int
 */
function to_int($val)
{
    switch (gettype($val)) {
        case "integer":
            return $val;
        case "double":
            if (!is_infinite($val) && !is_nan($val) && $val < PHP_INT_MAX) {
                return (int) $val; // valid floats should cast
            } else {
                return false;
            }
        case "string":
            $val = trim($val, " \t\n\r\v\f"); // trim whitespace
            return filter_var($val, FILTER_VALIDATE_INT);
        default:
            return false;
    }
}

/**
 * Returns the value as a float, or false if it cannot be safely cast
 * @param mixed $val
 * @return float
 */
function to_float($val)
{
    if (is_bool($val)) {
        return false;
    }

    if (is_float($val)) {
        return $val;
    }

    if (is_string($val)) {
        $val = trim($val, " \t\n\r\v\f");
    }

    return filter_var($val, FILTER_VALIDATE_FLOAT);
}

/**
 * Returns the value as a string, or false if it cannot be safely cast
 * @param mixed $val
 * @return string
 */
function to_string($val)
{
    if (is_string($val)) {
        return $val;
    }

    if (is_int($val) || is_float($val)) {
        return (string) $val;
    }

    if (is_object($val) && method_exists($val, '__toString')) {
        return $val->__toString();
    }

    return false;
}
