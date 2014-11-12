<?php

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
            return ($val === (string) (int) $val) ? (int) $val : null;
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
            if ($val === "0") {
                return 0.0; // special-case zero
            }

            if ($val === "") {
                return null;
            }

            $c = $val[0]; // get the first character of the string

            if (!("1" <= $c && $c <= "9") && $c !== "-") {
                return null; // reject leading whitespace, + sign
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
