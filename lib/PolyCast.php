<?php

declare(strict_types=1);

namespace theodorejb\polycast;

/**
 * Returns true if the value can be safely converted to an integer
 * @param mixed $val
 */
function safe_int($val): bool
{
    switch (gettype($val)) {
        case "integer":
            return true;
        case "double":
            return $val === (float)(int)$val;
        case "string":
            $losslessCast = (string)(int)$val;

            if ($val !== $losslessCast && $val !== "+$losslessCast") {
                return false;
            }

            return $val <= PHP_INT_MAX && $val >= PHP_INT_MIN;
        default:
            return false;
    }
}

/**
 * Returns true if the value can be safely converted to a float
 * @param mixed $val
 */
function safe_float($val): bool
{
    switch (gettype($val)) {
        case "double":
        case "integer":
            return true;
        case "string":
            // reject leading zeros unless they are followed by a decimal point
            if (strlen($val) > 1 && $val[0] === "0" && $val[1] !== ".") {
                return false;
            }

            // Use regular expressions since FILTER_VALIDATE_FLOAT allows trailing whitespace
            // Based on http://php.net/manual/en/language.types.float.php
            $lnum    = "[0-9]+";
            $dnum    = "([0-9]*[\.]{$lnum})|({$lnum}[\.][0-9]*)";
            $expDnum = "/^[+-]?(({$lnum}|{$dnum})[eE][+-]?{$lnum})$/";

            return
                preg_match("/^[+-]?{$lnum}$/", $val) ||
                preg_match("/^[+-]?{$dnum}$/", $val) ||
                preg_match($expDnum, $val);
        default:
            return false;
    }
}

/**
 * Returns true if the value can be safely converted to a string
 * @param mixed $val
 */
function safe_string($val): bool
{
    switch (gettype($val)) {
        case "string":
        case "integer":
        case "double":
            return true;
        case "object":
            return method_exists($val, "__toString");
        default:
            return false;
    }
}

/**
 * Returns the value as an integer
 * @param mixed $val
 * @throws CastException if the value cannot be safely cast to an integer
 */
function to_int($val): int
{
    if (!safe_int($val)) {
        throw new CastException("Value could not be converted to int");
    } else {
        return (int)$val;
    }
}

/**
 * Returns the value as a float
 * @param mixed $val
 * @throws CastException if the value cannot be safely cast to a float
 */
function to_float($val): float
{
    if (!safe_float($val)) {
        throw new CastException("Value could not be converted to float");
    } else {
        return (float)$val;
    }
}

/**
 * Returns the value as a string
 * @param mixed $val
 * @throws CastException if the value cannot be safely cast to a string
 */
function to_string($val): string
{
    if (!safe_string($val)) {
        throw new CastException("Value could not be converted to string");
    } else {
        return (string)$val;
    }
}
