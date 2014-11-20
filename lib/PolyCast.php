<?php

// conditionally define PHP_INT_MIN since PHP 5.x doesn't
// include it and it's necessary for validating integers.
if (!defined("PHP_INT_MIN")) {
    define("PHP_INT_MIN", ~PHP_INT_MAX);
}

if (!class_exists("CastException")) {
    require "CastException.php";
}

/**
 * Returns the value as an int
 * @param mixed $val
 * @return int
 * @throws CastException if the value cannot be safely cast to an integer
 */
function to_int($val)
{
    $overflowCheck = function ($val) {
        if ($val > PHP_INT_MAX) {
            throw new CastException("Value could not be converted to int");
        } elseif ($val < PHP_INT_MIN) {
            throw new CastException("Value could not be converted to int");
        }
    };

    $type = gettype($val);

    switch ($type) {
        case "integer":
            return $val;
        case "double":
            if ($val !== (float) (int) $val) {
                $overflowCheck($val); // if value doesn't overflow, then it's non-integral
                throw new CastException("Value could not be converted to int");
            }

            return (int) $val;
        case "string":
            $losslessCast = (string) (int) $val;

            if ($val !== $losslessCast && $val !== "+$losslessCast") {
                throw new CastException("Value could not be converted to int");
            }

            $overflowCheck((float) $val);
            return (int) $val;
        default:
            throw new CastException("Value could not be converted to int");
    }
}

/**
 * Returns the value as a float
 * @param mixed $val
 * @return float
 * @throws CastException if the value cannot be safely cast to a float
 */
function to_float($val)
{
    $type = gettype($val);

    switch ($type) {
        case "double":
            return $val;
        case "integer":
            return (float) $val;
        case "string":
            if ($val === "0") {
                return 0.0; // special-case zero
            }

            if ($val === "") {
                throw new CastException("Value could not be converted to float");
            }

            $c = $val[0]; // get the first character of the string

            if (!("1" <= $c && $c <= "9") && $c !== "-" && $c !== "+") {
                // reject leading whitespace and zeros
                throw new CastException("Value could not be converted to float");
            }

            $float = filter_var($val, FILTER_VALIDATE_FLOAT);

            if ($float === false) {
                throw new CastException("Value could not be converted to float");
            }

            return $float;
        default:
            throw new CastException("Value could not be converted to float");
    }
}

/**
 * Returns the value as a string
 * @param mixed $val
 * @return string
 * @throws CastException if the value cannot be safely cast to a string
 */
function to_string($val)
{
    $type = gettype($val);

    switch ($type) {
        case "string":
            return $val;
        case "integer":
        case "double":
            return (string) $val;
        case "object":
            if (method_exists($val, "__toString")) {
                return $val->__toString();
            } else {
                throw new CastException("Value could not be converted to string");
            }
        default:
            throw new CastException("Value could not be converted to string");
    }
}

/**
 * Returns the value as an int, or null if it cannot be safely cast
 * @param mixed $val
 * @return int
 */
function try_int($val)
{
    try {
        return to_int($val);
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Returns the value as a float, or null if it cannot be safely cast
 * @param mixed $val
 * @return float
 */
function try_float($val)
{
    try {
        return to_float($val);
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Returns the value as a string, or null if it cannot be safely cast
 * @param mixed $val
 * @return string
 */
function try_string($val)
{
    try {
        return to_string($val);
    } catch (Exception $e) {
        return null;
    }
}
