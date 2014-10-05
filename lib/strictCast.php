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
