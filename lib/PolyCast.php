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
            $PHP_INT_MIN = ~PHP_INT_MAX; // const isn't in PHP 5.x

            if (!is_infinite($val) && !is_nan($val) && $val >= $PHP_INT_MIN) {
                // due to rounding issues, on 64-bit platforms
                // the float must be less than PHP_INT_MAX

                if (
                    (PHP_INT_SIZE === 8 && $val < PHP_INT_MAX) || // valid 64-bit
                    (PHP_INT_SIZE !== 8 && $val <= PHP_INT_MAX) // valid non-64-bit
                ) {
                    return (int) $val; // valid floats should cast
                }
            }

            return false;
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
    switch (gettype($val)) {
        case "double":
            return $val;
        case "integer":
            return (float) $val;
        case "string":
            $val = trim($val, " \t\n\r\v\f"); // trim whitespace
            return filter_var($val, FILTER_VALIDATE_FLOAT);
        default:
            return false;
    }
}

/**
 * Returns the value as a string, or false if it cannot be safely cast
 * @param mixed $val
 * @return string
 */
function to_string($val)
{
    switch (gettype($val)) {
        case "string":
            return $val;
        case "integer":
        case "double":
            return (string) $val;
        case "object":
            if (method_exists($val, "__toString")) {
                return $val->__toString();
            } else {
                return false;
            }
        default:
            return false;
    }
}
