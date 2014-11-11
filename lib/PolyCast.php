<?php

// conditionally define PHP_INT_MIN since PHP 5.x doesn't
// include it and it's necessary for validating integers.
if (!defined("PHP_INT_MIN")) {
    define("PHP_INT_MIN", ~PHP_INT_MAX);
}

/**
 * Returns the value as an int, or null if it cannot be safely cast
 * @param mixed $val
 * @return int
 */
function to_int($val)
{
    switch (gettype($val)) {
        case "integer":
            return $val;
        case "double":
            return ($val === (float) (int) $val) ? (int) $val : null;
        case "string":
            if (!preg_match("/^[+-]?[0-9]+$/", $val)) {
                return null; // reject leading/trailing whitespace
            }

            if ((float) $val > PHP_INT_MAX || (float) $val < PHP_INT_MIN) {
                return null; // reject overflows
            }

            return (int) $val;
        default:
            return null;
    }
}

/**
 * Returns the value as a float, or null if it cannot be safely cast
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
            if (preg_match("/^\s/", $val) || preg_match("/\s$/", $val)) {
                return null; // reject leading/trailing whitespace
            }

            $float = filter_var($val, FILTER_VALIDATE_FLOAT);
            return $float === false ? null : $float;
        default:
            return null;
    }
}

/**
 * Returns the value as a string, or null if it cannot be safely cast
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
                return null;
            }
        default:
            return null;
    }
}
