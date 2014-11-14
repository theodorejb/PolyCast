<?php

// conditionally define PHP_INT_MIN since PHP 5.x doesn't
// include it and it's necessary for validating integers.
if (!defined("PHP_INT_MIN")) {
    define("PHP_INT_MIN", ~PHP_INT_MAX);
}

if (!class_exists("FormatException")) {
    require "FormatException.php";
}

/**
 * Returns the value as an int
 * @param mixed $val
 * @return int
 * @throws InvalidArgumentException if the value has an invalid type
 * @throws FormatException if the value is a string with an invalid format
 * @throws OverflowException if the value is less than PHP_INT_MIN or greater than PHP_INT_MAX
 * @throws DomainException if the value is a float which cannot be safely cast
 */
function to_int($val)
{
    $overflowCheck = function ($val) {
        if ($val > PHP_INT_MAX) {
            throw new OverflowException("Value $val exceeds maximum integter size");
        } elseif ($val < PHP_INT_MIN) {
            throw new OverflowException("Value $val is less than minimum integer size");
        }
    };

    $type = gettype($val);

    switch ($type) {
        case "integer":
            return $val;
        case "double":
            if ($val !== (float) (int) $val) {
                $overflowCheck($val); // if value doesn't overflow, then it's non-integral
                throw new DomainException("The float $val cannot be safely converted to an integer");
            }

            return (int) $val;
        case "string":
            if ($val !== (string) (int) $val) {
                throw new FormatException("The string $val does not have a valid integer format");
            }

            $overflowCheck((float) $val);
            return (int) $val;
        default:
            throw new InvalidArgumentException("Expected integer, float, or string, given $type");
    }
}

/**
 * Returns the value as a float
 * @param mixed $val
 * @return float
 * @throws InvalidArgumentException if the value has an invalid type
 * @throws FormatException if the value is a string with an incorrect format
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
                throw new FormatException("Failed to convert empty string to float");
            }

            $c = $val[0]; // get the first character of the string

            if (!("1" <= $c && $c <= "9") && $c !== "-") {
                // reject leading whitespace, + sign
                throw new FormatException("The string $val does not have a valid float format");
            }

            $float = filter_var($val, FILTER_VALIDATE_FLOAT);

            if ($float === false) {
                throw new FormatException("The string $val does not have a valid float format");
            }

            return $float;
        default:
            throw new InvalidArgumentException("Expected float, integer, or string, given $type");
    }
}

/**
 * Returns the value as a string
 * @param mixed $val
 * @return string
 * @throws BadMethodCallException if an object without a __toString method is passed
 * @throws InvalidArgumentException if the value has an invalid type
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
                throw new BadMethodCallException("Object " . get_class($val) . " cannot be converted to a string without a __toString method");
            }
        default:
            throw new InvalidArgumentException("Expected string, integer, float, or object, given $type");
    }
}
